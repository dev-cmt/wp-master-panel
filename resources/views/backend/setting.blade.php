<x-backend-layout title="Setting">
    @push('css')

    @endpush

    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <h1 class="page-title fw-semibold fs-18 mb-0">Settings</h1>
        <div class="ms-md-1 ms-0">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Settings</li>
                </ol>
            </nav>
        </div>
    </div>
    <form action="{{ route('setting.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-xl-7">
                <div class="card custom-card">
                    <div class="card-header justify-content-between">
                        <div class="card-title">
                            Info
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" id="email"
                                       value="{{ $settings ? $settings->email : '' }}">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="phone" class="form-label">Phone</label>
                                <input type="number" name="phone" class="form-control" id="phone" value="{{ $settings ? $settings->phone : '' }}">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label" for="address">Address</label>
                            <textarea name="address" id="address" rows="3" class="form-control">{{ $settings ? $settings->address : '' }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-5">

                <div class="card custom-card">
                    <div class="card-header justify-content-between">
                        <div class="card-title">Logo</div>
                    </div>
                    <div class="card-body">
                        @if ($settings)
                            @if ($settings->logo)
                                <img class="mb-2" src="{{ asset($settings->logo) }}" alt="{{ $settings->logo }}" width="50">
                            @endif
                        @endif
                        <input type="file" name="logo" class="form-control">
                    </div>
                </div>
                <div class="card custom-card">
                    <div class="card-header justify-content-between">
                        <div class="card-title">Favicon </div>
                    </div>
                    <div class="card-body">
                        @if ($settings)
                            @if ($settings->favicon)
                                <img class="mb-2" src="{{ asset($settings->favicon) }}" alt="{{ $settings->favicon }}" width="50">
                            @endif
                        @endif
                        <input type="file" name="favicon" class="form-control">
                    </div>
                </div>
                <div class="mt-0">
                    <button type="submit" class="btn btn-success w-100">Update</button>
                </div>
            </div>
        </div>
    </form>

    @push('js')
        
    @endpush
</x-backend-layout>
