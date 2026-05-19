@extends('layouts.admin.app')

@section('title', 'Menus')

@push('style')
<link href="{{ asset('assets/admin/plugins/select2/css/select2.min.css') }}" rel="stylesheet" />
<style>
    .select2-container--default .select2-selection--single {
        height: 45px;
        padding: 10px;
    }
</style>
@endpush

@section('content')
<div class="page-header d-lg-flex d-block">
    <div class="page-leftheader">
        <h4 class="page-title">Menu's</h4>
    </div>
    <div class="page-rightheader">
        <div class="btn-list">
            @if($currentView === 'menu')
                <a href="{{ route('menus.create', ['type' => 'menu']) }}" class="btn btn-primary">Create Menu</a>
            @elseif($currentView === 'submenu')
                <a href="{{ route('menus.create', ['type' => 'submenu', 'menu_id' => $menuId]) }}" class="btn btn-primary">Create Sub Menu</a>
                <a href="{{ route('menus.index') }}" class="btn btn-danger">Back</a>
            @elseif($currentView === 'subchild')
                <a href="{{ route('menus.create', ['type' => 'subchild', 'menu_id' => $menuId, 'submenu_id' => $submenuId]) }}" class="btn btn-primary">Create Sub Child</a>
                <a href="{{ route('menus.submenu', ['menuId' => $menuId]) }}" class="btn btn-danger">Back</a>
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
                        @if($currentView === 'submenu')
                            <li class="breadcrumb-item active">Sub Menus</li>
                        @elseif($currentView === 'subchild')
                            <li class="breadcrumb-item"><a href="{{ route('menus.submenu', ['menuId' => $menuId]) }}">Sub Menus</a></li>
                            <li class="breadcrumb-item active">Sub Child</li>
                        @endif
                    </ol>
                </nav>

                <!-- Data Table -->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Page/Link</th>
                                <th>Priority</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $counter = ($records->currentPage() - 1) * $records->perPage() + 1; @endphp
                            @foreach($records as $record)
                                <tr>
                                    <td>{{ $counter++ }}</td>
                                    <td>{{ $record->name }}</td>
                                    <td>
                                        @if($record->page)
                                            {{ $record->page->title }}
                                        @elseif($record->link)
                                            {{ $record->link }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>{{ $record->priority }}</td>
                                    <td>
                                        <span class="badge bg-{{ $record->status === 'active' ? 'success' : 'danger' }}">
                                            {{ ucfirst($record->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            @if($currentView === 'menu')
                                                <a href="{{ route('menus.submenu', ['menuId' => $record->id]) }}" 
                                                   class="btn btn-sm btn-info" title="View Sub Menus">
                                                    <i class="fa fa-list"></i>
                                                </a>
                                            @elseif($currentView === 'submenu')
                                                <a href="{{ route('menus.subchild', ['menuId' => $menuId, 'submenuId' => $record->id]) }}" 
                                                   class="btn btn-sm btn-info" title="View Sub Child">
                                                    <i class="fa fa-sitemap"></i>
                                                </a>
                                            @endif
                                            
                                            <a href="{{ route('menus.edit', [
                                                'id' => $record->id,
                                                'type' => $currentView,
                                                'menu_id' => $currentView === 'submenu' || $currentView === 'subchild' ? $menuId : null,
                                                'submenu_id' => $currentView === 'subchild' ? $submenuId : null
                                            ]) }}" 
                                               class="btn btn-sm btn-warning" title="Edit">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            
                                            <button type="button" 
                                                    class="btn btn-sm btn-danger delete-btn" 
                                                    data-id="{{ $record->id }}" 
                                                    data-type="{{ $currentView }}"
                                                    data-name="{{ $record->name }}"
                                                    title="Delete">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    
                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $records->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script src="{{ asset('assets/admin/plugins/select2/js/select2.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        // Delete confirmation
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
                                    location.reload();
                                });
                            } else {
                                Swal.fire(
                                    'Error!',
                                    response.message,
                                    'error'
                                );
                            }
                        },
                        error: function() {
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
    });
</script>
@endpush