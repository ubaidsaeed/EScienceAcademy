@php
    $total = $folder['totalLectures'] ?? 0;
    $completed = $folder['completedCount'] ?? 0;
    $progress = $total > 0 ? ($completed / $total) * 100 : 0;
    $status = $progress == 100 ? 'completed' : ($progress > 0 ? 'in-progress' : 'locked');
@endphp

<div class="col-lg-6">
    <div class="chapter-card h-100">
        <div class="p-4">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-{{ $status === 'completed' ? 'success' : ($status === 'in-progress' ? 'primary' : 'light') }} 
                                 bg-opacity-10 rounded-circle p-3">
                        <i class="fas fa-{{ $status === 'completed' ? 'check' : 'play' }}-circle 
                           text-{{ $status === 'completed' ? 'success' : 'primary' }} fs-4"></i>
                    </div>
                    <div>
                        <h5 class="mb-1">{{ $folder['text'] }}</h5>
                        <p class="text-muted small mb-0">{{ $total }} lessons • ~{{ $folder['duration'] ?? '3 hrs' }}</p>
                    </div>
                </div>
                @if($progress == 100)
                    <span class="badge bg-success">Completed</span>
                @endif
            </div>

            <div class="chapter-progress mt-3">
                <div class="chapter-progress-bar" style="width: {{ $progress }}%"></div>
            </div>
            <div class="d-flex justify-content-between align-items-center mt-2">
                <small class="text-muted">{{ $completed }} / {{ $total }} completed</small>
                <strong class="{{ $progress == 100 ? 'text-success' : 'text-primary' }}">{{ number_format($progress) }}%</strong>
            </div>

            <div class="mt-4 d-flex gap-2">
                @if($progress == 100)
                    <button class="btn btn-success w-100" onclick="startChapter({{ $folder['id'] }}, '{{ addslashes($folder['text']) }}')">
                        <i class="fas fa-redo"></i> Review
                    </button>
                @elseif($progress > 0)
                    <button class="btn btn-primary w-100" onclick="startChapter({{ $folder['id'] }}, '{{ addslashes($folder['text']) }}')">
                        <i class="fas fa-play"></i> Continue
                    </button>
                @else
                    <button class="btn btn-outline-primary w-100" onclick="startChapter({{ $folder['id'] }}, '{{ addslashes($folder['text']) }}')">
                        <i class="fas fa-play"></i> Start Learning
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>