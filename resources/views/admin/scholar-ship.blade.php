<div class="">

    <!-- Row -->
    <div class="page-header d-lg-flex d-block">

        <div class="page-leftheader">
            <h4 class="page-title">Scholarship's</h4>
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
                            <livewire:scholar-ship-table />
                        </div>
                </div>
            </div>
        @else
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="mb-3 col-lg-6">
                            <label for="exampleFormControlInput1" class="form-label">Full Name</label>
                            <input type="text" class="form-control shadow-none" readonly id="name"
                                wire:model="name">
                        </div>
                        <div class="mb-3 col-lg-6">
                            <label for="exampleFormControlInput1" class="form-label">Father Name</label>
                            <input type="text" class="form-control shadow-none" readonly id="father_name"
                                wire:model="father_name">
                        </div>
                        <div class="mb-3 col-lg-6">
                            <label for="exampleFormControlInput1" class="form-label">Contact No</label>
                            <input type="text" class="form-control shadow-none" readonly id="contact_no"
                                wire:model="contact_no">
                        </div>
                        <div class="mb-3 col-lg-6">
                            <label for="exampleFormControlInput1" class="form-label">Email</label>
                            <input type="text" class="form-control shadow-none" readonly id="email"
                                wire:model="email">
                        </div>
                        <div class="mb-3 col-lg-2">
                            <label for="exampleFormControlInput1" class="form-label">Parents Id Card </label>
                            <a href="{{ asset('storage/app/' . $parents_id_card) }}" target="_blank"
                                class="btn btn-primary shadow-none">Download</a>
                        </div>
                        <div class="mb-3 col-lg-2">
                            <label for="exampleFormControlInput1" class="form-label">Electricity Bills </label>
                            <a href="{{ asset('storage/app/' . $electricity_bills) }}"
                                target="_blank" class="btn btn-primary shadow-none">Download</a>
                        </div>
                        <div class="mb-3 col-lg-2">
                            <label for="exampleFormControlInput1" class="form-label">Academic Transcripts </label>
                            <a href="{{ asset('storage/app/' . $academic_transcripts) }}"
                                target="_blank" class="btn btn-primary shadow-none">Download</a>
                        </div>
                        <div class="mb-3 col-lg-4">
                            <label for="exampleFormControlInput1" class="form-label">Parental Bank Certificate </label>
                            {{-- download --}}
                            <a href="{{ asset('storage/app/' . $parental_bank_certificate) }}"
                                target="_blank" class="btn btn-primary shadow-none">Download</a>
                        </div>

                        {{-- for achievment --}}
                        @foreach($achievements as $key => $value)
                        <div class="mb-3 col-lg-4">
                            <label for="exampleFormControlInput1" class="form-label">Achievements ({{$key + 1}})</label>
                            {{-- download --}}
                            <a href="{{ asset('storage/app/' . $value->achievement) }}"
                                target="_blank" class="btn btn-primary shadow-none">Download</a>
                        </div>
                        @endforeach
                    </div>
                    {{-- asset(Storage::url("app/board/$model->image_url")) --}}
                    <div class="mb-3 col-lg-12">
                        <label for="exampleFormControlInput1" class="form-label">Message</label>
                        <textarea class="form-control" id="myeditorinstances" x-ref="myeditorinstances" readonly rows="3"
                            wire:model="message"></textarea>
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
