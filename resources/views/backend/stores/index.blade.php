<x-backend-layout title="Store">
    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <h1 class="page-title fw-semibold fs-18 mb-0">Store Management</h1>
        <div class="ms-md-1 ms-0">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Stores</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Store List -->
    <div class="card custom-card">
        <div class="card-header justify-content-between">
            <div class="card-title">Store List</div>
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createStoreModal">
                <i class="ri-add-line me-1"></i>Add Store
            </button>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table text-nowrap table-hover">
                    <thead>
                        <tr>
                            <th>SL</th>
                            <th>Name</th>
                            <th>Prefix</th>
                            <th>Base URL</th>
                            <th>API Key</th>
                            <th>API Secret</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stores as $key => $store)
                        <tr>
                            <td>{{ $stores->firstItem() + $key }}</td>
                            <td>{{ $store->name }}</td>
                            <td>{{ $store->prefix }}</td>
                            <td>{{ $store->base_url }}</td>
                            <td>{{ $store->api_key }}</td>
                            <td>{{ $store->api_secret }}</td>
                            <td>
                                <span class="badge bg-{{ $store->status ? 'success' : 'danger' }}-transparent">
                                    {{ $store->status ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-list">
                                    <button type="button" class="btn btn-warning-light btn-sm edit-store"
                                        data-id="{{ $store->id }}"
                                        data-name="{{ $store->name }}"
                                        data-prefix="{{ $store->prefix }}"
                                        data-base_url="{{ $store->base_url }}"
                                        data-api_key="{{ $store->api_key }}"
                                        data-api_secret="{{ $store->api_secret }}"
                                        data-custom_secret="{{ $store->custom_secret }}"
                                        data-ep_order_store="{{ $store->ep_order_store }}"
                                        data-ep_order_update="{{ $store->ep_order_update }}"
                                        data-ep_order_status="{{ $store->ep_order_status }}"
                                        data-ep_order_delete="{{ $store->ep_order_delete }}"
                                        data-status="{{ $store->status }}"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editStoreModal">
                                        <i class="ri-pencil-line"></i>
                                    </button>

                                    <form action="{{ route('stores.destroy', $store->id) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-danger-light btn-sm" onclick="return confirm('Delete this store?')">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                            <tr><td colspan="7" class="text-center">No stores found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-3">
                {{ $stores->links() }}
            </div>
        </div>
    </div>

    <!-- Create Store Modal -->
    <div class="modal fade" id="createStoreModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('stores.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h6 class="modal-title">Add New Store</h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body row g-3">
                        @include('backend.stores.__form', ['store' => null])
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Create Store</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Store Modal -->
    <div class="modal fade" id="editStoreModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('stores.update') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h6 class="modal-title">Edit Store</h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body row g-3">
                        <input type="hidden" id="edit_id" name="id">
                        @include('backend.stores.__form', ['store' => null])
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update Store</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@push('js')
<script>
    $('#createStoreModal').on('show.bs.modal', function (e) {
        const form = $(this).find('form')[0];
        form.reset();

        // Optional: clear validation messages (if any)
        $(this).find('.is-invalid').removeClass('is-invalid');
        $(this).find('.invalid-feedback').remove();
    });
</script>

<script>
    $(document).on('click', '.edit-store', function() {
        $('#edit_id').val($(this).data('id'));
        $('input[name="name"]').val($(this).data('name'));
        $('input[name="prefix"]').val($(this).data('prefix'));
        $('input[name="base_url"]').val($(this).data('base_url'));
        $('input[name="api_key"]').val($(this).data('api_key'));
        $('input[name="api_secret"]').val($(this).data('api_secret'));
        $('input[name="custom_secret"]').val($(this).data('custom_secret'));
        $('input[name="ep_order_store"]').val($(this).data('ep_order_store'));
        $('input[name="ep_order_update"]').val($(this).data('ep_order_update'));
        $('input[name="ep_order_status"]').val($(this).data('ep_order_status'));
        $('input[name="ep_order_delete"]').val($(this).data('ep_order_delete'));
        $('select[name="status"]').val($(this).data('status'));
    });
</script>
@endpush
</x-backend-layout>
