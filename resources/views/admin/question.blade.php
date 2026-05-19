<div class="">

    <!-- Row -->
    <div class="page-header d-lg-flex d-block">

        <div class="page-leftheader">
            <h4 class="page-title">Question's</h4>
        </div>
        <div class="page-rightheader ">
            <div class=" btn-list">
                @if ($page_type == 'View')
                    <button type="button" class="btn btn-primary" wire:click="create">
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
                            <livewire:question-table />
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
                                    @error('board_id')
                                        <span class="error" style="color: red">{{ $message }}</span>
                                    @enderror
                                </div>
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
                                    @error('level_id')
                                        <span class="error" style="color: red">{{ $message }}</span>
                                    @enderror
                                </div>
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
                                })">
                                    <label for="exampleFormControlInput1" class="form-label">Subject</label>
                                    <select class="form-control select2-show-search custom-select" x-ref="subject_id"
                                        wire:model="subject_id" id="subject_id">
                                        <option value="">Choose....</option>
                                        @foreach ($subjects ?? [] as $subject)
                                            <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('subject_id')
                                        <span class="error" style="color: red">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- <div class="mb-3 col-lg-6">
                                <div x-data x-init="$nextTick(() => {
                                    let chapterSelect = $refs.chapter_id;
                                    $(chapterSelect).select2();

                                    // Capture Select2 change and manually sync to Livewire
                                    $(chapterSelect).on('select2:select', function (e) {
                                        // Directly set the group_id value in Livewire
                                        $wire.set('chapter_id', $(this).val());
                                    });

                                    // Sync the Select2 component with Livewire changes
                                    $watch('chapter_id', value => {
                                        $(chapterSelect).val(value).trigger('change.select2');
                                    });
                                })">
                                    <label for="exampleFormControlInput1" class="form-label">Chapters</label>
                                    <select class="form-control select2-show-search custom-select" x-ref="chapter_id"
                                        wire:model="chapter_id" id="chapter_id">
                                        <option value="">Choose....</option>
                                        @foreach ($grandchildData as $chapter)
                                        <option value="{{$chapter->folder_id}}">{{$chapter->media_name}}</option>
                                        @endforeach
                                    </select>
                                    @error('chapter_id') <span class="error" style="color: red">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div> --}}
                            <div class="mb-3 col-lg-6">
                                <label class="form-label">Select Chapters</label>
                                <div id="jstree_demo_div" x-data="jstreeData(@entangle('folderHierarchy'), @entangle('chapter_id'))" x-init="initJsTree" wire:ignore>
                                    <!-- The jsTree will be dynamically populated here -->
                                </div>
                                @error('chapter_id')
                                    <span class="error" style="color: red">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3 col-lg-6 d-none">
                                <!-- Hidden input to store the selected chapter ids -->
                                <input type="hidden" wire:model="chapter_id" id="chapter_id_hidden" wire:ignore>
                            </div>
                            <div class="mb-3 col-lg-4">
                                <div x-data x-init="$nextTick(() => {
                                    let groupSelect = $refs.group_id;
                                    $(groupSelect).select2();
                                
                                    // Capture Select2 change and manually sync to Livewire
                                    $(groupSelect).on('select2:select', function(e) {
                                        // Directly set the group_id value in Livewire
                                        $wire.set('group_id', $(this).val());
                                    });
                                
                                    // Sync the Select2 component with Livewire changes
                                    $watch('group_id', value => {
                                        $(groupSelect).val(value).trigger('change.select2');
                                    });
                                })" wire:ignore>

                                    <label for="group_id" class="form-label">Group <span
                                            class="text-danger">*</span></label>

                                    <select class="form-control select2-show-search custom-select" wire:model="group_id"
                                        x-ref="group_id" id="group_id">
                                        <option value="">Choose...</option>
                                        @foreach (App\Models\QuestionGroups::all() as $group)
                                            <option value="{{ $group->id }}">{{ $group->title }}</option>
                                        @endforeach
                                    </select>

                                    @error('group_id')
                                        <span class="error" style="color: red">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-3 col-lg-4" wire:ignore>

                                <label for="exampleFormControlInput1" class="form-label">Question Type <span
                                        class="text-danger">*</span></label>
                                <select class="form-control select2-show-search custom-select"
                                    wire:model="question_type" x-ref="question_type" id="question_type" question_type>
                                    <option selected value="">Choose....</option>
                                    @foreach ($questionTypeData as $type)
                                        <option value="{{ $type['name'] }}">{{ $type['name'] }}</option>
                                    @endforeach
                                </select>
                                @error('question_type')
                                    <span class="error" style="color: red">{{ $message }}</span>
                                @enderror
                            </div>
                            {{-- </div> --}}
                            <div class="mb-3 col-lg-2">
                                <label for="exampleFormControlInput1" class="form-label">Marks <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control shadow-none" id="marks"
                                    placeholder="Enter Marks" wire:model="marks">
                                <input type="hidden" wire:model="id">
                                @error('marks')
                                    <span class="error" style="color: red">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3 col-lg-2 @if ($shuffle_answer) d-block @else d-none @endif"
                                id="shuffle" wire:ignore>
                                <label for="exampleFormControlInput1" class="form-label">Shuffle Answer <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control shadow-none" id="shuffle_answer"
                                    placeholder="Enter shuffle" wire:model="shuffle_answer"
                                    value="@if ($shuffle_answer) {{ $shuffle_answer }} @else @endif">
                                <input type="hidden" wire:model="id">
                                @error('shuffle_answer')
                                    <span class="error" style="color: red">{{ $message }}</span>
                                @enderror
                            </div>
                            {{-- <div class="mb-3 col-lg-4">
                                <label for="exampleFormControlInput1" class="form-label">Image(Optional)</label>
                                <input type="file" class="form-control shadow-none" id="image" placeholder="Enter image"
                                    wire:model="image">
                                @error('image') <span class="error" style="color: red">{{ $message }}</span>
                                @enderror
                            </div> --}}
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
                                    <label for="content" class="form-label">Question <span
                                            class="text-danger">*</span></label>
                                    <textarea class="form-control" id="content" wire:model="content"></textarea>
                                </div>
                            </div>
                            {{-- <div
                                class="mb-3 @if ($question_type == 'Multiple Choice') d-block @else d-none @endif d-none"
                                id="options" wire:ignore> --}}
                            <div class="mb-3 d-none " id="options" wire:ignore>
                                <div class="row">
                                    {{-- <div class="mb-0 col-lg-8">
                                            <label for="exampleFormControlInput1" class="form-label">Number Of Options
                                            </label>
                                            <input type="number" wire:model="number_options"
                                                class="form-control shadow-none" wire:model="number_options"
                                                id="number_options" number_options
                                                placeholder="Enter number of options">
                                        </div> --}}
                                    {{-- <div class="col-lg-4">
                                            <label for="exampleFormControlInput1" class="form-label"><br>
                                            </label>
                                            <button type="button" class="btn btn-primary shadow-none" id="add_options"
                                                add_options>Added</button>
                                        </div> --}}
                                </div>

                                <div class="col-lg-4" id="add_button">
                                    <label for="exampleFormControlInput1" class="form-label"><br>
                                    </label>
                                    <button type="button" class="btn btn-primary shadow-none" id="add_options"
                                        add_options>Added</button>
                                </div>
                            </div>

                        </div>
                        <!-- Display existing options -->
                        @if ($page_type == 'Edit')
                            @foreach ($option as $index => $options)
                                <div class="row mb-3">
                                    <div class="col-lg-8">
                                        <input type="text" wire:model.defer="options.{{ $index }}"
                                            class="form-control shadow-none" value="{{ $options }}">
                                    </div>
                                    <div class="col-lg-4">
                                        <label for="correct.{{ $index }}" class="form-label"></label>
                                        <input type="checkbox" @if ($correct[$index] == 1) checked @else @endif
                                            wire:model.defer="correct.{{ $index }}"
                                            class="form-check-input shadow-none" id="correct.{{ $index }}">

                                    </div>
                                </div>
                            @endforeach
                        @endif
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
        </div>
        <div x-data x-init="$nextTick(() => {
            let questionType = $wire.$el.querySelector('[question_type]');
        
            if (questionType) {
                if (questionType.value == 'Multiple Choice') {
                    document.getElementById('options').classList.remove('d-none');
                    document.getElementById('shuffle').classList.remove('d-none');
                } else {
                    document.getElementById('shuffle').classList.add('d-none');
                    document.getElementById('options').classList.add('d-none');
                }
                questionType.addEventListener('change', function() {
                    if (this.value == 'Multiple Choice') {
                        document.getElementById('options').classList.remove('d-none');
                        document.getElementById('shuffle').classList.remove('d-none');
                    } else {
                        document.getElementById('shuffle').classList.add('d-none');
                        document.getElementById('options').classList.add('d-none');
                    }
                });
            }
        
            let add_options = $wire.$el.querySelector('[add_options]');
        
            if (add_options) {
                let A = 1;
                add_options.addEventListener('click', function() {
                    let number_options = $wire.$el.querySelector('[number_options]');
                    A++;
                    let newOption = document.createElement('div');
                    newOption.classList.add('mb-3');
                    newOption.innerHTML = `
                                                                                <div class='section'>
                                                                                    <div class='row'>
                                                                                        <div class='mt-4 col-lg-8'>
                                                                                            <input type='text' wire:model.defer='option.${A}' wire:key='option.${A}' class='form-control shadow-none' id='option${A}' placeholder='Option'>
                                                                                        </div>
                                                                                        <div class='col-lg-4'>
                                                                                            <label for='correct.${A}' class='form-label'><br></label>
                                                                                            <input type='checkbox' wire:model.defer='correct.${A}' wire:key='correct.${A}' class='form-check-input shadow-none' id='correct${A}'>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                    <a href='javascript:volid(0)' type='button' class='remove my-2 shadow-none' style='font-size:x-large' id='remove_field${A}'
                                                                                    add_options><i class='bi bi-x'></i></a>
                                                                                `;
                    document.getElementById('options').appendChild(newOption);
                    let removeButton = document.getElementById('remove_field' + A);
                    removeButton.addEventListener('click', () => {
                        let parentContainer = removeButton.parentElement;
                        parentContainer.remove();
                    });
                });
            }
        })">
        </div>

    </div>
