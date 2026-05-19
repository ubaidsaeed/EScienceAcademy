@extends('layouts.admin.app')

@section('title', 'File Manager')

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Breadcrumbs -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('file-manager.index') }}">
                        <i class="bi bi-house-door"></i> Root
                    </a>
                </li>
                @foreach($breadcrumbs as $crumb)
                    <li class="breadcrumb-item">
                        <a href="{{ route('file-manager.index', ['folder_id' => $crumb->id]) }}">
                            {{ $crumb->name }}
                        </a>
                    </li>
                @endforeach
                @if($currentFolder)
                    <li class="breadcrumb-item active">{{ $currentFolder->name }}</li>
                @endif
            </ol>
        </nav>
        
        <!-- Toolbar -->
        <div class="card mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#uploadModal">
                            <i class="bi bi-upload"></i> Upload
                        </button>
                        <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#folderModal">
                            <i class="bi bi-folder-plus"></i> New Folder
                        </button>
                    </div>
                    <div class="w-25">
                        <form action="{{ route('file-manager.search') }}" method="GET">
                            <div class="input-group input-group-sm">
                                <input type="text" name="q" class="form-control" placeholder="Search...">
                                <button class="btn btn-outline-secondary" type="submit">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- File List -->
        <div class="card">
            <div class="card-body">
                @if($items->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th width="5%"></th>
                                    <th>Name</th>
                                    <th>Size</th>
                                    <th>Modified</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($items as $item)
                                    <tr class="file-item" data-id="{{ $item->id }}">
                                        <td>
                                            @if($item->type === 'folder')
                                                <i class="bi bi-folder-fill folder-icon fs-4"></i>
                                            @else
                                                <i class="bi bi-file-earmark file-icon fs-4"></i>
                                            @endif
                                        </td>
                                        <td>
                                            @if($item->type === 'folder')
                                                <a href="{{ route('file-manager.index', ['folder_id' => $item->id]) }}" class="text-decoration-none">
                                                    {{ $item->name }}
                                                </a>
                                                @if($item->description)
                                                    <br><small class="text-muted">{{ Str::limit($item->description, 50) }}</small>
                                                @endif
                                            @else
                                                <strong>{{ $item->name }}</strong>
                                                @if($item->description)
                                                    <br><small class="text-muted">{{ Str::limit($item->description, 50) }}</small>
                                                @endif
                                                <br>
                                                <small class="text-muted">{{ $item->mime_type }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            @if($item->type === 'file')
                                                {{ $item->readable_size }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>{{ $item->updated_at->format('Y-m-d H:i') }}</td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                @if($item->type === 'file')
                                                    <a href="{{ route('file-manager.download', $item->id) }}" class="btn btn-outline-primary">
                                                        <i class="bi bi-download"></i>
                                                    </a>
                                                @endif
                                                <button class="btn btn-outline-secondary rename-btn" data-id="{{ $item->id }}" data-name="{{ $item->name }}">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <button class="btn btn-outline-danger delete-btn" data-id="{{ $item->id }}">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $items->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-folder-x display-1 text-muted"></i>
                        <h4 class="mt-3">No files or folders</h4>
                        <p>Upload files or create folders to get started</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Upload Modal -->
<div class="modal fade" id="uploadModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="uploadForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Upload File</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="file" class="form-label">Choose File</label>
                        <input type="file" class="form-control" id="file" name="file" required>
                        <div class="form-text">Max file size: 10MB</div>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description (Optional)</label>
                        <textarea class="form-control" id="description" name="description" rows="2"></textarea>
                    </div>
                    <input type="hidden" name="folder_id" value="{{ $currentFolder ? $currentFolder->id : '' }}">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Folder Modal -->
<div class="modal fade" id="folderModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="folderForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Create New Folder</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="folderName" class="form-label">Folder Name</label>
                        <input type="text" class="form-control" id="folderName" name="name" required>
                    </div>
                    <input type="hidden" name="folder_id" value="{{ $currentFolder ? $currentFolder->id : '' }}">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Rename Modal -->
<div class="modal fade" id="renameModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="renameForm">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Rename</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="newName" class="form-label">New Name</label>
                        <input type="text" class="form-control" id="newName" name="name" required>
                    </div>
                    <input type="hidden" id="renameId" name="id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Rename</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this item?</p>
                <p class="text-danger"><small>This action cannot be undone.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Upload Form
    document.getElementById('uploadForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const response = await fetch('{{ route("file-manager.upload") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        const result = await response.json();
        
        if (response.ok) {
            alert('File uploaded successfully!');
            window.location.reload();
        } else {
            alert('Error: ' + (result.errors ? Object.values(result.errors).flat().join(', ') : 'Upload failed'));
        }
    });
    
    // Folder Form
    document.getElementById('folderForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const response = await fetch('{{ route("file-manager.create-folder") }}', {
            method: 'POST',
            body: new FormData(this),
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        const result = await response.json();
        
        if (response.ok) {
            alert('Folder created successfully!');
            window.location.reload();
        } else {
            alert('Error: ' + (result.errors ? Object.values(result.errors).flat().join(', ') : 'Creation failed'));
        }
    });
    
    // Rename buttons
    document.querySelectorAll('.rename-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            const name = this.dataset.name;
            
            document.getElementById('renameId').value = id;
            document.getElementById('newName').value = name;
            
            new bootstrap.Modal(document.getElementById('renameModal')).show();
        });
    });
    
    // Rename Form
    document.getElementById('renameForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const id = document.getElementById('renameId').value;
        const response = await fetch(`/file-manager/${id}/rename`, {
            method: 'PUT',
            body: new FormData(this),
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        const result = await response.json();
        
        if (response.ok) {
            alert('Renamed successfully!');
            window.location.reload();
        } else {
            alert('Error: ' + (result.errors ? Object.values(result.errors).flat().join(', ') : 'Rename failed'));
        }
    });
    
    // Delete buttons
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            document.getElementById('deleteForm').action = `/file-manager/${id}`;
            new bootstrap.Modal(document.getElementById('deleteModal')).show();
        });
    });
    
    // Delete Form
    document.getElementById('deleteForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const response = await fetch(this.action, {
            method: 'DELETE',
            body: new FormData(this),
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        const result = await response.json();
        
        if (response.ok) {
            alert('Deleted successfully!');
            window.location.reload();
        } else {
            alert('Error: ' + (result.error || 'Delete failed'));
        }
    });
});
</script>
@endpush