<div class="">

    <!-- Row -->
    <div class="page-header d-lg-flex d-block">

        <div class="page-leftheader">
            <h4 class="page-title">Subject's</h4>
        </div>
        <div class="page-rightheader ">

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
                        <livewire:s-ubject-table />
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
                                <label for="exampleFormControlInput1" class="form-label">Name</label>
                                <input type="text" class="form-control shadow-none" id="name" placeholder="Enter Title"
                                    wire:model="name">
                                <input type="hidden" wire:model="id">
                                @error('name') <span class="error" style="color: red">{{ $message }}</span> @enderror
                            </div>
                            
                            <div class="mb-3 col-lg-6">

                                <div x-data x-init="$nextTick(() => {
                                    let boardSelect = $refs.board;
                                    $(boardSelect).select2();

                                    // Capture Select2 change and manually sync to Livewire
                                    $(boardSelect).on('select2:select', function (e) {
                                        // Directly set the group_id value in Livewire
                                        $wire.set('board', $(this).val());
                                    });

                                    // Sync the Select2 component with Livewire changes
                                    $watch('board', value => {
                                        $(boardSelect).val(value).trigger('change.select2');
                                    });
                                })" wire:ignore>
                                    <label for="exampleFormControlInput1" class="form-label">Board</label>
                                    <select class="form-control select2-show-search custom-select" x-ref="board"
                                        wire:model="board" id="board">
                                        <option >Choose....</option>
                                        @foreach($boards as $board)
                                        @if($board->parent_id == null)
                                        <option value="{{$board->id}}">{{$board->name}}</option>
                                        @endif
                                        @endforeach
                                    </select>
                                    @error('board') <span class="error" style="color: red">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3 col-lg-6">

                                <div x-data x-init="$nextTick(() => {
                                    let levelSelect = $refs.level;
                                    $(levelSelect).select2();

                                    // Capture Select2 change and manually sync to Livewire
                                    $(levelSelect).on('select2:select', function (e) {
                                        // Directly set the group_id value in Livewire
                                        $wire.set('level', $(this).val());
                                    });

                                    // Sync the Select2 component with Livewire changes
                                    $watch('level', value => {
                                        $(levelSelect).val(value).trigger('change.select2');
                                    });
                                })" wire:ignore>
                                    <label for="exampleFormControlInput1" class="form-label">Levels</label>
                                    <select class="form-control select2-show-search custom-select" x-ref="level"
                                        wire:model="level" id="level">
                                        <option selected value="" >Choose....</option>
                                        @foreach($leveldata as $levels)
                                        <option value="{{$levels->id}}">{{$levels->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('level') <span class="error" style="color: red">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-3 col-lg-6">
                                <label for="exampleFormControlInput1" class="form-label">Image</label>
                                <input type="file" class="form-control shadow-none" id="image_url"
                                    placeholder="Enter Image" wire:model="image_url">
                                @error('image_url') <span class="error" style="color: red">{{ $message }}</span>
                                @enderror
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
                                    <label for="exampleFormControlInput1" class="form-label">Status</label>
                                    <select class="form-control select2-show-search custom-select" x-ref="status"
                                        wire:model="status" id="status">
                                        <option selected value="" disabled="disabled">Choose....</option>
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                    </select>
                                    @error('status') <span class="error" style="color: red">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                </div>
                <div class="modal-footer">
                    <button type="button" wire:click="back" class="btn btn-secondary">Close</button>
                    <button type="button" wire:click="store" class="btn btn-primary shadow-none">{{$button}}
                    </button>
                </div>
                </form>
                @endif

            </div>
        </div>
        <!--div-->
    </div>
</div>
</div>

@push('script')
<script src="{{asset('build/assets/admin/js/custom/subject.js')}}"></script>
<script>
    document.addEventListener('deleteConfirm', function (event) {
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
                url: '/subjects/subjectDelete',
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id,
                },
                success: function (response) {
                    Swal.fire(
                        'Deleted!',
                        'User has been deleted.',
                        'success'
                    );
                    setTimeout(() => {

                        window.location.reload();
                    }, 1000);

                },
                error: function (xhr, status, error) {
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