@php
    $hasChildren = isset($item['children']) && count($item['children']) > 0;
    $hasFiles = isset($item['files']) && count($item['files']) > 0;
    $isContentType = isset($item['type']) && $item['type'] == 'content';
    
    // Get icon based on type
    $iconClasses = [
        'board' => 'fas fa-school text-primary',
        'level' => 'fas fa-layer-group text-info',
        'subject' => 'fas fa-book text-danger',
        'feature' => 'fas fa-star text-warning',
        'content' => 'fas fa-folder text-warning'
    ];
    $iconClass = $iconClasses[$item['type'] ?? 'content'] ?? 'fas fa-folder text-warning';
    
    // Calculate progress for content nodes
    $completedCount = 0;
    $totalCount = 0;
    $progressPercentage = 0;
    
    if ($isContentType && $hasFiles) {
        $completedCount = collect($item['files'])->where('is_completed', 1)->count();
        $totalCount = count($item['files']);
        $progressPercentage = $totalCount > 0 ? ($completedCount / $totalCount) * 100 : 0;
    }
    
    $viewMode = $viewMode ?? 'grid';
@endphp

@if($viewMode === 'grid')
    {{-- Grid Card View --}}
    <div class="col-md-4 col-lg-3">
        <div class="file-manager-card" 
             data-item-id="{{ $item['id'] }}"
             data-item-type="{{ $item['type'] ?? 'content' }}"
             data-has-children="{{ $hasChildren ? 'true' : 'false' }}"
             data-has-files="{{ $hasFiles ? 'true' : 'false' }}">
            
            {{-- Card Icon --}}
            <div class="file-manager-card-icon">
                <i class="{{ $iconClass }}"></i>
            </div>
            
            {{-- Card Body --}}
            <div class="file-manager-card-body">
                <h6 class="file-manager-card-title" title="{{ $item['name'] }}">
                    {{ Str::limit($item['name'], 20) }}
                </h6>
                
                @if($isContentType && $hasFiles)
                    <div class="file-manager-card-progress mb-2">
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-success" 
                                 style="width: {{ $progressPercentage }}%"></div>
                        </div>
                        <small class="text-muted">{{ $completedCount }}/{{ $totalCount }}</small>
                    </div>
                @elseif($hasChildren)
                    <small class="text-muted">{{ count($item['children']) }} items</small>
                @elseif($hasFiles)
                    <small class="text-muted">{{ count($item['files']) }} files</small>
                @else
                    <small class="text-muted">Empty</small>
                @endif
            </div>
            
            {{-- Card Actions --}}
            <div class="file-manager-card-actions">
                @if($hasChildren || ($hasFiles && !$isContentType))
                    <button class="btn btn-sm btn-primary" onclick="navigateToFolder('{{ $item['id'] }}')">
                        <i class="fas fa-folder-open"></i> Open
                    </button>
                @elseif($isContentType && $hasFiles)
                    <div class="d-flex gap-1 flex-wrap justify-content-center">
                        @if($completedCount == 0)
                            <button class="btn btn-sm btn-warning" 
                                    onclick="startChapter({{ $item['id'] }})"
                                    id="start_btn_{{ $item['id'] }}">
                                <i class="fas fa-play me-1"></i> Start
                            </button>
                        @elseif($completedCount == $totalCount)
                            <button class="btn btn-sm btn-success" disabled>
                                <i class="fas fa-check me-1"></i> Completed
                            </button>
                            <button class="btn btn-sm btn-outline-secondary" 
                                    onclick="restartChapter({{ $item['id'] }})"
                                    id="restart_btn_{{ $item['id'] }}">
                                <i class="fas fa-redo me-1"></i> Restart
                            </button>
                        @else
                            <button class="btn btn-sm btn-outline-secondary" 
                                    onclick="restartChapter({{ $item['id'] }})"
                                    id="restart_btn_{{ $item['id'] }}">
                                <i class="fas fa-redo me-1"></i> Restart
                            </button>
                            <button class="btn btn-sm btn-info" 
                                    onclick="continueChapter({{ $item['id'] }})"
                                    id="continue_btn_{{ $item['id'] }}">
                                <i class="fas fa-play-circle me-1"></i> Continue
                            </button>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
