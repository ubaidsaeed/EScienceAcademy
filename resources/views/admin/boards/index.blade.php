@extends('layouts.admin.app')
@section('title', 'Boards')

@section('content')
    <div class="app-content main-content">
        <div class="side-app">
            <!-- Row -->
            <div class="page-header d-lg-flex d-block">
                <div class="page-leftheader">
                    <h4 class="page-title">Board's</h4>
                </div>
                <div class="page-rightheader">
                    @can('view boards')
                    <div class="btn-list">
                        <a href="{{ route('boards.create') }}" class="btn btn-primary">
                            <i class="fa fa-plus"></i> Create Board
                        </a>
                    </div>
                    @endcan
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="boards-table" class="table table-bordered text-nowrap border-bottom w-100">
                                    <thead>
                                        <tr>
                                            <th>S.No</th>
                                            <th>Image</th>
                                            <th>Name</th>
                                            <th>Status</th>
                                            <th>Created At</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('script')
    <script>
        $(document).ready(function() {
            // Initialize DataTable
            var table = $('#boards-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('boards.index') }}",
                    type: "GET",
                    dataSrc: function(json) {
                        console.log("Data received:", json);
                        return json.data || [];
                    },
                    error: function(xhr, error, thrown) {
                        console.error('AJAX Error Details:');
                        console.error('Status:', xhr.status);
                        console.error('Response:', xhr.responseText);
                        console.error('Error:', error);
                        console.error('Thrown:', thrown);

                        // Show user-friendly error
                        Swal.fire({
                            icon: 'error',
                            title: 'Data Loading Failed',
                            html: 'Unable to load board data.<br>Please try refreshing the page.<br><br>' +
                                '<small>Error: ' + (xhr.responseJSON?.error || xhr.statusText) +
                                '</small>',
                            confirmButtonText: 'Refresh',
                            showCancelButton: true,
                            cancelButtonText: 'Close'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                location.reload();
                            }
                        });
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                        width: '5%'
                    },
                    {
                        data: 'image_preview',
                        name: 'image_preview',
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                        width: '10%'
                    },
                    {
                        data: 'name',
                        name: 'name',
                        width: '25%'
                    },
                    {
                        data: 'status_badge',
                        name: 'status',
                        className: 'text-center',
                        width: '10%'
                    },
                    {
                        data: 'created_at_formatted',
                        name: 'created_at',
                        className: 'text-center',
                        width: '15%'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                        width: '15%'
                    }
                ],
                order: [
                    [0, 'desc']
                ],
                pageLength: 10,
                lengthMenu: [
                    [10, 25, 50, 100],
                    [10, 25, 50, 100]
                ],
                language: {
                    search: "Search:",
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ entries",
                    infoEmpty: "No records available",
                    infoFiltered: "(filtered from _MAX_ total entries)",
                    zeroRecords: "No matching records found",
                    paginate: {
                        first: "First",
                        last: "Last",
                        next: "Next",
                        previous: "Previous"
                    }
                },
                initComplete: function() {
                    console.log("DataTable initialized successfully");
                }
            });

            // Delete button functionality
            $(document).on('click', '.delete-btn', function(e) {
                e.preventDefault();
                var id = $(this).data('id');
                var name = $(this).data('name');
                var deleteUrl = "{{ route('boards.destroy', ':id') }}".replace(':id', id);

                Swal.fire({
                    title: 'Are you sure?',
                    html: `Delete board: <strong>${name}</strong>?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: deleteUrl,
                            type: 'DELETE',
                            data: {
                                _token: "{{ csrf_token() }}"
                            },
                            success: function(response) {
                                if (response.success) {
                                    table.ajax.reload(null, false);
                                    Swal.fire({
                                        title: 'Deleted!',
                                        text: response.message,
                                        icon: 'success',
                                        timer: 2000,
                                        showConfirmButton: false
                                    });
                                } else {
                                    Swal.fire('Error!', response.message, 'error');
                                }
                            },
                            error: function(xhr) {
                                Swal.fire('Error!', xhr.responseJSON?.message ||
                                    'Delete failed', 'error');
                            }
                        });
                    }
                });
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
                    },
                });
                Toast.fire({
                    icon: "success",
                    title: '{{ session()->get('success') }}',
                });
            }, 3000);
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
                    },
                });
                Toast.fire({
                    icon: "error",
                    title: '{{ session()->get('error') }}',
                });
            }, 3000);
        </script>
    @endif
@endpush