</div>
</div>
@push('script')
    <script src="{{ asset('build/assets/admin/js/custom/questions.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/jstree.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Pre-selected chapter IDs from the backend
            const chapterSelected = @json($chapter_id);
            console.log('Selected Chapter:', chapterSelected);
        });

        // Function to initialize and manage jsTree
        function jstreeData(folderHierarchy, chapterSelected) {
            return {
                treeData: folderHierarchy, // Dynamic data for jsTree
                selectedIds: chapterSelected, // Pre-selected chapter IDs
                initJsTree() {
                    // Initialize jsTree with the provided data
                    $('#jstree_demo_div').jstree({
                        core: {
                            data: this.treeData, // Tree data
                            multiple: false, // Ensure single selection
                            themes: {
                                stripes: true // Optional: Add stripe styling
                            },
                        },
                        plugins: ["wholerow"] // Optional: Enable plugins (e.g., wholerow)
                    });

                    // Select pre-selected nodes once jsTree is ready
                    $('#jstree_demo_div').on('ready.jstree', () => {
                        if (this.selectedIds) {
                            $('#jstree_demo_div').jstree('select_node', this.selectedIds.toString());
                        }
                    });

                    // Handle selection change in jsTree
                    $('#jstree_demo_div').on('changed.jstree', (e, data) => {
                        const selectedNode = data.selected[0]; // Get the first selected node ID
                        $('#chapter_id_hidden').val(selectedNode); // Set the value in the hidden input
                        @this.set('chapter_id', selectedNode); // Pass the single chapter ID to Livewire
                    });
                },
                openAllNodes() {
                    $('#jstree_demo_div').jstree('open_all'); // Open all nodes in jsTree
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
                    let id = event.detail[0]['userId'];
                    $.ajax({
                        type: 'post', // POST should be in uppercase
                        url: '/question/delete',
                        data: {
                            _token: "{{ csrf_token() }}",
                            userId: id,
                        },
                        success: function(response) {
                            Swal.fire(
                                'Deleted!', 'Question has been deleted.', 'success'
                            );
                            setTimeout(() => {

                                window.location.reload();
                            }, 1000);

                        },
                        error: function(xhr, status, error) {
                            Swal.fire(
                                'Error!', 'An error occurred while deleting the question.',
                                'error'
                            );
                        }
                    });
                }
            });
        });
    </script>
@endpush
