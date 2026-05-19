@props([
    'folder' => [],
    'openFolders' => [],
    'level' => 0,
    'isChild' => false,
    'parentId' => null
])

@php
    $folderId        = $folder['id'] ?? 0;
    $folderName      = $folder['text'] ?? $folder['name'] ?? 'Untitled';
    $chapterId       = $folder['chapter_id'] ?? $folderId;
    $totalPages      = $folder['totalLectures'] ?? $folder['totalMediaCount'] ?? $folder['total_pages'] ?? 0;
    $learnedPages    = $folder['completedCount'] ?? $folder['learned_pages'] ?? 0;
    $progressPercent = $totalPages > 0 ? ($learnedPages / $totalPages) * 100 : 0;
    $hasChildren     = !empty($folder['children']) && count($folder['children']) > 0;
    
    // Check if this is a leaf node (no children or children array is empty)
    $isLeafNode = !$hasChildren;
    
    // Check if this folder has media/files directly
    $hasDirectMedia = isset($folder['has_media']) ? $folder['has_media'] : ($totalPages > 0);

    // Determine if expanded
    $isExpanded = in_array($folderId, $openFolders) || $level < 2; // Auto-expand first few levels
    
    // Determine which buttons to show
    $showStart     = $learnedPages == 0 && $totalPages > 0;
    $showContinue  = $learnedPages > 0 && $learnedPages < $totalPages;
    $showComplete  = $learnedPages == $totalPages && $totalPages > 0;

    $accordionId = "folder_{$folderId}";
    $collapseId  = "collapse_{$folderId}";
@endphp

@if($isLeafNode || ($level === 0 && !$hasChildren))
    {{-- Display as table row for leaf nodes --}}
    <tr>
        <td>
            <div class="d-flex align-items-center">
                {{-- Indentation for nested levels --}}
                @for($i = 0; $i < $level; $i++)
                    <span class="ms-{{ $i * 2 }}"></span>
                @endfor
                
                <h6 class="mb-0 tx-inverse tx-15">
                    @if($hasDirectMedia && $totalPages > 0)
                        <i class="fas fa-file me-2 text-primary"></i>
                    @else
                        <i class="fas fa-folder me-2 text-warning"></i>
                    @endif
                    {{ $folderName }}
                </h6>
            </div>
            @if($hasDirectMedia && $totalPages > 0)
                <div class="progress progress-md my-2 progress-bar-container" style="max-width: 300px;">
                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-info"
                         style="width: {{ $progressPercent }}%"
                         id="progress_{{ $folderId }}">
                        {{ number_format($progressPercent, 1) }}%
                    </div>
                </div>
            @endif
        </td>
        <td class="align-middle">
            @if($hasDirectMedia)
                {{ $totalPages }}
            @else
                -
            @endif
        </td>
        <td class="align-middle" id="count_{{ $folderId }}">
            @if($hasDirectMedia)
                {{ $learnedPages }}
            @else
                -
            @endif
        </td>
        <td class="align-middle">
            @if($hasDirectMedia && $totalPages > 0)
                @if($showComplete)
                    <button class="btn btn-success btn-sm me-2" id="complete_{{ $folderId }}" disabled>
                        <i class="fas fa-check-circle me-1"></i> Complete
                    </button>
                    <a href="{{ route('student.take-exam', $folderId) }}" class="btn btn-primary btn-sm me-2">
                        <i class="fas fa-file-alt me-1"></i> Exam
                    </a>
                    <button class="btn btn-outline-secondary btn-sm" onclick="restartFolder('{{ $folderId }}', '{{ $chapterId }}')">
                        <i class="fas fa-redo me-1"></i> Restart
                    </button>
                @elseif($showStart)
                    <button class="btn btn-warning btn-sm" onclick="startCourse('{{ $folderId }}', '{{ $chapterId }}')">
                        <i class="fas fa-play me-1"></i> Start
                    </button>
                @elseif($showContinue)
                    <button class="btn btn-primary btn-sm me-2" onclick="continueCourse('{{ $folderId }}', '{{ $chapterId }}')">
                        <i class="fas fa-play-circle me-1"></i> Continue
                    </button>
                    <button class="btn btn-outline-secondary btn-sm" onclick="restartFolder('{{ $folderId }}', '{{ $chapterId }}')">
                        <i class="fas fa-redo me-1"></i> Restart
                    </button>
                @else
                    <span class="text-muted">No content</span>
                @endif
            @else
                <span class="text-muted">No content</span>
            @endif
        </td>
    </tr>
