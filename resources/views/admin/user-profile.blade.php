@extends('layouts.admin.app')

@section('content')

<style>
    body {
        background-color: #f8f9fa;
    }

    .profile-card {
        max-width: 600px;
        margin: 50px auto;
        padding: 30px;
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .profile-card h2 {
        margin-bottom: 20px;
        font-weight: 600;
        color: #333;
    }

    .form-label {
        font-weight: 500;
        color: #555;
    }

    .form-control {
        border-radius: 8px;
        padding: 10px;
        border: 1px solid #ddd;
    }

    .form-control:focus {
        border-color: #007bff;
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.25);
    }

    .btn-update {
        background-color: #007bff;
        color: #fff;
        border: none;
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 500;
        transition: background-color 0.3s ease;
    }

    .btn-update:hover {
        background-color: #0056b3;
    }

    .password-toggle {
        position: absolute;
        right: 10px;
        top: 70%;
        transform: translateY(-50%);
        cursor: pointer;
        color: #777;
    }

    .password-toggle:hover {
        color: #333;
    }

    .avatar-container {
        position: relative;
        width: 200px;
        height: 200px;
        margin: 0 auto 20px;
    }

    .avatar-preview {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #ddd;
    }

    .avatar-upload-btn {
        position: absolute;
        bottom: 10px;
        right: 10px;
        background: #007bff;
        color: white;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }
</style>

<div class="app-content main-content">
    <div class="side-app">
<div class="page-header d-lg-flex d-block">
    <div class="page-leftheader">
        <h4 class="page-title">Profile</h4>
    </div>
    <div class="page-rightheader">
        <div class="btn-list"></div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6 col-md-12">
        <div class="card">
            <div class="card-body">
                <h1 class="mb-4">Personal Information</h1>
                <div class="profile-card">
                    <form id="profileForm" action="{{ route('admin.profile.update-name') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="text-center mb-4">
                            <div class="avatar-container">
                                <img id="selectedAvatar" 
                                 src="{{ auth()->user()->avatar 
                                        ? asset('storage/app/public/' . auth()->user()->avatar) 
                                        : asset('build/assets/admin/images/userEmptyImage.jpg') }}"
                                 class="avatar-preview" 
                                 alt="Profile Picture">

                                
                                <div class="avatar-upload-btn" onclick="document.getElementById('avatarInput').click()">
                                    <i class="fas fa-camera"></i>
                                </div>
                                
                                <input type="file" 
                                       name="avatar" 
                                       id="avatarInput" 
                                       class="d-none" 
                                       accept="image/*"
                                       onchange="displaySelectedImage(event, 'selectedAvatar')">
                            </div>
                            
                            @error('avatar')
                                <div class="alert alert-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="fullName" class="form-label">Full Name</label>
                            <input type="text" 
                                   class="form-control @error('full_name') is-invalid @enderror" 
                                   name="full_name" 
                                   id="fullName"
                                   value="{{ old('full_name', auth()->user()->name) }}" 
                                   required>
                            @error('full_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" 
                                   class="form-control" 
                                   id="email" 
                                   readonly
                                   value="{{ auth()->user()->email }}">
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-update btn-lg">
                                <i class="fas fa-save me-2"></i>Update Profile
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6 col-md-12">
        <div class="card">
            <div class="card-body">
                <h1 class="mb-4">Change Password</h1>
                <div class="profile-card">
                    <form id="passwordForm" action="{{ route('admin.profile.update-password') }}" method="POST">
                        @csrf
                        
                        <div class="mb-4 position-relative">
                            <label for="old_password" class="form-label">Old Password</label>
                            <input type="password" 
                                   class="form-control @error('old_password') is-invalid @enderror" 
                                   name="old_password" 
                                   id="old_password" 
                                   required>
                            <span class="password-toggle" onclick="togglePasswordVisibility('old_password', this)">
                                <i class="fas fa-eye"></i>
                            </span>
                            @error('old_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4 position-relative">
                            <label for="new_password" class="form-label">New Password</label>
                            <input type="password" 
                                   class="form-control @error('new_password') is-invalid @enderror" 
                                   name="new_password" 
                                   id="new_password" 
                                   required>
                            <span class="password-toggle" onclick="togglePasswordVisibility('new_password', this)">
                                <i class="fas fa-eye"></i>
                            </span>
                            @error('new_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4 position-relative">
                            <label for="new_password_confirmation" class="form-label">Confirm Password</label>
                            <input type="password" 
                                   class="form-control @error('new_password_confirmation') is-invalid @enderror" 
                                   name="new_password_confirmation" 
                                   id="new_password_confirmation" 
                                   required>
                            <span class="password-toggle" onclick="togglePasswordVisibility('new_password_confirmation', this)">
                                <i class="fas fa-eye"></i>
                            </span>
                            @error('new_password_confirmation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-update btn-lg">
                                <i class="fas fa-key me-2"></i>Update Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</div>
</div>
@push('style')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endpush

@endsection
@push('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('build/assets/admin/js/custom/message.js') }}"></script>
<script>
    function togglePasswordVisibility(inputId, toggleElement) {
        const passwordInput = document.getElementById(inputId);
        const icon = toggleElement.querySelector('i');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }

    function displaySelectedImage(event, elementId) {
        const selectedImage = document.getElementById(elementId);
        const fileInput = event.target;

        if (fileInput.files && fileInput.files[0]) {
            const file = fileInput.files[0];
            
            // Validate file size (2MB)
            if (file.size > 2 * 1024 * 1024) {
                Swal.fire({
                    icon: 'error',
                    title: 'File too large',
                    text: 'Please select an image smaller than 2MB',
                });
                fileInput.value = '';
                return;
            }

            // Validate file type
            const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
            if (!validTypes.includes(file.type)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid file type',
                    text: 'Please select a JPEG, PNG, JPG, or GIF image',
                });
                fileInput.value = '';
                return;
            }

            const reader = new FileReader();

            reader.onload = function(e) {
                selectedImage.src = e.target.result;
            };

            reader.readAsDataURL(file);
        }
    }

    // Form submission with SweetAlert confirmation
    document.getElementById('profileForm')?.addEventListener('submit', function(e) {
        const fullName = document.getElementById('fullName').value;
        if (!fullName.trim()) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Required Field',
                text: 'Please enter your full name',
            });
        }
    });

    document.getElementById('passwordForm')?.addEventListener('submit', function(e) {
        const newPassword = document.getElementById('new_password').value;
        const confirmPassword = document.getElementById('new_password_confirmation').value;
        
        if (newPassword !== confirmPassword) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Password Mismatch',
                text: 'New password and confirmation password do not match',
            });
        }
    });

</script>
</script>
     @if (session()->has('success'))
        <script>
            setTimeout(() => {
                const Toast = Swal.mixin({
                    toast: true,
                    position: "top-end",
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.onmouseenter = Swal.stopTimer;
                        toast.onmouseleave = Swal.resumeTimer;
                    },
                });
                Toast.fire({
                    icon: "success",
                    title: '{{ session()->get('success') }}',
                });
            }, 3000);
        </script>
    @endif
     @if (session()->has('error'))
        <script>
            setTimeout(() => {
                const Toast = Swal.mixin({
                    toast: true,
                    position: "top-end",
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.onmouseenter = Swal.stopTimer;
                        toast.onmouseleave = Swal.resumeTimer;
                    },
                });
                Toast.fire({
                    icon: "error",
                    title: '{{ session()->get('error') }}',
                });
            }, 3000);
        </script>
    @endif
@endpush