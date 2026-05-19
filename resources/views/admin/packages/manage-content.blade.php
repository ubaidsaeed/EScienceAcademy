@extends('layouts.admin.app')

@section('title', 'Manage Package Content')

@push('style')
<link href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.3.12/themes/default/style.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<style>
    .jstree-default .jstree-clicked {
        background: #e7f4ff;
        box-shadow: inset 0 0 1px #ccc;
    }
    .jstree-default .jstree-hovered {
        background: #f0f0f0;
    }
    .access-badge {
        font-size: 0.75rem;
        padding: 2px 6px;
    }
    .content-stats {
        font-size: 0.9rem;
    }
    #content-tree {
        max-height: 600px;
        overflow-y: auto;
        border: 1px solid #e3e6f0;
        border-radius: 5px;
        padding: 10px;
        background: #f8f9fc;
    }
    .tree-container {
        position: relative;
        min-height: 400px;
    }
    .tree-loading {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255,255,255,0.9);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 10;
        border-radius: 5px;
    }
    .subject-item.active {
        background-color: #e7f4ff !important;
        border-left: 3px solid #007bff !important;
    }
    .quick-stats-card {
        border-left: 4px solid;
        transition: all 0.3s;
    }
    .quick-stats-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
</style>
@endpush

@section('content')
<div class="app-content main-content">
    <div class="side-app">
        <div class="container-fluid">
            <!-- Page Heading -->
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">Manage Content Access: {{ $package->name }}</h1>
                <div>
                    <a href="{{ route('admin.packages.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Packages
                    </a>
                    <a href="{{ route('admin.packages.subjects', $package->id) }}" class="btn btn-info">
                        <i class="fas fa-book"></i> Manage Subjects
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i> Please fix the following errors:
                    <ul class="mb-0 mt-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row">
                <!-- Left Sidebar -->
                <div class="col-xl-3 col-lg-4">
                    <!-- Package Info Card -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 bg-primary text-white">
                            <h6 class="m-0 font-weight-bold"><i class="fas fa-info-circle me-2"></i>Package Info</h6>
                        </div>
                        <div class="card-body">
                            <p><strong>Board:</strong> {{ $package->board->name ?? 'N/A' }}</p>
                            <p><strong>Level:</strong> {{ $package->level->name ?? 'N/A' }}</p>
                            <p><strong>Price:</strong> ₹{{ number_format($package->price, 2) }}</p>
                            <p><strong>Duration:</strong> {{ $package->duration }} {{ $package->duration_type }}</p>
                            <hr>
                            <h6><i class="fas fa-star me-2"></i>Features:</h6>
                            <ul class="list-unstyled mb-0">
                                @if($package->online_notes)
                                <li><i class="fas fa-check text-success me-2"></i> Online Notes</li>
                                @endif
                                @if($package->top_past_paper)
                                <li><i class="fas fa-check text-success me-2"></i> Past Papers</li>
                                @endif
                                @if($package->ws_aw_bg)
                                <li><i class="fas fa-check text-success me-2"></i> Worksheets</li>
                                @endif
                                @if($package->recorded)
                                <li><i class="fas fa-check text-success me-2"></i> Recorded Lectures</li>
                                @endif
                            </ul>
                        </div>
                    </div>

                    <!-- Subjects Card -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 bg-info text-white">
                            <h6 class="m-0 font-weight-bold"><i class="fas fa-book me-2"></i>Package Subjects</h6>
                        </div>
                        <div class="card-body p-0">
                            <div class="list-group list-group-flush">
                                @forelse($subjects as $subject)
                                <a href="#" 
                                   class="list-group-item list-group-item-action subject-item"
                                   data-subject-id="{{ $subject->id }}"
                                   data-subject-name="{{ $subject->name }}">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span>{{ $subject->name }}</span>
                                        <span class="badge bg-primary">₹{{ number_format($subject->pivot->subject_price ?? 0, 2) }}</span>
                                    </div>
                                </a>
                                @empty
                                <div class="list-group-item text-center text-muted">
                                    <i class="fas fa-exclamation-circle me-2"></i>
                                    No subjects added
                                </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- Access Summary Card -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 bg-success text-white">
                            <h6 class="m-0 font-weight-bold"><i class="fas fa-chart-bar me-2"></i>Access Summary</h6>
                        </div>
                        <div class="card-body">
                            <div id="access-summary" class="text-center">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <p class="mt-2 text-muted">Loading access summary...</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Content Area -->
                <div class="col-xl-9 col-lg-8">
                    <!-- Content Management Card -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="m-0 font-weight-bold text-primary" id="subject-title">
                                    <i class="fas fa-folder me-2"></i>Select a subject to manage content
                                </h6>
                                <div id="bulk-actions" style="display: none;">
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-success btn-sm" id="add-all-content">
                                            <i class="fas fa-check-circle me-1"></i> Add All
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm" id="remove-all-content">
                                            <i class="fas fa-times-circle me-1"></i> Remove All
                                        </button>
                                        <button type="button" class="btn btn-info btn-sm" id="expand-all">
                                            <i class="fas fa-expand me-1"></i> Expand
                                        </button>
                                        <button type="button" class="btn btn-secondary btn-sm" id="collapse-all">
                                            <i class="fas fa-compress me-1"></i> Collapse
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="tree-container">
                                <div id="content-tree"></div>
                                <div id="tree-loading" class="tree-loading" style="display: none;">
                                    <div class="text-center">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                        <p class="mt-2 text-muted">Loading content...</p>
                                    </div>
                                </div>
                            </div>
                            <div id="no-content-message" class="text-center text-muted py-5">
                                <i class="fas fa-folder-open fa-3x mb-3"></i>
                                <h5>No Content Available</h5>
                                <p>Please select a subject to view its content structure</p>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Stats -->
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="card quick-stats-card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                <i class="fas fa-folder me-1"></i>Folders Access
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="accessible-folders-count">0</div>
                                            <div class="mt-2 mb-0 text-muted text-xs">
                                                <span id="folder-percentage">0%</span> of total
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-folder fa-2x text-primary opacity-25"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <div class="card quick-stats-card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                <i class="fas fa-file me-1"></i>Files Access
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="accessible-files-count">0</div>
                                            <div class="mt-2 mb-0 text-muted text-xs">
                                                <span id="file-percentage">0%</span> of total
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-file fa-2x text-success opacity-25"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Selected Items Summary -->
                    <div class="card shadow mb-4" id="selected-summary" style="display: none;">
                        <div class="card-header py-3 bg-warning text-white">
                            <h6 class="m-0 font-weight-bold"><i class="fas fa-list me-2"></i>Selected Items</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-folder text-warning fa-lg me-3"></i>
                                        <div>
                                            <h5 class="mb-0" id="selected-folders-count">0</h5>
                                            <p class="text-muted mb-0 small">Folders Selected</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-file text-info fa-lg me-3"></i>
                                        <div>
                                            <h5 class="mb-0" id="selected-files-count">0</h5>
                                            <p class="text-muted mb-0 small">Files Selected</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.3.12/jstree.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
