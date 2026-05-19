<div class="">

    <!-- Row -->
    <div class="page-header d-lg-flex d-block">

        <div class="page-leftheader">
            <h4 class="page-title">Grade's</h4>
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
                        <livewire:grade-table />
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
                                <label for="exampleFormControlInput1" class="form-label">Grade Name <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control shadow-none" id="name" placeholder="Enter Grade"
                                    wire:model="name">
                                <input type="hidden" wire:model="id">
                                @error('name') <span class="error" style="color: red">{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-3 col-lg-6">
                                <label for="exampleFormControlInput1" class="form-label">Grade Point <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control shadow-none" id="name" placeholder="Enter Point"
                                    wire:model="point">
                                @error('point') <span class="error" style="color: red">{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-3 col-lg-6">
                                <label for="exampleFormControlInput1" class="form-label">Mark From <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control shadow-none" id="mark_from"
                                    placeholder="Enter Mark From" wire:model="mark_from">
                                @error('mark_from') <span class="error" style="color: red">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3 col-lg-6">
                                <label for="exampleFormControlInput1" class="form-label">Mark To <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control shadow-none" id="mark_to"
                                    placeholder="Enter Mark To" wire:model="mark_to">
                                @error('mark_to') <span class="error" style="color: red">{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-3 col-lg-12">
                                <label for="exampleFormControlInput1" class="form-label">Note</label>
                                <textarea class="form-control" id="note" rows="3" wire:model="note"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" wire:click="back" class="btn btn-secondary">Close</button>
                            <button type="button" wire:click="store" class="btn btn-primary shadow-none">{{$button}}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

@push('script')
<script src="{{asset('build/assets/admin/js/custom/message.js')}}"></script>
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
        url: '/grade/gradeDelete',
        data: {
            _token: "{{ csrf_token() }}",
            id: id,
        },
        success: function(response) {
                Swal.fire(
                    'Deleted!',
                    'Level has been deleted.',
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