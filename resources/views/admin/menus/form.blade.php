@extends('layouts.admin.app')

@section('title', $record ? 'Edit ' . ucfirst($type) : 'Create ' . ucfirst($type))

@push('style')
<link href="{{ asset('assets/admin/plugins/select2/css/select2.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<div class="app-content main-content">
        <div class="side-app">
<div class="page-header d-lg-flex d-block">
    <div class="page-leftheader">
        <h4 class="page-title">{{ $record ? 'Edit' : 'Create' }} {{ ucfirst($type) }}</h4>
    </div>
    <div class="page-rightheader">
        <div class="btn-list">
            @if($type === 'menu')
                <a href="{{ route('menus.index') }}" class="btn btn-secondary">Back</a>
            @elseif($type === 'submenu')
                <a href="{{ route('menus.submenu', ['menuId' => $menuId]) }}" class="btn btn-secondary">Back</a>
            @elseif($type === 'subchild')
                <a href="{{ route('menus.subchild', ['menuId' => $menuId, 'submenuId' => $submenuId]) }}" 
                   class="btn btn-secondary">Back</a>
            @endif
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form action="{{ route('menus.store-update') }}" method="POST" id="menuForm">
                    @csrf
                    
                    <!-- Hidden fields -->
                    <input type="hidden" name="id" value="{{ $record->id ?? '' }}">
                    <input type="hidden" name="type" value="{{ $type }}">
                    @if($type === 'submenu')
                        <input type="hidden" name="menu_id" value="{{ $menuId }}">
                    @elseif($type === 'subchild')
                        <input type="hidden" name="menu_id" value="{{ $menuId }}">
                        <input type="hidden" name="submenu_id" value="{{ $submenuId }}">
                    @endif

                    <div class="row">
                        <!-- Name Field -->
                        <div class="mb-3 col-lg-6">
                            <label class="form-label">Name <span class="text-danger">*</span></label>
                            <input class="form-control" type="text" name="name" 
                                   value="{{ old('name', $record->name ?? '') }}" 
                                   required>
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Page Selection -->
                        <div class="mb-3 col-lg-6">
                            <label class="form-label">Page</label>
                            <select class="form-control select2" name="page_id" id="pageSelect" 
                                    data-placeholder="Choose Page">
                                <option value="">{{ 'Choose...' }}</option>
                                @foreach($pageData as $page)
                                    <option value="{{ $page->id }}"
                                        {{ old('page_id', $record->page_id ?? '') == $page->id ? 'selected' : '' }}>
                                        {{ $page->title }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Select a page or use custom link below</small>
                        </div>

                        <!-- Priority Field -->
                        <div class="mb-3 col-lg-6">
                            <label class="form-label">Priority <span class="text-danger">*</span></label>
                            <input class="form-control" type="number" name="priority" 
                                   value="{{ old('priority', $record->priority ?? 0) }}" 
                                   required min="0">
                        </div>

                        <!-- Custom Link Field -->
                        <div class="mb-3 col-lg-6">
                            <label class="form-label">Custom Link</label>
                            <input class="form-control" type="text" name="link" 
                                   value="{{ old('link', $record->link ?? '') }}" 
                                   placeholder="https://example.com">
                            <small class="text-muted">If filled, will override selected page</small>
                        </div>

                        <!-- Target Window -->
                        <div class="mb-3 col-lg-6">
                            <label class="form-label">Target Window <span class="text-danger">*</span></label>
                            <select class="form-control select2" name="target_window" required>
                                <option value="">Choose....</option>
                                <option value="_blank" 
                                    {{ old('target_window', $record->target_window ?? '') == '_blank' ? 'selected' : '' }}>
                                    Blank (New Tab)
                                </option>
                                <option value="_self"
                                    {{ old('target_window', $record->target_window ?? '') == '_self' ? 'selected' : '' }}>
                                    Self (Same Tab)
                                </option>
                            </select>
                        </div>

                        <!-- Status -->
                        <div class="mb-3 col-lg-6">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-control select2" name="status" required>
                                <option value="">Choose....</option>
                                <option value="active"
                                    {{ old('status', $record->status ?? '') == 'active' ? 'selected' : '' }}>
                                    Active
                                </option>
                                <option value="inactive"
                                    {{ old('status', $record->status ?? '') == 'inactive' ? 'selected' : '' }}>
                                    Inactive
                                </option>
                            </select>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="col-12">
                            <div class="modal-footer">
                                @if($type === 'menu')
                                    <a href="{{ route('menus.index') }}" class="btn btn-secondary">Close</a>
                                @elseif($type === 'submenu')
                                    <a href="{{ route('menus.submenu', ['menuId' => $menuId]) }}" 
                                       class="btn btn-secondary">Close</a>
                                @elseif($type === 'subchild')
                                    <a href="{{ route('menus.subchild', ['menuId' => $menuId, 'submenuId' => $submenuId]) }}" 
                                       class="btn btn-secondary">Close</a>
                                @endif
                                <button type="submit" class="btn btn-primary shadow-none">
                                    <i class="fa fa-save"></i> {{ $record ? 'Update' : 'Save' }}
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
</div>
@endsection

@push('script')
<script src="{{ asset('assets/admin/plugins/select2/js/select2.min.js') }}"></script>
<script>
    $(document).ready(function() {
        // Initialize Select2
        $('.select2').select2({
            width: '100%'
        });
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