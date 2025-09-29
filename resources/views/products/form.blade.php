@php
    $isEdit = isset($product);

    // Build action URL
    $actionUrl = $isEdit
        ? route('products.update', [$product->uid, $organisation->uid])
        : route('products.store', $organisation->uid);

    // Conditional layout
    $layout = class_exists(\Iquesters\UserManagement\UserManagementServiceProvider::class)
        ? 'usermanagement::layouts.app'
        : config('product.layout');
@endphp

@extends($layout)

@section('content')
<div>
    <div class="mb-3">
        <h5 class="mb-2 fs-6">{{ $isEdit ? 'Edit' : 'Create' }} Product</h5>
    </div>

    <form action="{{ $actionUrl }}" method="POST">
        @csrf
        @if($isEdit) @method('PUT') @endif

        <div class="row g-3">
            {{-- SKU --}}
            <div class="col-md-4">
                <label for="sku" class="form-label">SKU *</label>
                <input type="text" name="sku" id="sku" 
                    class="form-control @error('sku') is-invalid @enderror"
                    value="{{ old('sku', $product->sku ?? '') }}" required>
                @error('sku')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Name --}}
            <div class="col-md-8">
                <label for="name" class="form-label">Name</label>
                <input type="text" name="name" id="name" 
                    class="form-control" 
                    value="{{ old('name', $isEdit ? $product->getMetaValue('name') : '') }}">
            </div>

            {{-- MRP --}}
            <div class="col-md-4">
                <label for="mrp" class="form-label">MRP</label>
                <input type="number" name="mrp" id="mrp" class="form-control" step="0.01"
                    value="{{ old('mrp', $isEdit ? $product->getMetaValue('mrp') : '') }}">
            </div>

            {{-- Tax --}}
            <div class="col-md-4">
                <label for="tax" class="form-label">Tax %</label>
                <input type="number" name="tax" id="tax" class="form-control" step="0.01"
                    value="{{ old('tax', $isEdit ? $product->getMetaValue('tax') : '') }}">
            </div>

            {{-- Buying Price --}}
            <div class="col-md-4">
                <label for="buying_price" class="form-label">Buying Price</label>
                <input type="number" name="buying_price" id="buying_price" class="form-control" step="0.01"
                    value="{{ old('buying_price', $isEdit ? $product->getMetaValue('buying_price') : '') }}">
            </div>

            {{-- Selling Price --}}
            <div class="col-md-4">
                <label for="selling_price" class="form-label">Selling Price</label>
                <input type="number" name="selling_price" id="selling_price" class="form-control" step="0.01"
                    value="{{ old('selling_price', $isEdit ? $product->getMetaValue('selling_price') : '') }}">
            </div>

            {{-- Category --}}
            <div class="col-md-4">
                <label for="category" class="form-label">Category</label>
                <input type="text" name="category" id="category" class="form-control"
                    value="{{ old('category', $isEdit ? $product->getMetaValue('category') : '') }}">
            </div>

            {{-- Quantity --}}
            <div class="col-md-4">
                <label for="quantity" class="form-label">Quantity</label>
                <input type="number" name="quantity" id="quantity" class="form-control"
                    value="{{ old('quantity', $isEdit ? $product->getMetaValue('quantity') : '') }}">
            </div>

            {{-- Barcode --}}
            <div class="col-md-6">
                <label for="barcode" class="form-label">Barcode</label>
                <input type="text" name="barcode" id="barcode" class="form-control"
                    value="{{ old('barcode', $isEdit ? $product->getMetaValue('barcode') : '') }}">
            </div>

            {{-- Reorder Level --}}
            <div class="col-md-6">
                <label for="reorder_level" class="form-label">Reorder Level</label>
                <input type="number" name="reorder_level" id="reorder_level" class="form-control"
                    value="{{ old('reorder_level', $isEdit ? $product->getMetaValue('reorder_level') : '') }}">
            </div>

            {{-- Description --}}
            <div class="col-12">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" rows="3" class="form-control">{{ old('description', $isEdit ? $product->getMetaValue('description') : '') }}</textarea>
            </div>
        </div>

        {{-- Actions --}}
        <div class="d-flex justify-content-end gap-2 mt-3">
            <a href="{{ route('products.index', $organisation->uid) }}" class="btn btn-sm btn-outline-dark">
                Cancel
            </a>
            <button type="submit" class="btn btn-sm btn-outline-primary">
                Save
            </button>
        </div>
    </form>
</div>
@endsection