$(document).ready(function() {
    // Initialize toastr
    if (typeof toastr !== 'undefined') {
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": "3000",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };
    }

    let currentSubjectId = null;
    let currentSubjectName = null;
    let currentPackageId = {{ $package->id }};
    let jsTreeInstance = null;
    let accessibleFolderIds = [];
    let accessibleFileIds = [];

    // Load initial access summary
    loadAccessSummary();

    // Subject selection
    $(document).on('click', '.subject-item', function(e) {
        e.preventDefault();
        
        const $this = $(this);
        currentSubjectId = $this.data('subject-id');
        currentSubjectName = $this.data('subject-name');
        
        // Update UI
        $('.subject-item').removeClass('active');
        $this.addClass('active');
        
        $('#subject-title').html(`<i class="fas fa-folder me-2"></i>Managing content for: <strong>${currentSubjectName}</strong>`);
        $('#bulk-actions').show();
        $('#no-content-message').hide();
        $('#selected-summary').hide();
        
        // Load subject content
        loadSubjectContent();
    });

    function loadSubjectContent() {
        $('#content-tree').html('');
        $('#tree-loading').show();
        
        const url = "{{ route('admin.packages.subject-folders', ['packageId' => ':packageId', 'subjectId' => ':subjectId']) }}"
            .replace(':packageId', currentPackageId)
            .replace(':subjectId', currentSubjectId);

        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                console.log('Response:', response);
                
                if (response.folders && response.folders.length > 0) {
                    accessibleFolderIds = response.accessibleFolderIds || [];
                    accessibleFileIds = response.accessibleFileIds || [];
                    
                    // Initialize jsTree
                    initJsTree(response.folders);
                    $('#tree-loading').hide();
                    
                    // Update quick stats
                    updateQuickStats();
                    updateSelectedSummary();
                    
                    $('#selected-summary').show();
                } else {
                    $('#content-tree').html(`
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            No content found for this subject. Please add content first.
                        </div>
                    `);
                    $('#tree-loading').hide();
                }
            },
            error: function(xhr, status, error) {
                console.error('Error loading content:', xhr.responseText);
                $('#content-tree').html(`
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Error loading content. Please try again.
                        ${xhr.responseJSON && xhr.responseJSON.message ? `<br><small>${xhr.responseJSON.message}</small>` : ''}
                    </div>
                `);
                $('#tree-loading').hide();
                if (typeof toastr !== 'undefined') {
                    toastr.error('Error loading content. Please try again.');
                }
            }
        });
    }

    function initJsTree(folders) {
        const treeData = buildTreeData(folders);
        
        $('#content-tree').jstree({
            'core': {
                'data': treeData,
                'themes': {
                    'responsive': true,
                    'dots': true,
                    'icons': true
                },
                'check_callback': true
            },
            'types': {
                'folder': {
                    'icon': 'fas fa-folder text-warning'
                },
                'file': {
                    'icon': 'fas fa-file text-secondary'
                }
            },
            'plugins': ['types', 'checkbox', 'wholerow'],
            'checkbox': {
                'three_state': false,
                'whole_node': false,
                'keep_selected_style': true
            }
        }).on('ready.jstree', function() {
            jsTreeInstance = $('#content-tree').jstree(true);
            
            // Check accessible items
            checkAccessibleItems();
            
            // Expand all nodes initially
            jsTreeInstance.open_all();
        }).on('changed.jstree', function(e, data) {
            handleTreeSelection(data);
        });
    }

    function buildTreeData(folders, parent = '#') {
        let treeData = [];
        
        folders.forEach(folder => {
            const folderId = `folder_${folder.id}`;
            const hasAccess = accessibleFolderIds.includes(folder.id);
            
            // Build folder node
            const folderNode = {
                id: folderId,
                text: `<span class="folder-text">${folder.name} 
                       <span class="badge ${hasAccess ? 'bg-success' : 'bg-secondary'} access-badge ms-2">
                       ${hasAccess ? 'Access' : 'No Access'}</span></span>`,
                icon: 'fas fa-folder text-warning',
                type: 'folder',
                data: {
                    id: folder.id,
                    type: 'folder',
                    hasAccess: hasAccess
                },
                children: [],
                state: {
                    opened: true,
                    selected: hasAccess
                }
            };
            
            // Add files in this folder
            if (folder.files && folder.files.length > 0) {
                folder.files.forEach(file => {
                    const fileHasAccess = accessibleFileIds.includes(file.id);
                    folderNode.children.push({
                        id: `file_${file.id}`,
                        text: `<span class="file-text">${file.original_name || file.name} 
                               <span class="badge ${fileHasAccess ? 'bg-success' : 'bg-secondary'} access-badge ms-2">
                               ${fileHasAccess ? 'Access' : 'No Access'}</span></span>`,
                        icon: getFileIcon(file),
                        type: 'file',
                        data: {
                            id: file.id,
                            type: 'file',
                            hasAccess: fileHasAccess,
                            size: file.size || 0,
                            mime_type: file.mime_type || 'unknown'
                        },
                        state: {
                            selected: fileHasAccess
                        }
                    });
                });
            }
            
            // Add subfolders recursively
            if (folder.children && folder.children.length > 0) {
                const childFolders = buildTreeData(folder.children, folderId);
                folderNode.children = folderNode.children.concat(childFolders);
            }
            
            treeData.push(folderNode);
        });
        
        return treeData;
    }

    function getFileIcon(file) {
        if (file.is_vimeo || (file.mime_type && file.mime_type.includes('video'))) {
            return 'fas fa-video text-danger';
        } else if (file.mime_type && file.mime_type.startsWith('image/')) {
            return 'fas fa-image text-success';
        } else if (file.mime_type === 'application/pdf') {
            return 'fas fa-file-pdf text-danger';
        } else if (file.mime_type && file.mime_type.includes('document') || file.mime_type === 'application/msword') {
            return 'fas fa-file-word text-primary';
        } else if (file.mime_type && file.mime_type.includes('spreadsheet')) {
            return 'fas fa-file-excel text-success';
        } else {
            return 'fas fa-file text-secondary';
        }
    }

    function checkAccessibleItems() {
        if (!jsTreeInstance) return;
        
        // Check accessible folders
        accessibleFolderIds.forEach(folderId => {
            jsTreeInstance.check_node(`folder_${folderId}`);
        });
        
        // Check accessible files
        accessibleFileIds.forEach(fileId => {
            jsTreeInstance.check_node(`file_${fileId}`);
        });
    }

    function handleTreeSelection(data) {
        if (!data || !data.selected) return;
        
        const selectedNodes = data.selected || [];
        selectedNodes.forEach(nodeId => {
            const node = jsTreeInstance.get_node(nodeId);
            if (!node || !node.data) return;
            
            const itemType = node.data.type;
            const itemId = node.data.id;
            const currentAccess = node.data.hasAccess;
            
            // Determine action based on current state
            const action = currentAccess ? 'remove' : 'add';
            
            if (itemType === 'folder') {
                toggleFolderAccess(itemId, action, node);
            } else if (itemType === 'file') {
                toggleFileAccess(itemId, action, node);
            }
        });
        
        // Update statistics after changes
        updateSelectedSummary();
    }

    function toggleFolderAccess(folderId, action, node) {
        $.ajax({
            url: "{{ route('admin.packages.toggle-folder-access', ['packageId' => $package->id]) }}",
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                folder_id: folderId,
                action: action
            },
            success: function(response) {
                if (response.success) {
                    // Update local state
                    const index = accessibleFolderIds.indexOf(folderId);
                    if (action === 'add' && index === -1) {
                        accessibleFolderIds.push(folderId);
                        node.data.hasAccess = true;
                    } else if (action === 'remove' && index !== -1) {
                        accessibleFolderIds.splice(index, 1);
                        node.data.hasAccess = false;
                    }
                    
                    // Update UI
                    updateQuickStats();
                    loadAccessSummary();
                    updateSelectedSummary();
                    
                    if (typeof toastr !== 'undefined') {
                        toastr.success(`Folder access ${action === 'add' ? 'added' : 'removed'} successfully`);
                    }
                }
            },
            error: function(xhr) {
                console.error('Error toggling folder access:', xhr);
                // Revert tree selection
                if (jsTreeInstance) {
                    jsTreeInstance.uncheck_node(`folder_${folderId}`);
                }
                if (typeof toastr !== 'undefined') {
                    toastr.error('Error updating folder access');
                }
            }
        });
    }

    function toggleFileAccess(fileId, action, node) {
        $.ajax({
            url: "{{ route('admin.packages.toggle-file-access', ['packageId' => $package->id]) }}",
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                file_id: fileId,
                action: action
            },
            success: function(response) {
                if (response.success) {
                    // Update local state
                    const index = accessibleFileIds.indexOf(fileId);
                    if (action === 'add' && index === -1) {
                        accessibleFileIds.push(fileId);
                        node.data.hasAccess = true;
                    } else if (action === 'remove' && index !== -1) {
                        accessibleFileIds.splice(index, 1);
                        node.data.hasAccess = false;
                    }
                    
                    // Update UI
                    updateQuickStats();
                    loadAccessSummary();
                    updateSelectedSummary();
                    
                    if (typeof toastr !== 'undefined') {
                        toastr.success(`File access ${action === 'add' ? 'added' : 'removed'} successfully`);
                    }
                }
            },
            error: function(xhr) {
                console.error('Error toggling file access:', xhr);
                // Revert tree selection
                if (jsTreeInstance) {
                    jsTreeInstance.uncheck_node(`file_${fileId}`);
                }
                if (typeof toastr !== 'undefined') {
                    toastr.error('Error updating file access');
                }
            }
        });
    }

    // Bulk actions
    $('#add-all-content').on('click', function() {
        if (!currentSubjectId) {
            if (typeof toastr !== 'undefined') {
                toastr.warning('Please select a subject first');
            }
            return;
        }
        
        if (confirm('Are you sure you want to add ALL content for this subject to the package?\n\nThis will grant access to all folders and files.')) {
            $.ajax({
                url: "{{ route('admin.packages.toggle-all-content', ['packageId' => $package->id, 'subjectId' => ':subjectId']) }}"
                    .replace(':subjectId', currentSubjectId),
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    action: 'add'
                },
                success: function(response) {
                    if (response.success) {
                        loadSubjectContent();
                        loadAccessSummary();
                        if (typeof toastr !== 'undefined') {
                            toastr.success('All content added successfully');
                        }
                    }
                },
                error: function(xhr) {
                    console.error('Error adding all content:', xhr);
                    if (typeof toastr !== 'undefined') {
                        toastr.error('Error adding all content');
                    }
                }
            });
        }
    });

    $('#remove-all-content').on('click', function() {
        if (!currentSubjectId) {
            if (typeof toastr !== 'undefined') {
                toastr.warning('Please select a subject first');
            }
            return;
        }
        
        if (confirm('Are you sure you want to remove ALL content for this subject from the package?\n\nThis will revoke access to all folders and files.')) {
            $.ajax({
                url: "{{ route('admin.packages.toggle-all-content', ['packageId' => $package->id, 'subjectId' => ':subjectId']) }}"
                    .replace(':subjectId', currentSubjectId),
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    action: 'remove'
                },
                success: function(response) {
                    if (response.success) {
                        loadSubjectContent();
                        loadAccessSummary();
                        if (typeof toastr !== 'undefined') {
                            toastr.success('All content removed successfully');
                        }
                    }
                },
                error: function(xhr) {
                    console.error('Error removing all content:', xhr);
                    if (typeof toastr !== 'undefined') {
                        toastr.error('Error removing all content');
                    }
                }
            });
        }
    });

    $('#expand-all').on('click', function() {
        if (jsTreeInstance) {
            jsTreeInstance.open_all();
        }
    });

    $('#collapse-all').on('click', function() {
        if (jsTreeInstance) {
            jsTreeInstance.close_all();
        }
    });

    function loadAccessSummary() {
        $.ajax({
            url: "{{ route('admin.packages.access-summary', ['id' => $package->id]) }}",
            type: 'GET',
            success: function(response) {
                const folderPercentage = response.folder_percentage || 0;
                const filePercentage = response.file_percentage || 0;
                
                $('#access-summary').html(`
                    <div class="text-center">
                        <div class="mb-4">
                            <h3 class="text-primary mb-2">${response.accessible_folders || 0}/${response.total_folders || 0}</h3>
                            <p class="text-muted mb-1">Folders Accessible</p>
                            <div class="progress mb-3">
                                <div class="progress-bar bg-primary" style="width: ${folderPercentage}%"></div>
                            </div>
                        </div>
                        
                        <div>
                            <h3 class="text-success mb-2">${response.accessible_files || 0}/${response.total_files || 0}</h3>
                            <p class="text-muted mb-1">Files Accessible</p>
                            <div class="progress">
                                <div class="progress-bar bg-success" style="width: ${filePercentage}%"></div>
                            </div>
                        </div>
                    </div>
                `);
                
                // Update quick stats
                $('#accessible-folders-count').text(response.accessible_folders || 0);
                $('#accessible-files-count').text(response.accessible_files || 0);
                $('#folder-percentage').text(`${folderPercentage}%`);
                $('#file-percentage').text(`${filePercentage}%`);
            },
            error: function(xhr) {
                console.error('Error loading access summary:', xhr);
                $('#access-summary').html(`
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Error loading access summary
                    </div>
                `);
            }
        });
    }

    function updateQuickStats() {
        $('#accessible-folders-count').text(accessibleFolderIds.length);
        $('#accessible-files-count').text(accessibleFileIds.length);
    }

    function updateSelectedSummary() {
        if (!jsTreeInstance) return;
        
        const selectedNodes = jsTreeInstance.get_selected(true);
        let folderCount = 0;
        let fileCount = 0;
        
        selectedNodes.forEach(node => {
            if (node.data && node.data.type === 'folder') {
                folderCount++;
            } else if (node.data && node.data.type === 'file') {
                fileCount++;
            }
        });
        
        $('#selected-folders-count').text(folderCount);
        $('#selected-files-count').text(fileCount);
    }

    // Auto-select first subject if available
    setTimeout(function() {
        if ($('.subject-item').length > 0) {
            $('.subject-item:first').trigger('click');
        } else {
            $('#no-content-message').show();
        }
    }, 500);

    // Handle window resize
    $(window).on('resize', function() {
        if (jsTreeInstance) {
            jsTreeInstance.redraw();
        }
    });
});
</script>
@endpush