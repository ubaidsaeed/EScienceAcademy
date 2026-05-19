<div class="">

    <!-- Row -->
    <div class="page-header d-lg-flex d-block">

        <div class="page-leftheader">
            <h4 class="page-title">Custom Query's</h4>
        </div>
        <div class="page-rightheader ">
            @if (session('message'))
            <div class="alert alert-success alert-message fade show" role="alert" id="success-alert">
                {{ session('message') }}
            </div>
            @endif

            @if (session('error'))
            <div class="alert alert-danger alert-message fade show" role="alert" id="error-alert">
                {{ session('error') }}
            </div>
            @endif
            <div class=" btn-list">

            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <!--div-->
            <div class="card">
                <div class="card-body">
                    @if($page_type == 'View')
                    <div id="file-export_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                        <livewire:custom-query-table/>
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
                                <input type="text" class="form-control shadow-none" id="name" readonly placeholder="Enter Title"
                                    wire:model="name">
                                <input type="hidden" wire:model="id">
                                @error('name') <span class="error" style="color: red">{{ $message }}</span> @enderror
                            </div>
                             <div class="mb-3 col-lg-6">
                                <label for="exampleFormControlInput1" class="form-label">Email</label>
                                <input type="text" class="form-control shadow-none" id="name" readonly placeholder="Enter "
                                    wire:model="email">
                                <input type="hidden" wire:model="id">
                                @error('email') <span class="error" style="color: red">{{ $message }}</span> @enderror
                            </div>
                             <div class="mb-3 col-lg-6">
                                <label for="exampleFormControlInput1" class="form-label">Board</label>
                                <input type="text" class="form-control shadow-none" id="name" readonly placeholder="Enter"
                                    wire:model="board">
                                @error('board') <span class="error" style="color: red">{{ $message }}</span> @enderror
                            </div>
                             <div class="mb-3 col-lg-6">
                                <label for="exampleFormControlInput1" class="form-label">Level</label>
                                <input type="text" class="form-control shadow-none" id="name"readonly  placeholder="Enter Title"
                                    wire:model="level">
                                @error('leve') <span class="error" style="color: red">{{ $message }}</span> @enderror
                            </div>
                             <div class="mb-3 col-lg-6">
                                <label for="exampleFormControlInput1" class="form-label">Subjects</label>
                                <input type="text" class="form-control shadow-none" id="name" readonly  placeholder="Enter "
                                    wire:model="subject">
                                @error('subject') <span class="error" style="color: red">{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-3 col-lg-12">
                               <label for="exampleFormControlInput1" class="form-label">Content</label>
                               <textarea class="form-control" id="myeditorinstances" x-ref="myeditorinstances" readonly rows="3" wire:model="message"></textarea>
                            </div>

                            </div>
                            <div class="modal-footer">
                                <button type="button" wire:click="back" class="btn btn-secondary">Close</button>
                                <!--<button type="button" wire:click="store" class="btn btn-primary shadow-none">{{$button}}-->
                                </button>
                            </div>
                    </form>
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
<script src="{{asset('build/assets/admin/js/custom/message.js')}}"></script>
@endpush
