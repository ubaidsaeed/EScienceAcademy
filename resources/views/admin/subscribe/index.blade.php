@extends('layouts.admin.app')
@push('style')
<style>
    .dataTables_wrapper .dataTables_filter {
        float: right;
        margin-bottom: 10px;
    }
    
    .dataTables_wrapper .dataTables_length {
        float: left;
        margin-bottom: 10px;
    }
    
    .dataTables_wrapper .dataTables_paginate {
        float: right;
        margin-top: 10px;
    }
    
    .dataTables_wrapper .dataTables_info {
        float: left;
        margin-top: 10px;
    }
    
    .btn-list {
        display: flex;
        gap: 5px;
    }
    
    .badge {
        font-size: 12px;
        padding: 5px 10px;
    }
    
    .badge-success {
        background-color: #28a745;
    }
    
    .badge-warning {
        background-color: #ffc107;
        color: #212529;
    }
    
    .badge-danger {
        background-color: #dc3545;
    }
    
    .badge-info {
        background-color: #17a2b8;
    }
    
    /* Style for select filters */
    .dataTables_wrapper th select {
        width: 100%;
        margin-top: 5px;
    }
</style>
@endpush

@section('content')
<div class="app-content main-content">
    <div class="side-app">
        <div class="page-header d-lg-flex d-block">
            <div class="page-leftheader">
                <h4 class="page-title">Subscriptions</h4>
            </div>
            <div class="page-rightheader">
                @if (session('success'))
                    <div class="alert alert-success alert-message fade show" role="alert" id="success-alert">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-message fade show" role="alert" id="error-alert">
                        {{ session('error') }}
                    </div>
                @endif
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">All Subscriptions</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" id="subscriptions-table" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>User Name</th>
                                        <th>Email</th>
                                        <th>Plan Name</th>
                                        <th>Price</th>
                                        <th>Status</th>
                                        <th>Payment Status</th>
                                        <!--<th>Start Date</th>-->
                                        <!--<th>End Date</th>-->
                                        <!--<th>Duration</th>-->
                                        <!--<th>Created At</th>-->
                                        <th>Actions</th>
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
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.bootstrap5.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.3.0/css/responsive.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css">

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.colVis.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.3.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.3.0/js/responsive.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        $('.alert-message').fadeOut('slow');
    }, 5000);

    // Initialize DataTable
    var table = $('#subscriptions-table').DataTable({
        processing: true, // Uncommented this
        serverSide: true,
        responsive: true,
        ajax: "{{ route('admin.subscribe.index') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
            {data: 'user_name', name: 'user_name'},
            {data: 'user_email', name: 'user_email'},
            {data: 'plan_name', name: 'plan_name'},
            {data: 'price', name: 'price', render: function(data) {
                return 'PKR' + parseFloat(data).toFixed(2);
            }},
            {data: 'status_badge', name: 'status'},
            {data: 'payment_status_badge', name: 'payment_status'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ],
        order: [[0, 'asc']], // Changed to order by index column
        pageLength: 10,
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
        
        language: {
            search: "_INPUT_",
            searchPlaceholder: "Search subscriptions...",
            lengthMenu: "Show _MENU_ entries",
            info: "Showing _START_ to _END_ of _TOTAL_ entries",
            infoEmpty: "Showing 0 to 0 of 0 entries",
            infoFiltered: "(filtered from _MAX_ total entries)",
            zeroRecords: "No matching records found",
            paginate: {
                first: "First",
                last: "Last",
                next: '<i class="fas fa-chevron-right"></i>',
                previous: '<i class="fas fa-chevron-left"></i>'
            }
        },
        drawCallback: function() {
            // Initialize tooltips
            $('[data-toggle="tooltip"]').tooltip();
        }
    });
});
</script>
@endpush