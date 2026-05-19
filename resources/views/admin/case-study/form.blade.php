@extends('layouts.admin.app')

@section('title', isset($caseStudy) ? 'Edit Case Study' : 'Create Case Study')

@push('style')
<link href="{{ asset('assets/admin/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
<style>
    .select2-container--default .select2-selection--multiple {
        min-height: 45px;
    }
    .tox-tinymce {
        border-radius: 5px;
        border: 1px solid #dee2e6 !important;
    }
</style>
@endpush

@section('content')
<div class="app-content main-content">
    <div class="side-app">
    <div class="page-header d-lg-flex d-block">
        <div class="page-leftheader">
            <h4 class="page-title">{{ isset($caseStudy) ? 'Edit' : 'Create' }} Case Study</h4>
        </div>
        <div class="page-rightheader">
            <div class="btn-list">
                <a href="{{ route('case-studies.index') }}" class="btn btn-secondary">Back</a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    
                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ isset($caseStudy) ? route('case-studies.update', $caseStudy->id) : route('case-studies.store') }}" 
                          method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="mb-3 col-lg-6">
                                <label class="form-label">Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control shadow-none" name="title"
                                       value="{{ old('title', $caseStudy->title ?? '') }}" 
                                       placeholder="Enter Title" required>
                                @error('title')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3 col-lg-6">
                                <label class="form-label">Image</label>
                                <input type="file" class="form-control shadow-none" name="image" 
                                       accept="image/*">
                                @error('image')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                
                                @if(isset($caseStudy) && $caseStudy->image)
                                    <div class="mt-2">
                                        <img src="{{ asset('storage/case-study/' . $caseStudy->image) }}" 
                                             alt="{{ $caseStudy->title }}" 
                                             style="width: 100px; height: 100px; object-fit: cover;">
                                        <p class="text-muted mt-1">
                                            <small>Current image: {{ $caseStudy->image }}</small>
                                        </p>
                                    </div>
                                @endif
                            </div>

                            <div class="mb-3 col-lg-6">
                                <label class="form-label">Board</label>
                                <select class="form-control select2" name="board[]" multiple 
                                        data-placeholder="Select Boards...">
                                    @foreach($boards as $board)
                                        <option value="{{ $board->id }}"
                                            {{ in_array($board->id, old('board', $selectedBoards ?? [])) ? 'selected' : '' }}>
                                            {{ $board->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('board')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3 col-lg-6">
                                <label class="form-label">Status <span class="text-danger">*</span></label>
                                <select class="form-control select2" name="status" required>
                                    <option value="">Select Status...</option>
                                    <option value="active" 
                                        {{ old('status', $caseStudy->status ?? '') == 'active' ? 'selected' : '' }}>
                                        Active
                                    </option>
                                    <option value="inactive"
                                        {{ old('status', $caseStudy->status ?? '') == 'inactive' ? 'selected' : '' }}>
                                        Inactive
                                    </option>
                                </select>
                                @error('status')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3 col-lg-12">
                                <label class="form-label">Content</label>
                                <textarea id="content" class="form-control" name="sections">{{ old('sections', $caseStudy->content ?? '') }}</textarea>
                                @error('sections')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="modal-footer">
                            <a href="{{ route('case-studies.index') }}" class="btn btn-secondary">Close</a>
                            <button type="submit" class="btn btn-primary shadow-none">
                                 <i class="fa fa-save me-2"></i>{{ $button }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Template Modal -->
<div class="modal fade" id="templateModal" tabindex="-1" aria-labelledby="templateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="templateModalLabel">Select Template</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <!-- Template 1: Team Section -->
                    <div class="col-md-4 mb-3">
                        <div class="card">
                            <div class="card-body text-center">
                                <i class="fa fa-users fa-2x mb-2 text-primary"></i>
                                <h6>Team Section</h6>
                                <button type="button" class="btn btn-sm btn-primary use-template" 
                                        data-content="team-template">
                                    Use Template
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Template 2: Hero Section -->
                    <div class="col-md-4 mb-3">
                        <div class="card">
                            <div class="card-body text-center">
                                <i class="fa fa-star fa-2x mb-2 text-warning"></i>
                                <h6>Hero Section</h6>
                                <button type="button" class="btn btn-sm btn-primary use-template" 
                                        data-content="hero-template">
                                    Use Template
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Template 3: Testimonial -->
                    <div class="col-md-4 mb-3">
                        <div class="card">
                            <div class="card-body text-center">
                                <i class="fa fa-comment fa-2x mb-2 text-success"></i>
                                <h6>Testimonial</h6>
                                <button type="button" class="btn btn-sm btn-primary use-template" 
                                        data-content="testimonial-template">
                                    Use Template
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Hidden Templates -->
<div id="team-template" style="display: none;">
    <div class="advisor-items text-center">
        <div class="row">
            <div class="col-lg-4 col-md-6 single-item">
                <div class="advisor-item">
                    <div class="info-box">
                        <div class="editable-image">
                            <img src="/path/to/image.jpg" alt="Team Member" class="advisor-img" style="width: 150px; height: 150px; object-fit: cover; border-radius: 50%;">
                        </div>
                        <div class="info-title">
                            <h4 class="editable-text" contenteditable="true">Professor. Nuri Paul</h4>
                            <span class="editable-text" contenteditable="true">Chemistry Specialist</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="hero-template" style="display: none;">
    <div class="hero-section text-center py-5" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
        <h1 class="display-4 editable-text" contenteditable="true">Case Study Title</h1>
        <p class="lead editable-text" contenteditable="true">A brief description of your case study goes here. Highlight the key achievements and outcomes.</p>
        <a href="#" class="btn btn-light btn-lg mt-3">Learn More</a>
    </div>
</div>

<div id="testimonial-template" style="display: none;">
    <div class="testimonial-section py-5" style="background-color: #f8f9fa;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="testimonial-card p-4" style="background: white; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                        <div class="testimonial-text">
                            <p class="editable-text" contenteditable="true" style="font-size: 1.1rem; line-height: 1.6;">
                                "This case study provided valuable insights that helped our team achieve significant results. The methodology was thorough and the findings were impactful."
                            </p>
                        </div>
                        <div class="testimonial-author mt-4">
                            <div class="d-flex align-items-center">
                                <img src="/path/to/client.jpg" alt="Client" style="width: 60px; height: 60px; border-radius: 50%; object-fit: cover;">
                                <div class="ms-3">
                                    <h5 class="mb-0 editable-text" contenteditable="true">John Smith</h5>
                                    <p class="text-muted mb-0 editable-text" contenteditable="true">CEO, Company Name</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

@endsection

@push('script')
<script src="{{ asset('assets/admin/plugins/select2/js/select2.min.js') }}"></script>
<script src="{{ asset('build/assets/admin/tinymce/tinymce.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        // Initialize Select2
        $('.select2').select2({
            width: '100%'
        });

        // Initialize TinyMCE - SIMPLIFIED VERSION
        tinymce.init({
            selector: '#content',
            height: 500,
            menubar: true,
            plugins: 'advlist autolink lists link image charmap print preview anchor ' +
                     'searchreplace visualblocks code fullscreen ' +
                     'insertdatetime media table paste code help wordcount',
            toolbar: 'undo redo | formatselect | ' +
                     'bold italic backcolor | alignleft aligncenter ' +
                     'alignright alignjustify | bullist numlist outdent indent | ' +
                     'removeformat | help | image',
            
            // Remove setup function temporarily to test
            
            // Image upload (simplified)
            images_upload_url: '{{ route("upload.image") }}',
            
            // Content style
            content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'
        });

        // Use template button handler - EXTERNAL TEMPLATE SYSTEM
        $(document).on('click', '.use-template', function() {
            const templateId = $(this).data('content');
            const templateElement = document.getElementById(templateId);
            
            if (templateElement && tinymce.activeEditor) {
                const templateContent = templateElement.innerHTML;
                tinymce.activeEditor.insertContent(templateContent);
                
                $('#templateModal').modal('hide');
                
                Swal.fire({
                    icon: 'success',
                    title: 'Template Added',
                    text: 'Template inserted successfully.',
                    timer: 1500,
                    showConfirmButton: false
                });
            }
        });

        // Add external template button
        $(document).on('click', '#external-template-btn', function() {
            $('#templateModal').modal('show');
        });
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