<div class="">

    <!-- Row -->
    <div class="page-header d-lg-flex d-block">

        <div class="page-leftheader">
            <h4 class="page-title">Take Exam's</h4>
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
    @elseif($page_type == 'View')
    <div class="row">
        <div class="col-12">
            <!--div-->
            <div class="card">
                <div class="card-body">
                  
                    <div id="file-export_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                        <livewire:take-exam-table />
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="row">
        <form wire:submit.prevent="store" method="post" enctype="multipart/form-data">
            @csrf
            <div class="col-12">

                {{-- Multiple Choice Questions --}}
                @if(count($selectedMCQS) > 0)
                <h3 class="text-center">Multiple Choice</h3>
                <h6 class="text-center">Total Marks ({{$exam_schedules_data->quiz_limit}})</h6>

                @foreach($selectedMCQS as $key => $value)
                <div class="col-md-12">
                    <div class="card overflow-hidden">
                        <div class="card-body">
                            @php
                            $finalContent = preg_replace('/\s+/', ' ',
                            trim(html_entity_decode(strip_tags($value['question_title']))));
                            @endphp
                            <b>{{ $finalContent }}</b>
                            <input type="hidden" wire:model.defer="question_ids.[{{ $value['question_id'] }}]"
                                value="{{ $value['question_id'] }}" />

                            @foreach($value['options'] as $optionKey => $optionValue)
                            <div class="form-check">
                                <input class="form-check-input shadow-none" type="checkbox"
                                    wire:model.defer="selectedOptions.{{ $value['question_id'] }}.{{ $optionValue['option'] }}"
                                    value="{{ $optionValue['option'] }}"
                                    id="flexCheck{{ $value['question_id'] }}{{ $optionKey }}">
                                <label class="form-check-label"
                                    for="flexCheck{{ $value['question_id'] }}{{ $optionKey }}">
                                    {{ $optionValue['option'] }}
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endforeach
                @endif

                {{-- Short Answer Questions --}}
                @if(count($questions_short) > 0)
                <h3 class="text-center">Short Answer</h3>
                <h6 class="text-center">Total Marks ({{$exam_schedules_data->s_q_limit}})</h6>

                @foreach($questions_short as $key => $question)
                <div class="col-md-12">
                    <div class="card overflow-hidden">
                        <div class="card-header border-bottom-0">

                            {{-- <h4 class="card-title">Q{{ $key + 1 }}. {!! $question->question !!}</h4> --}}
                            <h4 class="card-title">Q{{ $key + 1 }}. {!! $question->question !!}</h4>

                            <input type="hidden" wire:model.defer="question_ids.{{ $question->id }}"
                                value="{{ $question->id }}" />
                        </div>
                        <div class="card-body">
                            <div wire:ignore>
                                <label for="contentShort{{ $question->id }}" class="form-label">Answer<span
                                        class="text-danger">*</span></label>
                                <textarea class="form-control" id="contentShort{{ $question->id }}"
                                    wire:model.defer="shortanswers.{{ $question->id }}" x-data x-init="
                                        tinymce.init({
                                            selector: '#contentShort{{ $question->id }}',
                                            height: 300,
                                            plugins: 'image code table link media codesample',
                                            toolbar: 'undo redo | styleselect | bold italic | image | alignleft aligncenter alignright alignjustify | outdent indent | media | table',
                                            setup: function(editor) {
                                                editor.on('change', function () {
                                                    @this.set('shortanswers.{{ $question->id }}', editor.getContent());
                                                });
                                            }
                                        });
                                      "></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
                @endif

                {{-- Long Answer Questions --}}
                @if(count($questions_long) > 0)
                <h3 class="text-center">Long Answer</h3>
                <h6 class="text-center">Total Marks ({{$exam_schedules_data->l_q_limit}})</h6>

                @foreach($questions_long as $key => $question_value)
                <div class="col-md-12">
                    <div class="card overflow-hidden">
                        <div class="card-header border-bottom-0">
                            <h4 class="card-title">Q{{ $key + 1 }}. {!! $question_value->question !!}</h4>
                            <input type="hidden" wire:model.defer="question_ids.{{ $question_value->id }}"
                                value="{{ $question_value->id }}" />
                        </div>
                        <div class="card-body">
                            <div wire:ignore>
                                <label for="contentLong{{ $question_value->id }}" class="form-label">Answer<span
                                        class="text-danger">*</span></label>
                                <textarea class="form-control" id="contentLong{{ $question_value->id }}"
                                    wire:model.defer="longanswers.{{ $question_value->id }}" x-data x-init="
                                        tinymce.init({
                                            selector: '#contentLong{{ $question_value->id }}',
                                            height: 300,
                                            plugins: 'image code table link media codesample',
                                            toolbar: 'undo redo | styleselect | bold italic | image | alignleft aligncenter alignright alignjustify | outdent indent | media | table',
                                            setup: function(editor) {
                                                editor.on('change', function () {
                                                    @this.set('longanswers.{{ $question_value->id }}', editor.getContent());
                                                });
                                            }
                                        });
                                      "></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
                @endif
            </div>

            {{-- Submit/Cancel Buttons --}}
            <div class="col-md-12">
                <div class="card overflow-hidden">
                    <div class="card-body text-center">
                        <button type="button" class="btn btn-secondary text-white px-6"
                            wire:click="cancel">Cancel</button>
                        <button type="submit" class="btn btn-primary text-white px-6">Submit</button>
                    </div>
                </div>
            </div>
        </form>

    </div>
</div>
@endif

@push('script')
<script src="{{ asset('build/assets/admin/js/custom/message.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        window.addEventListener('tinymce-remove', () => {
            tinymce.remove(); // Remove all TinyMCE instances
        });
    });
    
      // block right click
        document.addEventListener('contextmenu', function(e) {
    e.preventDefault();  // Prevents the right-click menu from appearing
});
document.addEventListener('keydown', function(e) {
if (e.keyCode === 123) {  // F12
e.preventDefault();    // Prevent F12
}
if (e.ctrlKey && e.shiftKey && (e.keyCode === 73 || e.keyCode === 74)) {  // Ctrl+Shift+I or Ctrl+Shift+J
e.preventDefault();    // Prevent opening DevTools
}
if (e.ctrlKey && e.keyCode === 85) {  // Ctrl+U
e.preventDefault();    // Prevent viewing page source
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