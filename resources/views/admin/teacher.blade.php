<div class="">
    <!-- Row -->
    <div class="page-header d-lg-flex d-block">

        <div class="page-leftheader">
            <h4 class="page-title">Teacher's</h4>
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
                        <livewire:teacher-table />
                    </div>
                </div>
            </div>
            @else

            <div class="card">
                <div class="card-body">
                    <form wire:submit="{{$form_url}}" method="post" enctype="multipart/form-data">
                        @csrf

                        <div class="row">

                        <div class="col-lg-12 heading my-4">
                            <h5 class="mt-3">Basic Information:</h5>
                        </div>
                            <div class="mb-3 col-lg-4">
                                <label for="exampleFormControlInput1" class="form-label">Name<span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control shadow-none" id="name" placeholder="Enter Name"
                                    wire:model="name">
                                <input type="hidden" wire:model="id">
                                @error('name') <span class="error" style="color: red">{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-3 col-lg-4">
                                <label for="exampleFormControlInput1" class="form-label">National ID <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control shadow-none" id="national_id"
                                    placeholder="Enter National ID" wire:model="national_id">
                                @error('national_id') <span class="error" style="color: red">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3 col-lg-4">
                                <label for="exampleFormControlInput1" class="form-label">Phone No <span
                                        class="text-danger">*</span></label>
                                <input type="number" class="form-control shadow-none" id="phone_no"
                                    placeholder="Enter Phone No" wire:model="phone_no">
                                @error('phone_no') <span class="error" style="color: red">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3 col-lg-4">
                                <div x-data x-init="$nextTick(() => {
                                    let statusSelect = $refs.gender;
                                    $(statusSelect).select2();

                                    // Capture Select2 change and manually sync to Livewire
                                    $(statusSelect).on('select2:select', function (e) {
                                        // Directly set the group_id value in Livewire
                                        $wire.set('gender', $(this).val());
                                    });

                                    // Sync the Select2 component with Livewire changes
                                    $watch('gender', value => {
                                        $(statusSelect).val(value).trigger('change.select2');
                                    });
                                })" wire:ignore>
                                    <label for="exampleFormControlInput1" class="form-label">Gender <span
                                            class="text-danger">*</span></label>
                                    <select class="form-control select2-show-search custom-select" required
                                        x-ref="gender" wire:model="gender" id="gender">
                                        <option selected value="" disabled="disabled">Choose....</option>
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                    </select>
                                </div>

                                @error('gender') <span class="error" style="color: red">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="exampleFormControlInput1" class="form-label">Religion</label>
                                <input type="text" class="form-control shadow-none" id="religion"
                                    placeholder="Enter Religion" wire:model="religion">
                                @error('religion') <span class="error" style="color: red">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="exampleFormControlInput1" class="form-label">Birth Date <span
                                        class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <span class="feather feather-clock"></span>
                                                </div>
                                            </div>
                                <input type="date" class="form-control shadow-none" id="birth_date"
                                    placeholder="Enter Birth Date" wire:model="birth_date">
                                        </div>

                            @error('birth_date') <span class="error" style="color: red">{{ $message }}</span>
                            @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="exampleFormControlInput1" class="form-label">Present Address</label>
                                <textarea class="form-control" id="myeditorinstance"  rows="3"
                                    wire:model="present_address"></textarea>
                            </div>
                            @error('present_address') <span class="error" style="color: red">{{ $message }}</span>
                            @enderror
                        {{-- </div> --}}

                        <div class="col-md-6">
                            <label for="exampleFormControlInput1" class="form-label">Permanent Address</label>
                            <textarea class="form-control" id="permanent_address" rows="3"
                                wire:model="permanent_address"></textarea>
                        </div>
                        @error('permanent_address') <span class="error" style="color: red">{{ $message }}</span>
                        @enderror
                {{-- </div> --}}
                <div class="col-lg-12 heading my-4 " >
                    <h5 class="mt-3">Academic Information:</h5>
                </div>
                <div class="mb-3 col-lg-4">
                    <label for="exampleFormControlInput1" class="form-label">Email</label>
                    <input type="email" class="form-control shadow-none" id="email" placeholder="Enter Email"
                        wire:model="email" autocomplete="email">
                    @error('email') <span class="error" style="color: red">{{ $message }}</span> @enderror
                </div>

                <div class="mb-3 col-lg-4">
                    <label for="exampleFormControlInput1" class="form-label">Username <span
                            class="text-danger">*</span></label>
                    <input type="text" class="form-control shadow-none" id="username" placeholder="Enter Username"
                        wire:model="username" autocomplete="username">
                    @error('username') <span class="error" style="color: red">{{ $message }}</span> @enderror
                </div>
                <div class="mb-3 col-lg-4">
                    <label for="exampleFormControlInput1" class="form-label">Password <span
                            class="text-danger">*</span></label>
                    <input type="password" class="form-control shadow-none" id="password" placeholder="Enter Password"
                        wire:model="password">
                    @error('password') <span class="error" style="color: red">{{ $message }}</span> @enderror
                </div>

                <div class="mb-3 col-lg-4">
                    <div x-data x-init="$nextTick(() => {
                                    let roleSelect = $refs.role;
                                    $(roleSelect).select2();

                                    // Capture Select2 change and manually sync to Livewire
                                    $(roleSelect).on('select2:select', function (e) {
                                        // Directly set the group_id value in Livewire
                                        $wire.set('role', $(this).val());
                                    });

                                    // Sync the Select2 component with Livewire changes
                                    $watch('role', value => {
                                        $(roleSelect).val(value).trigger('change.select2');
                                    });
                                })" wire:ignore>
                        <label for="exampleFormControlInput1" class="form-label">Role <span
                                class="text-danger">*</span></label>
                        <select class="form-control select2-show-search custom-select" required x-ref="role"
                            wire:model="role" id="role">
                            <option selected value="" disabled="disabled">Choose....</option>
                            @foreach($roleRecords as $role)
                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('role') <span class="error" style="color: red">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mb-3 col-lg-4">
                    <label for="exampleFormControlInput1" class="form-label">Joining Date <span
                            class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <span class="feather feather-clock"></span>
                                    </div>
                                </div>
                    <input type="date" class="form-control shadow-none" id="joining_date"
                        placeholder="Enter Joining Date" wire:model="joining_date">
                </div>

                @error('joining_date') <span class="error" style="color: red">{{ $message }}</span> @enderror
                </div>


                <div class="col-lg-12 heading my-4">
                    <h5 class="mt-3">Other Information:</h5>
                </div>

                <div class="mb-3 col-lg-4">
                    <label for="exampleFormControlInput1" class="form-label">Facebook URL</label>
                    <input type="text" class="form-control shadow-none" id="facebook_url"
                        placeholder="Enter Facebook URL" wire:model="facebook_url">
                    @error('facebook_url') <span class="error" style="color: red">{{ $message }}</span> @enderror
                </div>

                <div class="mb-3 col-lg-4">
                    <label for="exampleFormControlInput1" class="form-label">Linkedin URL </label>
                    <input type="text" class="form-control shadow-none" id="linkedin_url"
                        placeholder="Enter Linkedin URL" wire:model="linkedin_url">
                    @error('linkedin_url') <span class="error" style="color: red">{{ $message }}</span> @enderror
                </div>

                <div class="mb-3 col-lg-4">
                    <label for="exampleFormControlInput1" class="form-label">Twitter URL </label>
                    <input type="text" class="form-control shadow-none" id="twitter_url"
                        placeholder="Enter Twitter URL" wire:model="twitter_url">
                    @error('twitter_url') <span class="error" style="color: red">{{ $message }}</span> @enderror
                </div>

                <div class="mb-3 col-lg-4">
                    <label for="exampleFormControlInput1" class="form-label">Instagram URL </label>
                    <input type="text" class="form-control shadow-none" id="instagram_url"
                        placeholder="Enter Instagram URL" wire:model="instagram_url">
                    @error('instagram_url') <span class="error" style="color: red">{{ $message }}</span> @enderror
                </div>

                <div class="mb-3 col-lg-4">
                    <label for="exampleFormControlInput1" class="form-label">Youtube URL</label>
                    <input type="text" class="form-control shadow-none" id="youtube_url"
                        placeholder="Enter Youtube URL" wire:model="youtube_url">
                    @error('youtube_url') <span class="error" style="color: red">{{ $message }}</span> @enderror
                </div>

                <div class="mb-3 col-lg-4">
                    <label for="exampleFormControlInput1" class="form-label">Pinterest URL</label>
                    <input type="text" class="form-control shadow-none" id="pinterest_url"
                        placeholder="Enter Pinterest URL" wire:model="pinterest_url">
                    @error('pinterest_url') <span class="error" style="color: red">{{ $message }}</span> @enderror
                </div>

                <div class="mb-3 col-lg-12">
                    <label for="exampleFormControlInput1" class="form-label">Other Info</label>
                    <textarea class="form-control" id="other_info" x-ref="other_info" rows="3"
                        wire:model="other_info"></textarea>

                </div>
            </div>
        </div>
        {{--
    </div> --}}
    <div class="modal-footer">
        <button type="button" wire:click="back" class="btn btn-secondary">Close</button>
        <button type="button" wire:click="store" class="btn btn-primary shadow-none">{{$button}}
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
        url: '/teacher/teacherDelete',
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
                },1000);

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