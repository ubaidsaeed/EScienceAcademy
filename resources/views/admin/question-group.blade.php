<div class="">
    <!-- Row -->
    <div class="page-header d-lg-flex d-block">

        <div class="page-leftheader">
            <h4 class="page-title">Question's</h4>
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
                        <livewire:question-group-table />
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
                                <label for="exampleFormControlInput1" class="form-label">Title</label>
                                <input type="text" class="form-control shadow-none" id="title" placeholder="Enter Title"
                                    wire:model="title">
                                <input type="hidden" wire:model="id">
                                @error('title') <span class="error" style="color: red">{{ $message }}</span> @enderror
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

            </div>
        </div>
        <!--div-->
    </div>
</div>
</div>
@push('script')
<script src="{{asset('build/assets/admin/js/custom/question.js')}}"></script>
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
        url: '/question/group/delete',
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