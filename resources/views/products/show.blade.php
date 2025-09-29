@php
    // Conditional layout
    $layout = class_exists(\Iquesters\UserInterface\UserInterfaceServiceProvider::class)
        ? 'userinterface::layouts.app'
        : config('product.layout');
@endphp

@extends($layout)

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
        <h5 class="fs-6 text-muted mb-2 mb-md-0">Product Details</h5>
        <div>
            <a href="{{ route('products.edit', [$product->uid, $organisation->uid ?? null]) }}" class="btn btn-sm btn-outline-dark">
                <i class="fas fa-fw fa-edit"></i>
                <span class="d-none d-md-inline-block ms-2">Edit</span>
            </a>
        </div>
    </div>

    <div class="card p-3">
        <div class="row g-3">
            {{-- Main Fields --}}
            <div class="col-md-6"><strong>SKU:</strong> {{ $product->sku }}</div>
            <div class="col-md-6"><strong>Status:</strong> {{ ucfirst($product->status) }}</div>
            <div class="col-md-6"><strong>Organisation:</strong> {{ $organisation->name ?? 'Default Organisation' }}</div>
            <div class="col-md-6"><strong>Created At:</strong> {{ $product->created_at->format('d M Y H:i') }}</div>
            <div class="col-md-6"><strong>Updated At:</strong> {{ $product->updated_at->format('d M Y H:i') }}</div>

            {{-- Meta Fields --}}
            <div class="col-md-6"><strong>Name:</strong> {{ $product->getMetaValue('name') ?? '-' }}</div>
            <div class="col-md-6"><strong>Quantity:</strong> {{ $product->getMetaValue('quantity') ?? '-' }}</div>
            <div class="col-md-6"><strong>Category:</strong> {{ $product->getMetaValue('category') ?? '-' }}</div>
            <div class="col-md-6"><strong>MRP:</strong> {{ $product->getMetaValue('mrp') ?? '-' }}</div>
            <div class="col-md-6"><strong>Tax %:</strong> {{ $product->getMetaValue('tax') ?? '-' }}</div>
            <div class="col-md-6"><strong>Buying Price:</strong> {{ $product->getMetaValue('buying_price') ?? '-' }}</div>
            <div class="col-md-6"><strong>Selling Price:</strong> {{ $product->getMetaValue('selling_price') ?? '-' }}</div>
            <div class="col-md-6"><strong>Barcode:</strong> {{ $product->getMetaValue('barcode') ?? '-' }}</div>
            <div class="col-md-6"><strong>Reorder Level:</strong> {{ $product->getMetaValue('reorder_level') ?? '-' }}</div>
            <div class="col-12"><strong>Description:</strong> {{ $product->getMetaValue('description') ?? '-' }}</div>
        </div>
    </div>
</div>
@endsection