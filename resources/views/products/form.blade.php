@php
    $isEdit = isset($product);

    // Build action URL correctly (avoid // when organisation is missing)
    if ($isEdit) {
        $actionUrl = route('products.update', [$product->uid, $organisation->uid]);
    } else {
        $actionUrl = route('products.store', $organisation->uid);
    }

    // Conditional layout
    $layout = class_exists(\Iquesters\UserManagement\UserManagementServiceProvider::class)
        ? 'usermanagement::layouts.app'
        : config('product.layout');
@endphp

@extends($layout)

@section('content')
<div class="container">
    <div class="mb-3">
        <h5 class="mb-2 fs-6">{{ $isEdit ? 'Edit' : 'Create' }} Product</h5>
    </div>

    <div class="">
        <form action="{{ $actionUrl }}" method="POST">
            @csrf
            @if($isEdit) 
                @method('PUT') 
            @endif

            {{-- SKU --}}
            <div class="mb-3">
                <label for="sku" class="form-label">SKU</label>
                <input 
                    type="text" 
                    class="form-control @error('sku') is-invalid @enderror"
                    id="sku" 
                    name="sku" 
                    value="{{ old('sku', $product->sku ?? '') }}" 
                    required
                >
                @error('sku')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            {{-- Status --}}
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status" name="status">
                    <option value="active" {{ old('status', $product->status ?? '') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status', $product->status ?? '') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="unknown" {{ old('status', $product->status ?? '') === 'unknown' ? 'selected' : '' }}>Unknown</option>
                </select>
            </div>

            {{-- Actions --}}
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('products.index', $organisation->uid) }}" 
                   class="btn btn-sm btn-outline-dark">
                   Cancel
                </a>
                <button type="submit" class="btn btn-sm btn-outline-primary">
                    Save
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
