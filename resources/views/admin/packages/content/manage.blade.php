@extends('layouts.admin.app')

@section('title', 'Manage Package Content')

@push('style')
<link href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.3.12/themes/default/style.min.css" rel="stylesheet">
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
    }
    .tree-loading {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255,255,255,0.8);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 10;
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

    <div class="row">
        <div class="col-xl-3 col-lg-4">
            <!-- Package Info Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-primary text-white">
                    <h6 class="m-0 font-weight-bold">Package Info</h6>
                </div>
                <div class="card-body">
                    <p><strong>Board:</strong> {{ $package->board->name ?? 'N/A' }}</p>
                    <p><strong>Level:</strong> {{ $package->level->name ?? 'N/A' }}</p>
                    <p><strong>Price:</strong> ₹{{ number_format($package->price, 2) }}</p>
                    <p><strong>Duration:</strong> {{ $package->duration }} {{ $package->duration_type }}</p>
                    <hr>
                    <h6>Features:</h6>
                    <ul class="list-unstyled mb-0">
                        @if($package->online_notes)
                        <li><i class="fas fa-check text-success mr-2"></i> Online Notes</li>
                        @endif
                        @if($package->top_past_paper)
                        <li><i class="fas fa-check text-success mr-2"></i> Past Papers</li>
                        @endif
                        @if($package->ws_aw_bg)
                        <li><i class="fas fa-check text-success mr-2"></i> Worksheets</li>
                        @endif
                        @if($package->recorded)
                        <li><i class="fas fa-check text-success mr-2"></i> Recorded Lectures</li>
                        @endif
                    </ul>
                </div>
            </div>

            <!-- Subjects Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-info text-white">
                    <h6 class="m-0 font-weight-bold">Package Subjects</h6>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @foreach($subjects as $subject)
                        <a href="#" 
                           class="list-group-item list-group-item-action subject-item"
                           data-subject-id="{{ $subject->id }}"
                           data-subject-name="{{ $subject->name }}">
                            <div class="d-flex justify-content-between align-items-center">
                                <span>{{ $subject->name }}</span>
                                <span class="badge bg-primary">₹{{ number_format($subject->pivot->subject_price, 2) }}</span>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Access Summary Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-success text-white">
                    <h6 class="m-0 font-weight-bold">Access Summary</h6>
                </div>
                <div class="card-body">
                    <div id="access-summary" class="text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-9 col-lg-8">
            <!-- Content Management Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary" id="subject-title">
                            Select a subject to manage content
                        </h6>
                        <div id="bulk-actions" style="display: none;">
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-success btn-sm" id="add-all-content">
                                    <i class="fas fa-check-circle"></i> Add All
                                </button>
                                <button type="button" class="btn btn-danger btn-sm" id="remove-all-content">
                                    <i class="fas fa-times-circle"></i> Remove All
                                </button>
                                <button type="button" class="btn btn-info btn-sm" id="expand-all">
                                    <i class="fas fa-expand"></i> Expand All
                                </button>
                                <button type="button" class="btn btn-secondary btn-sm" id="collapse-all">
                                    <i class="fas fa-compress"></i> Collapse All
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
                                    <span class="sr-only">Loading...</span>
                                </div>
                                <p class="mt-2">Loading content...</p>
                            </div>
                        </div>
                    </div>
                    <div id="no-content-message" class="text-center text-muted py-5" style="display: none;">
                        <i class="fas fa-folder-open fa-3x mb-3"></i>
                        <h5>No Content Available</h5>
                        <p>Please select a subject to view its content structure</p>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="row">
                <div class="col-md-6">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Folders Access
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800" id="accessible-folders-count">0</div>
                                    <div class="mt-2 mb-0 text-muted text-xs">
                                        <span id="folder-percentage">0%</span> of total
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-folder fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                        Files Access
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800" id="accessible-files-count">0</div>
                                    <div class="mt-2 mb-0 text-muted text-xs">
                                        <span id="file-percentage">0%</span> of total
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-file fa-2x text-gray-300"></i>
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
<script>
$(document).ready(function() {
    let currentSubjectId = null;
    let currentSubjectName = null;
    let currentPackageId = {{ $package->id }};
    let jsTreeInstance = null;
    let accessibleFolderIds = [];
    let accessibleFileIds = [];

    // Initialize toastr
    toastr.options = {
        closeButton: true,
        progressBar: true,
        positionClass: 'toast-top-right',
        timeOut: 3000
    };

    // Load initial access summary
    loadAccessSummary();

    // Subject selection
    $('.subject-item').on('click', function(e) {
        e.preventDefault();
        
        currentSubjectId = $(this).data('subject-id');
        currentSubjectName = $(this).data('subject-name');
        
        // Update UI
        $('.subject-item').removeClass('active');
        $(this).addClass('active');
        
        $('#subject-title').html(`Managing content for: <strong>${currentSubjectName}</strong>`);
        $('#bulk-actions').show();
        $('#no-content-message').hide();
        
        // Load subject content
        loadSubjectContent();
    });

    function loadSubjectContent() {
        $('#content-tree').html('');
        $('#tree-loading').show();
        
        $.ajax({
            url: "{{ route('admin.packages.subject-folders', ['packageId' => ':packageId', 'subjectId' => ':subjectId']) }}"
                .replace(':packageId', currentPackageId)
                .replace(':subjectId', currentSubjectId),
            type: 'GET',
            success: function(response) {
                accessibleFolderIds = response.accessibleFolderIds || [];
                accessibleFileIds = response.accessibleFileIds || [];
                
                // Initialize jsTree
                initJsTree(response.folders || []);
                $('#tree-loading').hide();
                
                // Update quick stats
                updateQuickStats();
            },
            error: function(xhr) {
                console.error('Error loading content:', xhr);
                toastr.error('Error loading content. Please try again.');
                $('#tree-loading').hide();
                $('#no-content-message').show();
            }
        });
    }

    function initJsTree(folders) {
        if (!folders || folders.length === 0) {
            $('#content-tree').html('<div class="alert alert-info">No content found for this subject.</div>');
            return;
        }

        const treeData = buildTreeData(folders);
        
        $('#content-tree').jstree({
            'core': {
                'data': treeData,
                'themes': {
                    'responsive': true,
                    'dots': true,
                    'icons': true
                }
            },
            'types': {
                'folder': {
                    'icon': 'fas fa-folder text-warning'
                },
                'file': {
                    'icon': 'fas fa-file text-secondary'
                }
            },
            'plugins': ['types', 'checkbox', 'wholerow']
        }).on('ready.jstree', function() {
            jsTreeInstance = $('#content-tree').jstree(true);
            
            // Check accessible items
            checkAccessibleItems();
        }).on('changed.jstree', function(e, data) {
            handleTreeSelection(data);
        });
    }

    function buildTreeData(folders, parent = '#') {
        let treeData = [];
        
        folders.forEach(folder => {
            const folderId = `folder_${folder.id}`;
            const hasAccess = accessibleFolderIds.includes(folder.id);
            
            treeData.push({
                id: folderId,
                text: `<span class="folder-text">${folder.name} 
                       <span class="badge ${hasAccess ? 'bg-success' : 'bg-secondary'} access-badge">
                       ${hasAccess ? 'Access' : 'No Access'}</span></span>`,
                icon: 'fas fa-folder text-warning',
                type: 'folder',
                data: {
                    id: folder.id,
                    type: 'folder',
                    hasAccess: hasAccess
                },
                children: true,
                state: {
                    opened: true,
                    selected: hasAccess
                }
            });
            
            // Add files in this folder
            if (folder.files && folder.files.length > 0) {
                folder.files.forEach(file => {
                    const fileHasAccess = accessibleFileIds.includes(file.id);
                    treeData.push({
                        id: `file_${file.id}`,
                        parent: folderId,
                        text: `<span class="file-text">${file.original_name || file.name} 
                               <span class="badge ${fileHasAccess ? 'bg-success' : 'bg-secondary'} access-badge">
                               ${fileHasAccess ? 'Access' : 'No Access'}</span></span>`,
                        icon: getFileIcon(file),
                        type: 'file',
                        data: {
                            id: file.id,
                            type: 'file',
                            hasAccess: fileHasAccess
                        },
                        state: {
                            selected: fileHasAccess
                        }
                    });
                });
            }
        });
        
        return treeData;
    }

    function getFileIcon(file) {
        if (file.is_vimeo) {
            return 'fas fa-video text-danger';
        } else if (file.mime_type && file.mime_type.startsWith('image/')) {
            return 'fas fa-image text-success';
        } else if (file.mime_type === 'application/pdf') {
            return 'fas fa-file-pdf text-danger';
        } else {
            return 'fas fa-file text-secondary';
        }
    }

    function checkAccessibleItems() {
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
        const selectedNodes = data.selected || [];
        
        selectedNodes.forEach(nodeId => {
            const node = jsTreeInstance.get_node(nodeId);
            const itemType = node.data.type;
            const itemId = node.data.id;
            const currentAccess = node.data.hasAccess;
            
            // Determine action based on current state
            const action = currentAccess ? 'remove' : 'add';
            
            if (itemType === 'folder') {
                toggleFolderAccess(itemId, action);
            } else if (itemType === 'file') {
                toggleFileAccess(itemId, action);
            }
        });
    }

    function toggleFolderAccess(folderId, action) {
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
                    } else if (action === 'remove' && index !== -1) {
                        accessibleFolderIds.splice(index, 1);
                    }
                    
                    updateQuickStats();
                    loadAccessSummary();
                    
                    toastr.success(`Folder access ${action === 'add' ? 'added' : 'removed'} successfully`);
                }
            },
            error: function(xhr) {
                toastr.error('Error updating folder access');
                // Revert tree selection
                jsTreeInstance.uncheck_node(`folder_${folderId}`);
            }
        });
    }

    function toggleFileAccess(fileId, action) {
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
                    } else if (action === 'remove' && index !== -1) {
                        accessibleFileIds.splice(index, 1);
                    }
                    
                    updateQuickStats();
                    loadAccessSummary();
                    
                    toastr.success(`File access ${action === 'add' ? 'added' : 'removed'} successfully`);
                }
            },
            error: function(xhr) {
                toastr.error('Error updating file access');
                // Revert tree selection
                jsTreeInstance.uncheck_node(`file_${fileId}`);
            }
        });
    }

    // Bulk actions
    $('#add-all-content').on('click', function() {
        if (!currentSubjectId) {
            toastr.warning('Please select a subject first');
            return;
        }
        
        if (confirm('Are you sure you want to add ALL content for this subject to the package?')) {
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
                        toastr.success('All content added successfully');
                    }
                },
                error: function(xhr) {
                    toastr.error('Error adding all content');
                }
            });
        }
    });

    $('#remove-all-content').on('click', function() {
        if (!currentSubjectId) {
            toastr.warning('Please select a subject first');
            return;
        }
        
        if (confirm('Are you sure you want to remove ALL content for this subject from the package?')) {
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
                        toastr.success('All content removed successfully');
                    }
                },
                error: function(xhr) {
                    toastr.error('Error removing all content');
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
                        <h3 class="text-primary mb-2">${response.accessible_folders}/${response.total_folders}</h3>
                        <p class="text-muted mb-1">Folders Accessible</p>
                        <div class="progress mb-3">
                            <div class="progress-bar bg-primary" style="width: ${folderPercentage}%"></div>
                        </div>
                        
                        <h3 class="text-success mb-2">${response.accessible_files}/${response.total_files}</h3>
                        <p class="text-muted mb-1">Files Accessible</p>
                        <div class="progress">
                            <div class="progress-bar bg-success" style="width: ${filePercentage}%"></div>
                        </div>
                    </div>
                `);
            },
            error: function(xhr) {
                console.error('Error loading access summary:', xhr);
            }
        });
    }

    function updateQuickStats() {
        // These would be updated based on current subject selection
        $('#accessible-folders-count').text(accessibleFolderIds.length);
        $('#accessible-files-count').text(accessibleFileIds.length);
    }

    // Auto-select first subject
    if ($('.subject-item').length > 0) {
        $('.subject-item:first').trigger('click');
    }
});
</script>
@endpush