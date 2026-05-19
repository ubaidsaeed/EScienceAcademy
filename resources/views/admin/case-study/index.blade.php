@extends('layouts.admin.app')

@section('title', 'Case Studies')

@push('style')
<style>
    .select2-container--default .select2-selection--single {
        height: 45px;
        padding: 10px;
    }
    .dt-buttons {
        margin-bottom: 10px;
    }
</style>
@endpush

@section('content')
<div class="app-content main-content">
    <div class="side-app">
    <div class="page-header d-lg-flex d-block">
        <div class="page-leftheader">
            <h4 class="page-title">Case Studies</h4>
        </div>
        <div class="page-rightheader">
           
            
            <div class="btn-list">
                <a href="{{ route('case-studies.create') }}" class="btn btn-primary">
                   <i class="fa fa-plus me-2"></i>Create Case Study
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered border-bottom text-nowrap" id="caseStudiesTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Image</th>
                                    <th>Title</th>
                                    <th>Boards</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
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
        var table = $('#caseStudiesTable').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: "{{ route('case-studies.index') }}",
            columns: [
                { 
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false,
                    width: '5%'
                },
                { 
                    data: 'image_preview',
                    name: 'image',
                    orderable: false,
                    searchable: false,
                    width: '10%'
                },
                { 
                    data: 'title',
                    name: 'title',
                    width: '25%'
                },
                { 
                    data: 'boards_list',
                    name: 'boards.name',
                    width: '20%'
                },
                { 
                    data: 'status_badge',
                    name: 'status',
                    width: '10%'
                },
                { 
                    data: 'created_at_formatted',
                    name: 'created_at',
                    width: '15%'
                },
                { 
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    width: '15%'
                }
            ],
            order: [[0, 'asc']],
            pageLength: 10,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search...",
                lengthMenu: "_MENU_ records per page",
                zeroRecords: "No data found",
                info: "Showing _START_ to _END_ of _TOTAL_ entries",
                infoEmpty: "Showing 0 to 0 of 0 entries",
                infoFiltered: "(filtered from _MAX_ total entries)",
                paginate: {
                    first: "First",
                    last: "Last",
                    next: "Next",
                    previous: "Previous"
                }
            },
            drawCallback: function(settings) {
                initDeleteButtons();
            }
        });

        // Delete button handler
        function initDeleteButtons() {
            $('.delete-btn').on('click', function() {
                const id = $(this).data('id');
                const name = $(this).data('name');
                
                Swal.fire({
                    title: 'Are you sure?',
                    text: `You want to delete "${name}"?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ route("case-studies.destroy") }}',
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                id: id
                            },
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire(
                                        'Deleted!',
                                        response.message,
                                        'success'
                                    ).then(() => {
                                        table.ajax.reload(null, false);
                                    });
                                } else {
                                    Swal.fire(
                                        'Error!',
                                        response.message,
                                        'error'
                                    );
                                }
                            },
                            error: function(xhr) {
                                console.log('Delete error:', xhr.responseText);
                                Swal.fire(
                                    'Error!',
                                    'An error occurred while deleting.',
                                    'error'
                                );
                            }
                        });
                    }
                });
            });
        }

        // Initialize delete buttons
        initDeleteButtons();
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