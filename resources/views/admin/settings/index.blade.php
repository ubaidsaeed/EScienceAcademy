@extends('layouts.admin.app')

@section('title', 'Website Settings')

@push('style')
<link href="{{ asset('assets/admin/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
<link href="{{ asset('assets/admin/plugins/dropify/css/dropify.min.css') }}" rel="stylesheet">
<style>
    .dynamic-form-row {
        margin-bottom: 10px;
    }
    .remove-row-btn {
        margin-top: 32px;
    }
</style>
@endpush

@section('content')
<div class="app-content main-content">
    <div class="container mt-5">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Website Settings</h4>
            </div>
            <div class="card-body">
               

                <form action="{{ route('settings.store') }}" method="POST" enctype="multipart/form-data" id="settingsForm">
                    @csrf
                    
                    <!-- Basic Information -->
                    <div class="row mb-4">
                        <div class="col-lg-6">
                            <h5>Site Title</h5>
                            <input type="text" class="form-control" name="site_title" 
                                   value="{{ old('site_title', $settings->site_title ?? '') }}" 
                                   placeholder="Enter Title of your site" required>
                            @error('site_title')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="col-lg-6">
                            <h5>Contact Mail</h5>
                            <input type="email" class="form-control" name="contact_mail" 
                                   value="{{ old('contact_mail', $settings->contact_mail ?? '') }}" 
                                   placeholder="Enter Contact Mail" required>
                            @error('contact_mail')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- WhatsApp Contact & Address -->
                    <div class="row mb-4">
                        <div class="col-lg-6">
                            <h5>WhatsApp Contact</h5>
                            <input type="text" class="form-control" name="whatsapp_contact" 
                                   value="{{ old('whatsapp_contact', $settings->whatsapp_contact ?? '') }}" 
                                   placeholder="Enter WhatsApp Contact">
                            @error('whatsapp_contact')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="col-lg-6">
                            <h5>Address</h5>
                            <textarea class="form-control" name="address" 
                                      placeholder="Enter Address of your site">{{ old('address', $settings->address ?? '') }}</textarea>
                            @error('address')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Site Description with TinyMCE -->
                    <div class="mb-4">
                        <h5>Site Description</h5>
                        <textarea class="form-control" id="site_description" name="site_description" 
                                  placeholder="Enter Description of your site">{{ old('site_description', $settings->site_description ?? '') }}</textarea>
                        @error('site_description')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Footer Quick Links -->
                    <div class="mb-4">
                        <h5>Footer Quick Links</h5>
                        <div id="footerQuickLinksContainer">
                            @php
                                $footerLinks = old('footer_quick_links', $footerQuickLinks);
                                $footerIndex = 0;
                            @endphp
                            
                            @if(count($footerLinks) > 0)
                                @foreach($footerLinks as $index => $link)
                                    <div class="row mb-2 dynamic-form-row" id="footer-row-{{ $index }}">
                                        <div class="col-lg-5">
                                            <input type="text" class="form-control" 
                                                   name="footer_quick_links[{{ $index }}][title]" 
                                                   value="{{ $link['title'] ?? '' }}" 
                                                   placeholder="Link Title">
                                        </div>
                                        <div class="col-lg-6">
                                            <select class="form-control" 
                                                    name="footer_quick_links[{{ $index }}][slug]">
                                                <option value="">Select Page...</option>
                                                @foreach($pages as $page)
                                                    <option value="{{ $page->slug }}" 
                                                        {{ ($link['slug'] ?? '') == $page->slug ? 'selected' : '' }}>
                                                        {{ $page->title }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-lg-1 remove-row-btn">
                                            <button type="button" class="btn btn-danger remove-row" 
                                                    data-target="footer-row-{{ $index }}">✖</button>
                                        </div>
                                    </div>
                                    @php $footerIndex = $index + 1; @endphp
                                @endforeach
                            @else
                                <div class="row mb-2 dynamic-form-row" id="footer-row-0">
                                    <div class="col-lg-5">
                                        <input type="text" class="form-control" 
                                               name="footer_quick_links[0][title]" 
                                               placeholder="Link Title">
                                    </div>
                                    <div class="col-lg-6">
                                        <select class="form-control" name="footer_quick_links[0][slug]">
                                            <option value="">Select Page...</option>
                                            @foreach($pages as $page)
                                                <option value="{{ $page->slug }}">{{ $page->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-1 remove-row-btn">
                                        <button type="button" class="btn btn-danger remove-row" 
                                                data-target="footer-row-0">✖</button>
                                    </div>
                                </div>
                                @php $footerIndex = 1; @endphp
                            @endif
                        </div>
                        
                        <input type="hidden" id="footerQuickLinksCount" value="{{ $footerIndex }}">
                        
                        <button type="button" class="btn btn-primary btn-sm mt-2" id="addFooterQuickLink">
                            + Add Link
                        </button>
                    </div>

                    <!-- Social Links -->
                    <div class="mb-4">
                        <h5>Social Links</h5>
                        <div id="socialLinksContainer">
                            @php
                                $socialLinksData = old('social_links', $socialLinks);
                                $socialIndex = 0;
                            @endphp
                            
                            @if(count($socialLinksData) > 0)
                                @foreach($socialLinksData as $index => $link)
                                    <div class="row mb-2 dynamic-form-row" id="social-row-{{ $index }}">
                                        <div class="col-lg-5">
                                            <input type="text" class="form-control" 
                                                   name="social_links[{{ $index }}][platform]" 
                                                   value="{{ $link['platform'] ?? '' }}" 
                                                   placeholder="Platform (e.g., Facebook)">
                                        </div>
                                        <div class="col-lg-6">
                                            <input type="url" class="form-control" 
                                                   name="social_links[{{ $index }}][url]" 
                                                   value="{{ $link['url'] ?? '' }}" 
                                                   placeholder="Profile URL">
                                        </div>
                                        <div class="col-lg-1 remove-row-btn">
                                            <button type="button" class="btn btn-danger remove-row" 
                                                    data-target="social-row-{{ $index }}">✖</button>
                                        </div>
                                    </div>
                                    @php $socialIndex = $index + 1; @endphp
                                @endforeach
                            @else
                                <div class="row mb-2 dynamic-form-row" id="social-row-0">
                                    <div class="col-lg-5">
                                        <input type="text" class="form-control" 
                                               name="social_links[0][platform]" 
                                               placeholder="Platform (e.g., Facebook)">
                                    </div>
                                    <div class="col-lg-6">
                                        <input type="url" class="form-control" 
                                               name="social_links[0][url]" 
                                               placeholder="Profile URL">
                                    </div>
                                    <div class="col-lg-1 remove-row-btn">
                                        <button type="button" class="btn btn-danger remove-row" 
                                                data-target="social-row-0">✖</button>
                                    </div>
                                </div>
                                @php $socialIndex = 1; @endphp
                            @endif
                        </div>
                        
                        <input type="hidden" id="socialLinksCount" value="{{ $socialIndex }}">
                        
                        <button type="button" class="btn btn-primary btn-sm mt-2" id="addSocialLink">
                            + Add Social Link
                        </button>
                    </div>

                    <!-- File Uploads -->
                    <div class="row mb-4">
                        <div class="col-lg-6">
                            <h5>Site Logo</h5>
                            <input type="file" class="dropify" name="site_logo" 
                                   data-default-file="{{ $settings->site_logo ? asset('storage/site-logo/' . $settings->site_logo) : '' }}" 
                                   data-height="180">
                            @error('site_logo')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="col-lg-6">
                            <h5>Favicon</h5>
                            <input type="file" class="dropify" name="favicon" 
                                   data-default-file="{{ $settings->favicon ? asset('storage/favicon/' . $settings->favicon) : '' }}" 
                                   data-height="180">
                            @error('favicon')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Save Button -->
                    @can('edit settings')
                    <button type="submit" class="btn btn-success w-100">Save Settings</button>
                    @endcan
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script src="{{ asset('assets/admin/plugins/select2/js/select2.min.js') }}"></script>
<script src="{{ asset('assets/admin/plugins/dropify/js/dropify.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function() {
        // Initialize Dropify
        $('.dropify').dropify({
            messages: {
                'default': 'Drag and drop a file here or click',
                'replace': 'Drag and drop or click to replace',
                'remove': 'Remove',
                'error': 'Ooops, something wrong appended.'
            },
            error: {
                
            }
        });

        // Initialize TinyMCE
        tinymce.init({
            selector: '#site_description',
            height: 300,
            plugins: 'image code table link media codesample lists',
            toolbar: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist | link image media | table',
            image_title: true,
            automatic_uploads: true,
            file_picker_types: 'image',
            file_picker_callback: function (cb, value, meta) {
                const input = document.createElement('input');
                input.setAttribute('type', 'file');
                input.setAttribute('accept', 'image/*');
                input.onchange = function () {
                    const file = this.files[0];
                    
                    // Create FormData for AJAX upload
                    const formData = new FormData();
                    formData.append('file', file);
                    formData.append('_token', '{{ csrf_token() }}');
                    
                    // Upload file via AJAX
                    $.ajax({
                        url: '{{ route("upload.image") }}', // You need to create this route
                        type: 'POST',
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            if (response.location) {
                                cb(response.location, { title: file.name });
                            }
                        },
                        error: function(xhr) {
                            console.error('Upload error:', xhr.responseText);
                        }
                    });
                };
                input.click();
            },
            content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'
        });

        // Add Footer Quick Link
        let footerCount = parseInt($('#footerQuickLinksCount').val());
        $('#addFooterQuickLink').click(function() {
            const index = footerCount++;
            
            $.ajax({
                url: '{{ route("settings.add-row") }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    type: 'footer_quick_links',
                    index: index
                },
                success: function(response) {
                    $('#footerQuickLinksContainer').append(response.html);
                },
                error: function(xhr) {
                    console.error('Error adding row:', xhr.responseText);
                }
            });
        });

        // Add Social Link
        let socialCount = parseInt($('#socialLinksCount').val());
        $('#addSocialLink').click(function() {
            const index = socialCount++;
            
            $.ajax({
                url: '{{ route("settings.add-row") }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    type: 'social_links',
                    index: index
                },
                success: function(response) {
                    $('#socialLinksContainer').append(response.html);
                },
                error: function(xhr) {
                    console.error('Error adding row:', xhr.responseText);
                }
            });
        });

        // Remove row
        $(document).on('click', '.remove-row', function() {
            const target = $(this).data('target');
            $('#' + target).remove();
        });

        // Initialize Select2
        $('select').select2({
            width: '100%'
        });

        // Form validation
        $('#settingsForm').validate({
            rules: {
                site_title: {
                    required: true
                },
                contact_mail: {
                    required: true,
                    email: true
                }
            },
            messages: {
                site_title: {
                    required: "Site title is required"
                },
                contact_mail: {
                    required: "Contact email is required",
                    email: "Please enter a valid email address"
                }
            },
            errorElement: 'span',
            errorPlacement: function (error, element) {
                error.addClass('text-danger');
                element.closest('.form-group').append(error);
            },
            highlight: function (element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
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