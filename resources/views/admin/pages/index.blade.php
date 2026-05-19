@extends('layouts.admin.app')
@section('title', 'Pages')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="app-content main-content">
    <div class="side-app">
        <div class="page-header d-lg-flex d-block">
            <div class="page-leftheader">
                <h4 class="page-title">Pages</h4>
            </div>
            <div class="page-rightheader ms-md-auto">
                <div class="btn-list">
                    @can('create page')
                    <a href="{{ route('pages.create') }}" class="btn btn-primary">
                        <i class="fa fa-plus me-2"></i>Create Page
                    </a>
                    @endcan
                </div>
            </div>
        </div>
        
        
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="pages-datatable" class="table table-striped table-bordered border-bottom text-nowrap">
                                <thead>
                                    <tr>
                                        <th class="border-bottom-0">ID</th>
                                        <th class="border-bottom-0">Thumbnail</th>
                                        <th class="border-bottom-0">Title</th>
                                        <th class="border-bottom-0">Slug</th>
                                        <th class="border-bottom-0">Status</th>
                                        <th class="border-bottom-0">Actions</th>
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
        $('#pages-datatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route("pages.show") }}',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'thumbnail', name: 'thumbnail', orderable: false, searchable: false },
                { data: 'title', name: 'title' },
                { data: 'slug', name: 'slug' },
                { data: 'status', name: 'status' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });
    });
    
    function deletePage(id) {
        if (confirm('Are you sure you want to delete this page?')) {
            $.ajax({
                url: '{{ route("pages.destroy") }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    id: id
                },
                success: function(response) {
                    alert(response.success);
                    $('#pages-datatable').DataTable().ajax.reload();
                },
                error: function(xhr) {
                    alert(xhr.responseJSON?.error || 'Failed to delete page');
                }
            });
        }
    }
    </script>
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