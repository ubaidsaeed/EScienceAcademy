<div class="">
    <!-- Row -->
    <div class="page-header d-lg-flex d-block">

        <div class="page-leftheader">
            <h4 class="page-title">Quiz's</h4>
        </div>
        <div class="page-rightheader ">
            {{-- alert message --}}
            @if (session('message'))
            <div class="alert alert-success alert-message fade show " role="alert" id="success-alert">
                {{ session('message') }}
            </div>
            @endif
            @if(session('error'))
            <div class="alert alert-danger alert-message fade show" role="alert" id="error-alert">
                {{ session('error') }}
            </div>
            @endif
            <div class=" btn-list">
                @if($page_type == 'View')
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
                    @if($page_type == 'view')
                    <div id="file-export_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                        <livewire:quiz-table />
                    </div>
                </div>
            </div>
            @else

            <div class="card">
                <div class="card-body">
                    <form wire:submit="{{$form_url}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="mb-3 col-lg-6">
                                <label for="exampleFormControlInput1" class="form-label">Title <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control shadow-none" id="title" placeholder="Enter Title"
                                    wire:model="title">
                                <input type="hidden" wire:model="id">
                                @error('title') <span class="error" style="color: red">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-3 col-lg-6">
                                <label for="exampleFormControlInput1" class="form-label">Instruction</label>
                                <input type="text" class="form-control shadow-none" id="instruction"
                                    placeholder="Enter Instruction" wire:model="instruction" autocomplete="instruction">
                                @error('instruction') <span class="error" style="color: red">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3 col-lg-6">
                                <div x-data x-init="$nextTick(() => {
                                    let boardSelect = $refs.board_id;
                                    $(boardSelect).select2();

                                    // Capture Select2 change and manually sync to Livewire
                                    $(boardSelect).on('select2:select', function (e) {
                                        // Directly set the group_id value in Livewire
                                        $wire.set('board_id', $(this).val());
                                    });

                                    // Sync the Select2 component with Livewire changes
                                    $watch('board_id', value => {
                                        $(boardSelect).val(value).trigger('change.select2');
                                    });
                                })" wire:ignore>

                                    <label for="exampleFormControlInput1" class="form-label">Board <span
                                            class="text-danger">*</span> </label>
                                    <select class="form-control select2-show-search custom-select" x-ref="board_id"
                                        wire:model="board_id" id="board_id">
                                        <option selected value="" >Choose....</option>
                                        @foreach ($boards as $board)
                                        @if($board->parent_id == null)
                                        <option value="{{ $board->id }}">{{ $board->name }}</option>
                                        @endif
                                        @endforeach
                                    </select>
                                    @error('board_id ') <span class="error" style="color: red">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-3 col-lg-6">
                                <div x-data x-init="$nextTick(() => {
                                    let levelSelect = $refs.level_id;
                                    $(levelSelect).select2();

                                    // Capture Select2 change and manually sync to Livewire
                                    $(levelSelect).on('select2:select', function (e) {
                                        // Directly set the group_id value in Livewire
                                        $wire.set('level_id', $(this).val());
                                    });

                                    // Sync the Select2 component with Livewire changes
                                    $watch('level_id', value => {
                                        $(levelSelect).val(value).trigger('change.select2');
                                    });
                                })" wire:ignore>
                                    <label for="exampleFormControlInput1" class="form-label">Level <span
                                            class="text-danger">*</span></label>
                                    <select class="form-control select2-show-search custom-select" x-ref="level_id"
                                        wire:model="level_id" id="level_id">
                                        <option selected value="" >Choose....</option>
                                        @foreach ($boards as $board)
                                        @if($board->parent_id != null)
                                        <option value="{{ $board->id }}">{{ $board->name }}</option>
                                        @endif
                                        @endforeach
                                    </select>
                                    @error('level_id') <span class="error" style="color: red">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3 col-lg-6">
                                <div x-data x-init="$nextTick(() => {
                                    let groupSelect = $refs.group_id;
                                    $(groupSelect).select2();

                                    // Capture Select2 change and manually sync to Livewire
                                    $(groupSelect).on('select2:select', function (e) {
                                        // Directly set the group_id value in Livewire
                                        $wire.set('group_id', $(this).val());
                                    });

                                    // Sync the Select2 component with Livewire changes
                                    $watch('group_id', value => {
                                        $(groupSelect).val(value).trigger('change.select2');
                                    });
                                })" wire:ignore>
                                    <label for="exampleFormControlInput1" class="form-label">Question Group <span
                                            class="text-danger">*</span></label>
                                    <select class="form-control select2-show-search custom-select" x-ref="group_id"
                                        wire:model="group_id" id="group_id">
                                        <option selected value="">Choose....</option>
                                        @foreach ($questionGroup as $group)
                                        <option value="{{ $group->id }}">{{ $group->title }}</option>
                                        @endforeach
                                    </select>
                                    @error('group_id') <span  style="color: red">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3 col-lg-6">
                                <label for="exampleFormControlInput1" class="form-label">Minimum Percentage <span
                                        class="text-danger">*</span></label>
                                <input type="number" class="form-control shadow-none" id="minimum_percentage"
                                    placeholder="Enter Minimum Percentage" wire:model="minimum_percentage">
                                @error('minimum_percentage') <span class="error" style="color: red">{{ $message
                                    }}</span> @enderror
                            </div>
                            <div class="mb-3 col-lg-6">
                                <div x-data x-init="$nextTick(() => {
                                    let minSelect = $refs.minimum_percentage;
                                    $(minSelect).select2();

                                    // Capture Select2 change and manually sync to Livewire
                                    $(minSelect).on('select2:select', function (e) {
                                        // Directly set the group_id value in Livewire
                                        $wire.set('minimum_percentage', $(this).val());
                                    });

                                    // Sync the Select2 component with Livewire changes
                                    $watch('minimum_percentage', value => {
                                        $(minSelect).val(value).trigger('change.select2');
                                    });
                                })" wire:ignore>
                                <label  for="exampleFormControlInput1" class="form-label">Random Question
                                            <span class="text-danger">*</span></label>
                                    <select class="form-control select2-show-search custom-select"
                                        x-ref="minimum_percentage" wire:model="minimum_percentage"
                                        id="minimum_percentage">
                                        <option value="">Choose....</option>
                                        <option value="yes">Yes</option>
                                        <option value="no">No</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3 col-lg-6">
                                <div x-data x-init="$nextTick(() => {
                                let statusSelect = $refs.status;
                                $(statusSelect).select2();

                                // Capture Select2 change and manually sync to Livewire
                                $(statusSelect).on('select2:select', function (e) {
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
                                    <select class="form-control select2-show-search custom-select" x-ref="status"
                                        wire:model="status" id="status">
                                        <option selected value="">Choose....</option>
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                    </select>
                                    @error('status') <span class="error" style="color: red">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" wire:click="back" class="btn btn-secondary">Close</button>
                            <button type="submit" wire:click="store" class="btn btn-primary shadow-none">{{$button}}
                            </button>
                        </div>
                    </form>
                        @endif
@if($page_type == 'detail')
@else
                    <div id="file-export_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                    <div class="col-lg-7 mb-3">
                        <table id="datatable" style="width:100%">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Quiz Name</th>
                                    <th>Level</th>
                                    <th>Question Group</th>
                                    <th>Minimum Percentage</th>
                                    <th>Random Question</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                        </table>
                    </div>
                    </div>
@endif
                </div>
            </div>
            <!--div-->
        </div>
    </div>
</div>

@push('script')


<script src="{{asset('build/assets/admin/js/custom/user.js')}}"></script>
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
let id = event.detail[0]['userId'];
            $.ajax({
        type: 'post', // POST should be in uppercase
        url: '/quiz/delete',
        data: {
            _token: "{{ csrf_token() }}",
            userId: id,
        },
        success: function(response) {
                Swal.fire(
                    'Deleted!',
                    'User has been deleted.',
                    'success'
                );
            setTimeout(() => {

                    window.location.reload();
                },1000);

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