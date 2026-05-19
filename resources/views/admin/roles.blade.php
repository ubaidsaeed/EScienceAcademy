<div class="">
    <!-- Row -->
    <div class="page-header d-lg-flex d-block">
        <div class="page-leftheader">
            <h4 class="page-title">Role's</h4>
        </div>
        <div class="page-rightheader">
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
                        <livewire:role-table />
                    </div>
                </div>
            </div>
                    @else

            <div class="card">
                <div class="card-body">
                    <form wire:submit.prevent="{{$form_url}}">
                        @csrf
                        <div class="mb-3">
                            {{-- <div class="mb-3"> --}}
                                <label for="exampleFormControlInput1" class="form-label">Name</label>
                                <input type="text" class="form-control" wire:model="name" id="name"  placeholder=" Enter Name">
                                {{--
                            </div> --}}
                            <input type="hidden" wire:model="id">

                            @error('name') <span class="error" style="color: red">{{ $message }}</span> @enderror

                        </div>
                        <meta name="csrf-token" content="{{ csrf_token() }}">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="table-responsive">
                                    <table class="table">

                                        <tr>
                                            <th scope="col"><input class="form-check-input ischecked " type="checkbox"
                                                    value="" id="checkall"> Selecte
                                                All</th>
                                            <th scope="col">Module</th>
                                            <th scope="col">Permission</th>

                                        </tr>
                                        @php
                                        $models = ['dashboard','boards','levels', 'subjects','chapters', 'topics', 'users', 'roles',
                                        'permissions','student dashboard' , 'student course','payment','take exam',
                                        'file manage','teacher','notification','question group','question','quiz',
                                        'exam','exam schedule','grade','subscription','assignment','submission'];

                                        @endphp

                                        @foreach ($models as $model)
                                        <tr>
                                            <td scope="row" class="col-lg-3">
                                                <div class="mb-3">
                                                    <input class="form-check-input   isschecked" type="checkbox"
                                                        data-id="{{ str_replace('', '', $model) }}"
                                                        id="flexCheck{{ ucfirst($model) }}">
                                                </div>
                                            </td>
                                            <td class="col-lg-1">{{ $model }}</td>
                                            <td>
                                                <div class="row">

                                                    @if (in_array('view ' . $model, (array) $dashboard))
                                                    @if ($key = array_search('view ' . $model, $dashboard))
                                                    <div class="col-md-3 ">
                                                        <label class="custom-control custom-checkbox-md mb-0 " for="permission{{ $key }}"
                                                            style="margin-left: 29px">
                                                        <input
                                                            class="form-check-input custom-control-input isschecked isscheck_{{ str_replace(' ', '', $model) }}"
                                                             wire:model.defer="permission" type="checkbox" value="{{ $key }}"
                                                            id="permission{{ $key }}" style="margin-left: 10px">
                                                        <span class="custom-control-label">{{ 'View'}}</span></label>
                                                    </div>
                                                    @endif
                                                    @endif
                                                    @if (in_array('create ' . $model, (array) $dashboard))
                                                    @if ($key = array_search('create ' . $model, $dashboard))
                                                    <div class="col-md-3">

                                                        <label class="custom-control custom-checkbox-md mb-0 " for="permission{{ $key }}">
                                                        <input
                                                            class="form-check-input custom-control-input isschecked isscheck_{{ str_replace(' ', '', $model) }}"
                                                             wire:model.defer="permission" type="checkbox" value="{{ $key }}"
                                                            id="permission{{ $key }}">
                                                            <span  class="custom-control-label">{{
                                                            'Create'
                                                            }}</span></label>
                                                    </div>
                                                    @endif
                                                    @endif
                                                    @if (in_array('edit ' . $model, (array) $dashboard))
                                                    @if ($key = array_search('edit ' . $model, $dashboard))
                                                    <div class="col-md-3">
                                                        <label class="custom-control custom-checkbox-md mb-0 " for="permission{{ $key }}">
                                                        <input
                                                            class="form-check-input custom-control-input  isschecked isscheck_{{ str_replace(' ', '', $model) }}"
                                                             wire:model.defer='permission' type="checkbox" value="{{ $key }}"
                                                            id="permission{{ $key }}">
                                                        <span class="custom-control-label">{{ 'Edit'
                                                            }}</span></label>
                                                    </div>
                                                    @endif
                                                    @endif
                                                    @if (in_array('delete ' . $model, (array) $dashboard))
                                                    @if ($key = array_search('delete ' . $model, $dashboard))
                                                    <div class="col-md-3">
                                                        <label class="custom-control custom-checkbox-md mb-0" for="permission{{ $key }}">
                                                        <input
                                                            class="form-check-input custom-control-input  isschecked isscheck_{{ str_replace(' ', '', $model) }}"
                                                             wire:model.defer="permission" type="checkbox" value="{{ $key }}"
                                                            id="permission{{ $key }}">
                                                        <span class="custom-control-label">{{
                                                            'Delete'
                                                            }}</span></label>
                                                    </div>
                                                    @endif
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach

                                    </table>
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
<script src="{{asset('build/assets/admin/js/custom/role.js')}}"></script>
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
        url: '/roles/roleDelete',
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