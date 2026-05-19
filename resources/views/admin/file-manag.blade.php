<!-- resources/views/livewire/file-manager.blade.php -->

<div>
    @push('style')
      
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <style>
            /* Include the provided CSS here */
            {{ file_get_contents(public_path('build/assets/admin/css/file-manager.css')) }}
        </style>
  
<style>
    .empty-state {
        grid-column: 1 / -1;
        text-align: center;
        padding: 40px;
        color: #7a7a7a;
    }
    
    .empty-state i {
        font-size: 48px;
        margin-bottom: 15px;
        opacity: 0.5;
    }
    
    .alert {
        padding: 12px 15px;
        background-color: #d4edda;
        color: #155724;
        border-radius: 4px;
        margin-bottom: 20px;
        border: 1px solid #c3e6cb;
    }
    
    .error {
        color: #dc3545;
        font-size: 12px;
        margin-top: 5px;
    }
    
    .text-danger {
        color: #dc3545;
    }
    
    .upload-preview {
        margin-top: 15px;
        padding: 10px;
        background-color: #f8f9fa;
        border-radius: 4px;
    }
    
    .upload-preview ul {
        margin: 10px 0 0 0;
        padding-left: 20px;
    }
</style>
@endpush
    <header>
        <div class="logo">
            <i class="fas fa-folder"></i>
            <h1>Advanced File Manager</h1>
        </div>
        <div class="search-bar">
            <i class="fas fa-search"></i>
            <input type="text" placeholder="Search files..." wire:model.live="search">
        </div>
        <div class="user-actions">
            <i class="fas fa-bell"></i>
            <i class="fas fa-cog"></i>
            <i class="fas fa-user-circle"></i>
        </div>
    </header>

    <div class="container">
        <div class="sidebar" style="position: relative !important;z-index:0 !important">
            <div class="sidebar-section">
                <h3>Quick Access</h3>
                <div class="sidebar-item {{ $currentFolder === null ? 'active' : '' }}" 
                     wire:click="loadRootFolder">
                    <i class="fas fa-home"></i>
                    <span>Home</span>
                </div>
                <div class="sidebar-item">
                    <i class="fas fa-star"></i>
                    <span>Favorites</span>
                </div>
                <div class="sidebar-item">
                    <i class="fas fa-history"></i>
                    <span>Recent</span>
                </div>
            </div>

            <div class="sidebar-section">
                <h3>Folders</h3>
                @foreach($this->getFoldersList() as $folder)
                <div class="sidebar-item" 
                     wire:click="navigateToFolder({{ $folder['id'] }})">
                    <i class="fas fa-folder"></i>
                    <span>{{ $folder['name'] }}</span>
                </div>
                @endforeach
            </div>
        </div>

        <div class="main-content">
            <!-- Breadcrumb -->
            <div class="breadcrumb">
                @foreach($breadcrumbs as $index => $crumb)
                    <a wire:click="navigateToFolder({{ $crumb['id'] }})" 
                       class="breadcrumb-item">
                        {{ $crumb['name'] }}
                    </a>
                    @if(!$loop->last)
                        <i class="fas fa-chevron-right"></i>
                    @endif
                @endforeach
            </div>

            <!-- Toolbar -->
            <div class="toolbar">
                <div class="view-options">
                    <button class="{{ $viewMode === 'grid' ? 'active' : '' }}" 
                            wire:click="setViewMode('grid')">
                        <i class="fas fa-th"></i>
                    </button>
                    <button class="{{ $viewMode === 'list' ? 'active' : '' }}" 
                            wire:click="setViewMode('list')">
                        <i class="fas fa-list"></i>
                    </button>
                </div>
                <div class="file-actions">
                    <button wire:click="$set('showNewFolderModal', true)">
                        <i class="fas fa-plus"></i> New Folder
                    </button>
                    <button wire:click="$set('showUploadModal', true)">
                        <i class="fas fa-upload"></i> Upload
                    </button>
                </div>
            </div>

            <!-- Flash Messages -->
            @if (session()->has('message'))
                <div class="alert alert-success">
                    {{ session('message') }}
                </div>
            @endif

            <!-- Files Grid View -->
            @if($viewMode === 'grid')
            <div class="file-grid">
                @foreach($files as $file)
                <div class="file-item {{ $file['type'] }} 
                    {{ $selectedItem && $selectedItem['id'] == $file['id'] ? 'selected' : '' }}"
                    wire:click="selectItem({{ $file }})"
                    wire:dblclick="navigateToFolder({{ $file['type'] === 'folder' ? $file['id'] : 'null' }})"
                    @if($file['type'] !== 'folder')
                    wire:contextmenu="selectItem({{ $file }})"
                    @endif>
                    
                    <div class="file-icon">
                        <i class="fas fa-{{ $this->getFileIcon($file['type'], $file['mime_type']) }}"></i>
                    </div>
                    <div class="file-name">{{ $file['name'] }}</div>
                    <div class="file-info">
                        {{ $file['type'] === 'folder' ? $this->getFolderItemCount($file['id']) . ' items' : $this->formatSize($file['size']) }}
                    </div>
                </div>
                @endforeach
                
                @if(count($files) === 0)
                <div class="empty-state">
                    <i class="fas fa-folder-open"></i>
                    <p>This folder is empty</p>
                </div>
                @endif
            </div>
            @endif

            <!-- Files List View -->
            @if($viewMode === 'list')
            <div class="file-list">
                <div class="list-header">
                    <div>Name</div>
                    <div>Date Modified</div>
                    <div>Type</div>
                    <div>Size</div>
                </div>
                @foreach($files as $file)
                <div class="list-item {{ $selectedItem && $selectedItem['id'] == $file['id'] ? 'selected' : '' }}"
                    wire:click="selectItem({{ $file }})"
                    wire:dblclick="navigateToFolder({{ $file['type'] === 'folder' ? $file['id'] : 'null' }})">
                    
                    <div>
                        <i class="fas fa-{{ $this->getFileIcon($file['type'], $file['mime_type']) }} list-icon"></i>
                        {{ $file['name'] }}
                    </div>
                    <div>{{ $file['updated_at']->format('Y-m-d H:i') }}</div>
                    <div>{{ $this->getFileType($file['type'], $file['mime_type']) }}</div>
                    <div>
                        {{ $file['type'] === 'folder' ? $this->getFolderItemCount($file['id']) . ' items' : $this->formatSize($file['size']) }}
                    </div>
                </div>
                @endforeach
            </div>
            @endif

            <!-- Status Bar -->
            <div class="status-bar">
                <div>{{ count($files) }} {{ count($files) === 1 ? 'item' : 'items' }}</div>
                <div>{{ $this->getTotalSize() }}</div>
            </div>
        </div>
    </div>

    <!-- Context Menu -->
    @if($selectedItem)
    <div class="context-menu" id="context-menu" 
         style="display: none; left: {{ $contextMenuX }}px; top: {{ $contextMenuY }}px;">
        <ul>
            @if($selectedItem['type'] === 'folder')
            <li wire:click="navigateToFolder({{ $selectedItem['id'] }})">
                <i class="fas fa-folder-open"></i> Open
            </li>
            @else
            <li wire:click="downloadFile({{ $selectedItem['id'] }})">
                <i class="fas fa-download"></i> Download
            </li>
            @endif
            <li wire:click="$set('showRenameModal', true)">
                <i class="fas fa-i-cursor"></i> Rename
            </li>
            <li wire:click="deleteItem" class="text-danger">
                <i class="fas fa-trash"></i> Delete
            </li>
        </ul>
    </div>
    @endif

    <!-- New Folder Modal -->
    <div class="modal" style="{{ $showNewFolderModal ? 'display: flex;' : 'display: none;' }}">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Create New Folder</h2>
                <button class="close" wire:click="$set('showNewFolderModal', false)">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="folder-name">Folder Name</label>
                    <input type="text" id="folder-name" wire:model="newFolderName" 
                           placeholder="Enter folder name">
                    @error('newFolderName') <span class="error">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn-secondary" wire:click="$set('showNewFolderModal', false)">Cancel</button>
                <button class="btn-primary" wire:click="createFolder">Create</button>
            </div>
        </div>
    </div>

    <!-- Rename Modal -->
    <div class="modal" style="{{ $showRenameModal ? 'display: flex;' : 'display: none;' }}">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Rename</h2>
                <button class="close" wire:click="$set('showRenameModal', false)">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="new-name">New Name</label>
                    <input type="text" id="new-name" wire:model="newItemName" 
                           placeholder="Enter new name" value="{{ $selectedItem['name'] ?? '' }}">
                    @error('newItemName') <span class="error">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn-secondary" wire:click="$set('showRenameModal', false)">Cancel</button>
                <button class="btn-primary" wire:click="renameItem">Rename</button>
            </div>
        </div>
    </div>

    <!-- Upload Modal -->
    <div class="modal" style="{{ $showUploadModal ? 'display: flex;' : 'display: none;' }}">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Upload Files</h2>
                <button class="close" wire:click="$set('showUploadModal', false)">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="upload-files">Select Files</label>
                    <input type="file" id="upload-files" wire:model="uploadedFiles" multiple>
                    @error('uploadedFiles.*') <span class="error">{{ $message }}</span> @enderror
                </div>
                
                @if($uploadedFiles)
                <div class="upload-preview">
                    <h4>Files to upload:</h4>
                    <ul>
                        @foreach($uploadedFiles as $file)
                        <li>{{ $file->getClientOriginalName() }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
            </div>
            <div class="modal-footer">
                <button class="btn-secondary" wire:click="$set('showUploadModal', false)">Cancel</button>
                <button class="btn-primary" wire:click="uploadFiles">Upload</button>
            </div>
        </div>
    </div>
</div>

@push('script')
<script src="{{ asset('build/assets/admin/js/custom/filemanager.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let contextMenu = document.getElementById('context-menu');
        let selectedItem = null;

        // Right-click context menu
        document.addEventListener('contextmenu', function(e) {
            if (e.target.closest('.file-item') || e.target.closest('.list-item')) {
                e.preventDefault();
                if (contextMenu) {
                    contextMenu.style.display = 'block';
                    contextMenu.style.left = e.pageX + 'px';
                    contextMenu.style.top = e.pageY + 'px';
                }
            }
        });

        // Hide context menu on click
        document.addEventListener('click', function() {
            if (contextMenu) {
                contextMenu.style.display = 'none';
            }
        });

        // Prevent context menu from closing when clicking on it
        if (contextMenu) {
            contextMenu.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        }
    });
</script>
@endpush