<div class="">

    <!-- Row -->
    <div class="page-header d-lg-flex d-block">

        <div class="page-leftheader">
            <h4 class="page-title">Main Page</h4>
        </div>
        <div class="page-rightheader ">
            {{-- alert message --}}
            @if (session('message'))
                <div class="alert alert-success alert-message fade show " role="alert" id="success-alert">
                    {{ session('message') }}
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-message fade show" role="alert" id="error-alert">
                    {{ session('error') }}
                </div>
            @endif
            <div class=" btn-list">
                @if ($page_type == 'View')
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
                    <div id="file-export_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">

                        @if ($page_type == 'View')
                            <livewire:page-table />
                        @else
                            {{-- Page Section --}}

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-header align-items-center d-flex">
                                            <h4 class="card-title mb-0 flex-grow-1">Page Sections</h4>
                                        </div>
                                        <div class="card-body">
                                            <form wire:submit.prevent="{{ $form_url }}" method="post"
                                                enctype="multipart/form-data">
                                                @csrf
                                                <div class="row">
                                                    <div class="mb-3 col-lg-4">
                                                        <label for="exampleFormControlInput1"
                                                            class="form-label">Title</label>
                                                        <input type="text" class="form-control shadow-none"
                                                            id="title" placeholder="Enter Title" wire:model="title">
                                                        <input type="hidden" wire:model="id">
                                                        @error('title')
                                                            <span class="error"
                                                                style="color: red">{{ $message }}</span>
                                                        @enderror
                                                    </div>

                                                    <div class="mb-3 col-lg-4">
                                                        <label for="exampleFormControlInput1"
                                                            class="form-label">Thumbnail</label>
                                                        <input type="file" class="form-control shadow-none"
                                                            id="email" placeholder="Enter thumbnail"
                                                            wire:model="thumbnail">
                                                        @error('thumbnail')
                                                            <span class="error"
                                                                style="color: red">{{ $message }}</span>
                                                        @enderror
                                                    </div>

                                                    <div class="mb-3 col-lg-4">
                                                        <div x-data x-init="$nextTick(() => {
                                                            let statusSelect = $refs.status;
                                                            $(statusSelect).select2();
                                                        
                                                            // Capture Select2 change and manually sync to Livewire
                                                            $(statusSelect).on('select2:select', function(e) {
                                                                // Directly set the group_id value in Livewire
                                                                $wire.set('status', $(this).val());
                                                            });
                                                        
                                                            // Sync the Select2 component with Livewire changes
                                                            $watch('status', value => {
                                                                $(statusSelect).val(value).trigger('change.select2');
                                                            });
                                                        })" wire:ignore>
                                                            <label for="exampleFormControlInput1"
                                                                class="form-label">Status</label>
                                                            <select
                                                                class="form-control select2-show-search custom-select"
                                                                x-ref="status" wire:model="status" id="status">
                                                                <option selected value="" disabled="disabled">
                                                                    Choose....</option>
                                                                <option value="active">Active</option>
                                                                <option value="inactive">Inactive</option>
                                                            </select>
                                                            @error('status')
                                                                <span class="error"
                                                                    style="color: red">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                                @if ($page_type == 'edit')
                                                    <span id="__section-container" wire:ignore>
                                                        @if (isset($section_record) && count($section_record))
                                                            @foreach ($section_record as $section)
                                                                @php
                                                                    $uniqueId = uniqid(); // Generate a unique ID for each section
                                                                @endphp
                                                                {{-- <div class="border my-4 px-3 " id="dup-{{ $uniqueId }}"> --}}
                                                                @switch($section->content_type)
                                                                    @case(1)
                                                                        <!-- Content Type 1: Textarea Section -->
                                                                        <div class="row my-4" id="uniq-{{ $uniqueId }}">
                                                                            <div class="col-md-11 col-sm-12 col-xs-12">
                                                                                <div id="bgid-{{ $uniqueId }}">
                                                                                    <div class="form-group">
                                                                                        <div x-data x-init="$nextTick(() => initTinyMCE('content-{{ $uniqueId }}', 'sections.{{ $uniqueId }}.page_data'))">
                                                                                            <textarea id="content-{{ $uniqueId }}" class="form-control" x-ref="content-{{ $uniqueId }}"
                                                                                                wire:model.fill="sections.{{ $uniqueId }}.page_data">{{ $section->page_data }}</textarea>
                                                                                        </div>
                                                                                        {{-- <textarea data-id="{{ $section->id }}" name="sections[{{ $section->id }}][page_data]" rows="5"
                                                                                    class="custom-multiple-editor">{{ $section->page_data }}</textarea> --}}
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-1 col-sm-12 col-xs-12 mb-3">
                                                                                <div class="form-group mb-3">
                                                                                    <label for=""
                                                                                        class="form-label text-center">
                                                                                        Priority <small
                                                                                            class="text-warning">(Optional)</small>
                                                                                    </label>
                                                                                    <input type="number"
                                                                                        name="sections[{{ $uniqueId }}][priority]"
                                                                                        value="{{ $section->priority ?? 0 }}"
                                                                                        wire:model.fill="sections.{{ $uniqueId }}.priority"
                                                                                        min="0" class="form-control">
                                                                                    <input type="hidden"
                                                                                        name="sections[{{ $uniqueId }}][content_type]"
                                                                                        value="{{ $section->content_type }}"
                                                                                        wire:model.fill="sections.{{ $uniqueId }}.content_type">
                                                                                </div>
                                                                                <div class="form-group d-grid gap-2">
                                                                                    <button type="button"
                                                                                        class="btn btn-danger removeSectionRecords"
                                                                                        wire:click="removeSectionRecord({{ $section->id }})"
                                                                                        data-id="{{ $uniqueId }}">
                                                                                        <i class="bi bi-x"></i>
                                                                                    </button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @break

                                                                    @case(3)
                                                                        <!-- Content Type 2: Package Section -->
                                                                        <div class="row my-4" id="uniq-{{ $uniqueId }}">
                                                                            <div class="col-md-11 col-sm-12 col-xs-12">
                                                                                <div class="card">
                                                                                    <div class="card-body">
                                                                                        <div
                                                                                            class="col d-flex justify-content-center align-items-center">
                                                                                            <h4>Package</h4>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-1 col-sm-12 col-xs-12">
                                                                                <div class="form-group mb-3">
                                                                                    <label for=""
                                                                                        class="form-label text-center">
                                                                                        Priority <small
                                                                                            class="text-warning">(Optional)</small>
                                                                                    </label>
                                                                                    <input type="number"
                                                                                        name="sections[{{ $uniqueId }}][priority]"
                                                                                        value="{{ $section->priority ?? 0 }}"
                                                                                        wire:model.fill="sections.{{ $uniqueId }}.priority"
                                                                                        min="0" class="form-control">
                                                                                    <input type="hidden"
                                                                                        name="sections[{{ $uniqueId }}][page_data]"
                                                                                        value="{{ $section->page_data ?? 0 }}"
                                                                                        wire:model.fill="sections.{{ $uniqueId }}.page_data">
                                                                                    <input type="hidden"
                                                                                        name="sections[{{ $uniqueId }}][content_type]"
                                                                                        value="{{ $section->content_type }}"
                                                                                        wire:model.fill="sections.{{ $uniqueId }}.content_type">
                                                                                </div>
                                                                                <div class="form-group d-grid gap-2">
                                                                                    <button type="button"
                                                                                        class="btn btn-danger removeSectionRecords"
                                                                                        wire:click="removeSectionRecord({{ $section->id }})"
                                                                                        data-id="{{ $uniqueId }}">
                                                                                        <i class="bi bi-x"></i>
                                                                                    </button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @break

                                                                    @case(2)
                                                                        <!-- Content Type 2: Package Section -->
                                                                        <div class="row my-4" id="uniq-{{ $uniqueId }}">
                                                                            <div class="col-md-11 col-sm-12 col-xs-12">
                                                                                <div class="card">
                                                                                    <div class="card-body">
                                                                                        <div
                                                                                            class="col d-flex justify-content-center align-items-center">
                                                                                            <h4>Subjects</h4>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-1 col-sm-12 col-xs-12">
                                                                                <div class="form-group mb-3">
                                                                                    <label for=""
                                                                                        class="form-label text-center">
                                                                                        Priority <small
                                                                                            class="text-warning">(Optional)</small>
                                                                                    </label>
                                                                                    <input type="number"
                                                                                        name="sections[{{ $uniqueId }}][priority]"
                                                                                        value="{{ $section->priority ?? 0 }}"
                                                                                        wire:model.fill="sections.{{ $uniqueId }}.priority"
                                                                                        min="0" class="form-control">
                                                                                    <input type="hidden"
                                                                                        name="sections[{{ $uniqueId }}][page_data]"
                                                                                        value="{{ $section->page_data ?? 0 }}"
                                                                                        wire:model.fill="sections.{{ $uniqueId }}.page_data">
                                                                                    <input type="hidden"
                                                                                        name="sections[{{ $uniqueId }}][content_type]"
                                                                                        value="{{ $section->content_type }}"
                                                                                        wire:model.fill="sections.{{ $uniqueId }}.content_type">
                                                                                </div>
                                                                                <div class="form-group d-grid gap-2">
                                                                                    <button type="button"
                                                                                        class="btn btn-danger removeSectionRecords"
                                                                                        wire:click="removeSectionRecord({{ $section->id }})"
                                                                                        data-id="{{ $uniqueId }}">
                                                                                        <i class="bi bi-x"></i>
                                                                                    </button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @break

                                                                    @case(4)
                                                                        <!-- Content Type 2: Package Section -->
                                                                        <div class="row my-4" id="uniq-{{ $uniqueId }}">
                                                                            <div class="col-md-11 col-sm-12 col-xs-12">
                                                                                <div class="card">
                                                                                    <div class="card-body">
                                                                                        <div
                                                                                            class="col d-flex justify-content-center align-items-center">
                                                                                            <h4>Board</h4>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-1 col-sm-12 col-xs-12">
                                                                                <div class="form-group mb-3">
                                                                                    <label for=""
                                                                                        class="form-label text-center">
                                                                                        Priority <small
                                                                                            class="text-warning">(Optional)</small>
                                                                                    </label>
                                                                                    <input type="number"
                                                                                        name="sections[{{ $uniqueId }}][priority]"
                                                                                        value="{{ $section->priority ?? 0 }}"
                                                                                        wire:model.fill="sections.{{ $uniqueId }}.priority"
                                                                                        min="0" class="form-control">
                                                                                    <input type="hidden"
                                                                                        name="sections[{{ $uniqueId }}][page_data]"
                                                                                        value="{{ $section->page_data ?? 0 }}"
                                                                                        wire:model.fill="sections.{{ $uniqueId }}.page_data">
                                                                                    <input type="hidden"
                                                                                        name="sections[{{ $uniqueId }}][content_type]"
                                                                                        value="{{ $section->content_type }}"
                                                                                        wire:model.fill="sections.{{ $uniqueId }}.content_type">
                                                                                </div>
                                                                                <div class="form-group d-grid gap-2">
                                                                                    <button type="button"
                                                                                        class="btn btn-danger removeSectionRecords"
                                                                                        wire:click="removeSectionRecord({{ $section->id }})"
                                                                                        data-id="{{ $uniqueId }}">
                                                                                        <i class="bi bi-x"></i>
                                                                                    </button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @break

                                                                    @case(5)
                                                                        <!-- Content Type 2: Package Section -->
                                                                        <div class="row my-4" id="uniq-{{ $uniqueId }}">
                                                                            <div class="col-md-11 col-sm-12 col-xs-12">
                                                                                <div class="card">
                                                                                    <div class="card-body">
                                                                                        <div
                                                                                            class="col d-flex justify-content-center align-items-center">
                                                                                            <h4>Counselling</h4>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-1 col-sm-12 col-xs-12">
                                                                                <div class="form-group mb-3">
                                                                                    <label for=""
                                                                                        class="form-label text-center">
                                                                                        Priority <small
                                                                                            class="text-warning">(Optional)</small>
                                                                                    </label>
                                                                                    <input type="number"
                                                                                        name="sections[{{ $uniqueId }}][priority]"
                                                                                        value="{{ $section->priority ?? 0 }}"
                                                                                        wire:model.fill="sections.{{ $uniqueId }}.priority"
                                                                                        min="0" class="form-control">
                                                                                    <input type="hidden"
                                                                                        name="sections[{{ $uniqueId }}][page_data]"
                                                                                        value="{{ $section->page_data ?? 0 }}"
                                                                                        wire:model.fill="sections.{{ $uniqueId }}.page_data">
                                                                                    <input type="hidden"
                                                                                        name="sections[{{ $uniqueId }}][content_type]"
                                                                                        value="{{ $section->content_type }}"
                                                                                        wire:model.fill="sections.{{ $uniqueId }}.content_type">
                                                                                </div>
                                                                                <div class="form-group d-grid gap-2">
                                                                                    <button type="button"
                                                                                        class="btn btn-danger removeSectionRecords"
                                                                                        wire:click="removeSectionRecord({{ $section->id }})"
                                                                                        data-id="{{ $uniqueId }}">
                                                                                        <i class="bi bi-x"></i>
                                                                                    </button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @break

                                                                    @case(6)
                                                                        <!-- Content Type 2: Package Section -->
                                                                        <div class="row my-4" id="uniq-{{ $uniqueId }}">
                                                                            <div class="col-md-11 col-sm-12 col-xs-12">
                                                                                <div class="card">
                                                                                    <div class="card-body">
                                                                                        <div
                                                                                            class="col d-flex justify-content-center align-items-center">
                                                                                            <h4>Case study</h4>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-1 col-sm-12 col-xs-12">
                                                                                <div class="form-group mb-3">
                                                                                    <label for=""
                                                                                        class="form-label text-center">
                                                                                        Priority <small
                                                                                            class="text-warning">(Optional)</small>
                                                                                    </label>
                                                                                    <input type="number"
                                                                                        name="sections[{{ $uniqueId }}][priority]"
                                                                                        value="{{ $section->priority ?? 0 }}"
                                                                                        wire:model.fill="sections.{{ $uniqueId }}.priority"
                                                                                        min="0" class="form-control">
                                                                                    <input type="hidden"
                                                                                        name="sections[{{ $uniqueId }}][page_data]"
                                                                                        value="{{ $section->page_data ?? 0 }}"
                                                                                        wire:model.fill="sections.{{ $uniqueId }}.page_data">
                                                                                    <input type="hidden"
                                                                                        name="sections[{{ $uniqueId }}][content_type]"
                                                                                        value="{{ $section->content_type }}"
                                                                                        wire:model.fill="sections.{{ $uniqueId }}.content_type">
                                                                                </div>
                                                                                <div class="form-group d-grid gap-2">
                                                                                    <button type="button"
                                                                                        class="btn btn-danger removeSectionRecords"
                                                                                        wire:click="removeSectionRecord({{ $section->id }})"
                                                                                        data-id="{{ $uniqueId }}">
                                                                                        <i class="bi bi-x"></i>
                                                                                    </button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @break

                                                                    @case(7)
                                                                        <!-- Content Type 2: Package Section -->
                                                                        <div class="row my-4" id="uniq-{{ $uniqueId }}">
                                                                            <div class="col-md-11 col-sm-12 col-xs-12">
                                                                                <div class="card">
                                                                                    <div class="card-body">
                                                                                        <div
                                                                                            class="col d-flex justify-content-center align-items-center">
                                                                                            <h4>Achievements</h4>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-1 col-sm-12 col-xs-12">
                                                                                <div class="form-group mb-3">
                                                                                    <label for=""
                                                                                        class="form-label text-center">
                                                                                        Priority <small
                                                                                            class="text-warning">(Optional)</small>
                                                                                    </label>
                                                                                    <input type="number"
                                                                                        name="sections[{{ $uniqueId }}][priority]"
                                                                                        value="{{ $section->priority ?? 0 }}"
                                                                                        wire:model.fill="sections.{{ $uniqueId }}.priority"
                                                                                        min="0" class="form-control">
                                                                                    <input type="hidden"
                                                                                        name="sections[{{ $uniqueId }}][page_data]"
                                                                                        value="{{ $section->page_data ?? 0 }}"
                                                                                        wire:model.fill="sections.{{ $uniqueId }}.page_data">
                                                                                    <input type="hidden"
                                                                                        name="sections[{{ $uniqueId }}][content_type]"
                                                                                        value="{{ $section->content_type }}"
                                                                                        wire:model.fill="sections.{{ $uniqueId }}.content_type">
                                                                                </div>
                                                                                <div class="form-group d-grid gap-2">
                                                                                    <button type="button"
                                                                                        class="btn btn-danger removeSectionRecords"
                                                                                        wire:click="removeSectionRecord({{ $section->id }})"
                                                                                        data-id="{{ $uniqueId }}">
                                                                                        <i class="bi bi-x"></i>
                                                                                    </button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @break

                                                                    @case(8)
                                                                        <!-- Content Type 2: Package Section -->
                                                                        <div class="row my-4" id="uniq-{{ $uniqueId }}">
                                                                            <div class="col-md-11 col-sm-12 col-xs-12">
                                                                                <div class="card">
                                                                                    <div class="card-body">
                                                                                        <div
                                                                                            class="col d-flex justify-content-center align-items-center">
                                                                                            <h4>Frequency & question's</h4>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-1 col-sm-12 col-xs-12">
                                                                                <div class="form-group mb-3">
                                                                                    <label for=""
                                                                                        class="form-label text-center">
                                                                                        Priority <small
                                                                                            class="text-warning">(Optional)</small>
                                                                                    </label>
                                                                                    <input type="number"
                                                                                        name="sections[{{ $uniqueId }}][priority]"
                                                                                        value="{{ $section->priority ?? 0 }}"
                                                                                        wire:model.fill="sections.{{ $uniqueId }}.priority"
                                                                                        min="0" class="form-control">
                                                                                    <input type="hidden"
                                                                                        name="sections[{{ $uniqueId }}][page_data]"
                                                                                        value="{{ $section->page_data ?? 0 }}"
                                                                                        wire:model.fill="sections.{{ $uniqueId }}.page_data">
                                                                                    <input type="hidden"
                                                                                        name="sections[{{ $uniqueId }}][content_type]"
                                                                                        value="{{ $section->content_type }}"
                                                                                        wire:model.fill="sections.{{ $uniqueId }}.content_type">
                                                                                </div>
                                                                                <div class="form-group d-grid gap-2">
                                                                                    <button type="button"
                                                                                        class="btn btn-danger removeSectionRecords"
                                                                                        wire:click="removeSectionRecord({{ $section->id }})"
                                                                                        data-id="{{ $uniqueId }}">
                                                                                        <i class="bi bi-x"></i>
                                                                                    </button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @break
                                                                @endswitch
                                                                {{-- </div> --}}
                                                            @endforeach
                                                        @endif
                                                    </span>
                                                @endif
                                        </div>
                                        @if ($page_type == 'edit')
                                            <div class="card-footer">
                                                <div class="row my-4">
                                                    <div class="col d-flex justify-content-center">
                                                        <a role="button" class="btn btn-primary"
                                                            data-bs-toggle="modal" data-bs-target="#loginModals">+ Add
                                                            Section</a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        <div class="modal-footer">
                                            <button type="submit" wire:click="back"
                                                class="btn btn-secondary">Close</button>
                                            <button type="submit" wire:click="{{ $form_url }}"
                                                class="btn btn-primary shadow-none">{{ $button }}
                                            </button>
                                        </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div id="loginModals" class="modal fade" tabindex="-1" aria-hidden="true" style="display: none;"
                aria-labelledby="exampleModalToggleLabel">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-body login-modal p-5">
                        </div>
                        <div class="modal-body p-5 text-center">
                            <h5 class="mb-3">Section Template *</h5>

                            <div class="mb-2">
                                <select name="status" class="form-select rounded-pill mb-3" id="sectionType">
                                    <option value="">---Select Template---</option>
                                    <option value="1">Section</option>
                                    <option value="3">Package</option>
                                    <option value="2">Subject</option>
                                    <option value="4">Board</option>
                                    <option value="5">Counselling</option>
                                    <option value="6">Case study</option>
                                    <option value="7">Achievements</option>
                                    <option value="8">Frequency & question's</option>
                                </select>
                            </div>
                        </div>
                        <button type="button" class="btn btn-primary w-100" id="addSection">Add</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--div-->
    @include('livewire.admin.include.template-model')
