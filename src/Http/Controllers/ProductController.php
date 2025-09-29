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
            return (object) [
                'id' => 1,
                'uid' => 'uid1',
                'name' => 'Demo Organisation',
            ];
        }

        return (object) [
            'id' => 1,
            'uid' => 'uid1',
            'name' => 'Demo Organisation',
        ];
    }

    /**
     * Helper to save meta fields.
     */
    private function saveMetaFields(Product $product, array $data)
    {
        $fields = [
            'name',
            'mrp',
            'tax',
            'buying_price',
            'selling_price',
            'category',
            'quantity',
            'barcode',
            'reorder_level',
            'description'
        ];

        foreach ($fields as $field) {
            if (!isset($data[$field])) {
                continue; // skip if field not in submitted data
            }

            $value = $data[$field] ?? ''; // use empty string if empty

            $product->metas()->updateOrCreate(
                [
                    'meta_key' => $field,
                    'ref_parent' => $product->id
                ],
                [
                    'meta_value' => $value,
                    'status' => 'active',
                    'created_by' => $product->metas()->where('meta_key', $field)->exists()
                        ? $product->metas()->where('meta_key', $field)->first()->created_by
                        : (Auth::id() ?? 0),
                    'updated_by' => Auth::id() ?? 0,
                ]
            );
        }
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
        $product = null;
        return view('product::products.form', compact('organisation', 'product'));
    }

    /**
     * Store product.
     */
    public function store(Request $request, $organisationUid = null)
    {
        $organisation = $this->getOrganisation($organisationUid);

        $validator = Validator::make($request->all(), [
            'sku' => 'required|string|max:255|unique:products,sku',
            'name' => 'nullable|string|max:255',
            'mrp' => 'nullable|numeric',
            'tax' => 'nullable|numeric',
            'buying_price' => 'nullable|numeric',
            'selling_price' => 'nullable|numeric',
            'category' => 'nullable|string|max:255',
            'quantity' => 'nullable|numeric',
            'barcode' => 'nullable|string|max:255',
            'reorder_level' => 'nullable|numeric',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $organisationId = $organisation->id ?? null;

        $product = Product::create([
            'uid' => Str::ulid(),
            'sku' => $request->sku,
            'status' => 'active', // always active
            'organisation_id' => $organisationId,
            'created_by' => Auth::id() ?? 0,
            'updated_by' => Auth::id() ?? 0,
        ]);

        $this->saveMetaFields($product, $request->all());

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
            'sku' => "required|string|max:255|unique:products,sku,{$product->id}",
            'name' => 'nullable|string|max:255',
            'mrp' => 'nullable|numeric',
            'tax' => 'nullable|numeric',
            'buying_price' => 'nullable|numeric',
            'selling_price' => 'nullable|numeric',
            'category' => 'nullable|string|max:255',
            'quantity' => 'nullable|numeric',
            'barcode' => 'nullable|string|max:255',
            'reorder_level' => 'nullable|numeric',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $product->update([
            'sku' => $request->sku,
            'status' => 'active', // always active
            'updated_by' => Auth::id() ?? $product->updated_by,
        ]);

        $this->saveMetaFields($product, $request->all());

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