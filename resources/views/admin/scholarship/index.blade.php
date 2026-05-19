@extends('layouts.admin.app')
@section('title', 'Scholarship Requests')

@section('content')
    <div class="app-content main-content">
        <div class="side-app">
            <div class="page-header d-lg-flex d-block">
                <div class="page-leftheader">
                    <h4 class="page-title">Scholarship Requests</h4>
                </div>
                <div class="page-rightheader ms-md-auto">
                    <div class="btn-group">
                        {{-- <a href="{{ route('admin.scholarship.create') }}" class="btn btn-primary">
                            <i class="fa fa-plus"></i> Add New
                        </a> --}}
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">All Scholarship Requests</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="scholarship-table" class="table table-striped table-bordered border-bottom text-nowrap">
                                    <thead>
                                        <tr>
                                            <th class="border-bottom-0">S.No</th>
                                            <th class="border-bottom-0">Name</th>
                                            <th class="border-bottom-0">Father Name</th>
                                            <th class="border-bottom-0">Contact No</th>
                                            <th class="border-bottom-0">Email</th>
                                            <th class="border-bottom-0">Files</th>
                                            <th class="border-bottom-0">Achievements</th>
                                            <th class="border-bottom-0">Submitted At</th>
                                            <th class="border-bottom-0">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Data will be loaded via AJAX -->
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
            var table = $('#scholarship-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.scholarship.index') }}",
                    type: "GET"
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        width: '5%'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'father_name',
                        name: 'father_name'
                    },
                    {
                        data: 'contact_no',
                        name: 'contact_no'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'files',
                        name: 'files',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'achievements_count',
                        name: 'achievements_count',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'created_at_formatted',
                        name: 'created_at'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        width: '15%'
                    }
                ],
                order: [
                    [7, 'desc']
                ], // Sort by created_at descending
                pageLength: 10,
                lengthMenu: [
                    [10, 25, 50, 100],
                    [10, 25, 50, 100]
                ],
                language: {
                    search: "Search:",
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ entries",
                    infoEmpty: "Showing 0 to 0 of 0 entries",
                    infoFiltered: "(filtered from _MAX_ total entries)",
                    zeroRecords: "No matching records found",
                    paginate: {
                        first: "First",
                        last: "Last",
                        next: "Next",
                        previous: "Previous"
                    }
                }
            });
            // Export buttons (optional)
            var buttons = new $.fn.dataTable.Buttons(table, {
                buttons: [{
                        extend: 'copy',
                        text: '<i class="fa fa-copy"></i> Copy',
                        className: 'btn btn-outline-primary btn-sm',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7]
                        }
                    },
                    {
                        extend: 'csv',
                        text: '<i class="fa fa-file-csv"></i> CSV',
                        className: 'btn btn-outline-primary btn-sm',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7]
                        }
                    },
                    {
                        extend: 'excel',
                        text: '<i class="fa fa-file-excel"></i> Excel',
                        className: 'btn btn-outline-primary btn-sm',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7]
                        }
                    },
                    {
                        extend: 'pdf',
                        text: '<i class="fa fa-file-pdf"></i> PDF',
                        className: 'btn btn-outline-primary btn-sm',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7]
                        }
                    },
                    {
                        extend: 'print',
                        text: '<i class="fa fa-print"></i> Print',
                        className: 'btn btn-outline-primary btn-sm',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7]
                        },
                        customize: function(win) {
                            $(win.document.body).find('h1').css('text-align', 'center');
                            $(win.document.body).find('table').addClass('display').css('font-size',
                                '9px');
                        }
                    }
                ]
            }).container().appendTo('#scholarship-table_wrapper .col-md-6:eq(0)');
        });
        // Update the delete button click handler
        $(document).on('click', '.delete-btn', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            var name = $(this).data('name');

            Swal.fire({
                title: 'Are you sure?',
                html: `<p>You are about to delete scholarship request for:</p><p><strong>${name}</strong></p><p>This action cannot be undone!</p>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel',
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    return $.ajax({
                        url: "{{ route('admin.scholarship.destroy', ':id') }}".replace(':id',
                            id),
                        type: 'DELETE',
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            return response;
                        },
                        error: function(xhr) {
                            throw new Error(xhr.responseJSON.message || 'Delete failed');
                        }
                    });
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    if (result.value.success) {
                        // Refresh the table
                        table.ajax.reload();

                        Swal.fire({
                            title: 'Deleted!',
                            text: result.value.message,
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    } else {
                        Swal.fire(
                            'Error!',
                            result.value.message,
                            'error'
                        );
                    }
                }
            }).catch(error => {
                Swal.fire(
                    'Error!',
                    error.message,
                    'error'
                );
            });
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