@else
    {{-- List View --}}
    <div class="file-manager-list-item d-flex align-items-center p-3 border-bottom"
         data-item-id="{{ $item['id'] }}"
         data-item-type="{{ $item['type'] ?? 'content' }}"
         data-has-children="{{ $hasChildren ? 'true' : 'false' }}"
         data-has-files="{{ $hasFiles ? 'true' : 'false' }}">
        
        <div class="col-md-4 d-flex align-items-center">
            <i class="{{ $iconClass }} me-2 fs-5"></i>
            <div class="flex-grow-1" style="min-width: 0;">
                <h6 class="mb-0 text-truncate" style="max-width: 200px;">{{ $item['name'] }}</h6>
                @if($isContentType && $hasFiles)
                    <small class="text-muted">{{ $completedCount }}/{{ $totalCount }} completed</small>
                @elseif($hasChildren)
                    <small class="text-muted">{{ count($item['children']) }} items</small>
                @elseif($hasFiles)
                    <small class="text-muted">{{ count($item['files']) }} files</small>
                @endif
            </div>
        </div>
        
        <div class="col-md-2">
            <span class="badge bg-secondary">{{ ucfirst($item['type'] ?? 'content') }}</span>
        </div>
        
        <div class="col-md-2">
            @if($isContentType && $hasFiles)
                <div class="progress" style="height: 18px;">
                    <div class="progress-bar bg-success" style="width: {{ $progressPercentage }}%">
                        <small style="font-size: 10px;">{{ round($progressPercentage) }}%</small>
                    </div>
                </div>
            @else
                <span class="text-muted">-</span>
            @endif
        </div>
        
        <div class="col-md-4">
            @if($hasChildren || ($hasFiles && !$isContentType))
                <button class="btn btn-sm btn-primary" onclick="navigateToFolder('{{ $item['id'] }}')">
                    <i class="fas fa-folder-open me-1"></i> Open
                </button>
            @elseif($isContentType && $hasFiles)
                <div class="d-flex gap-2 action-buttons" id="action_buttons_{{ $item['id'] }}">
                    @if($completedCount == 0)
                        <button class="btn btn-sm btn-warning" 
                                onclick="startChapter({{ $item['id'] }})"
                                id="start_btn_{{ $item['id'] }}">
                            <i class="fas fa-play me-1"></i> Start
                        </button>
                    @elseif($completedCount == $totalCount)
                        <button class="btn btn-sm btn-success" disabled>
                            <i class="fas fa-check me-1"></i> Completed
                        </button>
                        <button class="btn btn-sm btn-outline-secondary" 
                                onclick="restartChapter({{ $item['id'] }})"
                                id="restart_btn_{{ $item['id'] }}">
                            <i class="fas fa-redo me-1"></i> Restart
                        </button>
                    @else
                        <button class="btn btn-sm btn-outline-secondary" 
                                onclick="restartChapter({{ $item['id'] }})"
                                id="restart_btn_{{ $item['id'] }}">
                            <i class="fas fa-redo me-1"></i> Restart
                        </button>
                        <button class="btn btn-sm btn-info" 
                                onclick="continueChapter({{ $item['id'] }})"
                                id="continue_btn_{{ $item['id'] }}">
                            <i class="fas fa-play-circle me-1"></i> Continue
                        </button>
                    @endif
                </div>
            @endif
        </div>
    </div>
@endif

{{-- Hidden data for navigation --}}
<div style="display: none;" 
     data-folder-data="{{ json_encode($item) }}"
     data-folder-id="{{ $item['id'] }}">
</div>

