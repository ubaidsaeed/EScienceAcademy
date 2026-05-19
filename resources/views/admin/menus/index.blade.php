@extends('layouts.admin.app')

@section('title', 'Menus')

@push('style')
    <style>
        .select2-container--default .select2-selection--single {
            height: 45px;
            padding: 10px;
        }

        .dt-buttons {
            margin-bottom: 10px;
        }

        .dataTables_wrapper .dataTables_processing {
            background: rgba(255, 255, 255, 0.8);
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 20px;
        }
    </style>
@endpush

@section('content')
    <div class="app-content main-content">
        <div class="side-app">
            <div class="page-header d-lg-flex d-block">
                <div class="page-leftheader">
                    <h4 class="page-title">
                        @if ($currentView === 'menu')
                            Menu's
                        @elseif($currentView === 'submenu' && isset($menu))
                            Sub Menus - {{ $menu->name }}
                        @elseif($currentView === 'subchild' && isset($submenu))
                            Sub Child Menus - {{ $submenu->name }}
                        @endif
                    </h4>
                </div>
                <div class="page-rightheader">
                    <div class="btn-list">
                        @if ($currentView === 'menu')
                        @can('create header menu')
                            <a href="{{ route('menus.create', ['type' => 'menu']) }}" class="btn btn-primary">
                                <i class="fa fa-plus"></i> Create Menu
                            </a>
                            @endcan
                        @elseif($currentView === 'submenu')
                            <a href="{{ route('menus.create', ['type' => 'submenu', 'menu_id' => $menuId]) }}"
                                class="btn btn-primary">
                                <i class="fa fa-plus"></i> Create Sub Menu
                            </a>
                            <a href="{{ route('menus.index') }}" class="btn btn-danger">
                                <i class="fa fa-arrow-left"></i> Back
                            </a>
                        @elseif($currentView === 'subchild')
                            <a href="{{ route('menus.create', ['type' => 'subchild', 'menu_id' => $menuId, 'submenu_id' => $submenuId]) }}"
                                class="btn btn-primary">
                                <i class="fa fa-plus"></i> Create Sub Child
                            </a>
                            <a href="{{ route('menus.submenu', ['menuId' => $menuId]) }}" class="btn btn-danger">
                                <i class="fa fa-arrow-left"></i> Back
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <!-- Breadcrumb -->
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('menus.index') }}">Menus</a></li>
                                    @if ($currentView === 'submenu' && isset($menu))
                                        <li class="breadcrumb-item active">{{ $menu->name }}</li>
                                    @elseif($currentView === 'subchild' && isset($submenu))
                                        <li class="breadcrumb-item">
                                            <a href="{{ route('menus.submenu', ['menuId' => $menuId]) }}">Sub Menus</a>
                                        </li>
                                        <li class="breadcrumb-item active">{{ $submenu->name }}</li>
                                    @endif
                                </ol>
                            </nav>

                            <!-- Session Messages -->
                            

                            <!-- Data Table -->
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered border-bottom text-nowrap" id="menuDataTable">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Page/Link</th>
                                            <th>Priority</th>
                                            <th>Target Window</th>
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
            // Define DataTable URL based on current view
            let ajaxUrl;
            @if ($currentView === 'menu')
                ajaxUrl = "{{ route('menus.index') }}";
            @elseif ($currentView === 'submenu' && isset($menuId))
                ajaxUrl = "{{ route('menus.submenu', ['menuId' => $menuId]) }}";
            @elseif ($currentView === 'subchild' && isset($menuId) && isset($submenuId))
                ajaxUrl = "{{ route('menus.subchild', ['menuId' => $menuId, 'submenuId' => $submenuId]) }}";
            @endif

            console.log('AJAX URL:', ajaxUrl); // Debug log

            // Initialize DataTable
            var table = $('#menuDataTable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: {
                    url: ajaxUrl,
                    type: 'GET',
                    error: function(xhr, error, thrown) {
                        console.log('AJAX Error:', xhr.responseText);
                        console.log('Error:', error);
                        console.log('Thrown:', thrown);

                        // Show error in alert
                        Swal.fire({
                            icon: 'error',
                            title: 'DataTable Error',
                            text: 'Unable to load data. Please check console for details.',
                        });
                    }
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
                        name: 'name',
                        width: '15%'
                    },
                    {
                        data: 'page_link',
                        name: 'page_link',
                        width: '20%',
                        orderable: false
                    },
                    {
                        data: 'priority',
                        name: 'priority',
                        width: '10%'
                    },
                    {
                        data: 'target_window_badge',
                        name: 'target_window',
                        width: '10%'
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
                order: [
                    [0, 'asc']
                ],
                pageLength: 10,
                lengthMenu: [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, "All"]
                ],
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
                    // Re-initialize delete buttons after table redraw
                    initDeleteButtons();

                    // Debug: Log data
                    console.log('Data loaded:', settings.json);
                },
                initComplete: function(settings, json) {
                    console.log('DataTable initialized:', json);
                }
            });

            // Delete button handler
            // In your blade file's script section
function initDeleteButtons() {
    $('.delete-btn').on('click', function() {
        const id = $(this).data('id');
        const type = $(this).data('type');
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
                    url: '{{ route("menus.destroy") }}',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: id,
                        type: type
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire(
                                'Deleted!',
                                response.message,
                                'success'
                            ).then(() => {
                                // Reload DataTable
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
                    error: function(xhr, status, error) {
                        console.log('Delete error:', xhr.responseText);
                        
                        // Try to parse JSON error
                        try {
                            const response = JSON.parse(xhr.responseText);
                            Swal.fire(
                                'Error!',
                                response.message || 'An error occurred while deleting.',
                                'error'
                            );
                        } catch (e) {
                            // If response is HTML (debug page), show generic error
                            Swal.fire(
                                'Error!',
                                'An internal server error occurred. Please check the console for details.',
                                'error'
                            );
                        }
                    }
                });
            }
        });
    });
}

            // Initialize delete buttons on page load
            initDeleteButtons();

            // Test AJAX call
            $.ajax({
                url: ajaxUrl,
                type: 'GET',
                success: function(data) {
                    console.log('Direct AJAX success:', data);
                },
                error: function(xhr, status, error) {
                    console.log('Direct AJAX error:', xhr.responseText);
                    console.log('Status:', status);
                    console.log('Error:', error);
                }
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
