<div class="">

    <!-- Row -->
    <div class="page-header d-lg-flex d-block">

        <div class="page-leftheader">
            <h4 class="page-title">Case Studies</h4>
        </div>
        <div class="page-rightheader ">
            @if (session('message'))
                <div class="alert alert-success alert-message fade show" role="alert" id="success-alert">
                    {{ session('message') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-message fade show" role="alert" id="error-alert">
                    {{ session('error') }}
                </div>
            @endif
            <div class=" btn-list">
                @if ($page_type == 'View')
                    <button type="button" class="btn btn-primary" wire:click="Create">
                        Create
                    </button>
                @endif
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <!--div-->
            <div class="card">
                <div class="card-body">
                    @if ($page_type == 'view')
                        <div id="file-export_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                            <livewire:case-studies-table/>
                        </div>
                </div>
            </div>
        @else
            <div class="card">
                <div class="card-body">
                    <form wire:submit="{{ $form_url }}" method="post" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="mb-3 col-lg-6">
                                <label for="exampleFormControlInput1" class="form-label">Title</label>
                                <input type="text" class="form-control shadow-none" id="title"
                                    placeholder="Enter Title" wire:model="title">
                                <input type="hidden" wire:model="id">
                                @error('title')
                                    <span class="error" style="color: red">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3 col-lg-6">
                                <label for="exampleFormControlInput1" class="form-label">Image</label>
                                <input type="file" class="form-control shadow-none" id="image"
                                    placeholder="Enter Image" wire:model="image">
                                @error('image_url')
                                    <span class="error" style="color: red">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3 col-lg-6">
                                <div x-data x-init="$nextTick(() => {
                                    let statusSelect = $refs.board;
                                    $(statusSelect).select2();
                                
                                    // Capture Select2 change and manually sync to Livewire
                                    $(statusSelect).on('select2:select', function(e) {
                                        // Directly set the group_id value in Livewire
                                        $wire.set('board', $(this).val());
                                    });
                                
                                    // Sync the Select2 component with Livewire changes
                                    $watch('board', value => {
                                        $(statusSelect).val(value).trigger('change.select2');
                                    });
                                })" wire:ignore>
                                    <label for="exampleFormControlInput1" class="form-label">Board</label>
                                    <select class="form-control select2-show-search  custom-select" multiple
                                        wire:model.defer="board" id="board" x-ref="board">
                                        <option selected value="" disabled="disabled">Choose....</option>
                                        @foreach ($boards as $board)
                                            <option value="{{ $board->id }}">{{ $board->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('board')
                                        <span class="error" style="color: red">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-3 col-lg-6">
                                <div x-data x-init="$nextTick(() => {
                                    let statusSelect = $refs.status;
                                    $(statusSelect).select2();
                                
                                    // Capture Select2 change and manually sync to Livewire
                                    $(statusSelect).on('select2:select', function(e) {
                                        // Directly set the group_id value in Livewire
                                        $wire.set('status', $(this).val());
                                    });
                                
                                    // Sync the Select2 component with Livewire changes
                                    $watch('status', value => {
                                        $(statusSelect).val(value).trigger('change.select2');
                                    });
                                })" wire:ignore>
                                    <label for="exampleFormControlInput1" class="form-label">Status</label>
                                    <select class="form-control select2-show-search  custom-select" wire:model="status"
                                        id="status" x-ref="status">
                                        <option selected value="" disabled="disabled">Choose....</option>
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                    </select>
                                    @error('status')
                                        <span class="error" style="color: red">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-3 col-lg-12">
                               
                                <div class="mb-3 col-lg-12">
                                    <div x-data x-init="$nextTick(() => initTinyMCE('content', 'sections'))" wire:ignore>
                                        <label for="exampleFormControlInput1" class="form-label">Content</label>
                                        <textarea id="content" class="form-control" x-ref="content" wire:model.defer="sections"></textarea>
                                    </div>
                                </div>
                                
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" wire:click="back" class="btn btn-secondary">Close</button>
                            <button type="button" wire:click="store"
                                class="btn btn-primary shadow-none">{{ $button }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            @endif

            {{--
        </div>
    </div> --}}
            @include('livewire.admin.include.template-model')
            <!--div-->
        </div>
    </div>
    <div x-data x-init="$nextTick(() => {
        document.querySelectorAll('button[data-content]').forEach(button => {
            button.addEventListener('click', function () {
                const contentId = this.getAttribute('data-content');
                const templateContent = document.getElementById(contentId)?.innerHTML;
                
                if (templateContent) {
                    const editor = tinymce.get('content'); // Replace 'content' with your editor's ID
                    if (editor) {
                        editor.setContent(templateContent);
                    }
                }

                const modalElement = document.getElementById('exampleModal');
                if (modalElement) {
                    const modal = bootstrap.Modal.getInstance(modalElement);
                    if (modal) {
                        modal.hide();
                    }
                }
            });
        });
    })">
