
<ul class="nav nav-tabs nav-pills" id="myTab" role="tablist">
    @foreach ($nvqs as $index => $category)
        <li class="nav-item" role="presentation">
            <button class="nav-link {{ $loop->first ? 'active' : '' }}" 
                id="tab_{{ $index + 1 }}" 
                data-bs-toggle="tab" 
                data-bs-target="#tabs_{{ $index + 1 }}" 
                type="button" 
                role="tab" 
                aria-controls="tabs_{{ $index + 1 }}" 
                aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                {{ $category->name }}
            </button>
        </li>
    @endforeach
</ul>
<!-- End Tab Nav -->

<!-- Start Tab Content -->
<div class="tab-content tab-content-info" id="myTabContent">
    @foreach ($nvqs as $index => $category)
        <div class="tab-pane fade {{ $loop->first ? 'active show' : '' }}" 
             id="tabs_{{ $index + 1 }}" 
             role="tabpanel" 
             aria-labelledby="tab_{{ $index + 1 }}">
             
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover text-center align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>S.No</th>
                            <th>Qualification</th>
                            <th>Level</th>
                            <th>Download</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($category->nvqs as $key => $nvq)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $nvq->name }}</td>
                                <td>{{ $nvq->level }}</td>
                                <td>
                                    <div style="display: inline-grid;">
                                        @foreach($nvq->nvqsList as $nvqsList)
                                                   
                                                    @if($nvqsList->file_type == 'Competency Standards' && $nvqsList->file !='')
                                                    <a href="{{ asset($nvqsList->file) }}" target="_blank" class="btn btn-primary"> <i class="bi bi-download"></i> {{$nvqsList->file_type}}
                                                    </a>
                                                    @elseif($nvqsList->file_type == 'Curriculum' && $nvqsList->file !='')
                                                    <a href="{{ asset($nvqsList->file) }}" target="_blank" class="btn btn-warning "> <i class="bi bi-download"></i>{{$nvqsList->file_type}}
                                                    </a>
                                                     @elseif($nvqsList->file_type == 'Assessment Package' && $nvqsList->file !='')
                                                    <a href="{{ asset($nvqsList->file) }}" target="_blank" class="btn btn-danger "> <i class="bi bi-download"></i> {{$nvqsList->file_type}}
                                                    </a>
                                                     @elseif($nvqsList->file_type == 'Learners Guide' && $nvqsList->file !='')
                                                    <a href="{{ asset($nvqsList->file) }}" target="_blank" class="btn btn-info"> <i class="bi bi-download"></i> {{$nvqsList->file_type}}
                                                    </a>
                                                     @elseif($nvqsList->file_type == 'Trainers Guide' && $nvqsList->file !='')
                                                    <a href="{{ asset($nvqsList->file) }}" target="_blank" class="btn btn-success "> <i class="bi bi-download"></i> {{$nvqsList->file_type}}
                                                    </a>
                                                    @endif
                                                    @endforeach
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endforeach
</div>
