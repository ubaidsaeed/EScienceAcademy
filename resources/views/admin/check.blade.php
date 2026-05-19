<div class="">

    <!-- Row -->
    <div class="page-header d-lg-flex d-block">

        <div class="page-leftheader">
            <h4 class="page-title">Check Paper's</h4>
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
                {{-- <button type="button" class="btn btn-primary" wire:click="Create">
                    Create
                </button> --}}
                @endif
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
                        <livewire:check-paper/>
                    </div>
                </div>
            </div>
            @else

            <div class="card">
                <div class="card-body">
                        <form wire:submit.prevent="store" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="exam_name" class="form-label">Exam Name</label>
                                        <input type="text" class="form-control shadow-none" readonly id="exam_name" value="{{$paper->exam_name}}" wire:model.fill="exam_name" placeholder="Enter Name">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="user_name" class="form-label">User Name</label>
                                        <input type="text" class="form-control shadow-none" readonly id="user_name" value="{{$paper->user_name}}" wire:model.fill="user_name" placeholder="Enter Name">
                                    </div>
                                </div>
                                <input type="hidden" class="form-control shadow-none" readonly id="id_paper" value="{{$paper->id}}" wire:model.fill="id_paper">
                        
                                @foreach ($MCQs as $MCQs_detail)
                                    <div class="col-md-8">
                                        <div class="mb-3">
                                            <label for="question_{{$MCQs_detail->id}}" class="form-label">
                                                Question ({{ \Illuminate\Support\Str::limit($MCQs_detail->question_type, 4, '') }})
                                            </label>
                                            <input type="hidden" class="form-control shadow-none" readonly id="paper_detail_id" value="{{$MCQs_detail->id}}" wire:model.fill="section.{{$MCQs_detail->id}}.paper_detail_id">
                                            {!! $MCQs_detail->question !!}
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <label for="answer_{{$MCQs_detail->id}}" class="form-label">Answer</label>
                                        <ul class="mb-3">
                                            @foreach($MCQs_detail->options as $option)
                                                <li>
                                                    <input class="form-check-input" disabled @if($option == $MCQs_detail->correct_answer) checked @endif type="radio" wire:model="selectedOptions.{{$MCQs_detail->id}}" value="{{$option}}" id="flexRadioDefault{{$MCQs_detail->id}}">
                                                    {{ $option }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="number" class="form-control shadow-none" readonly wire:model.fill="section.{{$MCQs_detail->id}}.total_mark" value="{{$MCQs_detail->total_mark}}">
                                    </div>
                                    <div class="col-md-2">
                                        <input type="number" class="form-control shadow-none" wire:model="section.{{$MCQs_detail->id}}.marks">
                                    </div>
                                @endforeach
                        
                                @foreach ($paperRecord as $paper_detail)
                                    <div class="col-md-8">
                                        <div class="mb-3">
                                            <label for="question_{{$paper_detail->id}}" class="form-label">
                                                Question ({{ \Illuminate\Support\Str::limit($paper_detail->question_type, 4, '') }})
                                            </label>
                                            <input type="hidden" class="form-control shadow-none" readonly id="paper_detail_id" value="{{$paper_detail->id}}" wire:model.fill="section.{{$paper_detail->id}}.paper_detail_id">
                                            {!! $paper_detail->question !!}
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <label for="answer_{{$paper_detail->id}}" class="form-label">Answer</label>
                                        {!! $paper_detail->answer !!}
                                    </div>
                                    <div class="col-md-2">
                                        <input type="number" class="form-control shadow-none" readonly wire:model.fill="sections.{{$paper_detail->id}}.total_mark" value="{{$paper_detail->total_mark}}">
                                    </div>
                                    <div class="col-md-2">
                                        {{-- <input type="number" class="form-control shadow-none" wire:model="marks_{{$paper_detail->id}}"> --}}
                                        <input type="text" class="form-control shadow-none" wire:model="section.{{$paper_detail->id}}.marks">
                                    </div>
                                @endforeach
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="user_name" class="form-label">Total Marks</label>
                                        <input type="text" class="form-control shadow-none" id="total_marks"  wire:model="total_marks" placeholder="Enter Marks">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                        <div x-data x-init="$nextTick(() => {
                                            let groupSelect = $refs.grade_id;
                                            $(groupSelect).select2();
                                        
                                            // Capture Select2 change and manually sync to Livewire
                                            $(groupSelect).on('select2:select', function(e) {
                                                // Directly set the grade_id value in Livewire
                                                $wire.set('grade_id', $(this).val());
                                            });
                                        
                                            // Sync the Select2 component with Livewire changes
                                            $watch('grade_id', value => {
                                                $(groupSelect).val(value).trigger('change.select2');
                                            });
                                        })" wire:ignore>
        
                                            <label for="grade_id" class="form-label">Grade <span
                                                    class="text-danger">*</span></label>
        
                                            <select class="form-control select2-show-search custom-select" wire:model="grade_id"
                                                x-ref="grade_id" id="grade_id">
                                                <option value="">Choose...</option>
                                                @foreach ($grade as $grades)
                                                    <option value="{{ $grades->name }}">{{ $grades->name }}</option>
                                                @endforeach
                                            </select> 
                                    
                                </div>
                            </div>
                                <div class="mb-3 col-lg-4">
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
                                        <select class="form-control select2-show-search  custom-select" wire:model="status"
                                            id="status" x-ref="status">
                                            <option selected value="" disabled="disabled">Choose....</option>
                                            <option value="verify">Verify</option>
                                            <option value="unverify">Unverify</option>
                                        </select>
                                        @error('status') <span class="error" style="color: red">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        
                            <div class="modal-footer">
                                <button type="button" wire:click="back" class="btn btn-secondary">Close</button>
                                <button type="submit" class="btn btn-primary shadow-none">{{$button}}</button>
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
   
</script>
@endpush