</div>

</div>
@push('script')
    <script src="{{ asset('build/assets/admin/js/custom/message.js') }}"></script>
    
    <script>
        function initTinyMCE(selector, content) {
        if (tinymce.get(selector)) {
            tinymce.get(selector).remove();
        }

        tinymce.init({
            selector: `#${selector}`,
            height: 300,
            plugins: 'image code table link media codesample template',
            toolbar: 'undo redo | styleselect | bold italic | image | alignleft aligncenter alignright alignjustify | outdent indent | media | table | add_advisor',

            image_title: true,
            extended_valid_elements: "svg[*],path[*],g[*],circle[*],rect[*],line[*],polyline[*],polygon[*],text[*]",
            automatic_uploads: true,
            images_upload_url: '', // Specify the upload URL if handling image uploads
            file_picker_types: 'image',
            file_picker_callback: function (cb, value, meta) {
                var input = document.createElement('input');
                input.setAttribute('type', 'file');
                input.setAttribute('accept', 'image/*, svg/*');

                input.onchange = function () {
                    var file = this.files[0];
                    var reader = new FileReader();
                    reader.onload = function () {
                        var id = 'blobid' + (new Date()).getTime();
                        var blobCache = tinymce.activeEditor.editorUpload.blobCache;
                        var base64 = reader.result.split(',')[1];
                        var blobInfo = blobCache.create(id, file, base64);
                        blobCache.add(blobInfo);

                        // Callback with the image URI
                        cb(blobInfo.blobUri(), { title: file.name });
                    };
                    reader.readAsDataURL(file);
                };

                input.click();
            },
            setup: function (editor) {
                // Handle content update
                editor.on('change', function () {
                    @this.set(content, editor.getContent());
                });

                // Add custom link on initialization
                editor.on('init', function () {
                    editor.dom.add(editor.getBody(), 'link', {
                        rel: 'stylesheet',
                        href: 'https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css'
                    });
                    editor.dom.add(editor.getBody(), 'link', {
                        rel: 'stylesheet',
                        href: '/build/assets/frontend/css/plugins/fontawesome-5.css'
                    });
                    editor.dom.add(editor.getBody(), 'link', {
                        rel: 'stylesheet',
                        type: 'image/svg+xml',
                        href: '/build/assets/frontend/images/logo/fv icon.svg'
                    });
                    editor.dom.add(editor.getBody(), 'link', {
                        rel: 'stylesheet',
                        href: '/build/assets/frontend/css/vendor/bootstrap.min.css'
                    });
                    editor.dom.add(editor.getBody(), 'link', {
                        rel: 'stylesheet',
                        href: '/build/assets/frontend/css/vendor/animate.css'
                    });
                    editor.dom.add(editor.getBody(), 'link', {
                        rel: 'stylesheet',
                        href: '/build/assets/frontend/css/style.css'
                    });
                    editor.dom.add(editor.getBody(), 'link', {
                        rel: 'stylesheet',
                        href: '/build/assets/frontend/css/vendor/magnific-popup.css'
                    });
                    editor.dom.add(editor.getBody(), 'link', {
                        rel: 'stylesheet',
                        href: '/build/assets/frontend/css/vendor/fonts.css'
                    });
                    editor.dom.add(editor.getBody(), 'link', {
                        rel: 'stylesheet',
                        href: '/build/assets/frontend/css/vendor/metismenu.css'
                    });
                    editor.dom.add(editor.getBody(), 'link', {
                        rel: 'stylesheet',
                        href: '/build/assets/frontend/css/vendor/swiper.css'
                    });
                    editor.dom.add(editor.getBody(), 'script', {
                        src: '/build/assets/frontend/js/main.js'
                    });
                    editor.dom.add(editor.getBody(), 'script', {
                        src: '/build/assets/frontend/js/vendor/jquery.min.js'
                    });
                    editor.dom.add(editor.getBody(), 'script', {
                        src: '/build/assets/frontend/js/plugins/audio.js'
                    });
                    editor.dom.add(editor.getBody(), 'script', {
                        src: '/build/assets/frontend/js/vendor/bootstrap.min.js'
                    });
                    editor.dom.add(editor.getBody(), 'script', {
                        src: '/build/assets/frontend/js/vendor/swiper.js'
                    });
                    editor.dom.add(editor.getBody(), 'script', {
                        src: '/build/assets/frontend/js/vendor/counter-up.js'
                    });
                    editor.dom.add(editor.getBody(), 'script', {
                        src: '/build/assets/frontend/js/vendor/waypoint.js'
                    });
                    editor.dom.add(editor.getBody(), 'script', {
                        src: '/build/assets/frontend/js/vendor/wow.js'
                    });
                    editor.dom.add(editor.getBody(), 'script', {
                        src: '/build/assets/frontend/js/vendor/parallax.js'
                    });
                    editor.dom.add(editor.getBody(), 'script', {
                        src: '/build/assets/frontend/js/vendor/gsap.js'
                    });
                    editor.dom.add(editor.getBody(), 'script', {
                        src: '/build/assets/frontend/js/vendor/scrolltrigger.js'
                    });
                    editor.dom.add(editor.getBody(), 'script', {
                        src: '/build/assets/frontend/js/vendor/smooth-scroll.min.js'
                    });
                    editor.dom.add(editor.getBody(), 'script', {
                        src: '/build/assets/frontend/js/vendor/split-text.js'
                    });
                    editor.dom.add(editor.getBody(), 'script', {
                        src: '/build/assets/frontend/js/vendor/metisMenu.min.js'
                    });
                    editor.dom.add(editor.getBody(), 'script', {
                        src: '/build/assets/frontend/js/plugins/theia-sticky-sidebar.min.js'
                    });
                    editor.dom.add(editor.getBody(), 'script', {
                        src: '/build/assets/frontend/js/plugins/resize-sensor.min.js'
                    });
                });

                // Add custom button to insert content
                editor.addButton('add_advisor', {
                    type: 'menubutton',
                    text: 'Section',
                    icon: false,
                    menu: [{
                        text: 'Add More Team Member',
                        onclick: function () {
                            var advisorItems = editor.dom.select('.row')[0];
                            if (advisorItems) {
                                var newContent = `
                                    <div class="col-lg-4 col-md-6 single-item">
                                        <div class="advisor-item">
                                            <div class="info-box">
                                                <div class="editable-image">
                                                    <img src="" alt="Thumb" class="advisor-img" data-name="advisor-1-image">
                                                </div>
                                                <div class="info-title">
                                                    <h4 class="editable-text" contenteditable="true" data-name="advisor-1-name">Professor. Nuri Paul</h4>
                                                    <span class="editable-text" contenteditable="true" data-name="advisor-1-title">Chemistry Specialist</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>`;
                                advisorItems.innerHTML += newContent;
                            } else {
                                editor.insertContent(`
                                    <div class="advisor-items text-center text-light">
                                        <div class="row">${newContent}</div>
                                    </div>`);
                            }
                        }
                    },
                    {
                        text: 'Template',
                        onclick: function () {
                            loadExternalAssets([{
                                type: 'css',
                                href: 'https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css'
                            },
                            {
                                type: 'js',
                                src: 'https://code.jquery.com/jquery-3.6.0.min.js'
                            },
                            {
                                type: 'js',
                                src: 'https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js'
                            }]);
                            $('#exampleModal').modal('show');
                        }
                    }]
                });
            }
        });
    }

    // Event listener for "Use Template" buttons
    // Ensure the event is correctly handled in Livewire
