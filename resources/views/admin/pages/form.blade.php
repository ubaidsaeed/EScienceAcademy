@extends('layouts.admin.app')
@push('styles')
    <style>
        /* Slug Preview Styling */
        #slug-preview-full {
            font-size: 0.95rem;
            word-break: break-all;
            background: transparent;
            border: none;
            padding: 0;
        }

        #slug-value {
            transition: all 0.3s ease;
            padding: 2px 4px;
            border-radius: 3px;
            background-color: rgba(13, 110, 253, 0.1);
        }

        #slug-value.text-danger {
            background-color: rgba(220, 53, 69, 0.1);
        }

        #slug-value.fst-italic {
            opacity: 0.7;
        }

        /* Copy button animation */
        #copy-slug-url {
            transition: all 0.3s ease;
        }

        #copy-slug-url:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        /* Regenerate button animation */
        #regenerate-slug {
            transition: all 0.3s ease;
        }

        #regenerate-slug:hover {
            background-color: #e9ecef;
            transform: rotate(15deg);
        }

        #regenerate-slug:active {
            transform: rotate(45deg);
        }

        /* Preview card */
        .card.border {
            border-color: #dee2e6 !important;
            background-color: #f8f9fa;
        }

        .card.border:hover {
            border-color: #6c757d !important;
        }

        /* Preview link button */
        #preview-link {
            transition: all 0.2s ease;
        }

        #preview-link:hover {
            transform: translateY(-1px);
        }

        /* Validation messages */
        #slug-validation span {
            font-size: 0.85rem;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        #slug-validation .text-success::before {
            content: "✓";
            margin-right: 4px;
        }

        #slug-validation .text-danger::before {
            content: "❌";
            margin-right: 4px;
        }

        #slug-validation .text-warning::before {
            content: "⚠";
            margin-right: 4px;
        }
    </style>
    <style>
        .section-container {
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 15px;
            position: relative;
        }

        .remove-section-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 10;
        }

        .section-priority {
            width: 80px;
        }

        .template-card {
            cursor: pointer;
            transition: all 0.3s;
        }

        .template-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
    </style>
