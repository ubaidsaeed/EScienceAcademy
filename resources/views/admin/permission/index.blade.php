@extends('layouts.admin.app')
@section('title', 'Permissions')

@section('style')
@endsection
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="app-content main-content">
        <div class="side-app">
            <!-- Row -->
            <div class="page-header d-lg-flex d-block">
                <div class="page-leftheader">
                    <h4 class="page-title">Permission's</h4>
                </div>
                <div class="page-rightheader ms-md-auto">
                    <div class=" btn-list">
                        @can('create permissions')
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#createPermission">
                            <i class="fa fa-plus me-2"></i>Create Permission
                        </button>
                        @endcan
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <!--div-->
                    <div class="card">
                        <div class="card-header border-bottom-0">
                            <div class="card-title">  </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="details-datatable"
                                    class="table table-striped table-bordered border-bottom text-nowrap">
                                    <thead>
                                        <tr>
                                            <th class="border-bottom-0">#ID</th>
                                            <th class="border-bottom-0">Permission Name</th>
                                            <th class="border-bottom-0">Action</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                       

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!--div-->
                </div>
            </div>
        </div>
    </div>
    <!-- /Row -->
    <!--create Modal -->
    <div class="modal fade" id="createPermission" tabindex="-1" role="dialog" aria-labelledby="largemodal"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="largemodal1">Create Permission</h5>
                    <button class="btn btn-close shadow-none " data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form >
                        @csrf
                        <div class="mb-3">
                            <label for="exampleFormControlInput1" class="form-label">Permission Name</label>

                            <input type="text" class="form-control shadow-none" id="permissionName" name="permissionName"
                                placeholder="Enter Permission">
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary shadow-none" id="save"
                        onclick="addPermission()"><i class="fa fa-save me-2"></i>Save Permission</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    </div>
    </div><!-- end app-content-->
    {{-- update model --}}
    <div class="modal fade" id="updateModel" tabindex="-1" role="dialog" aria-labelledby="largemodal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="largemodal1">Update Permission</h5>
                    <button class="btn btn-close shadow-none " data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form >
                        @csrf
                        <div class="mb-3">
                            <label for="exampleFormControlInput1" class="form-label">Permission Name</label>
                            <input type="text" class="form-control shadow-none" id="updateName" name="updateName">
                            <input type="hidden" class="form-control shadow-none" id="updateId" name="updateId">
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary shadow-none" id="update"
                        onclick="updatePermission()"><i class="fa fa-save me-2"></i>Update Permission</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    {{-- end update modal --}}
@endsection
@push('script')
    <script src="{{ asset('build/assets/admin/js/custom/permission.js') }}"></script>
@endpush
