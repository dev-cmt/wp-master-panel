<x-backend-layout title="SEO Setting">
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

    <div class="row">
        @foreach ($pages as $page)
        <div class="col-xl-6">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title text-uppercase">{{ $page->title }} Page</div>
                </div>
                <div class="card-body">
                    <form action="{{ route('settings.seo.update',$page) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label>Meta Title</label>
                            <input type="text" name="meta_title" class="form-control"
                                value="{{ old('meta_title', optional($page->seo)->meta_title) }}">
                        </div>
                        <div class="mb-3">
                            <label>Meta Description</label>
                            <textarea name="meta_description" class="form-control" rows="3">{{ old('meta_description', optional($page->seo)->meta_description) }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label>Meta Keywords</label>
                            <input type="text" name="meta_keywords" class="form-control"
                                value="{{ old('meta_keywords', optional($page->seo)->meta_keywords) }}">
                        </div>
                        <div class="mb-3">
                            <label>OG Image</label>
                            <input type="file" name="og_image" class="form-control">
                            @if(optional($page->seo)->og_image)
                                <img src="{{ asset(optional($page->seo)->og_image) }}" height="80" class="mt-2">
                            @endif
                        </div>
                        <button class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
</div>
</x-backend-layout>
