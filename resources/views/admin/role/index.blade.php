@extends('layouts.admin.app')
@section('title', 'Roles')

@push('styles')
<style>
    .form-check-input {
        margin-right: 5px;
    }
    .form-label {
        margin-bottom: 0;
        cursor: pointer;
    }
    .module-checkbox {
        margin-left: 20px;
        font-weight: 600;
    }
    .permission-table th {
        background-color: #f8f9fa;
    }
    .permission-table td {
        vertical-align: middle;
    }
    .swal2-high-zindex {
        z-index: 999999 !important;
    }
</style>
@endpush

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="app-content main-content">
    <div class="side-app">
        <div class="page-header d-lg-flex d-block">
            <div class="page-leftheader">
                <h4 class="page-title">Role's</h4>
            </div>
            <div class="page-rightheader ms-md-auto">
                <div class="btn-list">
                    @can('create roles')
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#create">
                        <i class="fa fa-plus me-2"></i>Create Role
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
                                        <th class="border-bottom-0">Role Name</th>
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

<!-- Create Role Modal -->
<div class="modal fade" id="create" tabindex="-1" role="dialog" aria-labelledby="createRoleModal" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createRoleModal">Create New Role</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <form id="createRoleForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="roleName" class="form-label">Role Name</label>
                        <input type="text" name="name" class="form-control" id="roleName" placeholder="Enter role name" required>
                        <div class="invalid-feedback" id="nameError"></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Permissions</label>
                        <div class="table-responsive">
                            <table class="table table-bordered permission-table">
                                <thead>
                                    <tr>
                                        <th width="20">
                                            <input type="checkbox" id="checkAllPermissions" class="form-check-input">
                                        </th>
                                        <th>Module</th>
                                        @foreach($permissionTypes as $type)
                                            <th>{{ ucfirst($type) }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(is_array($modules) && count($modules) > 0)
                                        @foreach($modules as $module)
                                            @php
                                                // Ensure proper string values
                                                $moduleName = (string)($module['name'] ?? '');
                                                $displayName = (string)($module['display_name'] ?? $moduleName);
                                                $sanitizedModuleName = preg_replace('/[^a-zA-Z0-9]/', '', $moduleName);
                                            @endphp
                                            <tr>
                                                <td>
                                                    <input type="checkbox" class="form-check-input module-checkbox" 
                                                           data-module="{{ $sanitizedModuleName }}">
                                                </td>
                                                <td>{{ ucwords(str_replace(['_', '-'], ' ', $displayName)) }}</td>
                                                
                                                @foreach($permissionTypes as $type)
                                                    <td>
                                                        @php
                                                            $permission = $module['permissions'][$type] ?? null;
                                                            $permissionId = $permission['id'] ?? null;
                                                        @endphp
                                                        @if($permissionId)
                                                            <div class="form-check">
                                                                <input class="form-check-input permission-checkbox isscheck_{{ $sanitizedModuleName }}" 
                                                                       name="dash[]" type="checkbox" value="{{ $permissionId }}" 
                                                                       id="permission_{{ $permissionId }}">
                                                                <label class="form-check-label" for="permission_{{ $permissionId }}">
                                                                    {{ ucfirst($type) }}
                                                                </label>
                                                            </div>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="{{ count($permissionTypes) + 2 }}" class="text-center">No modules found</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        <div class="invalid-feedback" id="permissionsError"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" onclick="addRole()" class="btn btn-primary">
                        <i class="fa fa-save me-2"></i>Save Role
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Role Modal -->
<!-- Edit Role Modal -->
<div class="modal fade" id="update" tabindex="-1" role="dialog" aria-labelledby="editRoleModal" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editRoleModal">Edit Role</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <form id="editRoleForm">
                @csrf
                <input type="hidden" name="editid" id="editid">
                <div class="modal-body">
                    <!-- Hidden template that will be restored by JavaScript -->
                    <div id="updateModalContent" style="display: none;">
                        <div class="mb-3">
                            <label for="updateName" class="form-label">Role Name</label>
                            <input type="text" name="name" class="form-control" id="updateName" required>
                            <div class="invalid-feedback" id="updateNameError"></div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Permissions</label>
                            <div class="table-responsive">
                                <table class="table table-bordered permission-table">
                                    <thead>
                                        <tr>
                                            <th width="20">
                                                <input type="checkbox" id="checkAllPermissionsEdit" class="form-check-input">
                                            </th>
                                            <th>Module</th>
                                            @foreach($permissionTypes as $type)
                                                <th>{{ ucfirst($type) }}</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(is_array($modules) && count($modules) > 0)
                                            @foreach($modules as $module)
                                                @php
                                                    $moduleName = (string)($module['name'] ?? '');
                                                    $displayName = (string)($module['display_name'] ?? $moduleName);
                                                    $sanitizedModuleName = preg_replace('/[^a-zA-Z0-9]/', '', $moduleName);
                                                @endphp
                                                <tr>
                                                    <td>
                                                        <input type="checkbox" class="form-check-input module-checkbox-edit" 
                                                               data-module="{{ $sanitizedModuleName }}">
                                                    </td>
                                                    <td>{{ ucwords(str_replace(['_', '-'], ' ', $displayName)) }}</td>
                                                    
                                                    @foreach($permissionTypes as $type)
                                                        <td>
                                                            @php
                                                                $permission = $module['permissions'][$type] ?? null;
                                                                $permissionId = $permission['id'] ?? null;
                                                            @endphp
                                                            @if($permissionId)
                                                                <div class="form-check">
                                                                    <input class="form-check-input permission-checkbox-edit isscheck_{{ $sanitizedModuleName }}" 
                                                                           name="permission[]" type="checkbox" value="{{ $permissionId }}" 
                                                                           id="edit_permission_{{ $permissionId }}">
                                                                    <label class="form-check-label" for="edit_permission_{{ $permissionId }}">
                                                                        {{ ucfirst($type) }}
                                                                    </label>
                                                                </div>
                                                            @else
                                                                <span class="text-muted">-</span>
                                                            @endif
                                                        </td>
                                                    @endforeach
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="{{ count($permissionTypes) + 2 }}" class="text-center">No modules found</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                            <div class="invalid-feedback" id="editPermissionsError"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" onclick="updateRole()" class="btn btn-primary">
                        <i class="fa fa-save me-2"></i>Update Role
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('build/assets/admin/js/custom/role.js') }}"></script>
@endpush