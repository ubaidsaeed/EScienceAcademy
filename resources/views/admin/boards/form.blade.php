@extends('layouts.admin.app')
@section('title', 'Boards')
@section('content')
<div class="app-content main-content">
    <div class="side-app">
        <div class="page-header d-lg-flex d-block">
            <div class="page-leftheader">
                <h4 class="page-title">
                    @if($page_type == 'create')
                        Create Board
                    @else
                        Edit Board
                    @endif
                </h4>
            </div>
            <div class="page-rightheader">
                <a href="{{ route('boards.index') }}" class="btn btn-secondary">
                    <i class="fa fa-arrow-left"></i> Back to List
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        @if($page_type == 'create')
                            <form action="{{ route('boards.store') }}" method="POST" enctype="multipart/form-data">
                        @else
                            <form action="{{ route('boards.update', $board->id) }}" method="POST" enctype="multipart/form-data">
                                @method('PUT')
                        @endif
                            @csrf
                            
                            @if($page_type == 'edit')
                                <input type="hidden" name="id" value="{{ $board->id }}">
                            @endif

                            <div class="row">
                                <div class="mb-3 col-lg-6">
                                    <label for="name" class="form-label">Name *</label>
                                    <input type="text" class="form-control shadow-none @error('name') is-invalid @enderror" 
                                           id="name" name="name" 
                                           value="{{ old('name', $board->name ?? '') }}" 
                                           placeholder="Enter Board Name">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3 col-lg-6">
                                    <label for="image_url" class="form-label">
                                        Image 
                                        @if($page_type == 'edit' && $board->image_url)
                                            <br><small class="text-success d-none">Current image uploaded</small>
                                        @endif
                                    </label>
                                    
                                    @if($page_type == 'edit' && $board->image_url)
                                        <div class="mb-2">
                                            <img src="{{$board->image_url}}" 
                                                 alt="{{ $board->name }}" 
                                                 style="width: 100px; height: 100px; object-fit: cover; border-radius: 4px;" 
                                                 class="img-thumbnail mb-2">
                                            <br>
                                            
                                            <a href="{{$board->image_url}}" 
                                               target="_blank" class="btn btn-sm btn-outline-primary">
                                                <i class="fa fa-eye"></i> View
                                            </a>
                                            <br>
                                            <div class="form-check mt-2 ">
                                                <input class="form-check-input" type="checkbox" name="remove_image" value="1" id="removeImage">
                                                <label class="form-check-label text-danger" for="removeImage">
                                                    Remove current image
                                                </label>
                                            </div>
                                        </div>
                                    @endif
                                    
                                    <input type="file" class="form-control shadow-none @error('image_url') is-invalid @enderror" 
                                           id="image_url" name="image_url"
                                           {{ $page_type == 'create' ? 'required' : '' }}>
                                    <small class="text-muted">
                                        @if($page_type == 'create')
                                            Required. Allowed: jpeg, png, jpg, gif, webp. Max: 2MB
                                        @else
                                            Optional. Leave empty to keep existing image
                                        @endif
                                    </small>
                                    @error('image_url')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3 col-lg-6">
                                    <label for="status" class="form-label">Status *</label>
                                    <select class="form-control select2-show-search custom-select @error('status') is-invalid @enderror" 
                                            id="status" name="status">
                                        <option value="" disabled selected>Select Status</option>
                                        <option value="active" {{ old('status', $board->status ?? '') == 'active' ? 'selected' : '' }}>
                                            Active
                                        </option>
                                        <option value="inactive" {{ old('status', $board->status ?? '') == 'inactive' ? 'selected' : '' }}>
                                            Inactive
                                        </option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3 col-lg-12">
                                    <label for="content" class="form-label">Content</label>
                                    <textarea class="form-control @error('content') is-invalid @enderror" 
                                              id="myeditorinstance" name="content" 
                                              rows="6">{{ old('content', $board->content ?? '') }}</textarea>
                                    @error('content')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="modal-footer">
                                <a href="{{ route('boards.index') }}" class="btn btn-secondary">Cancel</a>
                                 <button type="submit" class="btn btn-primary shadow-none">
                                    @if($page_type == 'create')
                                        <i class="fa fa-save"></i> Save
                                    @else
                                        <i class="fa fa-sync"></i> Update
                                    @endif
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('style')
<link rel="stylesheet" href="{{ asset('assets/plugins/select2/select2.min.css') }}">
@endpush

@push('script')
<script src="{{ asset('assets/plugins/select2/select2.full.min.js') }}"></script>
<script src="{{ asset('assets/plugins/ckeditor/ckeditor.js') }}"></script>
<script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>

<script>
$(document).ready(function() {
    // Initialize Select2
    $('#status').select2({
        placeholder: 'Select Status',
        allowClear: false,
        width: '100%'
    });
    
    // Initialize CKEditor
    ClassicEditor
        .create(document.querySelector('#myeditorinstance'), {
            toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|', 'undo', 'redo'],
            height: '200px'
        })
        .catch(error => {
            console.error(error);
        });
    
    // Form validation
    $('form').on('submit', function(e) {
        var isValid = true;
        
        // Basic validation
        $('.form-control').each(function() {
            if ($(this).prop('required') && !$(this).val()) {
                $(this).addClass('is-invalid');
                isValid = false;
            } else {
                $(this).removeClass('is-invalid');
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                text: 'Please fill all required fields correctly.',
            });
        }
    });
});
</script>
@endpush