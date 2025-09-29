@php
    // Conditional layout
    $layout = class_exists(\Iquesters\UserManagement\UserManagementServiceProvider::class)
        ? 'usermanagement::layouts.app'
        : config('product.layout');
@endphp

@extends($layout)
@section('content')
<div class="">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fs-6 text-muted">Product Details</h5>
        <div>
            <a href="{{ route('products.edit', [$product->uid, $organisation->uid ?? null]) }}" class="btn btn-sm btn-outline-dark">
                <i class="fas fa-fw fa-edit"></i><span class="d-none d-md-inline-block ms-2">Edit</span>
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-9 d-flex flex-column align-items-start justify-content-center text-muted">
            <p><strong>SKU:</strong> {{ $product->sku }}</p>
            <p><strong>Status:</strong> {{ ucfirst($product->status) }}</p>
            <p><strong>Organisation:</strong> {{ $organisation->name ?? 'Default Organisation' }}</p>
            <p><strong>Created At:</strong> {{ $product->created_at->format('d M Y H:i') }}</p>
            <p><strong>Updated At:</strong> {{ $product->updated_at->format('d M Y H:i') }}</p>
        </div>
    </div>
</div>
@endsection