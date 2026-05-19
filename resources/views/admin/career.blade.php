<div class="">

    <!-- Row -->
    <div class="page-header d-lg-flex d-block">

        <div class="page-leftheader">
            <h4 class="page-title">Career's</h4>
        </div>
        <div class="page-rightheader ">
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <!--div-->
            <div class="card">
                <div class="card-body">
                    @if ($page_type == 'view')
                        <div id="file-export_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                            <livewire:career-table />
                        </div>
                </div>
            </div>
        @else
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="mb-3 col-lg-6">
                            <label for="exampleFormControlInput1" class="form-label">Name</label>
                            <input type="text" class="form-control shadow-none" readonly id="name"
                                wire:model="name">
                        </div>
                        <div class="mb-3 col-lg-6">
                            <label for="exampleFormControlInput1" class="form-label">Email</label>
                            <input type="text" class="form-control shadow-none" id="email" readonly
                                wire:model="email">
                        </div>
                        <div class="mb-3 col-lg-6">
                            <label for="exampleFormControlInput1" class="form-label">Position</label>
                            <input type="text" class="form-control shadow-none" id="position" wire:model="position"
                                readonly>
                        </div>
                        <div class="mb-3 col-lg-6">
                            <label for="exampleFormControlInput1" class="form-label">Contact No</label>
                            <input type="text" class="form-control shadow-none" id="contact_no" readonly
                                wire:model="contact">
                        </div>
                        <div class="mb-3 col-lg-6">
                            <label for="exampleFormControlInput1" class="form-label">Resume</label>
                            {{-- download resume  --}}
                            <a href="{{ asset('storage/app/' . $image_url) }}" target="_blank"
                                class="btn btn-primary shadow-none">Download Resume</a>
                        </div>
                    </div>
                    {{-- asset(Storage::url("app/board/$model->image_url")) --}}
                    <div class="mb-3 col-lg-12">
                        <label for="exampleFormControlInput1" class="form-label">Cover Letter</label>
                        <textarea class="form-control" id="myeditorinstances" x-ref="myeditorinstances" readonly rows="3"
                            wire:model="cover_letter"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" wire:click="back" class="btn btn-secondary">Close</button>
                    {{-- <button type="button" wire:click="store" class="btn btn-primary shadow-none">{{$button}}
                                </button> --}}
                </div>
            </div>
        </div>
        @endif

        {{--
        </div>
    </div> --}}

        <!--div-->
    </div>
</div>
</div>

@push('script')
    <script src="{{ asset('build/assets/admin/js/custom/message.js') }}"></script>
@endpush