</div>
</div>
</div>
@push('script')
    <div x-data x-init="initSectionHandler()"></div>
    <script>
        // remove section #
        // end remove section
        function initSectionHandler() {
            document.getElementById('addSection').addEventListener('click', function() {
                let sectionId = parseInt($('#sectionType').val());
                if (!sectionId) {
                    alert('Please select a section template.');
                    return;
                }
                let uniqId = Date.now();

                // Template for Section 1 (TinyMCE Editor)
                if (sectionId === 1) {
                    $('#__section-container').append(`
                        <div class='border my-4 px-3' id='uniq-${uniqId}'>
                            <div class='row my-4'>
                                <div class='col-md-11 col-sm-12 col-xs-12'>
                                    <div id='bgid-${uniqId}'>
                                        <div class='form-group'>
                                        <div x-data x-init="$nextTick(() => initTinyMCE('content-${uniqId}', 'sections.${uniqId}.page_data'))"> 
                                            <!-- Bind to Livewire property -->
                                            <textarea id="content-${uniqId}" class="form-control" x-ref="content-${uniqId}" wire:model="sections.${uniqId}.page_data" ></textarea>
                                        </div>
                                        </div>
                                    </div>
                                </div>
                                <div class='col-md-1 col-sm-12 col-xs-12'>
                                    <div class='form-group mb-3'>
                                        <label for='" class='form-label'>Priority <small class='text-warning'>(Optional)</small></label>
                                        <input type='number' name='sections[${uniqId}][priority]' value='0' wire:model.fill="sections.${uniqId}.priority" min='0' class='form-control'>
                                        <input type='hidden' name='sections[${uniqId}][content_type]'  wire:model.fill="sections.${uniqId}.content_type" value='1' class='form-control' >
                                    </div>
                                    <div class='form-group d-grid gap-2'>
                                        <a role='button' class='btn btn-danger remove-section' data-id='${uniqId}'>
                                            <i class='bi bi-x'></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `);
                }
                // Template for Section 2 (Package)
                else if (sectionId === 3) {
                    $('#__section-container').append(`
                        <div class='border my-4 px-3' id='uniq-${uniqId}'>
                            <div class='row my-4'>
                                <div class='col-md-11 col-sm-12 col-xs-12'>
                                    <div class='card'>
                                        <div class='card-body'>
                                            <div class='col d-flex justify-content-center align-items-center'>
                                                <h1>Package</h1>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class='col-md-1 col-sm-12 col-xs-12'>
                                    <div class='form-group mb-3'>
                                        <label for='" class='form-label'>Priority <small class='text-warning'>(Optional)</small></label>
                                        <input type='number' name='priority[${uniqId}][priority]' wire:model.fill="sections.${uniqId}.priority" value='0' min='0' class='form-control'>
                                        <input type="hidden" name="sections[${uniqId}][page_data]" wire:model.fill="sections.${uniqId}.page_data" value="0">
                                        <input type='hidden' name='sections[${uniqId}][content_type]' wire:model.fill="sections.${uniqId}.content_type" value='3'>
                                    </div>
                                    <div class='form-group d-grid gap-2'>
                                        <a role='button' class='btn btn-danger remove-section' data-id='${uniqId}'>
                                            <i class='bi bi-x'></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `);
                } else if (sectionId === 2) {
                    $('#__section-container').append(`
                        <div class='border my-4 px-3' id='uniq-${uniqId}'>
                            <div class='row my-4'>
                                <div class='col-md-11 col-sm-12 col-xs-12'>
                                    <div class='card'>
                                        <div class='card-body'>
                                            <div class='col d-flex justify-content-center align-items-center'>
                                                <h1>Subjects</h1>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class='col-md-1 col-sm-12 col-xs-12'>
                                    <div class='form-group mb-3'>
                                        <label for='" class='form-label'>Priority <small class='text-warning'>(Optional)</small></label>
                                        <input type='number' name='priority[${uniqId}][priority]' wire:model.fill="sections.${uniqId}.priority" value='0' min='0' class='form-control'>
                                        <input type="hidden" name="sections[${uniqId}][page_data]" wire:model.fill="sections.${uniqId}.page_data" value="0">
                                        <input type='hidden' name='sections[${uniqId}][content_type]' wire:model.fill="sections.${uniqId}.content_type" value='2'>
                                    </div>
                                    <div class='form-group d-grid gap-2'>
                                        <a role='button' class='btn btn-danger remove-section' data-id='${uniqId}'>
                                            <i class='bi bi-x'></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `);
                } else if (sectionId === 4) {
                    $('#__section-container').append(`
                        <div class='border my-4 px-3' id='uniq-${uniqId}'>
                            <div class='row my-4'>
                                <div class='col-md-11 col-sm-12 col-xs-12'>
                                    <div class='card'>
                                        <div class='card-body'>
                                            <div class='col d-flex justify-content-center align-items-center'>
                                                <h1>Boards</h1>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class='col-md-1 col-sm-12 col-xs-12'>
                                    <div class='form-group mb-3'>
                                        <label for='" class='form-label'>Priority <small class='text-warning'>(Optional)</small></label>
                                        <input type='number' name='priority[${uniqId}][priority]' wire:model.fill="sections.${uniqId}.priority" value='0' min='0' class='form-control'>
                                        <input type="hidden" name="sections[${uniqId}][page_data]" wire:model.fill="sections.${uniqId}.page_data" value="0">
                                        <input type='hidden' name='sections[${uniqId}][content_type]' wire:model.fill="sections.${uniqId}.content_type" value='4'>
                                    </div>
                                    <div class='form-group d-grid gap-2'>
                                        <a role='button' class='btn btn-danger remove-section' data-id='${uniqId}'>
                                            <i class='bi bi-x'></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `);
                } else if (sectionId === 5) {
                    $('#__section-container').append(`
                        <div class='border my-4 px-3' id='uniq-${uniqId}'>
                            <div class='row my-4'>
                                <div class='col-md-11 col-sm-12 col-xs-12'>
                                    <div class='card'>
                                        <div class='card-body'>
                                            <div class='col d-flex justify-content-center align-items-center'>
                                                <h1>Counselling</h1>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class='col-md-1 col-sm-12 col-xs-12'>
                                    <div class='form-group mb-3'>
                                        <label for='" class='form-label'>Priority <small class='text-warning'>(Optional)</small></label>
                                        <input type='number' name='priority[${uniqId}][priority]' wire:model.fill="sections.${uniqId}.priority" value='0' min='0' class='form-control'>
                                        <input type="hidden" name="sections[${uniqId}][page_data]" wire:model.fill="sections.${uniqId}.page_data" value="0">
                                        <input type='hidden' name='sections[${uniqId}][content_type]' wire:model.fill="sections.${uniqId}.content_type" value='5'>
                                    </div>
                                    <div class='form-group d-grid gap-2'>
                                        <a role='button' class='btn btn-danger remove-section' data-id='${uniqId}'>
                                            <i class='bi bi-x'></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `);
                } else if (sectionId === 6) {
                    $('#__section-container').append(`
                        <div class='border my-4 px-3' id='uniq-${uniqId}'>
                            <div class='row my-4'>
                                <div class='col-md-11 col-sm-12 col-xs-12'>
                                    <div class='card'>
                                        <div class='card-body'>
                                            <div class='col d-flex justify-content-center align-items-center'>
                                                <h1>Case Studies</h1>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class='col-md-1 col-sm-12 col-xs-12'>
                                    <div class='form-group mb-3'>
                                        <label for='" class='form-label'>Priority <small class='text-warning'>(Optional)</small></label>
                                        <input type='number' name='priority[${uniqId}][priority]' wire:model.fill="sections.${uniqId}.priority" value='0' min='0' class='form-control'>
                                        <input type="hidden" name="sections[${uniqId}][page_data]" wire:model.fill="sections.${uniqId}.page_data" value="0">
                                        <input type='hidden' name='sections[${uniqId}][content_type]' wire:model.fill="sections.${uniqId}.content_type" value='6'>
                                    </div>
                                    <div class='form-group d-grid gap-2'>
                                        <a role='button' class='btn btn-danger remove-section' data-id='${uniqId}'>
                                            <i class='bi bi-x'></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `);
                } else if (sectionId === 7) {
                    $('#__section-container').append(`
                        <div class='border my-4 px-3' id='uniq-${uniqId}'>
                            <div class='row my-4'>
                                <div class='col-md-11 col-sm-12 col-xs-12'>
                                    <div class='card'>
                                        <div class='card-body'>
                                            <div class='col d-flex justify-content-center align-items-center'>
                                                <h1>Achievements</h1>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class='col-md-1 col-sm-12 col-xs-12'>
                                    <div class='form-group mb-3'>
                                        <label for='" class='form-label'>Priority <small class='text-warning'>(Optional)</small></label>
                                        <input type='number' name='priority[${uniqId}][priority]' wire:model.fill="sections.${uniqId}.priority" value='0' min='0' class='form-control'>
                                        <input type="hidden" name="sections[${uniqId}][page_data]" wire:model.fill="sections.${uniqId}.page_data" value="0">
                                        <input type='hidden' name='sections[${uniqId}][content_type]' wire:model.fill="sections.${uniqId}.content_type" value='7'>
                                    </div>
                                    <div class='form-group d-grid gap-2'>
                                        <a role='button' class='btn btn-danger remove-section' data-id='${uniqId}'>
                                            <i class='bi bi-x'></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `);

                } else if (sectionId === 8) {
                    $('#__section-container').append(`
                        <div class='border my-4 px-3' id='uniq-${uniqId}'>
                            <div class='row my-4'>
                                <div class='col-md-11 col-sm-12 col-xs-12'>
                                    <div class='card'>
                                        <div class='card-body'>
                                            <div class='col d-flex justify-content-center align-items-center'>
                                                <h1>Frequently Asked Questions</h1>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class='col-md-1 col-sm-12 col-xs-12'>
                                    <div class='form-group mb-3'>
                                        <label for='" class='form-label'>Priority <small class='text-warning'>(Optional)</small></label>
                                        <input type='number' name='priority[${uniqId}][priority]' wire:model.fill="sections.${uniqId}.priority" value='0' min='0' class='form-control'>
                                        <input type="hidden" name="sections[${uniqId}][page_data]" wire:model.fill="sections.${uniqId}.page_data" value="0">
                                        <input type='hidden' name='sections[${uniqId}][content_type]' wire:model.fill="sections.${uniqId}.content_type" value='8'>
                                    </div>
                                    <div class='form-group d-grid gap-2'>
                                        <a role='button' class='btn btn-danger remove-section' data-id='${uniqId}'>
                                            <i class='bi bi-x'></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `);
                }
                $('#loginModals').modal('hide');
                // Reset modal form inputs
                $('#sectionType').val('').trigger('change');
                $('#loginModals').modal('hide');

                // Attach the remove event to the newly created remove button
                attachRemoveEvent();
            });

            // Function to handle section removal
            function attachRemoveEvent() {
                $('.remove-section').click(function() {
                    const sectionId = $(this).data('id');
                    // $(`#uniq-${sectionId}`).html('');
                    let uniq = document.getElementById(`uniq-${sectionId}`);
                    uniq.remove();
                });
            }
        }

        function initTinyMCE(selector, content) {
            if (tinymce.get(selector)) {
                tinymce.get(selector).remove();
            }

            tinymce.init({
                selector: `#${selector}`,
                height: 300,
                plugins: 'image code table link media codesample template',
                toolbar: 'undo redo | styleselect | bold italic | image | alignleft aligncenter alignright alignjustify | outdent indent | media | table | add_advisor',

                image_title: true,
                // svg allow icon 
                // extended_valid_elements : "svg[*]",
                // Allow SVG elements and attributes
                extended_valid_elements: "svg[*],path[*],g[*],circle[*],rect[*],line[*],polyline[*],polygon[*],text[*]",
                automatic_uploads: true,
                images_upload_url: '', // Specify the upload URL if handling image uploads
                file_picker_types: 'image',
                file_picker_callback: function(cb, value, meta) {
                    // File picker for image upload
                    var input = document.createElement('input');
                    input.setAttribute('type', 'file');
                    // input.setAttribute('accept', 'image/*');
                    input.setAttribute('accept', 'image/*', 'svg/*');

                    input.onchange = function() {
                        var file = this.files[0];
                        var reader = new FileReader();
                        reader.onload = function() {
                            var id = 'blobid' + (new Date()).getTime();
                            var blobCache = tinymce.activeEditor.editorUpload.blobCache;
                            var base64 = reader.result.split(',')[1];
                            var blobInfo = blobCache.create(id, file, base64);
                            blobCache.add(blobInfo);

                            // Callback with the image URI
                            cb(blobInfo.blobUri(), {
                                title: file.name
                            });
                        };
                        reader.readAsDataURL(file);
                    };

                    input.click();
                },
                setup: function(editor) {
                    // Handle content update
                    editor.on('change', function() {
                        @this.set(content, editor.getContent());
                    });

                    // Add custom link on initialization
                    editor.on('init', function() {
                        editor.dom.add(editor.getBody(), 'link', {
                            rel: 'stylesheet',
                            href: 'https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css'
                        });
                        editor.dom.add(editor.getBody(), 'link', {
                            rel: 'stylesheet',
                            href: '/build/assets/frontend/css/plugins/fontawesome-5.css'
                        });
                        editor.dom.add(editor.getBody(), 'link', {
                            rel: 'stylesheet',
                            type: 'image/svg+xml',
                            href: '/build/assets/frontend/images/logo/fv icon.svg'
                        });
                        editor.dom.add(editor.getBody(), 'link', {
                            rel: 'stylesheet',
                            href: '/build/assets/frontend/css/vendor/bootstrap.min.css'
                        });
                        editor.dom.add(editor.getBody(), 'link', {
                            rel: 'stylesheet',
                            href: '/build/assets/frontend/css/vendor/animate.css'
                        });
                        editor.dom.add(editor.getBody(), 'link', {
                            rel: 'stylesheet',
                            href: '/build/assets/frontend/css/style.css'
                        });
                        editor.dom.add(editor.getBody(), 'link', {
                            rel: 'stylesheet',
                            href: '/build/assets/frontend/css/vendor/magnific-popup.css'
                        });
                        editor.dom.add(editor.getBody(), 'link', {
                            rel: 'stylesheet',
                            href: '/build/assets/frontend/css/vendor/fonts.css'
                        });
                        editor.dom.add(editor.getBody(), 'link', {
                            rel: 'stylesheet',
                            href: '/build/assets/frontend/css/vendor/metismenu.css'
                        });

                        editor.dom.add(editor.getBody(), 'link', {
                            rel: 'stylesheet',
                            href: '/build/assets/frontend/css/vendor/swiper.css'
                        });
                        editor.dom.add(editor.getBody(), 'script', {
                            src: '/build/assets/frontend/js/main.js' // Add Bootstrap or your custom CSS
                        });
                        editor.dom.add(editor.getBody(), 'script', {
                            src: '/build/assets/frontend/js/vendor/jquery.min.js' // Add Bootstrap or your custom CSS
                        });
                        editor.dom.add(editor.getBody(), 'script', {
                            src: '/build/assets/frontend/js/plugins/audio.js' // Add Bootstrap or your custom CSS
                        });
                        editor.dom.add(editor.getBody(), 'script', {
                            src: '/build/assets/frontend/js/main.js' // Add Bootstrap or your custom CSS
                        });
                        editor.dom.add(editor.getBody(), 'script', {
                            src: '/build/assets/frontend/js/vendor/bootstrap.min.js' // Add Bootstrap or your custom CSS
                        });
                        editor.dom.add(editor.getBody(), 'script', {
                            src: '/build/assets/frontend/js/vendor/swiper.js' // Add Bootstrap or your custom CSS
                        });
                        editor.dom.add(editor.getBody(), 'script', {
                            src: '/build/assets/frontend/js/vendor/counter-up.js' // Add Bootstrap or your custom CSS
                        });
                        editor.dom.add(editor.getBody(), 'script', {
                            src: '/build/assets/frontend/js/vendor/waypoint.js' // Add Bootstrap or your custom CSS
                        });
                        editor.dom.add(editor.getBody(), 'script', {
                            src: '/build/assets/frontend/js/vendor/wow.js' // Add Bootstrap or your custom CSS
                        });
                        editor.dom.add(editor.getBody(), 'script', {
                            src: '/build/assets/frontend/js/vendor/parallax.js' // Add Bootstrap or your custom CSS
                        });
                        editor.dom.add(editor.getBody(), 'script', {
                            src: '/build/assets/frontend/js/vendor/gsap.js' // Add Bootstrap or your custom CSS
                        });
                        editor.dom.add(editor.getBody(), 'script', {
                            src: '/build/assets/frontend/js/vendor/scrolltrigger.js' // Add Bootstrap or your custom CSS
                        });
                        editor.dom.add(editor.getBody(), 'script', {
                            src: '/build/assets/frontend/js/vendor/smooth-scroll.min.js' // Add Bootstrap or your custom CSS
                        });
                        editor.dom.add(editor.getBody(), 'script', {
                            src: '/build/assets/frontend/js/vendor/split-text.js' // Add Bootstrap or your custom CSS
                        });
                        editor.dom.add(editor.getBody(), 'script', {
                            src: '/build/assets/frontend/js/vendor/metisMenu.min.js' // Add Bootstrap or your custom CSS
                        });
                        editor.dom.add(editor.getBody(), 'script', {
                            src: '/build/assets/frontend/js/plugins/theia-sticky-sidebar.min.js' // Add Bootstrap or your custom CSS
                        });
                        editor.dom.add(editor.getBody(), 'script', {
                            src: '/build/assets/frontend/js/plugins/resize-sensor.min.js' // Add Bootstrap or your custom CSS
                        });
                        editor.dom.add(editor.getBody(), 'script', {
                            src: 'build/assets/frontend/js/main.js' // Add Bootstrap or your custom CSS
                        });
                        // editor.dom.add(editor.getBody(), 'script', {
                        //     src: 'https://sindhttb.gov.pk/assets/frontend/js/bootstrap.bundle.min.js' // Add Bootstrap or your custom CSS
                        // });
                    });

                    // Add custom button to insert content
                    editor.addButton('add_advisor', {
                        type: 'menubutton',
                        text: 'Section',
                        icon: false,
                        menu: [{
                                text: 'Add More Team Member',
                                onclick: function() {
                                    var advisorItems = editor.dom.select('.row')[0];
                                    if (advisorItems) {
                                        var newContent = `
                                <div class="col-lg-4 col-md-6 single-item">
                                    <div class="advisor-item">
                                        <div class="info-box">
                                            <div class="editable-image">
                                                <img src="" alt="Thumb" class="advisor-img" data-name="advisor-1-image">
                                            </div>
                                            <div class="info-title">
                                                <h4 class="editable-text" contenteditable="true" data-name="advisor-1-name">Professor. Nuri Paul</h4>
                                                <span class="editable-text" contenteditable="true" data-name="advisor-1-title">Chemistry Specialist</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;
                                        advisorItems.innerHTML += newContent;
                                    } else {
                                        editor.insertContent(`
                                <div class="advisor-items text-center text-light">
                                    <div class="row">${newContent}</div>
                                </div>
                            `);
                                    }
                                }
                            },
                            {
                                text: 'Template',
                                onclick: function() {
                                    loadExternalAssets([{
                                            type: 'css',
                                            href: 'https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css'
                                        },
                                        {
                                            type: 'js',
                                            src: 'https://code.jquery.com/jquery-3.6.0.min.js'
                                        },
                                        {
                                            type: 'js',
                                            src: 'https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js'
                                        }
                                    ]);
                                    $('#exampleModal').modal('show');
                                }
                            }
                        ]
                    });
                }
            });
        }
        document.addEventListener('DOMContentLoaded', function() {
            let removeSectionRecord = document.querySelectorAll('.removeSectionRecord');
            removeSectionRecord.forEach(function(button) {
                button.addEventListener('click', function() {
                    const sectionId = this.getAttribute('data-id');
                    // $(`#uniq-${sectionId}`).html('');
                    let uniq = document.getElementById(`uniq-${sectionId}`);
                    uniq.remove();
                });
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('button[data-content]').forEach(button => {
                button.addEventListener('click', function() {
                    const contentId = this.getAttribute('data-content');
                    const templateContent = document.getElementById(contentId).innerHTML;
                    if (tinymce.activeEditor) {
                        tinymce.activeEditor.setContent(templateContent);
                    }
                    const modal = document.getElementById('exampleModal');
                    modal.hide();

                });
            });
        });

        function loadExternalAssets(files) {
            files.forEach(function(file) {
                if (file.type === 'css') {
                    var link = document.createElement('link');
                    link.rel = 'stylesheet';
                    link.href = file.href;
                    document.head.appendChild(link);
                } else if (file.type === 'js') {
                    var script = document.createElement('script');
                    script.src = file.src;
                    script.onload = function() {
                        console.log(file.src + ' loaded successfully.');
                    };
                    document.head.appendChild(script);
                }
            });
        }
    </script>

    <script src="{{ asset('build/assets/admin/js/custom/page.js') }}"></script>
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
                        url: '/pages/pageDelete',
                        data: {
                            _token: "{{ csrf_token() }}",
                            id: id,
                        },
                        success: function(response) {
                            Swal.fire(
                                'Deleted!',
                                'User has been deleted.',
                                'success'
                            );
                            setTimeout(() => {

                                window.location.reload();
                            }, 1000);

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
