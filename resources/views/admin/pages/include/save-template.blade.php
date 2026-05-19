   {{-- Page Section --}}
   <div class="card mt-1">
    <div class="card-header">
        <strong> {{ __('Page Section') }} </strong>
    </div>
    <div class="card-body">
        <span id="__section-container">
            @if (isset($sections) && count($sections))
                @foreach ($sections as $section)
                <?php
                        $uniqId = uniqid();
                        ?>
                    <div class="border my-4 px-3" id="dup-{{ $uniqId }}">
                        @switch($section->content_type)
                            @case(1)

                                <div class="row my-4" id="uniq-{{ $uniqId }}">
                                    <div class="col-md-10 col-sm-12 col-xs-12" c>
                                        <div id="bgid-{{ $section->id }}">
                                        <div class="form-group">
                                            <textarea class="form-control" id="editor-{{$uniqId}}" initTinyMCE(`editor-${uniqId}`);  name="sections[{{ $section->id }}][page_data]" rows="3" data-img="${uploadedImg}" data-id="{{$uniqId}}">{{{ $section->page_data }}}</textarea>

                                         {{-- <textarea data-id="{{ $section->id }}" name="sections[{{ $section->id }}][page_data]" rows="5" class="custom-multiple-editor">{{{ $section->page_data }}}</textarea> --}}
                                                </div>
                                        </div>
                                        </div>
                                        <div class="col-md-2 col-sm-12 col-xs-12 mb-3">
                                            <div class="form-group mb-3">
                                                <label for="" class="form-label text-center">Priority <small class="text-warning">(Optional)</small></label>
                                                <input type="number" name="sections[{{ $section->id }}][priority]" value="{{ $section->priority ?? 0 }}" min="0" class="form-control">
                                                <input type="hidden" name="sections[{{ $section->id }}][content_type]" value="{{ $section->content_type }}">
                                            </div>
                                            <div class="form-group d-grid gap-2">
                                                <a role="button" class="btn btn-danger remove-section" data-id="{{ $uniqId }}"><i class="bi bi-trash"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @break
                            @case(2)
                                <div class="row my-4" id="uniq-{{ $uniqId }}">
                                    <div class="col-md-11 col-sm-12 col-xs-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="col d-flex justify-content-center align-items-center">
                                                    <h4>Team</h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-1 col-sm-12 col-xs-12">
                                        <div class="form-group mb-3">
                                            <label for="" class="form-label text-center">Priority <small class="text-warning">(Optional)</small></label>
                                            <input type="number" name="sections[{{ $section->id }}][priority]" value="{{ $section->priority ?? 0 }}" min="0" class="form-control">
                                            <input type="hidden" name="sections[{{ $section->id }}][page_data]" value="{{ $section->page_data ?? 0 }}" min="0" class="form-control">
                                            <input type="hidden" name="sections[{{ $section->id }}][content_type]" value="{{ $section->content_type }}">
                                        </div>
                                        <div class="form-group d-grid gap-2">
                                            <a role="button" class="btn btn-danger remove-section" data-id="{{ $uniqId }}"><i class="bi bi-x"></i></a>
                                        </div>
                                        <div class="form-group d-grid gap-2 mt-1">
                                            <a role="button" class="btn btn-success duplicate-section" data-id="{{ $uniqId }}"><i class="bi bi-collection"></i></a>
                                        </div>
                                    </div>
                                </div>
                                @break
                            @case(3)
                                <div class="row my-4" id="uniq-{{ $uniqId }}">
                                    <div class="col-md-11 col-sm-12 col-xs-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="col d-flex justify-content-center align-items-center">
                                                    <h4>Dwonloaded File</h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-1 col-sm-12 col-xs-12">
                                        <div class="form-group mb-3">
                                            <label for="" class="form-label text-center">Priority <small class="text-warning">(Optional)</small></label>
                                            <input type="number" name="sections[{{ $section->id }}][priority]" value="{{ $section->priority ?? 0 }}" min="0" class="form-control">
                                            <input type="hidden" name="sections[{{ $section->id }}][page_data]" value="{{ $section->page_data ?? 0 }}" min="0" class="form-control">
                                            <input type="hidden" name="sections[{{ $section->id }}][content_type]" value="{{ $section->content_type }}">
                                        </div>
                                        <div class="form-group d-grid gap-2">
                                            <a role="button" class="btn btn-danger remove-section" data-id="{{ $uniqId }}"><i class="bi bi-x"></i></a>
                                        </div>
                                        <div class="form-group d-grid gap-2 mt-1">
                                            <a role="button" class="btn btn-success duplicate-section" data-id="{{ $uniqId }}" ><i class="bi bi-collection"></i></a>
                                        </div>
                                    </div>
                                </div>
                                @break
                            @case(4)
                                <div class="row my-4" id="uniq-{{ $uniqId }}">
                                    <div class="col-md-11 col-sm-12 col-xs-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="col d-flex justify-content-center align-items-center">
                                                    <h4>Media</h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-1 col-sm-12 col-xs-12">
                                        <div class="form-group mb-3">
                                            <label for="" class="form-label text-center">Priority <small class="text-warning">(Optional)</small></label>
                                            <input type="number" name="sections[{{ $section->id }}][priority]" value="{{ $section->priority ?? 0 }}" min="0" class="form-control">
                                            <input type="hidden" name="sections[{{ $section->id }}][page_data]" value="{{ $section->page_data ?? 0 }}" min="0" class="form-control">
                                            <input type="hidden" name="sections[{{ $section->id }}][content_type]" value="{{ $section->content_type }}">
                                        </div>
                                        <div class="form-group d-grid gap-2">
                                            <a role="button" class="btn btn-danger remove-section" data-id="{{ $uniqId }}"><i class="bi bi-x"></i></a>
                                        </div>
                                        <div class="form-group d-grid gap-2 mt-1">
                                            <a role="button" class="btn btn-success duplicate-section" data-id="{{ $uniqId }}"><i class="bi bi-collection"></i></a>
                                        </div>
                                    </div>
                                </div>
                                @break
                                 @case(5)
                                <div class="row my-4" id="uniq-{{ $uniqId }}">
                                    <div class="col-md-11 col-sm-12 col-xs-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="col d-flex justify-content-center align-items-center">
                                                    <h4>ORGANIZATIONAL</h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-1 col-sm-12 col-xs-12">
                                        <div class="form-group mb-3">
                                            <label for="" class="form-label text-center">Priority <small class="text-warning">(Optional)</small></label>
                                            <input type="number" name="sections[{{ $section->id }}][priority]" value="{{ $section->priority ?? 0 }}" min="0" class="form-control">
                                            <input type="hidden" name="sections[{{ $section->id }}][page_data]" value="{{ $section->page_data ?? 0 }}" min="0" class="form-control">
                                            <input type="hidden" name="sections[{{ $section->id }}][content_type]" value="{{ $section->content_type }}">
                                        </div>
                                        <div class="form-group d-grid gap-2">
                                            <a role="button" class="btn btn-danger remove-section" data-id="{{ $uniqId }}"><i class="bi bi-x"></i></a>
                                        </div>
                                        <div class="form-group d-grid gap-2 mt-1">
                                            <a role="button" class="btn btn-success duplicate-section" data-id="{{ $uniqId }}"><i class="bi bi-collection"></i></a>
                                        </div>
                                    </div>
                                </div>
                                @break
                                 @case(6)
                                <div class="row my-4" id="uniq-{{ $uniqId }}">
                                    <div class="col-md-11 col-sm-12 col-xs-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="col d-flex justify-content-center align-items-center">
                                                    <h4>Quiz</h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-1 col-sm-12 col-xs-12">
                                        <div class="form-group mb-3">
                                            <label for="" class="form-label text-center">Priority <small class="text-warning">(Optional)</small></label>
                                            <input type="number" name="sections[{{ $section->id }}][priority]" value="{{ $section->priority ?? 0 }}" min="0" class="form-control">
                                            <input type="hidden" name="sections[{{ $section->id }}][page_data]" value="{{ $section->page_data ?? 0 }}" min="0" class="form-control">
                                            <input type="hidden" name="sections[{{ $section->id }}][content_type]" value="{{ $section->content_type }}">
                                        </div>
                                        <div class="form-group d-grid gap-2">
                                            <a role="button" class="btn btn-danger remove-section" data-id="{{ $uniqId }}"><i class="bi bi-x"></i></a>
                                        </div>
                                        <div class="form-group d-grid gap-2 mt-1">
                                            <a role="button" class="btn btn-success duplicate-section" data-id="{{ $uniqId }}"><i class="bi bi-collection"></i></a>
                                        </div>
                                    </div>
                                </div>
                                @break
                                 @case(7)
                                <div class="row my-4" id="uniq-{{ $uniqId }}">
                                    <div class="col-md-11 col-sm-12 col-xs-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="col d-flex justify-content-center align-items-center">
                                                    <h4>Gallery</h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-1 col-sm-12 col-xs-12">
                                        <div class="form-group mb-3">
                                            <label for="" class="form-label text-center">Priority <small class="text-warning">(Optional)</small></label>
                                            <input type="number" name="sections[{{ $section->id }}][priority]" value="{{ $section->priority ?? 0 }}" min="0" class="form-control">
                                            <input type="hidden" name="sections[{{ $section->id }}][page_data]" value="{{ $section->page_data ?? 0 }}" min="0" class="form-control">
                                            <input type="hidden" name="sections[{{ $section->id }}][content_type]" value="{{ $section->content_type }}">
                                        </div>
                                        <div class="form-group d-grid gap-2">
                                            <a role="button" class="btn btn-danger remove-section" data-id="{{ $uniqId }}"><i class="bi bi-x"></i></a>
                                        </div>
                                        <div class="form-group d-grid gap-2 mt-1">
                                            <a role="button" class="btn btn-success duplicate-section" data-id="{{ $uniqId }}"><i class="bi bi-collection"></i></a>
                                        </div>
                                    </div>
                                </div>
                                @break
                                 @case(8)
                                <div class="row my-4" id="uniq-{{ $uniqId }}">
                                    <div class="col-md-11 col-sm-12 col-xs-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="col d-flex justify-content-center align-items-center">
                                                    <h4>Contact Page</h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-1 col-sm-12 col-xs-12">
                                        <div class="form-group mb-3">
                                            <label for="" class="form-label text-center">Priority <small class="text-warning">(Optional)</small></label>
                                            <input type="number" name="sections[{{ $section->id }}][priority]" value="{{ $section->priority ?? 0 }}" min="0" class="form-control">
                                            <input type="hidden" name="sections[{{ $section->id }}][page_data]" value="{{ $section->page_data ?? 0 }}" min="0" class="form-control">
                                            <input type="hidden" name="sections[{{ $section->id }}][content_type]" value="{{ $section->content_type }}">
                                        </div>
                                        <div class="form-group d-grid gap-2">
                                            <a role="button" class="btn btn-danger remove-section" data-id="{{ $uniqId }}"><i class="bi bi-x"></i></a>
                                        </div>
                                        <div class="form-group d-grid gap-2 mt-1">
                                            <a role="button" class="btn btn-success duplicate-section" data-id="{{ $uniqId }}"><i class="bi bi-collection"></i></a>
                                        </div>
                                    </div>
                                </div>
                                @break
                        @endswitch
                    </div>
                    <script>
                        document.addEventListener("DOMContentLoaded", function() {
                            initTinyMCE("editor-{{ $uniqId }}");
                        });
                    </script>
                @endforeach
            @endif
        </span>
        {{-- <hr> --}}
        {{-- <div class="row my-4">
            <div class="col d-flex justify-content-center">
                <a role="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#sectionModal">+ Add Section</a>
            </div>
        </div> --}}
    </div>
   </div>
{{-- END --}}

