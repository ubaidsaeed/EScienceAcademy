@php
    $iconClasses = [
        'board' => 'fas fa-school text-primary',
        'level' => 'fas fa-layer-group text-info',
        'subject' => 'fas fa-book text-danger',
        'feature' => 'fas fa-star text-warning',
        'content' => 'fas fa-folder text-warning'
    ];
    
    $iconClass = $iconClasses[$node['type'] ?? 'content'] ?? 'fas fa-folder text-warning';
    $hasChildren = isset($node['children']) && count($node['children']) > 0;
    $hasFiles = isset($node['files']) && count($node['files']) > 0;
    $isContentType = isset($node['type']) && $node['type'] == 'content';
    $isExpanded = isset($node['isExpanded']) ? $node['isExpanded'] : false;
    
    // Calculate completion stats for content nodes
    $completedCount = 0;
    $totalCount = 0;
    $progressPercentage = 0;
    
    if ($isContentType && $hasFiles) {
        $completedCount = collect($node['files'])->where('is_completed', 1)->count();
        $totalCount = count($node['files']);
        $progressPercentage = $totalCount > 0 ? ($completedCount / $totalCount) * 100 : 0;
    }
    
    $level = $level ?? 0;
@endphp

<div class="file-manager-tree-item" style="padding-left: {{ ($level * 16) + 4 }}px;">
    <div class="file-manager-tree-row" 
         id="content_{{ $node['id'] }}"
         data-node-id="{{ $node['id'] }}"
         data-has-children="{{ $hasChildren ? 'true' : 'false' }}"
         data-has-files="{{ $hasFiles ? 'true' : 'false' }}">
        
        {{-- Chevron for expand/collapse --}}
        <div class="file-manager-chevron">
            @if($hasChildren || ($hasFiles && !$isContentType) || ($hasFiles && $isContentType))
                <button type="button" 
                        class="file-manager-toggle-btn"
                        onclick="toggleNode('{{ $node['id'] }}', '{{ $node['id'] }}'); return false;"
                        aria-label="Toggle folder">
                    <i class="fas fa-chevron-right file-manager-chevron-icon" id="arrow_{{ $node['id'] }}"></i>
                </button>
            @else
                <span class="file-manager-chevron-spacer"></span>
            @endif
        </div>
        
        {{-- Folder/Node Icon and Name --}}
        <div class="file-manager-item-content" 
             @if($hasFiles && $isContentType)
             onclick="toggleNode('{{ $node['id'] }}', '{{ $node['id'] }}')"
             style="cursor: pointer;"
             @elseif(!$hasChildren && !($hasFiles && !$isContentType))
             onclick="handleNodeClick('{{ $node['id'] }}', '{{ $isContentType }}')"
             style="cursor: pointer;"
             @endif>
            <i class="{{ $iconClass }} file-manager-icon"></i>
            <div class="node-info" style="flex: 1; min-width: 0;">
                <span class="file-manager-name">{{ $node['name'] }}</span>
                <small style="display: block; color: #6b7280; font-size: 12px;">
                    @if($isContentType && $hasFiles)
                        <span id="chapter_progress_{{ $node['id'] }}">{{ $completedCount }}/{{ $totalCount }}</span>
                    @elseif($hasChildren)
                        {{ count($node['children']) }} Content
                    @elseif($hasFiles)
                        {{ count($node['files']) }} files
                    @else
                        No content
                    @endif
                </small>
            </div>
            
            {{-- Badge/Info --}}
            <span class="file-manager-badge">
                @if($isContentType && $hasFiles)
                    <span class="badge bg-info">{{ $completedCount }}/{{ $totalCount }}</span>
                @elseif($hasChildren)
                    <span class="badge bg-secondary">{{ count($node['children']) }}</span>
                @elseif($hasFiles)
                    <span class="badge bg-secondary">{{ count($node['files']) }}</span>
                @endif
            </span>
        </div>
    </div>
    
    {{-- Children/Folders --}}
    @if($hasChildren)
        <div class="file-manager-tree-children" id="children_{{ $node['id'] }}" style="display: none;">
            @foreach($node['children'] as $child)
                @include('student.partials.tree-node', ['node' => $child, 'level' => $level + 1])
            @endforeach
        </div>
    @endif
    
    {{-- Files for Content Type --}}
    @if($hasFiles && $isContentType)
        <div class="chapter-actions mt-3" id="chapter_actions_{{ $node['id'] }}" style="display: none; padding-left: {{ (($level + 1) * 16) + 20 }}px;">
            {{-- Progress Bar --}}
            <div class="progress mb-2" style="height: 20px;">
                <div class="progress-bar bg-success chapter-progress-bar" 
                     id="progress_bar_{{ $node['id'] }}"
                     role="progressbar" 
                     style="width: {{ $progressPercentage }}%"
                     aria-valuenow="{{ $progressPercentage }}" 
                     aria-valuemin="0" 
                     aria-valuemax="100">
                    {{ round($progressPercentage) }}%
                </div>
            </div>
            
            {{-- Action Buttons --}}
            <div class="d-flex gap-2 action-buttons" id="action_buttons_{{ $node['id'] }}">
                @if($completedCount == 0)
                    <button class="btn btn-warning btn-sm" 
                            onclick="startChapter({{ $node['id'] }})"
                            id="start_btn_{{ $node['id'] }}">
                        <i class="fas fa-play me-1"></i> Start
                    </button>
                @elseif($completedCount == $totalCount)
                    <button class="btn btn-success btn-sm" disabled>
                        <i class="fas fa-check me-1"></i> Completed
                    </button>
                    <button class="btn btn-outline-secondary btn-sm" 
                            onclick="restartChapter({{ $node['id'] }})"
                            id="restart_btn_{{ $node['id'] }}">
                        <i class="fas fa-redo me-1"></i> Restart
                    </button>
                @else
                    <button class="btn btn-outline-secondary btn-sm" 
                            onclick="restartChapter({{ $node['id'] }})"
                            id="restart_btn_{{ $node['id'] }}">
                        <i class="fas fa-redo me-1"></i> Restart
                    </button>
                    <button class="btn btn-info btn-sm" 
                            onclick="continueChapter({{ $node['id'] }})"
                            id="continue_btn_{{ $node['id'] }}">
                        <i class="fas fa-play-circle me-1"></i> Continue
                    </button>
                @endif
            </div>
            
            {{-- Files List --}}
            <div class="file-list mt-3 d-none" id="file_list_{{ $node['id'] }}">
                @foreach($node['files'] as $index => $file)
                    @php
                        $fileIcon = 'fas fa-file';
                        $fileClass = 'file-document';
                        
                        if (isset($file['mime_type']) && str_contains($file['mime_type'], 'pdf')) {
                            $fileIcon = 'fas fa-file-pdf';
                            $fileClass = 'file-pdf';
                        } elseif (isset($file['mime_type']) && str_contains($file['mime_type'], 'video')) {
                            $fileIcon = 'fas fa-video';
                            $fileClass = 'file-video';
                        } elseif (isset($file['mime_type']) && str_contains($file['mime_type'], 'image')) {
                            $fileIcon = 'fas fa-image';
                            $fileClass = 'file-image';
                        }
                    @endphp
                    
                    <div class="file-item mb-2 p-2 border rounded">
                        <div class="d-flex align-items-center">
                            <div class="file-icon {{ $fileClass }} me-3">
                                <i class="{{ $fileIcon }}"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0">{{ $file['name'] }}</h6>
                                <small class="text-muted">{{ $file['mime_type'] ?? 'File' }}</small>
                            </div>
                            <div>
                                @if($file['is_completed'])
                                    <span class="badge bg-success">
                                        <i class="fas fa-check"></i> Completed
                                    </span>
                                @else
                                    <button class="btn btn-sm btn-outline-primary"
                                            onclick="viewFile({{ $file['id'] }}, {{ $node['id'] }})">
                                        <i class="fas fa-eye"></i> View
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
    
    {{-- Files for Non-Content Type --}}
    @if($hasFiles && !$isContentType)
        <div class="file-manager-tree-children" id="children_{{ $node['id'] }}" style="display: none;">
            <div class="file-manager-file-list" id="file_list_{{ $node['id'] }}">
                @if(count($node['files']) > 0)
                    @foreach($node['files'] as $file)
                        @php
                            $fileIcon = 'fas fa-file';
                            $fileClass = 'text-secondary';
                            
                            if (isset($file['mime_type']) && str_contains($file['mime_type'], 'pdf')) {
                                $fileIcon = 'fas fa-file-pdf';
                                $fileClass = 'text-danger';
                            } elseif (isset($file['mime_type']) && str_contains($file['mime_type'], 'video')) {
                                $fileIcon = 'fas fa-video';
                                $fileClass = 'text-primary';
                            } elseif (isset($file['mime_type']) && str_contains($file['mime_type'], 'image')) {
                                $fileIcon = 'fas fa-image';
                                $fileClass = 'text-success';
                            }
                        @endphp
                        
                        <div class="file-manager-file-item" style="padding-left: {{ (($level + 1) * 16) + 20 }}px;">
                            <div class="d-flex align-items-center py-1">
                                <i class="{{ $fileIcon }} {{ $fileClass }} me-2" style="width: 16px;"></i>
                                <span class="flex-grow-1">{{ $file['name'] }}</span>
                                <button class="btn btn-sm {{ $file['is_completed'] ? 'btn-success' : 'btn-outline-primary' }}"
                                        onclick="viewMedia({{ $file['id'] }}, {{ $node['id'] }}, '{{ addslashes($file['name']) }}', '{{ $file['mime_type'] ?? '' }}', {{ $file['is_completed'] ? 'true' : 'false' }})">
                                    <i class="fas fa-{{ $file['is_completed'] ? 'check' : 'play' }}"></i>
                                </button>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="text-muted text-center py-2" style="padding-left: {{ (($level + 1) * 16) + 20 }}px;">
                        No files available
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>
