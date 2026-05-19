@extends('layouts.admin.app')
@section('title', 'Users')

@push('styles')
<style>
    /* Add custom styles if needed */
    .select2-container {
        z-index: 9999 !important;
    }
    .swal2-high-zindex {
        z-index: 999999 !important;
    }
    .modal {
        z-index: 9999 !important;
    }
    .modal-backdrop {
        z-index: 9998 !important;
    }
</style>
@endpush

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="app-content main-content">
    <div class="side-app">
        <div class="page-header d-lg-flex d-block">
            <div class="page-leftheader">
                <h4 class="page-title">Users</h4>
            </div>
            <div class="page-rightheader ms-md-auto">
                <div class="btn-list">
                    @can('create users')
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#create">
                        <i class="fa fa-plus me-2"></i>Create User
                    </button>
                    @endcan
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="details-datatable" class="table table-striped table-bordered border-bottom text-nowrap">
                                <thead>
                                    <tr>
                                        <th class="border-bottom-0">ID</th>
                                        <th class="border-bottom-0">Name</th>
                                        <th class="border-bottom-0">Email</th>
                                        <th class="border-bottom-0">Role</th>
                                        <th class="border-bottom-0">Status</th>
                                        <th class="border-bottom-0">Actions</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create User Modal -->
<div class="modal fade" id="create" tabindex="-1" role="dialog" aria-labelledby="createUserModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createUserModal">Create New User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="createUserForm">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="mb-3 col-lg-6">
                            <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" id="name" required 
                                   placeholder="Enter full name">
                            <div class="invalid-feedback" id="nameError"></div>
                        </div>
                        
                        <div class="mb-3 col-lg-6">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" id="email" required 
                                   placeholder="Enter email address">
                            <div class="invalid-feedback" id="emailError"></div>
                        </div>
                        
                        <div class="mb-3 col-lg-6">
                            <label for="role" class="form-label">Select Role <span class="text-danger">*</span></label>
                            <select class="form-control select2-show-search" id="role" name="role" required>
                                <option value="">Select Role</option>
                                @foreach ($roles as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" id="roleError"></div>
                        </div>
                        
                        <div class="mb-3 col-lg-6">
                            <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="password" name="password" class="form-control" id="password" required 
                                       minlength="8" placeholder="Enter password">
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                    <i class="fa fa-eye"></i>
                                </button>
                            </div>
                            <small class="form-text text-muted">Minimum 8 characters</small>
                            <div class="invalid-feedback" id="passwordError"></div>
                        </div>
                        
                        <div class="mb-3 col-lg-12">
                            <label for="status" class="form-label">Select Status <span class="text-danger">*</span></label>
                            <select class="form-control select2-show-search" id="status" name="status" required>
                                <option value="">Select Status</option>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                            <div class="invalid-feedback" id="statusError"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-save me-2"></i>Save User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Update User Modal -->
<div class="modal fade" id="update" tabindex="-1" role="dialog" aria-labelledby="updateUserModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateUserModal">Update User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="updateUserForm">
                @csrf
                <input type="hidden" name="updateId" id="updateId">
                <div class="modal-body">
                    <div id="updateLoading" class="text-center d-none">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Loading user data...</p>
                    </div>
                    
                    <div id="updateContent">
                        <div class="row">
                            <div class="mb-3 col-lg-6">
                                <label for="updateName" class="form-label">Name <span class="text-danger">*</span></label>
                                <input type="text" name="updateName" class="form-control" id="updateName" required 
                                       placeholder="Enter full name">
                                <div class="invalid-feedback" id="updateNameError"></div>
                            </div>
                            
                            <div class="mb-3 col-lg-6">
                                <label for="updateEmail" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" name="updateEmail" class="form-control" id="updateEmail" required 
                                       placeholder="Enter email address">
                                <div class="invalid-feedback" id="updateEmailError"></div>
                            </div>
                            
                            <div class="mb-3 col-lg-6">
                                <label for="updateRole" class="form-label">Select Role <span class="text-danger">*</span></label>
                                <select class="form-control select2-show-search" id="updateRole" name="updateRole" required>
                                    <option value="">Select Role</option>
                                    @foreach ($roles as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback" id="updateRoleError"></div>
                            </div>
                            
                            <div class="mb-3 col-lg-6">
                                <label for="updateStatus" class="form-label">Status <span class="text-danger">*</span></label>
                                <select class="form-control select2-show-search" id="updateStatus" name="updateStatus" required>
                                    <option value="">Select Status</option>
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                                <div class="invalid-feedback" id="updateStatusError"></div>
                            </div>
                            
                            <div class="mb-3 col-lg-12">
                                <label for="updatePassword" class="form-label">Password (Leave empty to keep current)</label>
                                <div class="input-group">
                                    <input type="password" name="updatePassword" class="form-control" id="updatePassword" 
                                           minlength="8" placeholder="Enter new password">
                                    <button class="btn btn-outline-secondary" type="button" id="toggleUpdatePassword">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                </div>
                                <small class="form-text text-muted">Minimum 8 characters. Leave empty if you don't want to change.</small>
                                <div class="invalid-feedback" id="updatePasswordError"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-save me-2"></i>Update User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('build/assets/admin/js/custom/user.js') }}"></script>

<script>
    // Initialize Select2 when modal is shown
    $(document).ready(function() {
        // Initialize Select2 for modals
        function initSelect2(modalId) {
            $(modalId + ' .select2-show-search').each(function() {
                if ($(this).hasClass('select2-hidden-accessible')) {
                    $(this).select2('destroy');
                }
                $(this).select2({
                    dropdownParent: $(modalId),
                    width: '100%',
                    placeholder: $(this).attr('placeholder') || 'Select an option'
                });
            });
        }
        
        // Initialize Select2 when modal is shown
        $('#create, #update').on('shown.bs.modal', function() {
            initSelect2('#' + $(this).attr('id'));
        });
        
        // Destroy Select2 when modal is hidden to prevent conflicts
        $('#create, #update').on('hidden.bs.modal', function() {
            $(this).find('.select2-show-search').select2('destroy');
        });
        
        // Password toggle functionality
        $(document).on('click', '#togglePassword', function() {
            const passwordField = $('#password');
            const icon = $(this).find('i');
            const type = passwordField.attr('type') === 'password' ? 'text' : 'password';
            passwordField.attr('type', type);
            icon.toggleClass('fa-eye fa-eye-slash');
        });
        
        $(document).on('click', '#toggleUpdatePassword', function() {
            const passwordField = $('#updatePassword');
            const icon = $(this).find('i');
            const type = passwordField.attr('type') === 'password' ? 'text' : 'password';
            passwordField.attr('type', type);
            icon.toggleClass('fa-eye fa-eye-slash');
        });
        
        // Clear form when create modal is closed
        $('#create').on('hidden.bs.modal', function() {
            $('#createUserForm')[0].reset();
            clearValidationErrors('create');
        });
        
        // Clear form when update modal is closed
        $('#update').on('hidden.bs.modal', function() {
            $('#updateUserForm')[0].reset();
            clearValidationErrors('update');
        });
    });
    
    // Helper function to clear validation errors
    function clearValidationErrors(formType) {
        const prefix = formType === 'create' ? '' : 'update';
        
        $(`#${prefix}name`).removeClass('is-invalid');
        $(`#${prefix}email`).removeClass('is-invalid');
        $(`#${prefix}role`).removeClass('is-invalid');
        $(`#${prefix}status`).removeClass('is-invalid');
        
        if (formType === 'create') {
            $('#password').removeClass('is-invalid');
            $('#passwordError').text('');
        } else {
            $('#updatePassword').removeClass('is-invalid');
            $('#updatePasswordError').text('');
        }
        
        $(`#${prefix}nameError`).text('');
        $(`#${prefix}emailError`).text('');
        $(`#${prefix}roleError`).text('');
        $(`#${prefix}statusError`).text('');
    }
    
    // Show loading in update modal
    function showUpdateLoading() {
        $('#updateLoading').removeClass('d-none');
        $('#updateContent').addClass('d-none');
    }
    
    // Hide loading in update modal
    function hideUpdateLoading() {
        $('#updateLoading').addClass('d-none');
        $('#updateContent').removeClass('d-none');
    }
</script>
@endpush