@endpush

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="app-content main-content">
        <div class="side-app">
            <div class="page-header d-lg-flex d-block">
                <div class="page-leftheader">
                    <h4 class="page-title">{{ isset($page) ? 'Edit Page' : 'Create New Page' }}</h4>
                </div>
                <div class="page-rightheader ms-md-auto">
                    <div class="btn-list">
                        <a href="{{ route('pages.list') }}" class="btn btn-secondary">
                            <i class="fa fa-arrow-left me-2"></i>Back to List
                        </a>
                    </div>
                </div>
            </div>

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form id="pageForm" action="{{ route('pages.store') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @if (isset($page))
                                    <input type="hidden" name="id" value="{{ $page->id }}">
                                @endif

                                <div class="row">
                                    <div class="mb-3 col-lg-6">
                                        <label for="title" class="form-label">Title <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="title"
                                            class="form-control @error('title') is-invalid @enderror" id="title"
                                            value="{{ old('title', $page->title ?? '') }}" required
                                            placeholder="Enter page title">
                                        @error('title')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3 col-lg-6">
                                        <label for="status" class="form-label">Status <span
                                                class="text-danger">*</span></label>
                                        <select name="status" class="form-control @error('status') is-invalid @enderror"
                                            id="status" required>
                                            <option value="">Select Status</option>
                                            <option value="active"
                                                {{ old('status', $page->status ?? '') == 'active' ? 'selected' : '' }}>
                                                Active</option>
                                            <option value="inactive"
                                                {{ old('status', $page->status ?? '') == 'inactive' ? 'selected' : '' }}>
                                                Inactive</option>
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3 col-lg-6">
                                        <label for="thumbnail" class="form-label">Thumbnail</label>
                                        <input type="file" name="thumbnail"
                                            class="form-control @error('thumbnail') is-invalid @enderror" id="thumbnail"
                                            accept="image/*">
                                        @error('thumbnail')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror

                                        @if (isset($page) && $page->thumbnail)
                                            <div class="mt-2">
                                                <img src="{{ asset('storage/app/public/pages/' . $page->thumbnail) }}"
                                                    alt="Current Thumbnail" width="100" class="img-thumbnail">
                                                <p class="text-muted mt-1 mb-0">Current thumbnail</p>
                                            </div>
                                        @endif
                                        <small class="text-muted">Max size: 1MB, Allowed: JPG, JPEG, PNG, SVG</small>
                                    </div>

                                    <div class="mb-3 col-lg-6">
                                        <label for="slug" class="form-label">Slug <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text" id="slug-prefix">/</span>
                                            <input type="text" name="slug"
                                                class="form-control @error('slug') is-invalid @enderror" id="slug"
                                                value="{{ old('slug', $page->slug ?? '') }}" required
                                                placeholder="url-friendly-slug">
                                            <button type="button" class="btn btn-outline-secondary" id="regenerate-slug"
                                                title="Regenerate from title">
                                                <i class="fa fa-refresh"></i>
                                            </button>
                                        </div>
                                        @error('slug')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        {{-- <div class="mt-2">
                                            <small class="text-muted">
                                                <span id="slug-preview-label">Preview: </span>
                                                <code id="slug-preview">{{ url('/') }}/<span
                                                        id="slug-value">{{ old('slug', $page->slug ?? '') }}</span></code>
                                            </small>
                                            <div id="slug-validation" class="small mt-1"></div>
                                        </div> --}}
                                        <!-- Slug Preview Section -->
                                        <div class="mt-3">
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <small class="text-muted fw-bold">
                                                    <i class="fa fa-eye me-1"></i> Page URL Preview
                                                </small>
                                                <button type="button" class="btn btn-sm btn-outline-secondary"
                                                    id="copy-slug-url" title="Copy URL to clipboard">
                                                    <i class="fa fa-copy"></i> Copy
                                                </button>
                                            </div>

                                            <div class="card border">
                                                <div class="card-body py-2">
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-grow-1">
                                                            <code class="text-primary" id="slug-preview-full">
                                                                <span id="base-url">{{ url('/') }}/</span>
                                                                <span id="slug-value"
                                                                    class="fw-bold">{{ old('slug', $page->slug ?? 'your-slug-here') }}</span>
                                                            </code>
                                                        </div>
                                                        <div class="ms-2">
                                                            <a href="#" id="preview-link" target="_blank"
                                                                class="btn btn-sm btn-outline-primary"
                                                                style="display: none;" title="Open preview in new tab">
                                                                <i class="fa fa-external-link"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div id="slug-validation" class="small mt-2"></div>
                                        </div>
                                        {{-- </div> --}}
                                    </div>
                                </div>

                                <hr>

                                <!-- Page Sections -->
                                @php
                                    // Add this helper function at the top of your Blade file
                                    function getContentTypeName($type)
                                    {
                                        $types = [
                                            1 => 'Rich Text',
                                            2 => 'Subjects',
                                            3 => 'Packages',
                                            4 => 'Boards',
                                            5 => 'Counselling',
                                            6 => 'Case Studies',
                                            7 => 'Achievements',
                                            8 => 'FAQ',
                                        ];
                                        return $types[$type] ?? 'Unknown';
                                    }
                                @endphp

                                <div class="mb-4">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h5 class="mb-0">Page Sections</h5>
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#addSectionModal">
                                            <i class="fa fa-plus me-2"></i>Add Section
                                        </button>
                                    </div>

                                    <div id="sections-container">
                                        @if (isset($sections) && $sections->count() > 0)
                                            @foreach ($sections as $index => $section)
                                                <div class="section-container" id="section-{{ $section->id }}">


                                                    <div class="row">



                                                        <div class="col-lg-8 mb-3">
                                                            @if ($section->content_type == 1)
                                                                <label class="form-label">Content</label>
                                                                <textarea name="sections[{{ $index }}][page_data]" class="form-control tinymce-editor"
                                                                    id="editor-{{ $section->id }}">{{ $section->page_data }}</textarea>
                                                            @else
                                                                <div class="alert alert-info mb-0">
                                                                    <strong>{{ getContentTypeName($section->content_type) }}
                                                                        Section</strong>
                                                                    <p class="mb-0">This section will display dynamic
                                                                        content based on its type.</p>
                                                                    <input type="hidden"
                                                                        name="sections[{{ $index }}][page_data]"
                                                                        value="{{ $section->page_data }}">
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div class="col-lg-1 mb-3">
                                                            <label class="form-label">Priority</label>
                                                            <input type="number"
                                                                name="sections[{{ $index }}][priority]"
                                                                class="form-control section-priority"
                                                                value="{{ $section->priority }}" min="0">
                                                        </div>
                                                        <div class="col-lg-2 mb-3">
                                                            <label class="form-label">Content Type</label>
                                                            <input type="text" class="form-control"
                                                                value="{{ getContentTypeName($section->content_type) }}"
                                                                readonly>
                                                            <input type="hidden"
                                                                name="sections[{{ $index }}][content_type]"
                                                                value="{{ $section->content_type }}">
                                                        </div>
                                                        <div class="col-lg-1 mb-3">
                                                            <button type="button"
                                                                class="btn btn-danger btn-sm remove-section-btn"
                                                                onclick="removeExistingSection({{ $section->id }})"
                                                                title="Remove Section">
                                                                <i class="fa fa-times"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>

                                    <div id="no-sections"
                                        class="text-center py-5 border rounded {{ isset($sections) && $sections->count() > 0 ? 'd-none' : '' }}">
                                        <i class="fa fa-layer-group fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">No sections added yet</h5>
                                        <p class="text-muted">Click "Add Section" to add content sections to this page.</p>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('pages.list') }}" class="btn btn-secondary">Cancel</a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-save me-2"></i>{{ isset($page) ? 'Update Page' : 'Create Page' }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Section Modal -->
    <div class="modal fade" id="addSectionModal" tabindex="-1" aria-labelledby="addSectionModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addSectionModalLabel">Add Section Template</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="card template-card h-100 text-center" data-type="1">
                                <div class="card-body">
                                    <i class="fa fa-edit fa-3x text-primary mb-3"></i>
                                    <h5>Rich Text</h5>
                                    <p class="text-muted">Add formatted text with images</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card template-card h-100 text-center" data-type="2">
                                <div class="card-body">
                                    <i class="fa fa-book fa-3x text-success mb-3"></i>
                                    <h5>Subjects</h5>
                                    <p class="text-muted">Display subjects grid</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card template-card h-100 text-center" data-type="3">
                                <div class="card-body">
                                    <i class="fa fa-box fa-3x text-warning mb-3"></i>
                                    <h5>Packages</h5>
                                    <p class="text-muted">Show packages/plans</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card template-card h-100 text-center" data-type="4">
                                <div class="card-body">
                                    <i class="fa fa-graduation-cap fa-3x text-info mb-3"></i>
                                    <h5>Boards</h5>
                                    <p class="text-muted">Education boards display</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card template-card h-100 text-center" data-type="5">
                                <div class="card-body">
                                    <i class="fa fa-comments fa-3x text-danger mb-3"></i>
                                    <h5>Counselling</h5>
                                    <p class="text-muted">Counselling information</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card template-card h-100 text-center" data-type="6">
                                <div class="card-body">
                                    <i class="fa fa-briefcase fa-3x text-secondary mb-3"></i>
                                    <h5>Case Studies</h5>
                                    <p class="text-muted">Success stories & cases</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card template-card h-100 text-center" data-type="7">
                                <div class="card-body">
                                    <i class="fa fa-trophy fa-3x text-warning mb-3"></i>
                                    <h5>Achievements</h5>
                                    <p class="text-muted">Milestones & awards</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card template-card h-100 text-center" data-type="8">
                                <div class="card-body">
                                    <i class="fa fa-question-circle fa-3x text-primary mb-3"></i>
                                    <h5>FAQ</h5>
                                    <p class="text-muted">Frequently asked questions</p>
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
    <script>
            
   
  
   
   // Global section counter and editors array
        let sectionCounter = {{ isset($sections) ? count($sections) : 0 }};
        let tinyMCEInitialized = false;

        // Initialize TinyMCE for existing editors
        document.addEventListener('DOMContentLoaded', function() {
            // Setup template selection
            document.querySelectorAll('.template-card').forEach(card => {
                card.addEventListener('click', function() {
                    const type = this.getAttribute('data-type');
                    addNewSection(type);
                    $('#addSectionModal').modal('hide');
                });
            });

            // Initialize existing TinyMCE editors
            if (document.querySelector('.tinymce-editor')) {
                initializeTinyMCE();
            }

            // Auto-generate slug from title
            document.getElementById('title').addEventListener('keyup', function() {
                const slugInput = document.getElementById('slug');
                if (slugInput) {
                    slugInput.value = generateSlug(this.value);
                }
            });

            // Set up existing section removal
            document.querySelectorAll('.remove-section-btn').forEach(btn => {
                const sectionId = btn.closest('.section-container').id.replace('section-', '');
                if (sectionId && !isNaN(sectionId)) {
                    btn.addEventListener('click', function() {
                        removeExistingSection(sectionId);
                    });
                }
            });
        });

        // Function to add new section
        function addNewSection(contentType) {
            sectionCounter++;
            const sectionId = 'new-' + Date.now() + '-' + sectionCounter;
            const contentTypeName = getContentTypeName(contentType);

            let sectionHTML = '';

            if (contentType == 1) {
                // Rich Text Editor Section
                sectionHTML = `
                <div class="section-container" id="section-${sectionId}">
                    
                    <div class="row">
                        
                        <div class="col-lg-8 mb-3">
                            <label class="form-label">Content</label>
                            <textarea name="sections[${sectionCounter}][page_data]" class="form-control tinymce-editor" id="editor-${sectionId}"></textarea>
                        </div>
                        <div class="col-lg-1 mb-3">
                            <label class="form-label">Priority</label>
                            <input type="number" name="sections[${sectionCounter}][priority]" class="form-control section-priority" value="0" min="0">
                        </div>
                        <div class="col-lg-2 mb-3">
                            <label class="form-label">Content Type</label>
                            <input type="text" class="form-control" value="${contentTypeName}" readonly>
                            <input type="hidden" name="sections[${sectionCounter}][content_type]" value="${contentType}">
                        </div>
                        <div class="col-lg-1 mb-3">
                            <button type="button" class="btn btn-danger btn-sm remove-section-btn" onclick="removeSection('${sectionId}')" title="Remove Section">
                        <i class="fa fa-times"></i>
                    </button>
                            </div>

                    </div>
                </div>
            `;
            } else {
                // Other section types
                sectionHTML = `
                <div class="section-container" id="section-${sectionId}">
                    <div class="row">
                        <div class="col-lg-8 mb-3">
                            <div class="alert alert-info mb-0">
                                <strong>${contentTypeName} Section</strong>
                                <p class="mb-0">This section will display dynamic content based on its type.</p>
                                <input type="hidden" name="sections[${sectionCounter}][page_data]" value="">
                            </div>
                        </div>
                        <div class="col-lg-1 mb-3">
                            <label class="form-label">Priority</label>
                            <input type="number" name="sections[${sectionCounter}][priority]" class="form-control section-priority" value="0" min="0">
                        </div>
                        <div class="col-lg-2 mb-3">
                            <label class="form-label">Content Type</label>
                            <input type="text" class="form-control" value="${contentTypeName}" readonly>
                            <input type="hidden" name="sections[${sectionCounter}][content_type]" value="${contentType}">
                        </div>
                        <div class="col-lg-1 mb-3">
                    <button type="button" class="btn btn-danger btn-sm remove-section-btn" onclick="removeSection('${sectionId}')" title="Remove Section">
                        <i class="fa fa-times"></i>
                    </button>
                    </div>
                    </div>
                </div>
            `;
            }

            // Add section to container
            document.getElementById('sections-container').insertAdjacentHTML('beforeend', sectionHTML);

            // Initialize TinyMCE if it's a rich text section
            if (contentType == 1) {
                // Wait for DOM to update
                setTimeout(() => {
                    initializeSingleTinyMCE(`editor-${sectionId}`);
                }, 100);
            }

            // Hide "no sections" message
            document.getElementById('no-sections').classList.add('d-none');
        }

        // Function to remove existing section from database
        function removeExistingSection(sectionId) {
            if (confirm('Are you sure you want to remove this section?')) {
                $.ajax({
                    url: '{{ route('pages.section.destroy', ':id') }}'.replace(':id', sectionId),
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        _method: 'DELETE'
                    },
                    success: function(response) {
                        // Remove TinyMCE instance if exists
                        if (tinymce.get(`editor-${sectionId}`)) {
                            tinymce.get(`editor-${sectionId}`).remove();
                        }
                        // Remove the section element
                        document.getElementById(`section-${sectionId}`).remove();
                        showNoSectionsMessage();
                        toastSuccess('Section removed successfully!');
                    },
                    error: function(xhr) {
                        console.error('Error removing section:', xhr);
                        toastError('Failed to remove section: ' + (xhr.responseJSON?.error || 'Unknown error'));
                    }
                });
            }
        }

        // Function to remove newly added section (not saved yet)
        function removeSection(sectionId) {
            if (confirm('Are you sure you want to remove this section?')) {
                // Remove TinyMCE instance if exists
                if (tinymce.get(`editor-${sectionId}`)) {
                    tinymce.get(`editor-${sectionId}`).remove();
                }
                // Remove the section element
                document.getElementById(`section-${sectionId}`).remove();
                showNoSectionsMessage();
                toastSuccess('Section removed!');
            }
        }

        // Show "no sections" message if container is empty
        function showNoSectionsMessage() {
            const container = document.getElementById('sections-container');
            if (container.children.length === 0) {
                document.getElementById('no-sections').classList.remove('d-none');
            }
        }

        // Get content type name
        function getContentTypeName(type) {
            const types = {
                1: 'Rich Text',
                2: 'Subjects',
                3: 'Packages',
                4: 'Boards',
                5: 'Counselling',
                6: 'Case Studies',
                7: 'Achievements',
                8: 'FAQ'
            };
            return types[type] || 'Unknown';
        }

        // Generate slug from text
        function generateSlug(text) {
            return text.toLowerCase()
                .replace(/[^\w\s-]/g, '') // Remove all non-word chars
                .replace(/\s+/g, '-') // Replace spaces with -
                .replace(/--+/g, '-') // Replace multiple - with single -
                .trim(); // Trim - from start and end
        }

        // Initialize all TinyMCE editors
        function initializeTinyMCE() {
            if (tinyMCEInitialized) {
                // Destroy existing instances first to avoid duplicates
                tinymce.remove('.tinymce-editor');
            }

            tinymce.init({
                selector: '.tinymce-editor',
                height: 300,
                menubar: true,
                plugins: [
                    'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview', 'anchor',
                    'searchreplace', 'visualblocks', 'code', 'fullscreen', 'insertdatetime', 'media',
                    'table', 'code', 'help', 'wordcount'
                ],
                toolbar: 'undo redo | blocks | bold italic forecolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media | code',
                image_title: true,
                automatic_uploads: true,
                images_upload_url: '{{ route('pages.uploadImage') }}',
                file_picker_types: 'image',
                relative_urls: false,
                remove_script_host: false,
                convert_urls: true,
                images_upload_handler: function(blobInfo, progress) {
                    return new Promise((resolve, reject) => {
                        const formData = new FormData();
                        formData.append('file', blobInfo.blob(), blobInfo.filename());
                        formData.append('_token', '{{ csrf_token() }}');

                        fetch('{{ route('pages.uploadImage') }}', {
                                method: 'POST',
                                body: formData
                            })
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error('Upload failed with status: ' + response
                                        .status);
                                }
                                return response.json();
                            })
                            .then(data => {
                                if (data.location) {
                                    resolve(data.location);
                                } else {
                                    reject(data.error || 'Upload failed');
                                }
                            })
                            .catch(error => {
                                console.error('Upload error:', error);
                                reject('Upload error: ' + error.message);
                            });
                    });
                },
                setup: function(editor) {
                    editor.on('change', function() {
                        editor.save(); // Save content back to textarea
                    });

                    // Add custom button for Bootstrap classes
                    editor.ui.registry.addButton('bootstrapClasses', {
                        text: 'Bootstrap',
                        icon: 'code',
                        onAction: function() {
                            editor.insertContent(
                                '<div class="row"><div class="col-md-12">Your content here</div></div>'
                            );
                        }
                    });
                },
                content_style: `
                body { 
                    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; 
                    font-size: 14px; 
                    line-height: 1.6;
                }
                .mce-content-body {
                    max-width: 100%;
                }
            `,
                branding: false,
                promotion: false,
                resize: true
            });

            tinyMCEInitialized = true;
        }

        // Initialize single TinyMCE instance
        function initializeSingleTinyMCE(editorId) {
            tinymce.init({
                selector: `#${editorId}`,
                height: 300,
                menubar: true,
                plugins: [
                    'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview', 'anchor',
                    'searchreplace', 'visualblocks', 'code', 'fullscreen', 'insertdatetime', 'media',
                    'table', 'code', 'help', 'wordcount'
                ],
                toolbar: 'undo redo | blocks | bold italic forecolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media | code',
                image_title: true,
                automatic_uploads: true,
                images_upload_url: '{{ route('pages.uploadImage') }}',
                file_picker_types: 'image',
                relative_urls: false,
                remove_script_host: false,
                convert_urls: true,
                images_upload_handler: function(blobInfo, progress) {
                    return new Promise((resolve, reject) => {
                        const formData = new FormData();
                        formData.append('file', blobInfo.blob(), blobInfo.filename());
                        formData.append('_token', '{{ csrf_token() }}');

                        fetch('{{ route('pages.uploadImage') }}', {
                                method: 'POST',
                                body: formData
                            })
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error('Upload failed with status: ' + response
                                        .status);
                                }
                                return response.json();
                            })
                            .then(data => {
                                if (data.location) {
                                    resolve(data.location);
                                } else {
                                    reject(data.error || 'Upload failed');
                                }
                            })
                            .catch(error => {
                                console.error('Upload error:', error);
                                reject('Upload error: ' + error.message);
                            });
                    });
                },
                setup: function(editor) {
                    editor.on('change', function() {
                        editor.save(); // Save content back to textarea
                    });

                    editor.on('init', function() {
                        // Set initial content if needed
                        const textarea = document.getElementById(editorId);
                        if (textarea && textarea.value) {
                            editor.setContent(textarea.value);
                        }
                    });
                },
                content_style: `
                body { 
                    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; 
                    font-size: 14px; 
                    line-height: 1.6;
                }
                .mce-content-body {
                    max-width: 100%;
                }
            `,
                branding: false,
                promotion: false,
                resize: true
            });
        }

        // Toast notification functions
        function toastSuccess(message) {
            if (typeof Toast !== 'undefined') {
                Toast.fire({
                    icon: 'success',
                    title: message
                });
            } else {
                alert(message);
            }
        }

        function toastError(message) {
            if (typeof Toast !== 'undefined') {
                Toast.fire({
                    icon: 'error',
                    title: message
                });
            } else {
                alert('Error: ' + message);
            }
        }

        // Clean up TinyMCE instances before form submission
        document.getElementById('pageForm').addEventListener('submit', function(e) {
            // Save all TinyMCE editors before form submission
            if (typeof tinymce !== 'undefined') {
                tinymce.triggerSave();
            }
        });

        // Clean up TinyMCE when leaving the page
        window.addEventListener('beforeunload', function() {
            if (typeof tinymce !== 'undefined' && tinyMCEInitialized) {
                tinymce.remove('.tinymce-editor');
            }
        });
        // Global variables
        let slugGenerationTimeout;
        let isEditingSlug = false;
        let originalSlug = '{{ old('slug', $page->slug ?? '') }}';

        document.addEventListener('DOMContentLoaded', function() {
            const titleInput = document.getElementById('title');
            const slugInput = document.getElementById('slug');
            const regenerateBtn = document.getElementById('regenerate-slug');
            const copyBtn = document.getElementById('copy-slug-url');
            const previewLink = document.getElementById('preview-link');
            const slugValueSpan = document.getElementById('slug-value');
            const baseUrlSpan = document.getElementById('base-url');
            const slugValidation = document.getElementById('slug-validation');

            if (!titleInput || !slugInput) return;

            // Initialize preview
            updateSlugPreview();

            // Live slug generation from title
            titleInput.addEventListener('input', function() {
                // Only auto-generate if user isn't manually editing slug
                if (!isEditingSlug && slugInput.value === originalSlug) {
                    clearTimeout(slugGenerationTimeout);
                    slugGenerationTimeout = setTimeout(() => {
                        generateAndSetSlug(this.value, slugInput);
                    }, 500);
                }
            });

            // Manual slug editing
            slugInput.addEventListener('focus', function() {
                isEditingSlug = true;
                this.select(); // Select all text for easy editing
            });

            slugInput.addEventListener('blur', function() {
                setTimeout(() => {
                    isEditingSlug = false;
                }, 1000);
            });

            // Real-time slug sanitization and preview update
            slugInput.addEventListener('input', function() {
                // Clean the slug as user types
                const cleanSlug = cleanSlugText(this.value);
                if (this.value !== cleanSlug) {
                    const cursorPos = this.selectionStart;
                    this.value = cleanSlug;
                    this.setSelectionRange(Math.max(0, cursorPos - 1), Math.max(0, cursorPos - 1));
                }

                // Update preview in real-time
                updateSlugPreview();

                // Validate slug
                validateSlug(this.value);
            });

            // Regenerate slug button
            regenerateBtn.addEventListener('click', function() {
                generateAndSetSlug(titleInput.value, slugInput);
                slugInput.focus();
            });

            // Copy URL to clipboard
            copyBtn.addEventListener('click', function() {
                const fullUrl = getFullSlugUrl();
                if (fullUrl) {
                    copyToClipboard(fullUrl);
                    showCopyFeedback(this);
                }
            });

            // Update preview link
            slugInput.addEventListener('blur', function() {
                if (this.value.trim()) {
                    updatePreviewLink();
                }
            });

            // Keyboard shortcuts
            document.addEventListener('keydown', function(e) {
                // Ctrl/Cmd + Shift + S to regenerate slug
                if ((e.ctrlKey || e.metaKey) && e.shiftKey && e.key === 'S') {
                    e.preventDefault();
                    generateAndSetSlug(titleInput.value, slugInput);
                }

                // Ctrl/Cmd + C when focused on slug input to copy URL
                if ((e.ctrlKey || e.metaKey) && e.key === 'c' && document.activeElement === slugInput) {
                    const fullUrl = getFullSlugUrl();
                    if (fullUrl) {
                        e.preventDefault();
                        copyToClipboard(fullUrl);
                        showCopyFeedback(copyBtn);
                    }
                }
            });
        });

        // Generate slug from text
        function generateSlug(text) {
            return text
                .toString()
                .toLowerCase()
                .trim()
                // Replace accented characters
                .replace(/á/gi, 'a')
                .replace(/é/gi, 'e')
                .replace(/í/gi, 'i')
                .replace(/ó/gi, 'o')
                .replace(/ú/gi, 'u')
                .replace(/ñ/gi, 'n')
                .replace(/ç/gi, 'c')
                // Replace spaces and special characters
                .replace(/\s+/g, '-')
                .replace(/&/g, '-and-')
                .replace(/[^\w\-]+/g, '')
                .replace(/\-\-+/g, '-')
                .replace(/^-+/, '')
                .replace(/-+$/, '');
        }

        // Clean slug text (for manual editing)
        function cleanSlugText(text) {
            return text
                .toLowerCase()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-')
                .replace(/^-+/, '')
                .replace(/-+$/, '');
        }

        // Generate and set slug
        function generateAndSetSlug(title, slugInput) {
            if (!title.trim()) {
                showValidationMessage('Please enter a title first', 'warning');
                return;
            }

            const generatedSlug = generateSlug(title);

            if (generatedSlug) {
                slugInput.value = generatedSlug;
                updateSlugPreview();
                validateSlug(generatedSlug);
                updatePreviewLink();

                // Show success feedback
                showValidationMessage('✓ Slug generated successfully!', 'success');
            }
        }

        // Update slug preview display
        function updateSlugPreview() {
            const slugInput = document.getElementById('slug');
            const slugValueSpan = document.getElementById('slug-value');
            const slugPreviewFull = document.getElementById('slug-preview-full');
            const baseUrlSpan = document.getElementById('base-url');

            if (!slugInput || !slugValueSpan) return;

            const slugValue = slugInput.value.trim();
            const baseUrl = '{{ url('/preview/') }}';

            // Update slug value
            slugValueSpan.textContent = slugValue || 'your-slug-here';
            baseUrlSpan.textContent = baseUrl + '/';

            // Visual feedback
            if (!slugValue) {
                slugValueSpan.classList.add('text-danger', 'fst-italic');
                slugValueSpan.classList.remove('text-primary', 'fw-bold');
                slugPreviewFull.classList.add('text-muted');
            } else {
                slugValueSpan.classList.remove('text-danger', 'fst-italic');
                slugValueSpan.classList.add('text-primary', 'fw-bold');
                slugPreviewFull.classList.remove('text-muted');
            }

            // Update page title if empty slug
            const titleInput = document.getElementById('title');
            if (!slugValue && titleInput && titleInput.value.trim()) {
                const autoSlug = generateSlug(titleInput.value);
                slugValueSpan.textContent = autoSlug || 'auto-generated-slug';
                slugValueSpan.classList.add('fst-italic');
            }
        }

        // Get full slug URL
        function getFullSlugUrl() {
            const slugInput = document.getElementById('slug');
            const slugValue = slugInput ? slugInput.value.trim() : '';
            const baseUrl = '{{ url('/preview/') }}';

            if (slugValue) {
                return `${baseUrl}/${slugValue}`;
            }
            return null;
        }

        // Update preview link
        function updatePreviewLink() {
            const previewLink = document.getElementById('preview-link');
            const fullUrl = getFullSlugUrl();

            if (previewLink && fullUrl) {
                previewLink.href = fullUrl;
                previewLink.style.display = 'inline-block';
                previewLink.title = `Open ${fullUrl} in new tab`;
            } else if (previewLink) {
                previewLink.style.display = 'none';
            }
        }

        // Validate slug format
        function validateSlug(slug) {
            const validationDiv = document.getElementById('slug-validation');
            if (!validationDiv) return;

            validationDiv.innerHTML = '';

            if (!slug.trim()) {
                showValidationMessage('❌ Slug cannot be empty', 'error');
                return false;
            }

            // Check length
            if (slug.length < 3) {
                showValidationMessage('⚠ Slug should be at least 3 characters', 'warning');
                return false;
            }

            if (slug.length > 100) {
                showValidationMessage('⚠ Slug is too long (max 100 characters)', 'warning');
                return false;
            }

            // Check format
            const slugRegex = /^[a-z0-9]+(?:-[a-z0-9]+)*$/;
            if (!slugRegex.test(slug)) {
                showValidationMessage(
                    '⚠ Use only lowercase letters, numbers, and hyphens. No spaces or special characters.', 'warning');
                return false;
            }

            // Check for reserved words
            const reservedWords = ['admin', 'api', 'css', 'js', 'img', 'images', 'assets', 'storage',
                'login', 'register', 'dashboard', 'profile', 'settings', 'logout'
            ];
            if (reservedWords.includes(slug)) {
                showValidationMessage('⚠ This slug matches a reserved system URL', 'warning');
                return false;
            }

            // Check for common file extensions
            const fileExtensions = ['.html', '.php', '.js', '.css', '.jpg', '.png', '.pdf', '.doc', '.zip'];
            const hasExtension = fileExtensions.some(ext => slug.endsWith(ext));
            if (hasExtension) {
                showValidationMessage('⚠ Avoid file extensions in slugs', 'warning');
                return false;
            }

            showValidationMessage('✓ Valid slug format', 'success');
            return true;
        }

        // Show validation message
        function showValidationMessage(message, type = 'info') {
            const validationDiv = document.getElementById('slug-validation');
            if (!validationDiv) return;

            const colors = {
                'success': 'text-success',
                'error': 'text-danger',
                'warning': 'text-warning',
                'info': 'text-info'
            };

            validationDiv.innerHTML = `<span class="${colors[type] || 'text-info'}">${message}</span>`;

            // Auto-remove success messages after 3 seconds
            if (type === 'success') {
                setTimeout(() => {
                    if (validationDiv.innerHTML.includes(message)) {
                        validationDiv.innerHTML = '';
                    }
                }, 3000);
            }
        }

        // Copy to clipboard
        function copyToClipboard(text) {
            // Create temporary textarea
            const textarea = document.createElement('textarea');
            textarea.value = text;
            textarea.style.position = 'fixed';
            textarea.style.opacity = '0';
            document.body.appendChild(textarea);

            // Select and copy
            textarea.select();
            textarea.setSelectionRange(0, 99999); // For mobile devices

            try {
                const successful = document.execCommand('copy');
                if (successful) {
                    return true;
                }
            } catch (err) {
                console.error('Copy failed:', err);
            }

            // Fallback for modern browsers
            try {
                navigator.clipboard.writeText(text).then(() => {
                    return true;
                }).catch(err => {
                    console.error('Clipboard write failed:', err);
                });
            } catch (err) {
                console.error('Clipboard API not available:', err);
            }

            // Cleanup
            document.body.removeChild(textarea);
            return false;
        }

        // Show copy feedback
        function showCopyFeedback(button) {
            const originalHtml = button.innerHTML;
            const originalTitle = button.title;

            // Change button appearance
            button.innerHTML = '<i class="fa fa-check"></i> Copied!';
            button.classList.remove('btn-outline-secondary');
            button.classList.add('btn-success');
            button.disabled = true;

            // Show toast notification
            if (typeof Toast !== 'undefined') {
                Toast.fire({
                    icon: 'success',
                    title: 'URL copied to clipboard!',
                    timer: 2000
                });
            }

            // Revert after 2 seconds
            setTimeout(() => {
                button.innerHTML = originalHtml;
                button.title = originalTitle;
                button.classList.remove('btn-success');
                button.classList.add('btn-outline-secondary');
                button.disabled = false;
            }, 2000);
        }

        // Character counter for slug
        function updateSlugCharacterCount() {
            const slugInput = document.getElementById('slug');
            const counter = document.getElementById('slug-char-counter');

            if (!slugInput || !counter) return;

            const currentLength = slugInput.value.length;
            const maxLength = 100;

            counter.textContent = `${currentLength}/${maxLength}`;

            if (currentLength > maxLength * 0.9) {
                counter.classList.add('text-warning');
            } else {
                counter.classList.remove('text-warning');
            }

            if (currentLength > maxLength) {
                counter.classList.add('text-danger');
            } else {
                counter.classList.remove('text-danger');
            }
        }

        // Initialize character counter if needed
        function initCharacterCounter() {
            const slugInput = document.getElementById('slug');
            if (slugInput) {
                slugInput.addEventListener('input', updateSlugCharacterCount);
                updateSlugCharacterCount();
            }
        }
    </script>
@endpush
