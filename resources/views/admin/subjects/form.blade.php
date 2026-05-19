@extends('layouts.admin.app')
@section('title', $page_type == 'create' ? 'Create Subject' : 'Edit Subject')

@section('content')
<div class="app-content main-content">
    <div class="side-app">
        <div class="page-header d-lg-flex d-block">
            <div class="page-leftheader">
                <h4 class="page-title">
                    @if($page_type == 'create')
                        Create Subject
                    @else
                        Edit Subject
                    @endif
                </h4>
            </div>
            <div class="page-rightheader">
                <a href="{{ route('subjects.index') }}" class="btn btn-secondary">
                    <i class="fa fa-arrow-left"></i> Back to List
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ $form_url }}" method="POST" enctype="multipart/form-data" id="subjectForm">
                            @csrf
                            
                            @if($page_type == 'edit')
                                @method('PUT')
                                <input type="hidden" name="id" value="{{ $subject->id }}">
                            @endif

                            <div class="row">
                                <div class="mb-3 col-lg-6">
                                    <label for="name" class="form-label">Name *</label>
                                    <input type="text" class="form-control shadow-none @error('name') is-invalid @enderror" 
                                           id="name" name="name" 
                                           value="{{ old('name', $subject->name ?? '') }}" 
                                           placeholder="Enter Subject Name" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3 col-lg-6">
                                    <label for="board" class="form-label">Board *</label>
                                    <select class="form-control select2-show-search @error('board') is-invalid @enderror" 
                                            id="board" name="board[]" multiple required>
                                        <option value="" disabled>Choose Board...</option>
                                        @foreach($boards as $board)
                                            @if($board->parent_id == null)
                                                <option value="{{ $board->id }}"
                                                   @isset($existingRecords) {{ in_array($board->id, old('board', $existingRecords->pluck('board_id')->toArray())) ? 'selected' : '' }} @endisset>
                                                    {{ $board->name }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                    @error('board')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3 col-lg-6">
                                    <label for="level" class="form-label">Level *</label>
                                    <select class="form-control select2-show-search @error('level') is-invalid @enderror" 
                                            id="level" name="level[]" multiple disabled required>
                                        <option value="" disabled>Choose Level...</option>
                                        <!-- Levels will be loaded dynamically via AJAX -->
                                        @if($page_type == 'edit' && isset($existingLevels))
                                            @foreach($existingLevels as $level)
                                                <option value="{{ $level->id }}" selected>
                                                    {{ $level->name }} ({{ $level->board->name ?? 'N/A' }})
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <small class="text-muted">Please select board(s) first to see available levels</small>
                                    @error('level')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3 col-lg-6">
                                    <label for="image_url" class="form-label">
                                        Image 
                                        @if($page_type == 'edit' && $subject->image_url)
                                            <br><small class="text-success ">Current image uploaded</small>
                                        @endif
                                    </label>
                                    
                                    @if($page_type == 'edit' && $subject->image_url)
                                        <div class="mb-2">
                                            <img src="{{ asset('storage/app/public/subject/' . $subject->image_url) }}" 
                                                 alt="{{ $subject->name }}" 
                                                 style="width: 100px; height: 100px; object-fit: cover; border-radius: 4px;" 
                                                 class="img-thumbnail mb-2">
                                            <br>
                                            <a href="{{ asset('storage/app/public/subject/' . $subject->image_url) }}" 
                                               target="_blank" class="btn btn-sm btn-outline-primary">
                                                <i class="fa fa-eye"></i> View
                                            </a>
                                            <div class="form-check mt-2">
                                                <input class="form-check-input" type="checkbox" name="remove_image" value="1" id="removeImage">
                                                <label class="form-check-label text-danger" for="removeImage">
                                                    Remove current image
                                                </label>
                                            </div>
                                        </div>
                                    @endif
                                    
                                    <input type="file" class="form-control shadow-none @error('image_url') is-invalid @enderror" 
                                           id="image_url" name="image_url">
                                    <small class="text-muted">
                                        Allowed: jpeg, png, jpg, gif, webp. Max: 2MB
                                        @if($page_type == 'create')
                                            (Required)
                                        @else
                                            (Optional - Leave empty to keep existing image)
                                        @endif
                                    </small>
                                    @error('image_url')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3 col-lg-6">
                                    <label for="status" class="form-label">Status *</label>
                                    <select class="form-control select2-show-search custom-select @error('status') is-invalid @enderror" 
                                            id="status" name="status" required>
                                        <option value="" disabled>Choose...</option>
                                        <option value="active" 
                                            {{ old('status', $subject->status ?? '') == 'active' ? 'selected' : '' }}>
                                            Active
                                        </option>
                                        <option value="inactive"
                                            {{ old('status', $subject->status ?? '') == 'inactive' ? 'selected' : '' }}>
                                            Inactive
                                        </option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="modal-footer">
                                 <a href="{{ route('subjects.index') }}" class="btn btn-secondary">Cancel</a>
                                <button type="submit" class="btn btn-primary shadow-none">
                                    @if($page_type == 'create')
                                        <i class="fa fa-save"></i> {{ $button }}
                                    @else
                                        <i class="fa fa-sync"></i> {{ $button }}
                                    @endif
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('style')
<link rel="stylesheet" href="{{ asset('assets/plugins/select2/select2.min.css') }}">
@endpush

@push('script')
<script src="{{ asset('assets/plugins/select2/select2.full.min.js') }}"></script>
<script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>

<script>
$(document).ready(function() {
    // Initialize Select2
    $('#board').select2({
        placeholder: 'Select Board',
        allowClear: false,
        width: '100%'
    });
    
    $('#level').select2({
        placeholder: 'Select Level',
        allowClear: false,
        width: '100%'
    });
    
    $('#status').select2({
        placeholder: 'Select Status',
        allowClear: false,
        width: '100%'
    });
    
    // Function to load levels based on selected boards
    function loadLevelsByBoards(boardIds) {
        console.log('Loading levels for boards:', boardIds);
        
        if (!boardIds || boardIds.length === 0) {
            $('#level').empty().append('<option value="" disabled>Choose Level...</option>')
                      .prop('disabled', true)
                      .trigger('change');
            return;
        }

        // Enable level dropdown
        $('#level').prop('disabled', false);
        
        // Show loading
        $('#level').empty().append('<option value="">Loading levels...</option>');
        
        // Make AJAX request to get levels
        $.ajax({
            url: '{{ route("subjects.levels.get-by-boards") }}',
            type: 'POST',
            data: {
                board_ids: boardIds,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                console.log('AJAX Response:', response);
                
                if (response.success) {
                    var $levelSelect = $('#level');
                    $levelSelect.empty();
                    
                    if (response.levels && response.levels.length > 0) {
                        $levelSelect.append('<option value="" disabled>Choose Level...</option>');
                        $.each(response.levels, function(index, level) {
                            $levelSelect.append('<option value="' + level.id + '">' + 
                                                level.name + ' (' + (level.board_name || 'N/A') + ')' + 
                                                '</option>');
                        });
                        
                        // For edit mode, restore previously selected levels
                        @if($page_type == 'edit' && isset($existingLevels))
                            var existingLevelIds = {!! isset($existingLevels) ? $existingLevels->pluck('id')->toJson() : '[]' !!};
                            $levelSelect.val(existingLevelIds).trigger('change');
                        @endif
                    } else {
                        $levelSelect.append('<option value="" disabled>No levels found for selected boards</option>');
                    }
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Info',
                        text: response.message || 'No levels available'
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', xhr.responseText);
                
                Swal.fire({
                    icon: 'error',
                    title: 'Server Error',
                    text: 'Failed to load levels. Please try again.'
                });
                $('#level').empty().append('<option value="" disabled>Error loading levels</option>');
            }
        });
    }

    // Event listener for board selection change
    $('#board').on('change', function() {
        var selectedBoards = $(this).val() || [];
        loadLevelsByBoards(selectedBoards);
    });

    // On page load, if boards are already selected, load levels
    @if($page_type == 'edit' && isset($existingRecords))
        var selectedBoards = {!! $existingRecords->pluck('board_id')->toJson() !!};
        console.log('Initial selected boards:', selectedBoards);
        if (selectedBoards && selectedBoards.length > 0) {
            $('#board').val(selectedBoards).trigger('change');
            $('#level').prop('disabled', false);
        }
    @endif
    
    // Form validation
    $('#subjectForm').on('submit', function(e) {
        var isValid = true;
        
        // Reset previous error states
        $('.is-invalid').removeClass('is-invalid');
        
        // Validate required fields
        var requiredFields = ['#name', '#status'];
        requiredFields.forEach(function(field) {
            if (!$(field).val()) {
                $(field).addClass('is-invalid');
                isValid = false;
            }
        });
        
        // Validate boards
        if (!$('#board').val() || $('#board').val().length === 0) {
            $('#board').addClass('is-invalid');
            isValid = false;
        }
        
        // Validate levels
        if (!$('#level').val() || $('#level').val().length === 0) {
            $('#level').addClass('is-invalid');
            isValid = false;
        }
        
        if (!isValid) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                text: 'Please fill all required fields correctly.',
            });
        }
    });

    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        $('.alert').alert('close');
    }, 5000);
});
</script>

@if (session()->has('success'))
    <script>
        setTimeout(() => {
            const Toast = Swal.mixin({
                toast: true,
                position: "top-end",
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.onmouseenter = Swal.stopTimer;
                    toast.onmouseleave = Swal.resumeTimer;
                }
            });
            Toast.fire({
                icon: "success",
                title: '{{ session('success') }}'
            });
        }, 500);
    </script>
@endif

@if (session()->has('error'))
    <script>
        setTimeout(() => {
            const Toast = Swal.mixin({
                toast: true,
                position: "top-end",
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.onmouseenter = Swal.stopTimer;
                    toast.onmouseleave = Swal.resumeTimer;
                }
            });
            Toast.fire({
                icon: "error",
                title: '{{ session('error') }}'
            });
        }, 500);
    </script>
@endif
@endpush