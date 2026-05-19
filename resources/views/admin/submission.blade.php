<div class="">

    <!-- Row -->

          <div class="page-header d-lg-flex d-block">

        <div class="page-leftheader">
            <h4 class="page-title">Assignment Submission</h4>
        </div>
        <div class="page-rightheader ">
            <div class=" btn-list">
            </div>
        </div>
    </div>
        @if (isset($main_parent_name['expirePackage']))
            <script>
                const plan_id = {!! json_encode($main_parent_name['plan_id']) !!};
                const level_id = {!! json_encode($main_parent_name['level_id']) !!};
                document.addEventListener('livewire:initialized', () => {
                    Swal.fire({
                        title: 'Package Expired',
                        text: 'Your current package has expired. Please upgrade to continue using services.',
                        icon: 'warning',
                        confirmButtonText: 'Upgrade Now'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const url = `/ReNewPackage/${plan_id}/${level_id}`;
                            window.location.href = url;
                        }
                    });
                });
            </script>

            <div class="row">
                <div class="col-12">
                    <!--div-->
                @elseif ($page_type == 'View')
                    <div class="card">
                        <div class="card-body">
                            <div id="file-export_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                                <livewire:submission-table />
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

                                        <label for="exampleFormControlInput1" class="form-label">Package</label>
                                        <input type="text" readonly wire:model="sub_name" class="form-control"
                                            id="board_id" value="{{ $sub_name }}">
                                        @error('sub_name')
                                            <span class="error" style="color: red">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="mb-3 col-lg-6">

                                        <label for="exampleFormControlInput1" class="form-label">Chapter</label>
                                        <input type="text" readonly wire:mode.setl="chapter_id" class="form-control"
                                            id="chapter_id" value="{{ $chapter_id }}">
                                        @error('chapter_id')
                                            <span class="error" style="color: red">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="mb-3 col-lg-6">
                                        <div x-data x-init="$nextTick(() => {
                                            let assignmentSelect = $refs.assignment_id;
                                            $(assignmentSelect).select2();

                                            // Capture Select2 change and manually sync to Livewire
                                            $(assignmentSelect).on('select2:select', function(e) {
                                                // Directly set the group_id value in Livewire
                                                $wire.set('assignment_id', $(this).val());
                                            });

                                            // Sync the Select2 component with Livewire changes
                                            $watch('assignment_id', value => {
                                                $(assignmentSelect).val(value).trigger('change.select2');
                                            });
                                        })" wire:ignore>
                                            <label for="exampleFormControlInput1" class="form-label">Assignments <span
                                                    class="text-danger">*</span></label>
                                            <select class="form-control select2-show-searchs" required
                                                x-ref="assignment_id" wire:model="assignment_id" id="assignment_id">
                                                <option value="{{ $assignment_id }}" selected>{{ $assigment_title }}
                                                </option>
                                            </select>
                                            @error('assignment_id')
                                                <span class="error" style="color: red">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="mb-3 col-lg-6">
                                        <label for="exampleFormControlInput1" class="form-label">Submission File</label>
                                        <input type="file" class="form-control shadow-none" id="file"
                                            placeholder="Enter file" wire:model="file">
                                        @error('file')
                                            <span class="error" style="color: red">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="mb-3 col-lg-12">
                                        <label for="exampleFormControlInput1" class="form-label">Notes</label>
                                        <textarea class="form-control" id="myeditorinstance" readonly x-ref="myeditorinstance" rows="3"
                                            wire:model="notes"></textarea>
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

                    </div>
        @endif
    </div>
            </div>
    </div>
</div>
{{-- </[object Object]> --}}

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
                            url: '/assignment/submissionDelete',
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

            // block right click
            document.addEventListener('contextmenu', function(e) {
                e.preventDefault(); // Prevents the right-click menu from appearing
            });
            document.addEventListener('keydown', function(e) {
                if (e.keyCode === 123) { // F12
                    e.preventDefault(); // Prevent F12
                }
                if (e.ctrlKey && e.shiftKey && (e.keyCode === 73 || e.keyCode === 74)) { // Ctrl+Shift+I or Ctrl+Shift+J
                    e.preventDefault(); // Prevent opening DevTools
                }
                if (e.ctrlKey && e.keyCode === 85) { // Ctrl+U
                    e.preventDefault(); // Prevent viewing page source
                }
                // Block Ctrl + C (Copy)
                if (e.ctrlKey && (e.key === 'c' || e.key === 'C')) {
                    e.preventDefault();
                }
                // Block Ctrl + S (Save)
                if (e.ctrlKey && (e.key === 's' || e.key === 'S')) {
                    e.preventDefault();
                }
            });
        </script>
    @endpush
