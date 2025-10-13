<x-backend-layout title="Edit Profile">

    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <h1 class="page-title fw-semibold fs-18 mb-0">Edit Profile</h1>
        <div class="ms-md-1 ms-0">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Profile</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Page Header Close -->



    <!-- Start::row-1 -->
    <div class="row justify-content-center">
        <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-12 col-xm-12">
            <div class="card custom-card overflow-hidden">
                <div class="card-body p-0">
                    <div class="contact-page-banner">
                        <div class="text-center">
                            @if($user->photo_path)
                                <span class="avatar avatar-xl avatar-rounded mb-3">
                                    <img src="{{ asset($user->photo_path) }}" alt="Profile" class="mt-2">
                                </span>
                            @endif
                            <h3 class="fw-semibold text-fixed-white">Update Profile !</h3>
                            <h6 class="text-fixed-white mb-4">Have any questions ? We would love to hear from you. </h6>
                            <a href="https://wa.me/8801681636068" target="_blank" class="btn btn-success btn-wave waves-effect waves-light">
                                WhatsApp <i class="ri-whatsapp-line ms-1 align-middle"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-10 col-xl-10 col-lg-10 col-md-10 col-sm-10 col-12">

            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PATCH')

                <div class="card custom-card contactus-form overflow-hidden">
                    <div class="card-header">
                        <div class="card-title">
                            Update Profile
                        </div>
                    </div>
                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success">{{ session('status') }}</div>
                        @endif
                        <div class="row gy-3">
                            <div class="col-xl-6">
                                <label class="form-label">Name</label>
                                <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                                @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="col-xl-6">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                                @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="col-xl-6">
                                <label class="form-label">Phone</label>
                                <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}">
                                @error('phone') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="col-xl-6">
                                <label class="form-label">Profile Photo</label>
                                <input type="file" name="photo_path" class="form-control">
                                @error('photo_path') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="text-center">
                            <button type="submit"  class="btn btn-primary-light btn-wave waves-effect waves-light">Update Profile</button>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>
    <!--End::row-1 -->

</x-backend-layout>
