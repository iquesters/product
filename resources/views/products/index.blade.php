@php
    $layout = class_exists(\Iquesters\UserManagement\UserManagementServiceProvider::class)
        ? 'usermanagement::layouts.app'
        : config('product.layout');
@endphp

@extends($layout)

@section('content')
<div class="">
    <div class="p-2 d-flex justify-content-between align-items-center flex-wrap border-bottom">
        <span>Product List</span>
        <a href="{{ route('products.create', $organisation->uid) }}" class="btn btn-sm btn-outline-primary">
            <i class="fas fa-plus me-1"></i> Add Product
        </a>
    </div>
    <div class="card-body px-0">
        <div class="table-responsive overflow-visible">
            <table id="productsTable" class="table table-hover w-100">
                <thead class="table-light">
                    <tr>
                        <th class="text-center font-weight-light">#</th>
                        <th>Name</th>
                        <th>SKU</th>
                        <th>Quantity</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th>Action</th>
                        <th></th> <!-- mobile responsive + button -->
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $index => $product)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $product->getMetaValue('name') ?? '-' }}</td>
                        <td>{{ $product->sku }}</td>
                        <td>{{ $product->getMetaValue('quantity') ?? '-' }}</td>
                        <td>{{ $product->getMetaValue('category') ?? '-' }}</td>
                        <td>{{ ucfirst($product->status) }}</td>
                        <td>
                            <div class="d-flex justify-content-start align-items-center">
                                <a href="{{ route('products.edit', [$product->uid, $organisation->uid ?? null]) }}">
                                    <i class="fas fa-fw fa-edit me-1"></i>
                                </a>
                                <form action="{{ route('products.destroy', [$product->uid, $organisation->uid ?? null]) }}" method="POST" onsubmit="return confirm('Are you sure?')" class="ms-1">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-link text-danger p-0 m-0">
                                        <i class="fas fa-fw fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                        <td class="text-center"></td> <!-- mobile responsive + button -->
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $("#productsTable").DataTable({
        responsive: {
            details: {
                type: 'column',
                target: -1,
                display: $.fn.dataTable.Responsive.display.modal({
                    header: function(row) { return 'Product Details'; }
                }),
                renderer: function(api, rowIdx, columns) {
                    let data = $.map(columns, function(col) {
                        if(col.columnIndex === 0) return '';
                        return `<tr class="align-top">
                            <td class="fw-semibold text-muted pb-2">${col.title}</td>
                            <td class="text-dark pb-2">${col.data}</td>
                        </tr>`;
                    }).join('');
                    return data ? $('<div class="table-responsive p-2">')
                        .append('<table class="table table-sm table-borderless align-middle mb-0"><tbody>' + data + '</tbody></table>') : false;
                }
            }
        },
        columnDefs: [
            { className: 'dtr-control', orderable: false, targets: -1 },
            { width: "5%", targets: 0 },
            { width: "20%", targets: 1 },
            { width: "15%", targets: 2 },
            { width: "10%", targets: 3 },
            { width: "15%", targets: 4 },
            { width: "10%", targets: 5 },
            { width: "15%", targets: 6 },
            { width: "5%", targets: 7 } // responsive + column
        ],
        dom: '<"dt-top d-flex justify-content-between"<"dt-length"l><"dt-search"f>>t<"dt-bottom pt-2"p>',
        language: {
            search: "_INPUT_",
            searchPlaceholder: "Search products...",
            zeroRecords: "No matching products found",
            emptyTable: "No products available",
            paginate: { previous: "‹", next: "›" }
        },
        order: [[0, 'asc']],
        pageLength: 10,
        lengthMenu: [10, 25, 50, 100]
    });

    $(document).on('show.bs.modal', '.dtr-bs-modal', function() {
        $(this).addClass('d-flex align-items-center justify-content-center');
        $(this).find('.modal-dialog').removeClass('modal-dialog-scrollable').addClass('m-0');
    });
    $(document).on('hidden.bs-modal', '.dtr-bs-modal', function() { $(this).remove(); });
});
</script>
@endpush