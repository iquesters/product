<?php

namespace Iquesters\Product\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Iquesters\Product\Models\Product;

class ProductController extends Controller
{
    /**
     * Get organisation (real or demo).
     */
    private function getOrganisation($organisationUid = null)
    {
        if (class_exists(\Iquesters\Organisation\Models\Organisation::class)) {
            if ($organisationUid) {
                $org = \Iquesters\Organisation\Models\Organisation::where('uid', $organisationUid)->first();
                if ($org) return $org;
            }
            // Demo organisation fallback
            return (object) [
                'id'   => 1,
                'uid'  => 'uid1',
                'name' => 'Demo Organisation',
            ];
        }

        // Package not installed → demo organisation
        return (object) [
            'id'   => 1,
            'uid'  => 'uid1',
            'name' => 'Demo Organisation',
        ];
    }

    /**
     * Product listing.
     */
    public function index($organisationUid = null)
    {
        $organisation = $this->getOrganisation($organisationUid);

        $products = Product::with('metas')
            ->when($organisation->id, fn($q) => $q->where('organisation_id', $organisation->id))
            ->get();

        return view('product::products.index', compact('products', 'organisation'));
    }

    /**
     * Create form.
     */
    public function create($organisationUid = null)
    {
        $organisation = $this->getOrganisation($organisationUid);
        return view('product::products.form', compact('organisation'));
    }

    /**
     * Store product.
     */
    public function store(Request $request, $organisationUid = null)
    {
        $organisation = $this->getOrganisation($organisationUid);

        $validator = Validator::make($request->all(), [
            'sku' => 'required|string|max:255|unique:products,sku',
            'status' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $organisationId = $organisation->id ?? null;

        $product = Product::create([
            'uid' => Str::ulid(),
            'sku' => $request->sku,
            'status' => $request->status ?? 'unknown',
            'organisation_id' => $organisationId,
            'created_by' => Auth::id() ?? 0,
            'updated_by' => Auth::id() ?? 0,
        ]);


        return redirect()->route('products.show', [$product->uid, $organisation->uid])
            ->with('success', 'Product created successfully');
    }

    /**
     * Show product.
     */
    public function show($productUid, $organisationUid = null)
    {
        $organisation = $this->getOrganisation($organisationUid);

        $product = Product::with('metas')
            ->when($organisation->id, fn($q) => $q->where('organisation_id', $organisation->id))
            ->where('uid', $productUid)
            ->firstOrFail();

        return view('product::products.show', compact('product', 'organisation'));
    }

    /**
     * Edit form.
     */
    public function edit($productUid, $organisationUid = null)
    {
        $organisation = $this->getOrganisation($organisationUid);

        $product = Product::with('metas')
            ->when($organisation->id, fn($q) => $q->where('organisation_id', $organisation->id))
            ->where('uid', $productUid)
            ->firstOrFail();

        return view('product::products.form', compact('product', 'organisation'));
    }

    /**
     * Update product.
     */
    public function update(Request $request, $productUid, $organisationUid = null)
    {
        $organisation = $this->getOrganisation($organisationUid);

        $product = Product::when($organisation->id, fn($q) => $q->where('organisation_id', $organisation->id))
            ->where('uid', $productUid)
            ->firstOrFail();

        $validator = Validator::make($request->all(), [
            'sku' => "sometimes|string|max:255|unique:products,sku,{$product->id}",
            'status' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $product->update([
            'sku' => $request->sku ?? $product->sku,
            'status' => $request->status ?? $product->status,
            'updated_by' => Auth::id() ?? $product->updated_by,
        ]);

        return redirect()->route('products.show', [$product->uid, $organisation->uid])
            ->with('success', 'Product updated successfully');
    }

    /**
     * Delete product.
     */
    public function destroy($productUid, $organisationUid = null)
    {
        $organisation = $this->getOrganisation($organisationUid);

        $product = Product::when($organisation->id, fn($q) => $q->where('organisation_id', $organisation->id))
            ->where('uid', $productUid)
            ->firstOrFail();

        $product->delete();

        return redirect()->route('products.index', $organisation->uid)
            ->with('success', 'Product deleted successfully');
    }
}