// document.addEventListener('DOMContentLoaded', function () {
//     document.querySelectorAll('button[data-content]').forEach(button => {
//         button.addEventListener('click', function () {
//             const contentId = this.getAttribute('data-content');
//             const templateContent = document.getElementById(contentId).innerHTML;
//             if (tinymce.activeEditor) {
//                 tinymce.activeEditor.setContent(templateContent);
//             }
//             const modal = bootstrap.Modal.getInstance(document.getElementById('exampleModal'));
//             modal.hide();
//         });
//     });
// });

    // Function to load external CSS and JS files
    function loadExternalAssets(files) {
        files.forEach(function (file) {
            if (file.type === 'css') {
                var link = document.createElement('link');
                link.rel = 'stylesheet';
                link.href = file.href;
                document.head.appendChild(link);
            } else if (file.type === 'js') {
                var script = document.createElement('script');
                script.src = file.src;
                script.onload = function () {
                    console.log(file.src + ' loaded successfully.');
                };
                document.head.appendChild(script);
            }
        });
    }
        
        document.addEventListener('deleteConfirm', function(event) {
            Swal.fire({
                title: event.detail[0]['title'],
                text: event.detail[0]['text'],
                icon: event.detail[0]['icon'],
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: event.detail[0]['confirmButtonText'],
                cancelButtonText: event.detail[0]['cancelButtonText']
            }).then((result) => {
                if (result.isConfirmed) {
                    let id = event.detail[0]['id'];
                    $.ajax({
                        type: 'post', // POST should be in uppercase
                        url: '/case-studies/caseDelete',
                        data: {
                            _token: "{{ csrf_token() }}",
                            id: id,
                        },
                        success: function(response) {
                            Swal.fire(
                                'Deleted!',
                                'Board has been deleted.',
                                'success'
                            );
                            setTimeout(() => {
                                window.location.reload();
                            }, 1000);

                        },
                        error: function(xhr, status, error) {
                            Swal.fire(
                                'Error!',
                                'An error occurred while deleting the user.',
                                'error'
                            );
                        }
                    });
                }
            });
        });
    </script>
@endpush
