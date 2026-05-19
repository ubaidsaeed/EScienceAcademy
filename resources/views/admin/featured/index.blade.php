@extends('layouts.admin.app')

@section('title', 'Manage Features')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="app-content main-content">
        <div class="side-app">
            <div class="container-fluid pt-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="page-title">Manage Features</h3>
                </div>

                <!-- Add New Feature -->
                <div class="card shadow mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Add New Feature</h5>
                    </div>
                    <div class="card-body">
                        <form id="createFeatureForm" action="{{ route('features.store') }}" method="POST">
                            @csrf
                            <div class="row g-3 align-items-end">
                                <div class="col-md-2 d-none">
                                    <label class="form-label">Key</label>
                                    <input type="text" name="key" class="form-control"
                                        placeholder="e.g. online_notes" >
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Name</label>
                                    <input type="text" name="name" class="form-control"
                                        placeholder="e.g. Online Notes" required>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Icon (FontAwesome)</label>
                                    <input type="text" name="icon" class="form-control"
                                        placeholder="e.g. fas fa-book">
                                </div>
                                <div class="col-md-2">
                                    <div class="form-check mb-2 d-none">
                                        <input class="form-check-input" type="checkbox" name="has_content"
                                            id="has_content_new">
                                        <label class="form-check-label" for="has_content_new">
                                            Has Content?
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="status" id="status_new"
                                            >
                                        <label class="form-check-label" for="status_new">
                                            Active
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    @can('create feature')
                                    <button type="submit" class="btn btn-success w-100" id="createFeatureBtn">
                                        <span class="spinner-border spinner-border-sm d-none" id="createSpinner"></span>
                                        <span id="createBtnText">Add Feature</span>
                                    </button>
                                    @endcan
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Features List (Drag to Reorder) -->
                <div class="card shadow">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0">Drag to Reorder Features</h5>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group sortable" id="features-list">
                            @forelse($features as $feature)
                                <li class="list-group-item d-flex align-items-center justify-content-between"
                                    data-id="{{ $feature->id }}">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-grip-lines me-3 text-muted cursor-move"></i>
                                        <div>
                                            <strong>{{ $feature->name }}</strong>
                                            <small class="text-muted">({{ $feature->key }})</small>
                                            @if ($feature->icon)
                                                <i class="{{ $feature->icon }} ms-2"></i>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="d-flex align-items-center gap-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input toggle-status" type="checkbox"
                                                data-id="{{ $feature->id }}" {{ $feature->status ? 'checked' : '' }}>
                                        </div>
                                        @can('delete feature')
                                        <button class="btn btn-sm btn-danger delete-feature" data-id="{{ $feature->id }}">
                                            <i class="fas fa-trash"></i> Remove
                                        </button>
                                        @endcan
                                    </div>
                                </li>
                            @empty
                                <li class="list-group-item text-center text-muted py-4">
                                    No features found.
                                </li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('style')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endpush

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

    <script>
        // CSRF Token
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        // Toast Notification (Success/Error)
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);
            }
        });

        // Confirmation Modal Style
        const Confirm = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-danger me-2',
                cancelButton: 'btn btn-secondary'
            },
            buttonsStyling: false,
            reverseButtons: true
        });

        // CREATE FEATURE
        $('#createFeatureForm').on('submit', function(e) {
            e.preventDefault();

            const $btn = $('#createFeatureBtn');
            const $spinner = $('#createSpinner');
            const $text = $('#createBtnText');

            $btn.prop('disabled', true);
            $spinner.removeClass('d-none');
            $text.text('Adding...');

            $.ajax({
                url: this.action,
                method: 'POST',
                data: $(this).serialize(),
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function(res) {
                    if (res.success) {
                        Toast.fire({
                            icon: 'success',
                            title: res.message || 'Feature added successfully!'
                        });
                        setTimeout(() => location.reload(), 800);
                    }
                },
                error: function(xhr) {
                    const msg = xhr.responseJSON?.errors ?
                        Object.values(xhr.responseJSON.errors)[0][0] :
                        xhr.responseJSON?.message || 'Something went wrong!';
                    Toast.fire({
                        icon: 'error',
                        title: msg
                    });
                },
                complete: function() {
                    $btn.prop('disabled', false);
                    $spinner.addClass('d-none');
                    $text.text('Add Feature');
                }
            });
        });

        // DRAG & DROP REORDER
        new Sortable(document.getElementById('features-list'), {
            handle: '.cursor-move',
            animation: 180,
            ghostClass: 'bg-light',
            onEnd: function() {
                const order = Array.from(this.el.children)
                    .map(li => li.dataset.id)
                    .filter(id => id);

                $.ajax({
                    url: '{{ route('features.reorder') }}',
                    method: 'POST',
                    data: {
                        order
                    },
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: res => {
                        if (res.success) {
                            Toast.fire({
                                icon: 'success',
                                title: res.message || 'Order updated!'
                            });
                        }
                    },
                    error: () => Toast.fire({
                        icon: 'error',
                        title: 'Failed to save order'
                    })
                });
            }
        });

        // TOGGLE STATUS
        $(document).on('change', '.toggle-status', function() {
            const id = this.dataset.id;
            const wasChecked = this.checked;

            $.ajax({
                url: '{{ url('/features') }}/' + id + '/toggle-status',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function(res) {
                    if (res.success) {
                        Toast.fire({
                            icon: 'success',
                            title: `Feature is now ${wasChecked ? 'active' : 'inactive'}`
                        });
                    } else {
                        throw new Error();
                    }
                },
                error: function() {
                    // Revert checkbox
                    $(`input[data-id="${id}"]`).prop('checked', !wasChecked);
                    Toast.fire({
                        icon: 'error',
                        title: 'Failed to update status'
                    });
                }
            });
        });

        // DELETE FEATURE - Beautiful Confirmation + Success
        $(document).on('click', '.delete-feature', function() {
            const id = this.dataset.id;
            const name = $(this).closest('li').find('strong').text().trim();
            const $row = $(this).closest('li');

            Confirm.fire({
                title: 'Delete Feature?',
                html: `Are you sure you want to delete <strong>"${name}"</strong>?<br><small>This action cannot be undone.</small>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: '<i class="fas fa-trash"></i> Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ url('/features') }}/' + id,
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        },
                        success: function(res) {
                            if (res.success) {
                                $row.fadeOut(400, function() {
                                    $(this).remove();
                                });

                                Toast.fire({
                                    icon: 'success',
                                    title: res.message || 'Feature deleted!'
                                });
                            }
                        },
                        error: function() {
                            Toast.fire({
                                icon: 'error',
                                title: 'Failed to delete feature'
                            });
                        }
                    });
                }
            });
        });
    </script>
@endpush
