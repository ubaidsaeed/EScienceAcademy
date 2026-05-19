<div class="">
    @push('style')
    <style>
        .days-selection {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
    
        .days-selection h3 {
            margin-bottom: 20px;
            font-weight: 600;
            color: #333;
        }
    
        .days-buttons {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
    
        .days-buttons .btn {
            flex: 1;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background: #f8f9fa;
            color: #333;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }
    
        .days-buttons .btn.active {
            background: #007bff;
            color: #fff;
            border-color: #007bff;
        }
    
        .custom-days-input, .custom-days-input2 {
            margin-top: 20px;
            display: none;
        }
    
        .custom-days-input.show, .custom-days-input2.show {
            display: block;
        }
    
        .custom-days-input input, .custom-days-input2 input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
        }
        
    </style>
    @endpush
    <!-- Row -->
    <div class="page-header d-lg-flex d-block">

        <div class="page-leftheader">
            <h4 class="page-title">Exam Schedule's</h4>
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
                            <livewire:exam-schedule-table />
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
                                    <label for="exampleFormControlInput1" class="form-label">Subject <span
                                            class="text-danger">*</span></label>
                                    <select class="form-control select2-show-searchs" required x-ref="subject_id"
                                        wire:model="subject_id" id="subject_id">
                                        <option value="">Choose....</option>

                                        @foreach ($subjects ?? [] as $subject)
                                            <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('subject_id')
                                    <span class="error" style="color: red">{{ $message }}</span>
                                @enderror
                            </div>
                            {{-- <div class="mb-3 col-lg-6">
                                <div x-data x-init="$nextTick(() => {
                                    let chapterSelect = $refs.chapter_ids;
                                    $(chapterSelect).select2();

                                    // Capture Select2 change and manually sync to Livewire
                                    $(chapterSelect).on('select2:select', function (e) {
                                        // Directly set the group_id value in Livewire
                                        $wire.set('chapter_ids', $(this).val());
                                    });

                                    // Sync the Select2 component with Livewire changes
                                    $watch('chapter_ids', value => {
                                        $(chapterSelect).val(value).trigger('change.select2');
                                    });
                                })" wire:ignore>
                                    <label for="exampleFormControlInput1" class="form-label">Chapters</label>
                                    <select class="form-control select2-show-search custom-select" multiple
                                        x-ref="chapter_ids" wire:model.defer="chapter_ids" id="chapter_id">
                                        <option value="">Choose....</option>
                                        @foreach ($grandchildData as $chapter)
                                        <option value="{{$chapter->folder_id}}">{{$chapter->media_name}}</option>
                                        @endforeach
                                    </select>
                                </div> --}}
                            {{-- @error('chapter_id') <span class="error" style="color: red">{{ $message }}</span>
                                @enderror --}}
                            {{-- Start folders records --}}
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
                                <input type="hidden" wire:ignore wire:model.defer="chapter_id" id="chapter_id_hidden">
                            </div>
                            {{-- </div> --}}
                            {{-- <div class="col-md-6 mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Date</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <span class="feather feather-clock"></span>
                                        </div>
                                    </div>
                                    <input class="form-control" id="datepicker-date" wire:model="date"
                                        placeholder="Date Range" type="date">
                                </div>
                                @error('date') <span class="error" style="color: red">{{ $message }}</span>
                                @enderror
                            </div> --}}
                            <!-- Schedule Days -->
                            <div class="days-selection">
                                <h3>Schedule Days</h3>
                                <div class="days-buttons">
                                    <button wire:click="selectScheduleDays(1)" type="button"
                                        class="btn {{ $scheduleselectedDays == 1 ? 'active' : '' }}">1 Day</button>
                                    <button wire:click="selectScheduleDays(7)" type="button"
                                        class="btn {{ $scheduleselectedDays == 7 ? 'active' : '' }}">7 Days</button>
                                    <button wire:click="selectScheduleDays(10)" type="button"
                                        class="btn {{ $scheduleselectedDays == 10 ? 'active' : '' }}">10 Days</button>
                                    <button
                                        wire:click="$set('scheduleshowCustomInput', true); $set('submissionshowCustomInput', false)"
                                        type="button"
                                        class="btn {{ $scheduleshowCustomInput ? 'active' : '' }}">Custom</button>
                                    <input type="hidden" wire:model="schedulecustomDays"
                                        value="{{ $scheduleselectedDays }}">
                                </div>

                                @if ($scheduleshowCustomInput == true)
                                    <div class="custom-days-input2 show">
                                        <input type="number" wire:model="schedulecustomDays"
                                            placeholder="Enter number of days" min="1">
                                        <button wire:click="submit" type="button"
                                            class="btn btn-primary w-100 mt-3">Submit</button>
                                    </div>
                                @endif
                            </div>

                            <!-- Submission Days -->
                            <div class="days-selection">
                                <h3>Submission Days</h3>
                                <div class="days-buttons">
                                    <button wire:click="selectSubmissionDays(1)" type="button"
                                        class="btn {{ $submissionselectedDays == 1 ? 'active' : '' }}">1 Day</button>
                                    <button wire:click="selectSubmissionDays(7)" type="button"
                                        class="btn {{ $submissionselectedDays == 7 ? 'active' : '' }}">7 Days</button>
                                    <button wire:click="selectSubmissionDays(10)" type="button"
                                        class="btn {{ $submissionselectedDays == 10 ? 'active' : '' }}">10
                                        Days</button>
                                    <button
                                        wire:click="$set('submissionshowCustomInput', true); $set('scheduleshowCustomInput', false)"
                                        type="button"
                                        class="btn {{ $submissionshowCustomInput ? 'active' : '' }}">Custom</button>
                                    <input type="hidden" wire:model="submissioncustomDays"
                                        value="{{ $submissionselectedDays }}">
                                </div>

                                @if ($submissionshowCustomInput)
                                    <div class="custom-days-input2 show">
                                        <input type="number" wire:model="submissioncustomDays"
                                            placeholder="Enter number of days" min="1">
                                        <button wire:click="submit" type="button"
                                            class="btn btn-primary w-100 mt-3">Submit</button>
                                    </div>
                                @endif
                            </div>
                            {{-- <div class="col-md-6 mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Start Time</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <span class="feather feather-clock"></span>
                                        </div>
                                    </div>
                                    <input class="form-control" id="datepicker-date" wire:model="start_time"
                                        placeholder="Date Range" type="time">
                                </div>
                                @error('start_time') <span class="error" style="color: red">{{ $message }}</span>
                                @enderror
                            </div> --}}


                            {{-- <div class="col-md-6">
                                <label for="exampleFormControlInput1" class="form-label">End Time</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <span class="feather feather-clock"></span>
                                        </div>
                                    </div>
                                    <input class="form-control" id="datepicker-date" wire:model="end_time"
                                        placeholder="Date Range" type="time">
                                </div>
                                @error('end_time') <span class="error" style="color: red">{{ $message }}</span>
                                @enderror
                            </div> --}}

                            <div class="mb-3 col-lg-6">
                                <div x-data x-init="$nextTick(() => {
                                    let selfSelect = $refs.exam_type;
                                    $(selfSelect).select2();
                                
                                    // Capture Select2 change and manually sync to Livewire
                                    $(selfSelect).on('select2:select', function(e) {
                                        // Directly set the group_id value in Livewire
                                        $wire.set('exam_type', $(this).val());
                                    });
                                
                                    // Sync the Select2 component with Livewire changes
                                    $watch('exam_type', value => {
                                        $(selfSelect).val(value).trigger('change.select2');
                                    });
                                })" wire:ignore>
                                    <label for="exampleFormControlInput1" class="form-label">Exam Type <span
                                            class="text-danger">*</span></label>
                                    <select class="form-control select2-show-search custom-select" x-ref="exam_type"
                                        wire:model="exam_type" id="exam_type">
                                        <option value="">Choose....</option>
                                        <option value="teacher">Teacher</option>
                                        <option value="self_assessment">Self Assessment</option>
                                    </select>
                                </div>
                                @error('exam_type')
                                    <span class="error" style="color: red">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3 col-lg-6">
                                <div x-data x-init="$nextTick(() => {
                                    let selfSelect = $refs.exam_id;
                                    $(selfSelect).select2();
                                
                                    // Capture Select2 change and manually sync to Livewire
                                    $(selfSelect).on('select2:select', function(e) {
                                        // Directly set the group_id value in Livewire
                                        $wire.set('exam_id', $(this).val());
                                    });
                                
                                    // Sync the Select2 component with Livewire changes
                                    $watch('exam_id', value => {
                                        $(selfSelect).val(value).trigger('change.select2');
                                    });
                                })" wire:ignore>
                                    <label for="exampleFormControlInput1" class="form-label">Exam Select<span
                                            class="text-danger">*</span></label>
                                    <select class="form-control select2-show-search custom-select" x-ref="exam_id"
                                        wire:model="exam_id" id="exam_id">
                                        <option value="">Choose....</option>
                                        @foreach ($exams as $exam)
                                            <option value="{{ $exam->id }}">{{ $exam->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('exam_type')
                                    <span class="error" style="color: red">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3 col-lg-6">
                                <div x-data x-init="$nextTick(() => {
                                    let result_after = $refs.result_after;
                                    $(result_after).select2();
                                
                                    // Capture Select2 change and manually sync to Livewire
                                    $(result_after).on('select2:select', function(e) {
                                        // Directly set the group_id value in Livewire
                                        $wire.set('result_after', $(this).val());
                                    });
                                
                                    // Sync the Select2 component with Livewire changes
                                    $watch('result_after', value => {
                                        $(result_after).val(value).trigger('change.select2');
                                    });
                                })" wire:ignore>
                                    <label for="exampleFormControlInput1" class="form-label">Show Result After Exam
                                        <span class="text-danger">*</span></label>
                                    <select class="form-control select2-show-search custom-select"
                                        x-ref="result_after" wire:model="result_after" id="result_after">
                                        <option value="">Choose....</option>
                                        <option value="yes">Yes</option>
                                        <option value="no">No</option>
                                    </select>
                                </div>

                                @error('result_after')
                                    <span class="error" style="color: red">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3 col-lg-6">
                                <label for="exampleFormControlInput1" class="form-label">Total Marks <span
                                        class="text-danger">*</span></label>
                                <input type="number" class="form-control" wire:model="total_marks">
                                @error('total_marks')
                                    <span class="error" style="color: red">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3 col-lg-6">
                                <label for="exampleFormControlInput1" class="form-label">Long Question Mark <span
                                        class="text-danger">*</span></label>
                                <input type="number" class="form-control" wire:model="l_q_mark">
                                @error('l_q_mark')
                                    <span class="error" style="color: red">{{ $message }}</span>
                                @enderror
                                @if ($longQuesError != '')
                                    <span class="error" style="color: red">{{ $longQuesError }}</span>
                                @endif
                            </div>
                            <div class="mb-3 col-lg-6">
                                <label for="exampleFormControlInput1" class="form-label">Short Question Mark <span
                                        class="text-danger">*</span></label>
                                <input type="number" class="form-control" wire:model="s_q_mark">
                                @error('s_q_mark')
                                    <span class="error" style="color: red">{{ $message }}</span>
                                @enderror
                                @if ($shortQuesError != '')
                                    <span class="error" style="color: red">{{ $shortQuesError }}</span>
                                @endif
                            </div>
                            <div class="mb-3 col-lg-6">
                                <label for="exampleFormControlInput1" class="form-label">MCQs Mark <span
                                        class="text-danger">*</span></label>
                                <input type="number" class="form-control" wire:model="quiz_mark">
                                @error('quiz_mark')
                                    <span class="error" style="color: red">{{ $message }}</span>
                                @enderror
                                @if ($mcqQuesError != '')
                                    <span class="error" style="color: red">{{ $mcqQuesError }}</span>
                                @endif
                            </div>
                            @if ($totalMarkError != '')
                                <div class="mb-3 col-lg-12 text-center">
                                    <span class="error" style="color: red">{{ $totalMarkError }}</span>
                                </div>
                            @endif
                            <div class="mb-3 col-lg-12">
                                <label for="exampleFormControlInput1" class="form-label">Note</label>
                                <textarea class="form-control" id="myeditorinstance" x-ref="note" rows="3" wire:model="note"></textarea>
                            </div>
                        </div>
                </div>
                {{--
            </div> --}}
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
    </div>
    <!--div-->
</div>
</div>
</div>

@push('script')
    <script src="{{ asset('build/assets/admin/js/custom/message.js') }}"></script>
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
                        url: '/exam-schedule/scheduleDelete',
                        data: {
                            _token: "{{ csrf_token() }}",
                            id: id,
                        },
                        success: function(response) {
                            Swal.fire(
                                'Deleted!',
                                'Assignment has been deleted.',
                                'success'
                            );
                            setTimeout(() => {
                                window.location.reload();
                            }, 1000);

                        },
                        error: function(xhr, status, error) {
                            Swal.fire(
                                'Error!',
                                'An error occurred while deleting the assignment.',
                                'error'
                            );
                        }
                    });
                }
            });
        });
    </script>
@endpush
