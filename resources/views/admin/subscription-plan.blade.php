<div class="">

    <!-- Row -->
    <div class="page-header d-lg-flex d-block">

        <div class="page-leftheader">
            <h4 class="page-title">Packages</h4>
        </div>
        <div class="page-rightheader ">

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
                            <livewire:subscription-plan-table />
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
                                <label for="exampleFormControlInput1" class="form-label">Plan Name</label>
                                <input type="text" class="form-control shadow-none" id="plan_name"
                                    placeholder="Enter Plan Name" wire:model="plan_name">
                                <input type="hidden" wire:model="id">
                                @error('plan_name')
                                    <span class="error" style="color: red">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3 col-lg-6">
                                <label for="exampleFormControlInput1" class="form-label">Plan Price</label>
                                <input type="text" class="form-control shadow-none" id="plan_price"
                                    placeholder="Enter Plan Price" wire:model="plan_price">
                                @error('plan_price')
                                    <span class="error" style="color: red">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3 col-lg-6">
                                <label for="exampleFormControlInput1" class="form-label">Duration No </label>
                                <input type="text" class="form-control shadow-none" id="duration"
                                    placeholder="Enter Plan Price" wire:model="duration">
                                @error('duration')
                                    <span class="error" style="color: red">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3 col-lg-6">
                                <div x-data x-init="$nextTick(() => {
                                    let durationSelect = $refs.duration_type;
                                    $(durationSelect).select2();
                                
                                    // Capture Select2 change and manually sync to Livewire
                                    $(durationSelect).on('select2:select', function(e) {
                                        // Directly set the group_id value in Livewire
                                        $wire.set('duration_type', $(this).val());
                                    });
                                
                                    // Sync the Select2 component with Livewire changes
                                    $watch('duration_type', value => {
                                        $(durationSelect).val(value).trigger('change.select2');
                                    });
                                })" wire:ignore>
                                    <label for="exampleFormControlInput1" class="form-label">Duration Type <span
                                            class="text-danger">*</span></label>
                                    <select class="form-control select2-show-searchs" required x-ref="duration_type"
                                        wire:model="duration_type" id="duration_type">
                                        <option value="">Choose....</option>
                                        <option value="day">Day</option>
                                        <option value="month">Month</option>
                                        <option value="year">Year</option>
                                    </select>
                                </div>
                                @error('duration_type')
                                    <span class="error" style="color: red">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3 col-lg-6">
                                <label for="exampleFormControlInput1" class="form-label">Student Limit(Optional)
                                </label>
                                <input type="text" class="form-control shadow-none" id="student_limit"
                                    placeholder="Enter Student Limit" wire:model="student_limit">
                                @error('student_limit')
                                    <span class="error" style="color: red">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3 col-lg-6">
                                <div x-data x-init="$nextTick(() => {
                                    let boardSelect = $refs.board_id;
                                    $(boardSelect).select2();
                                
                                    // Capture Select2 change and manually sync to Livewire
                                    $(boardSelect).on('select2:select', function(e) {
                                        // Directly set the group_id value in Livewire
                                        $wire.set('board_id', $(this).val());
                                    });
                                
                                    // Sync the Select2 component with Livewire changes
                                    $watch('board_id', value => {
                                        $(boardSelect).val(value).trigger('change.select2');
                                    });
                                })" wire:ignore>
                                    <label for="exampleFormControlInput1" class="form-label">Boards <span
                                            class="text-danger">*</span></label>
                                    <select class="form-control select2-show-searchs" required x-ref="board_id"
                                        wire:model="board_id" id="board_id">
                                        <option value="">Choose....</option>

                                        @foreach ($boards as $board)
                                            <option value="{{ $board->id }}">{{ $board->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('board_id')
                                    <span class="error" style="color: red">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3 col-lg-6">
                                <div x-data x-init="$nextTick(() => {
                                    let levelSelect = $refs.level_id;
                                    $(levelSelect).select2();
                                
                                    // Capture Select2 change and manually sync to Livewire
                                    $(levelSelect).on('select2:select', function(e) {
                                        // Directly set the group_id value in Livewire
                                        $wire.set('level_id', $(this).val());
                                    });
                                
                                    // Sync the Select2 component with Livewire changes
                                    $watch('level_id', value => {
                                        $(levelSelect).val(value).trigger('change.select2');
                                    });
                                })" wire:ignore>
                                    <label for="exampleFormControlInput1" class="form-label">Levels <span
                                            class="text-danger">*</span></label>
                                    <select class="form-control select2-show-searchs" required x-ref="level_id"
                                        wire:model="level_id" id="level_id">
                                        <option value="">Choose....</option>

                                        @foreach ($levels ?? [] as $level)
                                            <option value="{{ $level->id }}">{{ $level->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('level_id')
                                    <span class="error" style="color: red">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3 col-lg-6">
                                <div x-data x-init="$nextTick(() => {
                                    let subjectSelect = $refs.subject_id;
                                    $(subjectSelect).select2();
                                
                                    // Capture Select2 change and manually sync to Livewire
                                    $(subjectSelect).on('select2:select', function(e) {
                                        // Directly set the group_id value in Livewire
                                        $wire.set('subject_id', $(this).val());
                                    });
                                
                                    // Sync the Select2 component with Livewire changes
                                    $watch('subject_id', value => {
                                        $(subjectSelect).val(value).trigger('change.select2');
                                    });
                                })" wire:ignore>
                                    <label for="exampleFormControlInput1" class="form-label">Subject</label>
                                    <select class="form-control select2-show-search custom-select" x-ref="subject_id"
                                        wire:model="subject_id" id="subject_id">
                                        <option value="">Choose....</option>
                                        @foreach ($subjects ?? [] as $subject)
                                            <option value="{{ $subject->id }}">{{ $subject->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('subject_id')
                                    <span class="error" style="color: red">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3 col-lg-6 d-none">
                                <!-- Hidden input to store the selected chapter ids -->
                                <input type="hidden" wire:model.defer="chapter_id" id="chapter_id_hidden">
                            </div>
                            {{-- Start folders records --}}

                            {{-- <input type="hidden" wire:model.defer="chapter_id" id="chapter_id" x-ref="chapter_id"> --}}
                            <div class="mb-3 col-lg-6">
                                <label for="exampleFormControlInput1" class="form-label">Chapters</label>
                                <div id="jstree_demo_div" x-data="jstreeData(@entangle('folderHierarchy'), @entangle('chapter_id'))" x-init="initJsTree"
                                    wire:ignore>
                                    <!-- The jsTree will be dynamically populated here -->
                                </div>
                                @error('chapter_id')
                                    <span class="error" style="color: red">{{ $message }}</span>
                                @enderror
                            </div>
                             <div class="mb-3 col-lg-6">
                                <label for="exampleFormControlInput1" class="form-label">Total Chapters
                                </label>
                                <input type="text" class="form-control shadow-none" id="total_chapters"
                                    placeholder="Enter Total Chapters" wire:model="total_chapters">
                                @error('total_chapters')
                                    <span class="error" style="color: red">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3 col-lg-6">
                                <label for="exampleFormControlInput1" class="form-label">Online Notes
                                </label>
                                <select class="form-control select2-show-search custom-select" x-ref="online"
                                    wire:model="online" id="online">
                                    <option selected>Choose....</option>
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                                @error('online')
                                    <span class="error" style="color: red">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3 col-lg-6">
                                <label for="exampleFormControlInput1" class="form-label">Topical Past Papers
                                </label>
                                <select class="form-control select2-show-search custom-select" x-ref="tp"
                                    wire:model="tp" id="tp">
                                    <option selected >Choose....</option>
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                                @error('tp')
                                    <span class="error" style="color: red">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3 col-lg-6">
                                <label for="exampleFormControlInput1" class="form-label">Worksheets with Award Badges
                                </label>
                                <select class="form-control select2-show-search custom-select" x-ref="ws_aw_bg"
                                    wire:model="ws_aw_bg" id="ws_aw_bg">
                                    <option selected>Choose....</option>
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                                @error('ws_aw_bg')
                                    <span class="error" style="color: red">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3 col-lg-6">
                                <label for="exampleFormControlInput1" class="form-label">02 Recorded / Live Sessions
                                </label>
                                <select class="form-control select2-show-search custom-select" x-ref="rls"
                                    wire:model="rls" id="rls">
                                    <option selected>Choose....</option>
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                                @error('rls')
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
                                    <select class="form-control select2-show-search custom-select" x-ref="status"
                                        wire:model="status" id="status">
                                        <option selected value="" disabled="disabled">Choose....</option>
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                    </select>
                                </div>
                                @error('status')
                                    <span class="error" style="color: red">{{ $message }}</span>
                                @enderror
                            </div>
                             <div class="mb-3 col-lg-12">
                                 <div x-data x-init="$nextTick(() => {
                                    function initTinyMCE() {
                                        if (tinymce.get('content')) {
                                            tinymce.get('content').remove();
                                        }
                                        tinymce.init({
                                            selector: '#content',
                                            height: 300,
                                            plugins: 'image code table link media codesample',
                                            toolbar: 'undo redo | styleselect | bold italic | image | alignleft aligncenter alignright alignjustify | outdent indent | media | table',
                                            image_title: true,
                                            automatic_uploads: true,
                                            file_picker_types: 'image',
                                            file_picker_callback: function(cb, value, meta) {
                                                const input = document.createElement('input');
                                                input.setAttribute('type', 'file');
                                                input.setAttribute('accept', 'image/*');
                                                input.onchange = function() {
                                                    const file = this.files[0];
                                                    const reader = new FileReader();
                                                    reader.onload = function() {
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
                                            setup: function(editor) {
                                                editor.on('change', function() {
                                                    @this.set('content', editor.getContent());
                                                });
                                            }
                                        });
                                    }
                                
                                    initTinyMCE();
                                
                                    Livewire.hook('message.processed', () => {
                                        initTinyMCE();
                                        const editor = tinymce.get('myeditorinstance');
                                        if (editor) {
                                            editor.setContent(@this.get('content') || '');
                                        }
                                    });
                                })" wire:ignore>
                                    <label for="content" class="form-label">Content</label><span
                                            class="text-danger">*</span></label>
                                    <textarea class="form-control" id="content" wire:model="content"></textarea>
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
                    @endif

                </div>
                <!--div-->
            </div>
        </div>
    </div>
</div>
@push('script')
    <script src="{{ asset('build/assets/admin/js/custom/topic.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/jstree.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const chapterSelected = @json($chapter_id); // Pre-selected chapter IDs from backend
            console.log('Selected Chapters:', chapterSelected);
        });
        // start js tree
        function jstreeData(folderHierarchy, chapterSelected) {
            return {
                treeData: folderHierarchy,
                selectedIds: chapterSelected, // Pre-selected chapter IDs
                initJsTree() {
                    // Initialize jsTree with dynamic data
                    $('#jstree_demo_div').jstree({
                        core: {
                            data: this.treeData,
                            multiple: true,
                            themes: {
                                stripes: true // Optional: Stripes for styling
                            },
                        },
                        plugins: ["wholerow"] // Optional: Enable plugins
                    });
                    $('#jstree_demo_div').on('ready.jstree', () => {
                        this.selectedIds.forEach((id) => {
                            $('#jstree_demo_div').jstree('select_node', id.toString());
                        });
                    });
                    // Bind to jsTree events
                    $('#jstree_demo_div').on('changed.jstree', (e, data) => {
                        const selectedNodes = data.selected; // Array of selected node IDs
                        $('#chapter_id_hidden').val(selectedNodes.join(','));
                        @this.set('chapter_id', selectedNodes); // Use Livewire's @this to pass data to the backend

                    });
                },
                openAllNodes() {
                    $('#jstree_demo_div').jstree('open_all');
                }
            };
        }
        // end js tree 

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
                        url: '/subscription/planDelete',
                        data: {
                            _token: "{{ csrf_token() }}",
                            id: id,
                        },
                        success: function(response) {
                           
                            Swal.fire(
                                'Deleted!',
                                'Topic has been deleted.',
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
