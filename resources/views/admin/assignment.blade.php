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
            <h4 class="page-title">Assignment's</h4>
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
                            <livewire:assignment-table />
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
                                <label for="exampleFormControlInput1" class="form-label">Title <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control shadow-none" id="title"
                                    placeholder="Enter Title" wire:model="title">
                                <input type="hidden" wire:model="id">
                                @error('title')
                                    <span class="error" style="color: red">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3 col-lg-6">
                                <div x-data x-init="$nextTick(() => {
                                    let boardSelect = $refs.package_id;
                                    $(boardSelect).select2();
                                
                                    // Capture Select2 change and manually sync to Livewire
                                    $(boardSelect).on('select2:select', function(e) {
                                        let selectedBoardId = $(this).val();
                                        $wire.set('package_id', selectedBoardId); // Set board_id in Livewire
                                        $wire.getChapters(selectedBoardId); // Trigger function with board_id
                                    });
                                
                                    // Sync Select2 with Livewire changes
                                    $watch('package_id', value => {
                                        $(boardSelect).val(value).trigger('change.select2');
                                    });
                                })" wire:ignore>
                                    <label for="package_id" class="form-label">Packages<span
                                            class="text-danger">*</span></label>
                                    <select class="form-control select2-show-searchs" required x-ref="package_id"
                                        wire:model="package_id" id="package_id">
                                        <option value="">Choose....</option>
                                        @foreach ($package as $packages)
                                            <option value="{{ $packages->id }}">{{ $packages->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('package_id')
                                        <span class="error" style="color: red">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3 col-lg-6">
                                {{-- <div x-data x-init="$nextTick(() => {
                                    let statusSelect = $refs.status;
                                    $(statusSelect).select2();
                            
                                    // Capture Select2 change and manually sync to Livewire
                                    $(statusSelect).on('select2:select', function(e) {
                                        $wire.set('chapter_id', $(this).val());
                                    });
                            
                                    // Sync Select2 with Livewire changes
                                    $watch('chapter_id', value => {
                                        $(statusSelect).val(value).trigger('change.select2');
                                    });
                                })" wire:ignore> --}}
                                <label for="status" class="form-label">Chapters<span
                                        class="text-danger">*</span></label>
                                <select class="form-control select2-show-search custom-select" required x-ref="status"
                                    wire:model="chapter_id" id="status">
                                    <option selected value="" disabled="disabled">Choose....</option>
                                    @foreach ($board_record as $item)
                                        {{-- {{$item->id}} --}}
                                        <option value="{{ $item->id }}">
                                            {{ \Illuminate\Support\Str::beforeLast($item->name, '.') }}</option>
                                    @endforeach
                                </select>
                                @error('chapter_id')
                                    <span class="error" style="color: red">{{ $message }}</span>
                                @enderror
                                {{-- </div> --}}
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
                                    <label for="exampleFormControlInput1" class="form-label">Status <span
                                            class="text-danger">*</span></label>
                                    <select class="form-control select2-show-search custom-select" required
                                        x-ref="status" wire:model="status" id="status">
                                        <option selected value="" disabled="disabled">Choose....</option>
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                    </select>
                                    @error('status')
                                        <span class="error" style="color: red">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            
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
                                    <button wire:click="$set('scheduleshowCustomInput', true); $set('submissionshowCustomInput', false)" type="button"
                                        class="btn {{ $scheduleshowCustomInput ? 'active' : '' }}">Custom</button>
                                        <input type="hidden" wire:model="schedulecustomDays" value="{{ $scheduleselectedDays }}">
                                </div>
                            
                                @if ($scheduleshowCustomInput == true)
                                    <div class="custom-days-input2 show">
                                        <input type="number" wire:model="schedulecustomDays" placeholder="Enter number of days" min="1">
                                        <button wire:click="submit" type="button" class="btn btn-primary w-100 mt-3">Submit</button>
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
                                        class="btn {{ $submissionselectedDays == 10 ? 'active' : '' }}">10 Days</button>
                                    <button wire:click="$set('submissionshowCustomInput', true); $set('scheduleshowCustomInput', false)" type="button"
                                        class="btn {{ $submissionshowCustomInput ? 'active' : '' }}">Custom</button>
                                        <input type="hidden" wire:model="submissioncustomDays" value="{{ $submissionselectedDays }}">
                                </div>
                            
                                @if ($submissionshowCustomInput)
                                    <div class="custom-days-input2 show">
                                        <input type="number" wire:model="submissioncustomDays" placeholder="Enter number of days" min="1">
                                        <button wire:click="submit" type="button" class="btn btn-primary w-100 mt-3">Submit</button>
                                    </div>
                                @endif
                            </div>                            
                            <div class="mb-3 col-lg-6 d-none">
                                <label for="exampleFormControlInput1" class="form-label">Assignment File</label>
                                <input type="file" class="form-control shadow-none" id="file"
                                    placeholder="Enter fil.e" wire:model="file">
                                @error('file')
                                    <span class="error" style="color: red">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3 col-lg-12">
                                <label for="exampleFormControlInput1" class="form-label">Notes</label>
                                <textarea class="form-control" id="myeditorinstance" x-ref="myeditorinstance" rows="3" wire:model="notes"></textarea>

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
    <script src="{{ asset('build/assets/admin/js/custom/chapter.js') }}"></script>
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
                        url: '/assignments/assignmentDelete',
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
