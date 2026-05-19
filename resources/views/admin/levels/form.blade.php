@extends('layouts.admin.app')
@section('title', $page_type == 'create' ? 'Create Level' : 'Edit Level')

@section('content')
<div class="app-content main-content">
    <div class="side-app">
        <div class="page-header d-lg-flex d-block">
            <div class="page-leftheader">
                <h4 class="page-title">
                    @if($page_type == 'create')
                        Create Level
                    @else
                        Edit Level
                    @endif
                </h4>
            </div>
            <div class="page-rightheader">
                <a href="{{ route('levels.index') }}" class="btn btn-secondary">
                    <i class="fa fa-arrow-left"></i> Back to List
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        

                        <form action="{{ $form_url }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            
                            @if($page_type == 'edit')
                                @method('PUT')
                                <input type="hidden" name="id" value="{{ $level->id }}">
                            @endif

                            <div class="row">
                                <div class="mb-3 col-lg-6">
                                    <label for="name" class="form-label">Name *</label>
                                    <input type="text" class="form-control shadow-none @error('name') is-invalid @enderror" 
                                           id="name" name="name" 
                                           value="{{ old('name', $level->name ?? '') }}" 
                                           placeholder="Enter Level Name">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3 col-lg-6">
                                    <label for="board_id" class="form-label">Boards</label>
                                    <select class="form-control select2-show-search @error('board_id') is-invalid @enderror" 
                                            id="board_id" name="board_id[]" multiple>
                                        <option value="" disabled>Choose...</option>
                                        @foreach($boards as $board)
                                            @if($board->parent_id == null)
                                                <option value="{{ $board->id }}"
                                                    @if($page_type == 'edit' && $level->board_id == $board->id) selected @endif
                                                    @if(is_array(old('board_id')) && in_array($board->id, old('board_id'))) selected @endif>
                                                    {{ $board->name }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                    <small class="text-muted">Select multiple boards (Hold Ctrl/Cmd to select multiple)</small>
                                    @error('board_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3 col-lg-6 ">
                                    <label for="image_url" class="form-label">
                                        Image 
                                        @if($page_type == 'edit' && $level->image_url)
                                            <br><small class="text-success d-none ">Current image uploaded</small>
                                        @endif
                                    </label>
                                    
                                    @if($page_type == 'edit' && $level->image_url)
                                        <div class="mb-2">
                                            <img src="{{ asset('storage/app/public/level/' . $level->image_url) }}" 
                                                 alt="{{ $level->name }}" 
                                                 style="width: 100px; height: 100px; object-fit: cover; border-radius: 4px;" 
                                                 class="img-thumbnail mb-2">
                                            <br>
                                            <a href="{{ asset('storage/app/public/level/' . $level->image_url) }}" 
                                               target="_blank" class="btn btn-sm btn-outline-primary">
                                                <i class="fa fa-eye"></i> View
                                            </a>
                                            <div class="form-check mt-2">
                                                <input class="form-check-input" type="checkbox" name="remove_image" value="1" id="removeImage">
                                                <label class="form-check-label text-danger" for="removeImage">
                                                    Remove current image
                                                </label>
                                            </div>
                                        </div>
                                    @endif
                                    
                                    <input type="file" class="form-control shadow-none @error('image_url') is-invalid @enderror" 
                                           id="image_url" name="image_url">
                                    <small class="text-muted">
                                        Allowed: jpeg, png, jpg, gif, webp. Max: 2MB
                                        @if($page_type == 'create')
                                            (Required)
                                        @else
                                            (Optional - Leave empty to keep existing image)
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
                                        <option value="" disabled>Choose...</option>
                                        <option value="active" 
                                            {{ old('status', $level->status ?? '') == 'active' ? 'selected' : '' }}>
                                            Active
                                        </option>
                                        <option value="inactive"
                                            {{ old('status', $level->status ?? '') == 'inactive' ? 'selected' : '' }}>
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
                                              rows="6">{{ old('content', $level->content ?? '') }}</textarea>
                                    @error('content')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="modal-footer">
                                <a href="{{ route('levels.index') }}" class="btn btn-secondary">Cancel</a>
                                <button type="submit" class="btn btn-primary shadow-none">
                                    @if($page_type == 'create')
                                        <i class="fa fa-save"></i> {{ $button }}
                                    @else
                                        <i class="fa fa-sync"></i> {{ $button }}
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
    $('#board_id').select2({
        placeholder: 'Select Boards',
        allowClear: true,
        width: '100%'
    });
    
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

    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        $('.alert').alert('close');
    }, 5000);
});
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
                }
            });
            Toast.fire({
                icon: "success",
                title: '{{ session('success') }}'
            });
        }, 500);
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
                }
            });
            Toast.fire({
                icon: "error",
                title: '{{ session('error') }}'
            });
        }, 500);
    </script>
@endif
@endpush