@else
    {{-- Display as accordion for folders with children --}}
    <div class="accordion-item mb-2">
        <h2 class="accordion-header" id="heading_{{ $folderId }}">
            <button class="accordion-button {{ $isExpanded ? '' : 'collapsed' }}" 
                    type="button" 
                    data-bs-toggle="collapse" 
                    data-bs-target="#{{ $collapseId }}"
                    aria-expanded="{{ $isExpanded ? 'true' : 'false' }}"
                    aria-controls="{{ $collapseId }}">
                <i class="fas fa-folder me-2 text-warning"></i>
                <strong>{{ $folderName }}</strong>
                @if($totalPages > 0)
                    <span class="badge bg-info ms-2">
                        {{ $learnedPages }}/{{ $totalPages }}
                    </span>
                @endif
            </button>
        </h2>
        <div id="{{ $collapseId }}" 
             class="accordion-collapse collapse {{ $isExpanded ? 'show' : '' }}"
             aria-labelledby="heading_{{ $folderId }}"
             data-bs-parent="#mainFolderAccordion">
            <div class="accordion-body p-3">
                @if($hasChildren)
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Chapter Name</th>
                                <th>Total Pages</th>
                                <th>Learned Pages</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($folder['children'] as $child)
                                @include('student.partials.folder-accordion', [
                                    'folder' => $child,
                                    'openFolders' => $openFolders,
                                    'level' => $level + 1,
                                    'parentId' => $folderId
                                ])
                            @endforeach
                        </tbody>
                    </table>
                @endif
                
                {{-- Show direct media if this folder has it --}}
                @if($hasDirectMedia && !$hasChildren)
                    <div class="card mt-2">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="mb-0">{{ $folderName }}</h6>
                                <div class="progress" style="width: 150px; height: 20px;">
                                    <div class="progress-bar bg-info" 
                                         style="width: {{ $progressPercent }}%">
                                        {{ number_format($progressPercent, 1) }}%
                                    </div>
                                </div>
                            </div>
                            <div class="row align-items-center">
                                <div class="col-md-3">
                                    <span class="text-muted">Total Pages: {{ $totalPages }}</span>
                                </div>
                                <div class="col-md-3">
                                    <span class="text-muted">Learned: {{ $learnedPages }}</span>
                                </div>
                                <div class="col-md-6 text-end">
                                    @if($showComplete)
                                        <button class="btn btn-success btn-sm me-2" disabled>
                                            <i class="fas fa-check-circle me-1"></i> Complete
                                        </button>
                                        <a href="{{ route('student.take-exam', $folderId) }}" class="btn btn-primary btn-sm me-2">
                                            <i class="fas fa-file-alt me-1"></i> Exam
                                        </a>
                                        <button class="btn btn-outline-secondary btn-sm" onclick="restartFolder('{{ $folderId }}', '{{ $chapterId }}')">
                                            <i class="fas fa-redo me-1"></i> Restart
                                        </button>
                                    @elseif($showStart)
                                        <button class="btn btn-warning btn-sm" onclick="startCourse('{{ $folderId }}', '{{ $chapterId }}')">
                                            <i class="fas fa-play me-1"></i> Start
                                        </button>
                                    @elseif($showContinue)
                                        <button class="btn btn-primary btn-sm me-2" onclick="continueCourse('{{ $folderId }}', '{{ $chapterId }}')">
                                            <i class="fas fa-play-circle me-1"></i> Continue
                                        </button>
                                        <button class="btn btn-outline-secondary btn-sm" onclick="restartFolder('{{ $folderId }}', '{{ $chapterId }}')">
                                            <i class="fas fa-redo me-1"></i> Restart
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endif