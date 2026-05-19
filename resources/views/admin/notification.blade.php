<div class="">

    <!-- Row -->
    <div class="page-header d-lg-flex d-block">

        <div class="page-leftheader">
            <h4 class="page-title">Notification's</h4>
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
                            <livewire:notication-table />
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
                                @error('name')
                                    <span class="error" style="color: red">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="mb-3 col-lg-6">
                                <div x-data x-init="$nextTick(() => {
                                    let boardSelect = $refs.subscription;
                                    $(boardSelect).select2();

                                    // Capture Select2 change and manually sync to Livewire
                                    $(boardSelect).on('select2:select', function (e) {
                                        // Directly set the group_id value in Livewire
                                        $wire.set('subscription', $(this).val());
                                    });

                                    // Sync the Select2 component with Livewire changes
                                    $watch('subscription', value => {
                                        $(boardSelect).val(value).trigger('change.select2');
                                    });
                                })" wire:ignore>
                                    <label for="exampleFormControlInput1" class="form-label">Subscription</label>
                                    <select class="form-control select2-show-search selected2 custom-select" multiple wire:model.defer="subscription"
                                        id="subscription" x-ref="subscription">
                                        <option selected value="" disabled="disabled">Choose....</option>
                                        @foreach($subscription_plan as $item)
                                        <option value="{{$item->id}}">{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('subscription')
                                        <span class="error" style="color: red">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-3 col-lg-6">
                                <label for="exampleFormControlInput1" class="form-label">Date</label>
                                <input type="date" class="form-control shadow-none" id="date"
                                    placeholder="Enter Date" wire:model="date">
                                @error('date')
                                    <span class="error" style="color: red">{{ $message }}</span>
                                @enderror
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
    <div x-data x-init="$nextTick(() => {
        function initTinyMCE() {
            // Check if an instance already exists and remove it
            const existingEditor = tinymce.get('myeditorinstances');
            if (existingEditor) {
                existingEditor.remove();
            }

            // Initialize TinyMCE
            tinymce.init({
                selector: 'textarea#myeditorinstances',
                height: 300,
                plugins: 'image code table link media codesample',
                toolbar: 'undo redo | styleselect | bold italic | image | alignleft aligncenter alignright alignjustify | outdent indent | media | table',
                image_title: true,
                automatic_uploads: true,
                file_picker_types: 'image',
                file_picker_callback: function (cb, value, meta) {
                    const input = document.createElement('input');
                    input.setAttribute('type', 'file');
                    input.setAttribute('accept', 'image/*');
                    input.onchange = function () {
                        const file = this.files[0];
                        const reader = new FileReader();
                        reader.onload = function () {
                            const id = 'blobid' + (new Date()).getTime();
                            const blobCache = tinymce.activeEditor.editorUpload.blobCache;
                            const base64 = reader.result.split(',')[1];
                            const blobInfo = blobCache.create(id, file, base64);
                            blobCache.add(blobInfo);
                            cb(blobInfo.blobUri(), { title: file.name });
                        };
                        reader.readAsDataURL(file);
                    };
                    input.click();
                },
                setup: function (editor) {
                    editor.on('change', function () {
                        @this.set('content', editor.getContent()); // Sync TinyMCE content with Livewire property
                    });

                    editor.on('init', function () {
                        editor.setContent(@this.get('content') || ''); // Initialize content from Livewire property
                    });
                }
            });
        }

        initTinyMCE();

        // Reinitialize TinyMCE after Livewire updates
        Livewire.hook('message.processed', () => {
            initTinyMCE();
        });
    })" wire:ignore>
        <label for="exampleFormControlInput1" class="form-label">Content</label>
        <textarea class="form-control" id="myeditorinstances" x-ref="myeditorinstances" rows="3" wire:model.defer="content"></textarea>
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

            <!--div-->
        </div>
    </div>
</div>

@push('script')
    <script src="{{ asset('build/assets/admin/js/custom/message.js') }}"></script>
    <script>
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
                        url: '/notification/notificationDelete',
                        data: {
                            _token: "{{ csrf_token() }}",
                            id: id,
                        },
                        success: function(response) {
                            Swal.fire(
                                'Deleted!',
                                'Notification has been deleted.',
                                'success'
                            );
                            setTimeout(() => {
                                window.location.reload();
                            }, 1000);

                        },
                        error: function(xhr, status, error) {
                            Swal.fire(
                                'Error!',
                                'An error occurred while deleting the notification.',
                                'error'
                            );
                        }
                    });
                }
            });
        });
    </script>
@endpush
