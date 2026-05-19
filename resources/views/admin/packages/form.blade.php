@extends('layouts.admin.app')

@section('title', isset($package) ? 'Edit Package: ' . $package->name : 'Create New Package')

@section('content')

    <div class="app-content main-content">
        <div class="side-app">
            <div class="container-fluid">
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">
                        {{ isset($package) ? 'Edit Package: ' . $package->name : 'Create New Package' }}
                    </h1>
                    <div>
                        <a href="{{ route('admin.packages.index') }}" class="btn btn-secondary">
                            Back to Packages
                        </a>
                        @if (isset($package))
                            <a href="{{ route('admin.packages.subjects', $package->id) }}" class="btn btn-info">
                                Manage Subjects
                            </a>
                        @endif
                    </div>
                </div>

                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Package Details</h6>
                    </div>
                    <div class="card-body">
                        <form
                            action="{{ isset($package) ? route('admin.packages.update', $package->id) : route('admin.packages.store') }}"
                            method="POST">
                            @csrf
                            @if (isset($package))
                                @method('PUT')
                            @endif

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">Package Name *</label>
                                        <input type="text" class="form-control" id="name" name="name"
                                            value="{{ old('name', $package->name ?? '') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="board_id">Board *</label>
                                        <!--<select name="board_id" id="board_id" class="form-control" required>-->
                                        <!--    <option value="">Select Board</option>-->
                                        <!--    @foreach ($boards as $board)-->
                                        <!--        <option value="{{ $board->id }}"-->
                                        <!--            {{ old('board_id', $package->board_id ?? '') == $board->id ? 'selected' : '' }}>-->
                                        <!--            {{ $board->name }}-->
                                        <!--        </option>-->
                                        <!--    @endforeach-->
                                        <!--</select>-->
                                         <select name="board_id" id="board_id" class="form-control" required>
                                            <option value="">Select Board</option>
                                            @foreach ($boards as $board)
                                                <option value="{{ $board->id }}"
                                                    {{ old('board_id', $package->board_id ?? '') == $board->id ? 'selected' : '' }}>
                                                    {{ $board->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="level_id">Level *</label>
                                        <!--<select name="level_id" id="level_id" class="form-control" required>-->
                                        <!--    <option value="">Select Level</option>-->
                                        <!--    @foreach ($levels as $level)-->
                                        <!--        <option value="{{ $level->id }}"-->
                                        <!--            {{ old('level_id', $package->level_id ?? '') == $level->id ? 'selected' : '' }}>-->
                                        <!--            {{ $level->name }}-->
                                        <!--        </option>-->
                                        <!--    @endforeach-->
                                        <!--</select>-->
                                        <select name="level_id" id="level_id" class="form-control" required disabled>
                                            <option value="">Select Level</option>
                                            @foreach ($levels as $level)
                                                <option value="{{ $level->id }}"
                                                    {{ old('level_id', $package->level_id ?? '') == $level->id ? 'selected' : '' }}>
                                                    {{ $level->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="price">Package Price (PKR) *</label>
                                        <input type="number" class="form-control" id="price" name="price"
                                            value="{{ old('price', $package->price ?? '') }}" step="0.01" min="0"
                                            required>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="duration">Duration *</label>
                                        <div class="input-group">
                                            <input type="number" class="form-control" id="duration" name="duration"
                                                value="{{ old('duration', $package->duration ?? '') }}" min="1"
                                                required>
                                            <select name="duration_type" class="form-control" required>
                                                <option value="days"
                                                    {{ old('duration_type', $package->duration_type ?? '') == 'days' ? 'selected' : '' }}>
                                                    Days</option>
                                                <option value="months"
                                                    {{ old('duration_type', $package->duration_type ?? '') == 'months' ? 'selected' : '' }}>
                                                    Months</option>
                                                <option value="years"
                                                    {{ old('duration_type', $package->duration_type ?? '') == 'years' ? 'selected' : '' }}>
                                                    Years</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Dynamic Features from DB -->
                            <div class="card shadow mb-4 mt-4">
                                <div class="card-header bg-success text-white">
                                    <h5>Features Included</h5>
                                </div>
                                <div class="card-body">
                                    @forelse(\App\Models\Feature::active()->orderBy('sort_order')->get() as $feature)
                                        <div class="form-check mb-3">
                                            <input class="form-check-input feature-checkbox" type="checkbox"
                                                name="features[]" value="{{ $feature->id }}"
                                                id="feature-{{ $feature->id }}"
                                                {{ isset($package) && $package->features->contains($feature->id) ? 'checked' : '' }}>
                                            <label class="form-check-label font-weight-bold"
                                                for="feature-{{ $feature->id }}">
                                                <i class="{{ $feature->icon ?? 'fas fa-check-circle' }}"></i>
                                                {{ $feature->name }}
                                                @if ($feature->has_content)
                                                    <small class="text-muted">(content assignment below)</small>
                                                @endif
                                            </label>
                                        </div>
                                    @empty
                                        <p class="text-muted">No features available.</p>
                                    @endforelse
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="content">Package Description</label>
                                <textarea class="form-control" id="content" name="content" rows="3">{{ old('content', $package->content ?? '') }}</textarea>
                            </div>

                            <div class="form-group mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="status" name="status"
                                        value="1" {{ old('status', $package->status ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="status">Active Package</label>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('admin.packages.index') }}" class="btn btn-secondary">Cancel</a>
                                <button type="submit" class="btn btn-primary">
                                    {{ isset($package) ? 'Update' : 'Create' }} Package
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
<script>
      // Initialize TinyMCE
        tinymce.init({
            selector: '#content',
            height: 300,
            plugins: 'image code table link media codesample lists',
            toolbar: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist | link image media | table',
            image_title: true,
            automatic_uploads: true,
            file_picker_types: 'image',
            file_picker_callback: function (cb, value, meta) {
                const input = document.createElement('input');
                input.setAttribute('type', 'file');
                input.setAttribute('accept', 'image/*');
                input.onchange = function () {
                    const file = this.files[0];
                    
                    // Create FormData for AJAX upload
                    const formData = new FormData();
                    formData.append('file', file);
                    formData.append('_token', '{{ csrf_token() }}');
                    
                    // Upload file via AJAX
                    $.ajax({
                        url: '{{ route("upload.image") }}', // You need to create this route
                        type: 'POST',
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            if (response.location) {
                                cb(response.location, { title: file.name });
                            }
                        },
                        error: function(xhr) {
                            console.error('Upload error:', xhr.responseText);
                        }
                    });
                };
                input.click();
            },
            content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'
        });
        // Function to load levels based on selected board
    function loadLevelsByBoard(boardId) {
        console.log('Loading levels for board:', boardId);
        
        if (!boardId) {
            $('#level_id').empty().append('<option value="">Select Level</option>')
                         .prop('disabled', true);
            return;
        }

        // Enable level dropdown
        $('#level_id').prop('disabled', false);
        
        // Show loading
        $('#level_id').empty().append('<option value="">Loading levels...</option>');
        
        // Make AJAX request to get levels
        $.ajax({
            url: '{{ route("admin.packages.get-levels-by-board") }}',
            type: 'POST',
            data: {
                board_id: boardId,
                _token: '{{ csrf_token() }}',
                @if(isset($package) && $package->level_id)
                    current_level_id: '{{ $package->level_id }}'
                @endif
            },
            success: function(response) {
                console.log('AJAX Response:', response);
                
                if (response.success) {
                    var $levelSelect = $('#level_id');
                    $levelSelect.empty();
                    
                    if (response.levels && response.levels.length > 0) {
                        $levelSelect.append('<option value="">Select Level</option>');
                        $.each(response.levels, function(index, level) {
                            $levelSelect.append('<option value="' + level.id + '">' + 
                                                level.name + 
                                                '</option>');
                        });
                        
                        // For edit mode, restore previously selected level
                        @if(isset($package) && $package->level_id)
                            $levelSelect.val('{{ $package->level_id }}').trigger('change');
                        @endif
                    } else {
                        $levelSelect.append('<option value="">No levels found for this board</option>');
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
                $('#level_id').empty().append('<option value="">Error loading levels</option>');
            }
        });
    }

    // Event listener for board selection change
    $('#board_id').on('change', function() {
        var selectedBoard = $(this).val();
        loadLevelsByBoard(selectedBoard);
    });

    // On page load, if board is already selected, load levels
    @if(isset($package) && $package->board_id)
        loadLevelsByBoard('{{ $package->board_id }}');
        $('#level_id').prop('disabled', false);
    @endif
</script>

@endpush