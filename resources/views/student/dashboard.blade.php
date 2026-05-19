@extends('layouts.admin.app')
@section('title', 'Student Dashboard')

@push('style')
    <style>
        :root {
            --primary: #4361ee;
            --primary-dark: #3a56d4;
            --secondary: #2dd4bf;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --dark: #0f172a;
            --light: #f8fafc;
            --gray: #64748b;
            --border: #e2e8f0;
            --shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            --radius: 1rem;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .dashboard-wrapper {
            background: linear-gradient(135deg, #f0f4ff 0%, #e0e7ff 100%);
            min-height: 100vh;
            padding: 1.5rem 0;
        }

        .swal-custom-z {
            z-index: 999 !important;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            transition: var(--transition);
        }

        .glass-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(67, 97, 238, 0.15);
        }

        .header-gradient {
            background: linear-gradient(120deg, var(--primary) 0%, #667eea 70%, #764ba2 100%);
            border-radius: var(--radius);
            padding: 2.5rem 2rem;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .progress-ring {
            width: 140px;
            height: 140px;
        }

        .stat-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.5rem;
            margin: 2rem 0;
        }

        .stat-card {
            background: white;
            padding: 1.75rem;
            border-radius: var(--radius);
            border: 1px solid var(--border);
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        /* Hierarchy Tree Styles */
        /* File Manager Style Tree */
        .hierarchy-tree {
            position: relative;
            font-size: 14px;
        }

        .file-manager-tree-item {
            margin: 0;
            position: relative;
        }

        .file-manager-tree-row {
            display: flex;
            align-items: center;
            gap: 4px;
            padding: 6px 8px;
            border-radius: 4px;
            transition: background-color 0.15s ease;
            min-height: 32px;
        }

        .file-manager-tree-row:hover {
            background-color: #f3f4f6;
        }

        .file-manager-chevron {
            width: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .file-manager-toggle-btn {
            background: none;
            border: none;
            padding: 2px 4px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 3px;
            transition: background-color 0.15s ease;
        }

        .file-manager-toggle-btn:hover {
            background-color: #e5e7eb;
        }

        .file-manager-chevron-icon {
            font-size: 12px;
            color: #6b7280;
            transition: transform 0.2s ease;
        }

        .file-manager-chevron-icon.rotated {
            transform: rotate(90deg);
        }

        .file-manager-chevron-spacer {
            width: 12px;
            display: inline-block;
        }

        .file-manager-item-content {
            display: flex;
            align-items: center;
            gap: 8px;
            flex: 1;
            min-width: 0;
        }

        .file-manager-icon {
            font-size: 16px;
            width: 18px;
            flex-shrink: 0;
        }

        .file-manager-name {
            flex: 1;
            min-width: 0;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            color: #374151;
            font-weight: 500;
        }

        .file-manager-badge {
            flex-shrink: 0;
            margin-left: auto;
        }

        .file-manager-badge .badge {
            font-size: 11px;
            padding: 2px 6px;
        }

        .file-manager-tree-children {
            display: none;
            animation: fadeIn 0.2s ease;
        }

        .file-manager-tree-children.show {
            display: block;
        }

        .file-manager-files-section {
            padding: 12px 0 12px 24px;
            animation: fadeIn 0.2s ease;
        }

        .file-manager-progress {
            margin-bottom: 12px;
        }

        .file-manager-progress small {
            font-size: 12px;
            display: block;
            margin-top: 4px;
        }

        .file-manager-actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .file-manager-file-list {
            margin-top: 8px;
        }

        .file-manager-file-item {
            padding: 4px 0;
            transition: background-color 0.15s ease;
            border-radius: 4px;
        }

        .file-manager-file-item:hover {
            background-color: #f9fafb;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        /* File Manager View Styles */
        .file-manager-view {
            min-height: 400px;
        }

        .file-manager-breadcrumb {
            background: #f8f9fa;
            padding: 12px 16px;
            border-radius: 8px;
        }

        .file-manager-breadcrumb .breadcrumb {
            margin: 0;
            background: transparent;
        }

        .view-toggle-buttons .btn.active {
            background-color: var(--primary);
            color: white;
            border-color: var(--primary);
        }

        /* Grid View */
        .file-manager-grid {
            min-height: 300px;
        }

        .file-manager-card {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            transition: all 0.3s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
            cursor: pointer;
        }

        .file-manager-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            border-color: var(--primary);
        }

        .file-manager-card-icon {
            font-size: 48px;
            margin-bottom: 12px;
            color: var(--primary);
        }

        .file-manager-card-body {
            flex: 1;
        }

        .file-manager-card-title {
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 8px;
            color: #374151;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .file-manager-card-progress {
            margin-top: 8px;
        }

        .file-manager-card-actions {
            margin-top: 12px;
        }

        .file-manager-card-actions .btn {
            width: 100%;
        }

        /* List View */
        .file-manager-list {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            overflow: hidden;
        }

        .file-manager-list-item {
            transition: background-color 0.2s ease;
        }

        .file-manager-list-item:hover {
            background-color: #f8f9fa;
        }

        .file-manager-list-item .list-header {
            background-color: #f8f9fa;
            font-weight: 600;
        }

        /* Progress States Styles */
        .progress-state-item {
            padding: 12px;
            background: #f8f9fa;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .progress-state-item:hover {
            background: #f1f3f5;
        }

        .state-indicator {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            display: inline-block;
        }

        /* Mobile horizontal scroll for list view - compact size */
        @media (max-width: 768px) {
            .file-manager-list {
                overflow-x: auto;
                overflow-y: visible;
                -webkit-overflow-scrolling: touch;
                scroll-behavior: smooth;
            }
            
            .file-manager-list-item {
                min-width: 400px;
                flex-wrap: nowrap;
                padding: 10px 6px !important;
            }
            
            .file-manager-list-item > div {
                flex-shrink: 0;
                padding: 0 6px;
            }
            
            .file-manager-list-item .col-md-4:first-child {
                min-width: 140px;
                max-width: 140px;
            }
            
            .file-manager-list-item .col-md-2 {
                min-width: 60px;
                max-width: 60px;
                text-align: center;
            }
            
            .file-manager-list-item .col-md-4:last-child {
                min-width: 140px;
            }
            
            .list-header {
                min-width: 400px;
                padding: 8px 6px !important;
            }
            
            .list-header > div {
                flex-shrink: 0;
                padding: 0 6px;
            }
            
            /* Prevent auto-scroll on click */
            .file-manager-list-item,
            .file-manager-list-item * {
                scroll-margin: 0;
            }
            
            /* Smaller buttons on mobile */
            .file-manager-list-item .btn {
                padding: 4px 8px;
                font-size: 12px;
            }
            
            .file-manager-list-item .btn i {
                font-size: 11px;
            }
        }

        /* File List Styles */
        .file-list {
            margin-top: 1rem;
        }

        .file-item {
            background: white;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 0.5rem;
            border: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 1rem;
            transition: var(--transition);
        }

        .file-item:hover {
            background: #f8fafc;
            border-color: var(--primary);
            transform: translateX(5px);
        }

        .file-icon {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            color: white;
            flex-shrink: 0;
        }

        .file-pdf {
            background: #ef4444;
        }

        .file-video {
            background: #3b82f6;
        }

        .file-image {
            background: #10b981;
        }

        .file-document {
            background: #f59e0b;
        }

        .file-info {
            flex: 1;
            min-width: 0;
        }

        .file-info h6 {
            margin: 0;
            font-size: 0.95rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .file-info small {
            color: var(--gray);
            font-size: 0.8rem;
            display: block;
        }

        /* Media Modal Styles */
        .media-modal-content {
            /*max-height: 80vh;*/
            overflow-y: auto;
        }

        .media-viewer {
            width: 100%;
            min-height: 400px;
            max-height: 600px;
            border-radius: 12px;
            /*overflow: hidden;*/
            background: #f8fafc;
            margin-bottom: 1rem;
            /*display: flex;*/
            align-items: center;
            justify-content: center;
        }

        /* Slideshow styles */
        .slideshow-container {
            width: 100%;
            position: relative;
            margin: auto;
        }

        .mySlides {
            display: none;
            width: 100%;
        }

        .mySlides.fade {
            animation: fade 1.5s;
        }

        @keyframes fade {
            from {
                opacity: .4
            }

            to {
                opacity: 1
            }
        }

        .prev,
        .next {
            cursor: pointer;
            position: absolute;
            top: 50%;
            width: auto;
            padding: 16px;
            margin-top: -22px;
            color: white;
            font-weight: bold;
            font-size: 18px;
            transition: 0.6s ease;
            border-radius: 0 3px 3px 0;
            user-select: none;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .next {
            right: 0;
            border-radius: 3px 0 0 3px;
        }

        .prev:hover,
        .next:hover {
            background-color: rgba(0, 0, 0, 0.8);
        }

        /* Video container */
        .video-container {
            position: relative;
            padding-bottom: 56.25%;
            /* 16:9 Aspect Ratio */
            height: 0;
            overflow: hidden;
            background: #000;
            width: 100%;
        }

        .video-container iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }

        /* PDF container */
        .pdf-container {
            width: 100%;
            height: 500px;
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
            overflow: hidden;
        }

        .pdf-container iframe {
            width: 100%;
            height: 100%;
        }

        /* Slide counter */
        #slideCounter {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            background: rgba(0, 0, 0, 0.5);
            color: white;
            border-radius: 50px;
            font-size: 0.875rem;
        }
        .custom-toast {
    position: fixed;
    top: 20px;
    right: 20px;
    background: white;
    padding: 15px 20px;
    border-radius: 8px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    display: flex;
    align-items: center;
    gap: 10px;
    z-index: 10000;
    transform: translateX(400px);
    opacity: 0;
    transition: all 0.3s ease;
}

.custom-toast.show {
    transform: translateX(0);
    opacity: 1;
}

.toast-success {
    border-left: 4px solid #10b981;
}

.toast-success i {
    color: #10b981;
}

.toast-error {
    border-left: 4px solid #ef4444;
}

.toast-error i {
    color: #ef4444;
}           
/* Bootstrap Carousel Styles */
#mediaCarousel {
    min-height: 400px;
}

#mediaCarousel .carousel-item {
    min-height: 400px;
}

#mediaCarousel .carousel-control-prev,
#mediaCarousel .carousel-control-next {
    width: 50px;
    height: 50px;
    background: rgba(0, 0, 0, 0.5);
    border-radius: 50%;
    top: 50%;
    transform: translateY(-50%);
}

#mediaCarousel .carousel-control-prev {
    left: 10px;
}

#mediaCarousel .carousel-control-next {
    right: 10px;
}

#mediaCarousel .carousel-control-prev:hover,
#mediaCarousel .carousel-control-next:hover {
    background: rgba(0, 0, 0, 0.8);
}

/* Slide counter */
#slideCounter {
    font-size: 0.95rem;
    font-weight: 500;
    padding: 0.5rem 1rem;
}

/* Video responsive */
.ratio-16x9 {
    --bs-aspect-ratio: 56.25%;
}
.include-title {
       background-color: #ebdbdb !important;
      border-radius: 12px;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
      align-items: center;
      cursor: pointer;
      transition: all 0.3s;
      border: 2px solid transparent;
        border-top-color: transparent;
        border-right-color: transparent;
        border-bottom-color: transparent;
        border-left-color: transparent;
        border-color: #ff0024;
      background: rgba(0, 114, 255, 0.05);
    }
    </style>
@endpush

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="app-content main-content">
        <div class="side-app">
            <div class="dashboard-wrapper">
                <div class="container-fluid px-4">
                    <!-- Welcome Header -->
                    <div class="header-gradient text-white mb-4">
                        <div class="row align-items-center">
                            <div class="col-lg-8">
                                <h1 class="display-5 fw-bold mb-2">Welcome back, {{ Auth::user()->name }}! 🚀</h1>
                                <p class="lead mb-0 opacity-90">You're doing amazing! Keep up the momentum.</p>
                            </div>
                            <div class="col-lg-4 text-lg-end">
                                <div class="d-inline-block position-relative">
                                    <svg class="progress-ring" viewBox="0 0 140 140">
                                        <circle cx="70" cy="70" r="62" stroke="#e0e7ff" stroke-width="12"
                                            fill="none" />
                                        <circle cx="70" cy="70" r="62" stroke="url(#gradient)"
                                            stroke-width="12" fill="none" stroke-dasharray="390"
                                            stroke-dashoffset="{{ 390 - (390 * ($completionPercentage ?? 0)) / 100 }}"
                                            stroke-linecap="round" />
                                        <defs>
                                            <linearGradient id="gradient" x1="0%" y1="0%" x2="100%"
                                                y2="100%">
                                                <stop offset="0%" stop-color="#667eea" />
                                                <stop offset="100%" stop-color="#764ba2" />
                                            </linearGradient>
                                        </defs>
                                    </svg>
                                    <div class="position-absolute top-50 start-50 translate-middle text-center">
                                        <h2 class="mb-0 fw-bold">{{ round($completionPercentage ?? 0) }}%</h2>
                                        <small class="opacity-80">Usage</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Stats -->
                    <div class="stat-grid">
                        <div class="stat-card d-none">
                            <div class="d-flex  align-items-center justify-content-between">
                                <div>
                                    <p class="text-muted mb-1">Content Completed</p>
                                    <h3 class="mb-0" id="update_completed"></h3>
                                </div>
                                <div class="text-primary fs-2">
                                    <i class="fas fa-book-open"></i>
                                </div>
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <p class="text-muted mb-1">Total Content</p>
                                    <h3 class="mb-0">{{ $totalLectures ?? 0 }}</h3>
                                </div>
                                <div class="text-success fs-2">
                                    <i class="fas fa-chart-line"></i>
                                </div>
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <p class="text-muted mb-1">Current Plan</p>
                                    <h5 class="mb-0 text-primary">{{ $subscription->plan_name ?? 'Free' }}</h5>
                                </div>
                                <div class="text-warning fs-2">
                                    <i class="fas fa-gem"></i>
                                </div>
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <p class="text-muted mb-1">Board & Level</p>
                                    <h5 class="mb-0">{{ $subscription->board_name ?? 'N/A' }} -
                                        {{ $subscription->level_name ?? 'N/A' }}</h5>
                                </div>
                                <div class="text-info fs-2">
                                    <i class="fas fa-school"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Main Content Area -->
                    <div class="row">
                        <!-- Left Column - Content Hierarchy -->
                        <div class="col-xl-8">
                            <div class="glass-card">
                                <div class="p-4">
                                    <h4 class="mb-4">📚 Your Learning Content</h4>

                                    @if (count($hierarchy) > 0)
                                        {{-- File Manager Style View --}}
                                        <div class="file-manager-view">
                                            {{-- Breadcrumb Navigation --}}
                                            <div class="file-manager-breadcrumb mb-3">
                                                <nav aria-label="breadcrumb">
                                                    <ol class="breadcrumb mb-0" id="breadcrumb-nav">
                                                        <li class="breadcrumb-item">
                                                            <a href="#" onclick="navigateToFolder(null); return false;" class="text-decoration-none">
                                                                <i class="fas fa-home me-1"></i> Home
                                                            </a>
                                                        </li>
                                                        <li class="breadcrumb-item active" id="current-breadcrumb" aria-current="page">All Content</li>
                                                    </ol>
                                                </nav>
                                            </div>
                                            
                                            {{-- View Toggle Buttons --}}
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <div class="view-toggle-buttons">
                                                    <button class="btn btn-sm btn-outline-primary active" id="grid-view-btn" onclick="setViewMode('grid')">
                                                        <i class="fas fa-th"></i> Grid
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-primary" id="list-view-btn" onclick="setViewMode('list')">
                                                        <i class="fas fa-list"></i> List
                                                    </button>
                                                </div>
                                            </div>
                                            
                                            {{-- Content Grid/List --}}
                                            <div id="file-manager-content" class="file-manager-grid">
                                                @include('student.partials.file-manager-content', ['items' => $hierarchy, 'viewMode' => 'grid'])
                                            </div>
                                        </div>
                                    @else
                                        <div class="text-center py-5">
                                            <i class="fas fa-book-open text-muted fs-1 mb-3"></i>
                                            <h5>No content available yet</h5>
                                            <p class="text-muted">Your learning content will appear here soon!</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Right Column - Sidebar -->
                        <div class="col-xl-4">
                            <!-- Recent Activity -->
                            <div class="glass-card mb-4 d-none">
                                <div class="p-4">
                                    <h5 class="mb-3">📝 Recent Activity</h5>
                                    <div class="activity-list">
                                        <!-- Activity will be loaded here -->
                                    </div>
                                </div>
                            </div>
                            <!-- Quick Actions -->
                            <div class="glass-card mb-4">
                                <div class="p-4">
                                    <h5 class="mb-3">⚡ Quick Actions</h5>
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <button class="btn btn-outline-primary w-100" onclick="cancelPlan()">
                                                <i class="fas fa-times me-1"></i> Cancel Plan
                                            </button>
                                        </div>
                                        <div class="col-6">
                                            <button class="btn btn-primary w-100"
                                                onclick="upgradePlan({ plan_id: {{ $subscription->plan_id ?? 0 }}, level_id: {{ $subscription->level_id ?? 0 }} })">
                                                <i class="fas fa-arrow-up me-1"></i> Upgrade
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Progress States -->
                            <div class="glass-card mb-4">
                                <div class="p-4">
                                    <h5 class="mb-3">📊 Learning States</h5>
                                    <div id="progressStates">
                                        <div class="progress-state-item mb-3">
                                            <div class="d-flex align-items-center justify-content-between mb-2">
                                                <div class="d-flex align-items-center">
                                                    <div class="state-indicator bg-warning me-2"></div>
                                                    <span class="fw-semibold">In Progress</span>
                                                </div>
                                                <span class="badge bg-warning" id="inProgressCount">0</span>
                                            </div>
                                        </div>
                                        
                                        <div class="progress-state-item mb-3">
                                            <div class="d-flex align-items-center justify-content-between mb-2">
                                                <div class="d-flex align-items-center">
                                                    <div class="state-indicator bg-success me-2"></div>
                                                    <span class="fw-semibold">Completed</span>
                                                </div>
                                                <span class="badge bg-success" id="completedCount">0</span>
                                            </div>
                                        </div>
                                        
                                        <div class="progress-state-item mb-3">
                                            <div class="d-flex align-items-center justify-content-between mb-2">
                                                <div class="d-flex align-items-center">
                                                    <div class="state-indicator bg-secondary me-2"></div>
                                                    <span class="fw-semibold">Not Started</span>
                                                </div>
                                                <span class="badge bg-secondary" id="notStartedCount">0</span>
                                            </div>
                                        </div>
                                        
                                        <div class="progress-state-item">
                                            <div class="d-flex align-items-center justify-content-between mb-2">
                                                <div class="d-flex align-items-center">
                                                    <div class="state-indicator bg-info me-2"></div>
                                                    <span class="fw-semibold">Total Content</span>
                                                </div>
                                                <span class="badge bg-info" id="totalContentCount">0</span>
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

    <!-- Media Modal -->
    <div class="modal fade" id="mediaModal" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog modal-xl modal-dialog-centered" style="max-width:70%;">
            <div class="modal-content glass-card">
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title" id="mediaModalTitle">Media Viewer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                        <i class="bi bi-x " onclick="pauseAllVideos(); updateDashboardStats()"></i>
                    </button>
                </div>
                <div class="media-modal-content p-4">
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div>
                            <h6 id="mediaTitle"></h6>
                            <small class="text-muted" id="mediaInfo"></small>
                        </div>

                        <div>
                            <button class="btn btn-sm btn-success" id="markCompleteBtn" style="display: ;">
                                <i class="fas fa-check me-1"></i> Mark as Read
                            </button>
                            <input type="hidden" id="currentFileId">
                            <input type="hidden" id="currentFolderId">
                        </div>
                    </div>
                    <div class="progress mt-3" style="height: 8px;">
                        <div class="progress-bar" id="mediaProgress" style="width: 0%;background-color:#0dcd94"></div>
                    </div>
                </div>
                <div class="modal-body media-modal-content">
                    <div class="media-viewer" id="mediaViewer">
                        <!-- Slideshow will be loaded here -->
                    </div>
                    <!--<div class="d-flex justify-content-between align-items-center mt-3">-->
                    <!--    <div>-->
                    <!--        <h6 id="mediaTitle"></h6>-->
                    <!--        <small class="text-muted" id="mediaInfo"></small>-->
                    <!--    </div>-->

                    <!--    <div>-->
                    <!--        <button class="btn btn-sm btn-success" id="markCompleteBtn" style="display: ;">-->
                    <!--            <i class="fas fa-check me-1"></i> Mark as Read-->
                    <!--        </button>-->
                    <!--        <input type="hidden" id="currentFileId">-->
                    <!--        <input type="hidden" id="currentFolderId">-->
                    <!--    </div>-->
                    <!--</div>-->
                    <!--<div class="progress mt-3" style="height: 8px;">-->
                    <!--    <div class="progress-bar" id="mediaProgress" style="width: 0%;background-color:#0dcd94"></div>-->
                    <!--</div>-->
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- PDF.js library for PDF rendering -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
    <script>
    // ============================================
// GLOBAL VARIABLES
// ============================================
let openFolders = [];
let currentFiles = [];
let currentFolderId = null;
let carouselInstance = null;

// File Manager State
let fileManagerHierarchy = @json($hierarchy ?? []);
let currentPath = [];
let currentViewMode = 'grid';

// ============================================
// INITIALIZATION
// ============================================
// document.addEventListener('DOMContentLoaded', function() {
//     // Initialize Chart
//     const ctx = document.getElementById('progressChart');
//     if (ctx && typeof progressData !== 'undefined') {
//         new Chart(ctx, {
//             type: 'line',
//             data: {
//                 labels: progressData.labels,
//                 datasets: [{
//                     label: 'Daily Progress',
//                     data: progressData.data,
//                     borderColor: '#4361ee',
//                     backgroundColor: 'rgba(67, 97, 238, 0.1)',
//                     tension: 0.4,
//                     fill: true,
//                     pointBackgroundColor: '#4361ee'
//                 }]
//             },
//             options: {
//                 responsive: true,
//                 plugins: { legend: { display: false } },
//                 scales: {
//                     y: { beginAtZero: true },
//                     x: { display: false }
//                 }
//             }
//         });
//     }

//     initializeOpenFolders();
//     setupMediaModal();
// });
document.addEventListener('DOMContentLoaded', function() {
            // Calculate and display progress states
            calculateProgressStates();

            // Initialize folders - all closed by default
            initializeOpenFolders();

            // Set up media modal button
            setupMediaModal();
        });
// ============================================
// PROGRESS STATES CALCULATION
// ============================================
function calculateProgressStates() {
    if (!fileManagerHierarchy || fileManagerHierarchy.length === 0) {
        return;
    }
    
    let inProgress = 0;
    let completed = 0;
    let notStarted = 0;
    let total = 0;
    
    function countStates(items) {
        items.forEach(item => {
            if (item.type === 'content' && item.files && item.files.length > 0) {
                total++;
                const completedCount = item.files.filter(f => f.is_completed == 1 || f.is_completed === true).length;
                const totalCount = item.files.length;
                
                if (completedCount === 0) {
                    notStarted++;
                } else if (completedCount === totalCount) {
                    completed++;
                } else {
                    inProgress++;
                }
            }
            
            if (item.children && item.children.length > 0) {
                countStates(item.children);
            }
        });
    }
    
    countStates(fileManagerHierarchy);
    
    // Update the UI
    const inProgressEl = document.getElementById('inProgressCount');
    
    const completedEl = document.getElementById('completedCount');
    const TopCompleteCount = document.getElementById('update_completed');
    const notStartedEl = document.getElementById('notStartedCount');
    const totalEl = document.getElementById('totalContentCount');
    
    if (inProgressEl) inProgressEl.textContent = inProgress;
    if (completedEl) completedEl.textContent = completed;
     if (TopCompleteCount) TopCompleteCount.textContent = completed;
    if (notStartedEl) notStartedEl.textContent = notStarted;
    if (totalEl) totalEl.textContent = total;
}

// ============================================
// FOLDER MANAGEMENT
// ============================================
function initializeOpenFolders() {
    // Start with all folders closed by default
    // Uncomment the code below if you want to restore previously opened folders from localStorage
    
    // Ensure all nodes start closed (override any saved state)
    document.querySelectorAll('.file-manager-tree-children, .chapter-actions').forEach(element => {
        if (element) {
            element.style.display = 'none';
            element.classList.remove('show');
        }
    });
    
    // Ensure all arrows start unrotated
    document.querySelectorAll('.file-manager-chevron-icon').forEach(arrow => {
        if (arrow) {
            arrow.classList.remove('rotated');
        }
    });
    
    // Ensure all content rows are not active
    document.querySelectorAll('.file-manager-tree-row').forEach(row => {
        if (row) {
            row.classList.remove('active');
        }
    });
    
    // Clear saved open folders to start fresh
    // localStorage.removeItem('open_folders');
    // openFolders = [];
    
    /* Uncomment this section if you want to restore previously opened folders:
    const savedFolders = localStorage.getItem('open_folders');
    if (savedFolders) {
        try {
            openFolders = JSON.parse(savedFolders);
            openFolders.forEach(folderId => {
                const children = document.getElementById(`children_${folderId}`);
                const chapterActions = document.getElementById(`chapter_actions_${folderId}`);
                const arrow = document.getElementById(`arrow_${folderId}`);
                const content = document.getElementById(`content_${folderId}`);
                
                if (children) {
                    children.style.display = 'block';
                    children.classList.add('show');
                }
                if (chapterActions) {
                    chapterActions.style.display = 'block';
                }
                if (arrow) arrow.classList.add('rotated');
                if (content) content.classList.add('active');
            });
        } catch (e) {
            console.error('Error parsing localStorage:', e);
        }
    }
    */
}

function toggleNode(nodeId, folderId) {
    const children = document.getElementById(`children_${nodeId}`);
    const chapterActions = document.getElementById(`chapter_actions_${nodeId}`);
    const arrow = document.getElementById(`arrow_${nodeId}`);
    const content = document.getElementById(`content_${nodeId}`);
    
    // Helper function to check if element is visible
    function isElementVisible(element) {
        if (!element) return false;
        const style = window.getComputedStyle(element);
        return style.display !== 'none' && style.display !== '';
    }
    
    let wasOpen = false;
    let targetElement = null;
    
    // Determine which element to toggle (prioritize children over chapterActions)
    if (children) {
        targetElement = children;
        wasOpen = isElementVisible(children);
    } else if (chapterActions) {
        targetElement = chapterActions;
        wasOpen = isElementVisible(chapterActions);
    }
    
    // Toggle the target element
    if (targetElement) {
        const willBeOpen = !wasOpen;
        
        if (willBeOpen) {
            targetElement.style.display = 'block';
            if (targetElement === children) {
                targetElement.classList.add('show');
                addToOpenFolders(folderId);
                saveFolderState(folderId, true);
            }
        } else {
            targetElement.style.display = 'none';
            if (targetElement === children) {
                targetElement.classList.remove('show');
                removeFromOpenFolders(folderId);
                saveFolderState(folderId, false);
            }
        }
        
        // Update arrow rotation state
        if (arrow) {
            if (willBeOpen) {
                arrow.classList.add('rotated');
            } else {
                arrow.classList.remove('rotated');
            }
        }
        
        // Update content active state
        if (content) {
            if (willBeOpen) {
                content.classList.add('active');
            } else {
                content.classList.remove('active');
            }
        }
    }
    
    return false; // Prevent default behavior
}

function addToOpenFolders(folderId) {
    if (!openFolders.includes(folderId)) {
        openFolders.push(folderId);
        localStorage.setItem('open_folders', JSON.stringify(openFolders));
    }
}

function removeFromOpenFolders(folderId) {
    openFolders = openFolders.filter(id => id !== folderId);
    localStorage.setItem('open_folders', JSON.stringify(openFolders));
}

function saveFolderState(folderId, isOpen) {
    fetch(`/student/toggle-folder/${folderId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ is_open: isOpen })
    }).catch(error => console.error('Error saving folder state:', error));
}

// ============================================
// BOOTSTRAP CAROUSEL BUILDER
// ============================================
function buildBootstrapCarousel(startIndex = 0) {
    const mediaViewer = document.getElementById('mediaViewer');
    
    if (!currentFiles || currentFiles.length === 0) {
        mediaViewer.innerHTML = '<p class="text-center">No media files found</p>';
        return;
    }
    
    let carouselHTML = `
        <div id="mediaCarousel" class="carousel slide" data-bs-ride="false" data-bs-interval="false">
            <div class="carousel-inner">
    `;
    
    currentFiles.forEach((file, index) => {
        const isActive = index === startIndex ? 'active' : '';
        // Check if completed is 1 (completed) or 0 (not completed)
        const isCompleted = file.is_completed === 1 || file.is_completed === true || file.is_completed === '1';
        
        carouselHTML += `
            <div class="carousel-item ${isActive}" data-file-id="${file.id}" data-completed="${isCompleted ? '1' : '0'}">
                <div class="card border-0">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="mb-0">${escapeHtml(file.name)}</h6>
                            ${isCompleted ? '<span class="badge bg-success"><i class="fas fa-check"></i> Completed</span>' : '<span class="badge bg-warning text-dark"><i class="fas fa-clock"></i> Not Completed</span>'}
                        </div>
                        ${buildMediaContent(file)}
                    </div>
                </div>
            </div>
        `;
    });
    
    carouselHTML += `
            </div>
            
            ${currentFiles.length > 1 ? `
            <button class="carousel-control-prev" type="button" data-bs-target="#mediaCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"><i class="bi bi-arrow-left-circle"></i></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#mediaCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"><i class="bi bi-arrow-right-circle"></i></span>
                <span class="visually-hidden">Next</span>
            </button>
            ` : ''}
        </div>
        <div class="text-center mt-3">
            <span id="slideCounter" class="badge bg-primary d-none">${startIndex + 1} / ${currentFiles.length}</span>
        </div>
    `;
    
    mediaViewer.innerHTML = carouselHTML;
    initializeCarousel(startIndex);
}

// Function to render PDF using pdf.js
function renderPDFWithPDFJS(containerId, pdfUrl) {
    const container = document.getElementById(containerId);
    if (!container) return;
    
    const wrapper = container.querySelector('.pdf-canvas-wrapper');
    const loadingDiv = container.querySelector('.pdf-loading');
    
    if (!wrapper || !loadingDiv) return;
    
    // Check if pdf.js is loaded
    if (typeof pdfjsLib === 'undefined') {
        loadingDiv.innerHTML = '<p class="text-danger">PDF.js library not loaded. Please refresh the page.</p>';
        return;
    }
    
    // Set up pdf.js worker
    pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
    
    // Load and render PDF
    pdfjsLib.getDocument(pdfUrl).promise.then(function(pdf) {
        loadingDiv.style.display = 'none';
        
        // Render all pages
        const renderPromises = [];
        for (let pageNum = 1; pageNum <= pdf.numPages; pageNum++) {
            renderPromises.push(
                pdf.getPage(pageNum).then(function(page) {
                    const viewport = page.getViewport({ scale: 1.5 });
                    const canvas = document.createElement('canvas');
                    const context = canvas.getContext('2d');
                    
                    canvas.height = viewport.height;
                    canvas.width = viewport.width;
                    canvas.style.display = 'block';
                    canvas.style.margin = '0 auto 20px auto';
                    canvas.style.maxWidth = '100%';
                    canvas.style.height = 'auto';
                    canvas.style.pointerEvents = 'none'; // Disable clicks
                    canvas.style.userSelect = 'none'; // Disable text selection
                    canvas.style.cursor = 'default'; // Show default cursor
                    
                    // Prevent context menu
                    canvas.addEventListener('contextmenu', function(e) {
                        e.preventDefault();
                        return false;
                    });
                    
                    // Prevent clicks
                    canvas.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        return false;
                    });
                    
                    wrapper.appendChild(canvas);
                    
                    const renderContext = {
                        canvasContext: context,
                        viewport: viewport
                    };
                    
                    return page.render(renderContext).promise;
                })
            );
        }
        
        return Promise.all(renderPromises);
    }).catch(function(error) {
        console.error('PDF rendering error:', error);
        loadingDiv.innerHTML = '<p class="text-danger">Error loading PDF. Please try again.</p>';
    });
}

function buildMediaContent(file) {
    const mimeType = file.mime_type || '';
    const isVimeo = file.is_vimeo;
    const vimeoId = file.vimeo_id;
    
    // Vimeo Video
    if (isVimeo && vimeoId) {
        let vimeoEmbedUrl = `https://player.vimeo.com/video/${vimeoId}?autoplay=0&title=0&byline=0&portrait=0`;
        return `
            <div class="ratio ratio-16x9">
                <iframe 
                    src="${vimeoEmbedUrl}"
                    frameborder="0" 
                    allow="autoplay; fullscreen; picture-in-picture" 
                    allowfullscreen>
                </iframe>
            </div>
        `;
    }
    
    // Regular Video
    if (mimeType.includes('video')) {
        return `
            <div class="ratio ratio-16x9">
                <video controls class="w-100">
                    <source src="${file.path}" type="${mimeType}">
                    Your browser does not support the video tag.
                </video>
            </div>
        `;
    }
    
    // // PDF
    // if (mimeType.includes('pdf')) {
    //     const imageUrl = file.path.startsWith("filemanager/view/")
    //     ? `/${file.path}`
    //     : `/filemanager/view/${file.id}`;
    //     // const pdfUrl = file.path.startsWith('storage/') ? `/${file.path}` : `/storage/${file.path}`;
    //     return `
    //         <div style="height: 500px;"> 
    //         <iframe src="${imageUrl}#toolbar=0&navpanes=0&scrollbar=0" width="100%" 
    //                 height="100%" ></iframe>
                
    //         </div>
    //     `;
    // }
    // PDF - Using pdf.js for better control
if (mimeType.includes('pdf')) {
    const imageUrl = file.path.startsWith("filemanager/view/")
        ? `/${file.path}`
        : `/filemanager/view/${file.id}`;

    const containerId = `pdf-container-${file.id}-${Date.now()}`;
    
    // Render PDF after a short delay to ensure DOM is ready
    setTimeout(function() {
        renderPDFWithPDFJS(containerId, imageUrl);
    }, 200);
    
    return `
        <div id="${containerId}" class="pdf-container" style="
            height: 600px; 
            overflow-y: auto; 
            overflow-x: hidden; 
            position: relative; 
            border: 1px solid #ddd;
            background: #f8f9fa;
            text-align: center;">
            <div class="pdf-canvas-wrapper" style="padding: 20px;">
                <div class="pdf-loading" style="padding: 50px;">
                    <i class="fas fa-spinner fa-spin fa-2x text-muted"></i>
                    <p class="mt-2 text-muted">Loading PDF...</p>
                </div>
            </div>
        </div>
    `;
}
    // Image
    if (mimeType.includes('image')) {
        const imageUrl = file.path.startsWith("filemanager/view")
        ? `/${file.id}`
        : `/filemanager/view/${file.id}`;
        // const imageUrl = file.path.startsWith('storage/') ? `/${file.path}` : `/storage/${file.path}`;
        return `
            <div class="text-center">
                <img src="${imageUrl}" 
                     alt="${escapeHtml(file.name)}" 
                     class="img-fluid" 
                     style="max-height: 500px; object-fit: contain;">
            </div>
        `;
    }
    
    return `
        <div class="text-center py-5">
            <i class="fas fa-file fa-3x text-muted mb-3"></i>
            <p>Preview not available</p>
            <a href="${file.path}" class="btn btn-primary mt-2" download="${escapeHtml(file.name)}">
                <i class="fas fa-download me-1"></i> 
            </a>
        </div>
    `;
}
// ============================================
// CAROUSEL INITIALIZATION & EVENTS
// ============================================
function initializeCarousel(startIndex) {
    const carouselElement = document.getElementById('mediaCarousel');
    if (!carouselElement) return;
    
    carouselInstance = new bootstrap.Carousel(carouselElement, {
        interval: false,
        wrap: true,
        keyboard: true
    });
    
    carouselInstance.to(startIndex);
    
    // Listen for slide change events
    carouselElement.addEventListener('slid.bs.carousel', function(event) {
        onSlideChange(event.to);
        pauseAllVideos();
    });
     carouselElement.addEventListener('slide.bs.carousel', function() {
        pauseAllVideos();
    });
    updateCarouselUI(startIndex);
}
function pauseAllVideos() {
    
    // Pause regular HTML5 videos
    document.querySelectorAll('video').forEach(video => {
        if (!video.paused) {
            video.pause();
        }
    });
    
    // Pause Vimeo videos
    document.querySelectorAll('iframe').forEach(iframe => {
        if (iframe.src.includes('vimeo.com')) {
            // You might need to post a message to the iframe
            // This depends on Vimeo's API permissions
            try {
                iframe.contentWindow.postMessage('pause', '*');
            } catch (e) {
                // If we can't control the iframe, reload it to stop
                const src = iframe.src;
                iframe.src = src.replace('autoplay=1', 'autoplay=0');
            }
        }
    });
}
// function onSlideChange(newIndex) {
//     if (!currentFiles || !currentFiles[newIndex]) return;
    
//     const currentFile = currentFiles[newIndex];
    
//     // Update counter
//     const slideCounter = document.getElementById('slideCounter');
//     if (slideCounter) {
//         slideCounter.textContent = `${newIndex + 1} / ${currentFiles.length}`;
//     }
    
//     // Update progress bar
//     updateProgressBar(newIndex);
    
//     // Update modal header
//     const mediaTitle = document.getElementById('mediaTitle');
//     const mediaInfo = document.getElementById('mediaInfo');
    
//     if (mediaTitle) mediaTitle.textContent = currentFile.name;
//     if (mediaInfo) mediaInfo.textContent = currentFile.mime_type || 'File';
    
//     // Update hidden inputs
//     document.getElementById('currentFileId').value = currentFile.id;
//     document.getElementById('currentFolderId').value = currentFolderId;
    
//     // Update mark complete button based on completed status
//     const markCompleteBtn = document.getElementById('markCompleteBtn');
//     if (markCompleteBtn) {
//         const isCompleted = currentFile.is_completed === 1 || currentFile.is_completed === true || currentFile.is_completed === '1';
        
//         if (isCompleted) {
//             markCompleteBtn.style.display = 'none';
//         } else {
//             markCompleteBtn.style.display = 'inline-block';
//             markCompleteBtn.innerHTML = '<i class="fas fa-check me-1"></i> Mark as Read';
//             markCompleteBtn.disabled = false;
//         }
//     }
// }
function onSlideChange(newIndex) {
    if (!currentFiles || !currentFiles[newIndex]) return;
    
    const currentFile = currentFiles[newIndex];
    
    // Update counter
    const slideCounter = document.getElementById('slideCounter');
    if (slideCounter) {
        slideCounter.textContent = `${newIndex + 1} / ${currentFiles.length}`;
    }
    
    // Update progress bar
    updateProgressBar(newIndex);
    
    // Update modal header
    const mediaTitle = document.getElementById('mediaTitle');
    const mediaInfo = document.getElementById('mediaInfo');
    
    if (mediaTitle) mediaTitle.textContent = currentFile.name;
    if (mediaInfo) mediaInfo.textContent = currentFile.mime_type || 'File';
    
    // Update hidden inputs
    document.getElementById('currentFileId').value = currentFile.id;
    document.getElementById('currentFolderId').value = currentFolderId;
    
    // Update mark complete button based on completed status
    const markCompleteBtn = document.getElementById('markCompleteBtn');
    if (markCompleteBtn) {
        const isCompleted = currentFile.is_completed === 1 || currentFile.is_completed === true || currentFile.is_completed === '1';
        
        if (isCompleted) {
            // Already completed - show disabled completed state
            markCompleteBtn.disabled = true;
            markCompleteBtn.classList.remove('btn-primary');
            markCompleteBtn.classList.add('btn-success', 'disabled');
            markCompleteBtn.innerHTML = '<i class="fas fa-check-circle me-1"></i> Completed';
            markCompleteBtn.dataset.saving = 'false';
        } else {
            // Not completed - enable button
            markCompleteBtn.disabled = false;
            markCompleteBtn.classList.remove('btn-success', 'disabled', 'btn-secondary');
            markCompleteBtn.classList.add('btn-primary');
            markCompleteBtn.innerHTML = '<i class="fas fa-check me-1"></i> Mark as Read';
            markCompleteBtn.dataset.saving = 'false';
        }
        markCompleteBtn.style.display = 'inline-block';
    }
}
function updateCarouselUI(index) {
    const slideCounter = document.getElementById('slideCounter');
    if (slideCounter) {
        slideCounter.textContent = `${index + 1} / ${currentFiles.length}`;
    }
    
    updateProgressBar(index);
    
    if (currentFiles[index]) {
        const file = currentFiles[index];
        const mediaTitle = document.getElementById('mediaTitle');
        const mediaInfo = document.getElementById('mediaInfo');
        
        if (mediaTitle) mediaTitle.textContent = file.name;
        if (mediaInfo) mediaInfo.textContent = file.mime_type || 'File';
        
        document.getElementById('currentFileId').value = file.id;
        document.getElementById('currentFolderId').value = currentFolderId;
        
        const markCompleteBtn = document.getElementById('markCompleteBtn');
        if (markCompleteBtn) {
            const isCompleted = file.is_completed === 1 || file.is_completed === true || file.is_completed === '1';
            
            if (isCompleted) {
                // Already completed - show disabled completed state
                markCompleteBtn.disabled = true;
                markCompleteBtn.classList.remove('btn-primary');
                markCompleteBtn.classList.add('btn-success', 'disabled');
                markCompleteBtn.innerHTML = '<i class="fas fa-check-circle me-1"></i> Completed';
                markCompleteBtn.dataset.saving = 'false';
            } else {
                // Not completed - enable button
                markCompleteBtn.disabled = false;
                markCompleteBtn.classList.remove('btn-success', 'disabled', 'btn-secondary');
                markCompleteBtn.classList.add('btn-primary');
                markCompleteBtn.innerHTML = '<i class="fas fa-check me-1"></i> Mark as Read';
                markCompleteBtn.dataset.saving = 'false';
            }
            markCompleteBtn.style.display = 'inline-block';
        }
    }
}

function updateProgressBar(index) {
    const progressBar = document.getElementById('mediaProgress');
    if (progressBar && currentFiles.length > 0) {
        const progress = ((index + 1) / currentFiles.length) * 100;
        progressBar.style.width = `${progress}%`;
    }
}

// ============================================
// CHAPTER MANAGEMENT
// ============================================
async function startChapter(chapterId) {
    await loadChapterContent(chapterId, 'start');
}

async function continueChapter(chapterId) {
    await loadChapterContent(chapterId, 'continue');
}

async function loadChapterContent(chapterId, action) {
    showLoading(chapterId, action);
    
    try {
        const response = await fetch(`/student/complete-course/${chapterId}`, {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        });
        
        const data = await response.json();
        hideLoading(chapterId);
        
        if (data.success) {
            const updateData = {
                completed_count: data.total_complete,
                total_count: data.total_media_count
            };
            updateChapterUI(chapterId, updateData);
            updateFileManagerItem(chapterId, updateData);
            
            if (data.media) {
                await loadAllFolderFiles(chapterId, data.media.id);
            } else {
                 // All content completed
                 const completedData = {
                     completed_count: data.total_media_count,
                     total_count: data.total_media_count
                 };
                 updateChapterUI(chapterId, completedData);
                 updateFileManagerItem(chapterId, completedData);
                 
                Swal.fire({
                    icon: 'success',
                    title: 'Content Completed!',
                    text: 'You have completed all content in this folder.',
                    confirmButtonText: 'Great!'
                }).then(() => {
                    // location.reload();
                });
            }
        } else {
            Swal.fire('Error', data.message || 'Failed to load content', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        hideLoading(chapterId);
        Swal.fire('Error', 'Failed to load content', 'error');
    }
}

async function restartChapter(chapterId) {
    const result = await Swal.fire({
        title: 'Restart Content?',
        text: "All your progress will be lost. Are you sure?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, restart it!',
        cancelButtonText: 'No, keep my progress'
    });
    
    if (result.isConfirmed) {
        try {
            const response = await fetch(`/student/restart-chapter/${chapterId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json'
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                // 2. Update the chapter UI based on your PHP template structure
                updateChapterUI(chapterId, data);

                // 3. Update file list
                await refreshFileList(chapterId, data.files);

                // 4. Update file manager view if it exists
                updateFileManagerItem(chapterId, {
                    completed_count: data.completed_count || 0,
                    total_count: data.total_count || 0
                });

                // 5. Update dashboard stats
                await updateDashboardStats();

                // 6. Update progress states
                calculateProgressStates();
                
                // 7. Show success message
                showToast('Chapter progress reset successfully!', 'success');
            } else {
                Swal.fire('Error', data.message, 'error');
            }
        } catch (error) {
             hideLoading(chapterId);
            Swal.fire('Error', 'Failed to restart chapter', 'error');
        }
    }
}
function updateChapterUI(chapterId, data) {
            const completedCount = data.completed_count || 0;
            const totalCount = data.total_count || 1;
            const progressPercentage = totalCount > 0 ? (completedCount / totalCount) * 100 : 0;

            // Update progress bar
            const progressBar = document.querySelector(`#chapter_actions_${chapterId} .progress-bar`);
            if (progressBar) {
                progressBar.style.width = `${progressPercentage}%`;
                progressBar.setAttribute('aria-valuenow', progressPercentage);
                progressBar.textContent = `${Math.round(progressPercentage)}%`;

                // Update color based on completion
                if (progressPercentage === 0) {
                    progressBar.className = 'progress-bar bg-warning';
                } else if (progressPercentage === 100) {
                    progressBar.className = 'progress-bar bg-success';
                } else {
                    progressBar.className = 'progress-bar bg-info';
                }
            }

            // Update completion text in tree node
            const nodeInfo = document.querySelector(`#content_${chapterId} .node-info small`);
            if (nodeInfo) {
                nodeInfo.textContent = `${completedCount}/${totalCount} completed`;
            }

            // Update buttons based on completion status
            updateChapterButtons(chapterId, completedCount, totalCount);
        }

        // Update chapter buttons based on your PHP template logic
        function updateChapterButtons(chapterId, completedCount, totalCount) {
            const chapterActions = document.getElementById(`chapter_actions_${chapterId}`);
            if (!chapterActions) return;

            // Get buttons
            const startBtn = document.getElementById(`start_btn_${chapterId}`);
            const continueBtn = document.getElementById(`continue_btn_${chapterId}`);
            const restartBtn = document.getElementById(`restart_btn_${chapterId}`);
            const completedBtn = chapterActions.querySelector('.btn-success:disabled');

            // Hide all buttons first
            if (startBtn) startBtn.style.display = 'none';
            if (continueBtn) continueBtn.style.display = 'none';
            if (restartBtn) restartBtn.style.display = 'none';
            if (completedBtn) completedBtn.style.display = 'none';
            console.log(chapterId, completedCount, totalCount)
            let update_completed = document.getElementById(`update_completed`).innerHTML =
                `<h3 class="mb-0"></h3>`;
            // Show appropriate buttons based on completion
            if (completedCount === 0) {
                // No progress - show start button
                if (startBtn) {
                    startBtn.style.display = 'inline-block';
                    startBtn.innerHTML = '<i class="fas fa-play me-1"></i> Start';
                    startBtn.disabled = false;
                }
            } else if (completedCount === totalCount) {
                // Completed - show completed button and restart button
                if (completedBtn) {
                    completedBtn.style.display = 'inline-block';
                } else {
                    // Create completed button if it doesn't exist
                    const newCompletedBtn = document.createElement('button');
                    newCompletedBtn.className = 'btn btn-success btn-sm';
                    newCompletedBtn.disabled = true;
                    newCompletedBtn.innerHTML = '<i class="fas fa-check me-1"></i> Completed';
                    chapterActions.querySelector('.d-flex.gap-2').prepend(newCompletedBtn);
                }

                if (restartBtn) {
                    restartBtn.style.display = 'inline-block';
                }
            } else {
                // In progress - show continue button and restart button
                if (continueBtn) {
                    continueBtn.style.display = 'inline-block';
                    continueBtn.innerHTML =
                        `<i class="fas fa-play-circle me-1"></i> Continue (${completedCount}/${totalCount})`;
                }

                if (restartBtn) {
                    restartBtn.style.display = 'inline-block';
                }
            }
        }
// Refresh file list with updated completion status
        async function refreshFileList(chapterId, files) {
            const fileListContainer = document.getElementById(`file_list_${chapterId}`);
            if (!fileListContainer) return;

            // If file list is visible, update it
            if (!fileListContainer.classList.contains('d-none')) {
                fileListContainer.innerHTML = '';

                if (files && files.length > 0) {
                    files.forEach(file => {
                        const fileItem = createFileListItem(file, chapterId);
                        fileListContainer.appendChild(fileItem);
                    });
                } else {
                    fileListContainer.innerHTML = '<p class="text-muted text-center">No files available</p>';
                }

                // Update toggle button text
                const toggleBtn = document.getElementById(`toggle_files_${chapterId}`);
                if (toggleBtn) {
                    const completedCount = files.filter(f => f.is_completed).length;
                    const totalCount = files.length;
                    toggleBtn.innerHTML =
                        `<i class="fas fa-list me-1"></i> ${fileListContainer.classList.contains('d-none') ? 'Show' : 'Hide'} Files (${completedCount}/${totalCount})`;
                }
            }
        }


// ============================================
// LOAD FILES & SHOW CAROUSEL
// ============================================
async function loadAllFolderFiles(folderId, targetFileId) {
    const mediaViewer = document.getElementById('mediaViewer');
    
    mediaViewer.innerHTML = `
        <div class="d-flex align-items-center justify-content-center" style="min-height: 400px;">
            <div class="text-center">
                <div class="spinner-border text-primary" role="status"></div>
                <p class="mt-2">Loading content...</p>
            </div>
        </div>
    `;
    
    try {
        const response = await fetch(`/student/folder-media/${folderId}`, {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        });
        
        const data = await response.json();
        console.log(data);
        if (data.success) {
            currentFiles = data.files;
            currentFolderId = folderId;
            
            console.log('Loaded files:', currentFiles); // Debug log
            
            const startIndex = currentFiles.findIndex(file => file.id == targetFileId);
            
            if (startIndex >= 0) {
                buildBootstrapCarousel(startIndex);
                
                const modal = new bootstrap.Modal(document.getElementById('mediaModal'));
                modal.show();
            }
        } else {
            mediaViewer.innerHTML = `<div class="alert alert-danger">${data.message || 'Failed to load'}</div>`;
        }
    } catch (error) {
        console.error('Error:', error);
        mediaViewer.innerHTML = `<div class="alert alert-danger">Failed to load content</div>`;
    }
}

// ============================================
// MARK AS COMPLETED
// ============================================
// ============================================
// MARK AS COMPLETED
// ============================================
async function markMediaAsCompleted() {
    const fileId = document.getElementById('currentFileId').value;
    const folderId = document.getElementById('currentFolderId').value;
    const markCompleteBtn = document.getElementById('markCompleteBtn');

    if (!fileId || !folderId) {
        Swal.fire('Error', 'File information missing', 'error');
        return;
    }

    // Check if button is already disabled or in saving state
    if (markCompleteBtn.disabled || markCompleteBtn.dataset.saving === 'true') {
        console.log('Request already in progress or completed');
        return;
    }

    // Set saving flag
    markCompleteBtn.dataset.saving = 'true';
    
    // Save original state
    const originalHTML = markCompleteBtn.innerHTML;
    const originalText = markCompleteBtn.textContent;
    const originalClasses = markCompleteBtn.className;
    
    // Disable button and show saving state
    markCompleteBtn.disabled = true;
    markCompleteBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Saving...';
    markCompleteBtn.className = originalClasses + ' btn-secondary';

    try {
        const response = await fetch('/student/mark-file-completed', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ 
                file_id: fileId, 
                chapter_id: folderId 
            })
        });

        const data = await response.json();

        if (data.success) {
            // SUCCESS - Change to permanent completed state
            markCompleteBtn.innerHTML = '<i class="fas fa-check-circle me-1"></i> Completed';
            markCompleteBtn.className = originalClasses.replace('btn-primary', 'btn-success') + ' disabled';
            
            // Update the current slide's completion status
            const carouselElement = document.getElementById('mediaCarousel');
            const activeItem = carouselElement.querySelector('.carousel-item.active');
            const currentIndex = Array.from(carouselElement.querySelectorAll('.carousel-item')).indexOf(activeItem);
            
            if (activeItem && currentFiles[currentIndex]) {
                // Update data attribute
                activeItem.setAttribute('data-completed', '1');
                
                // Update current file in memory
                currentFiles[currentIndex].is_completed = 1;
                
                // Update badge in current slide
                const badgeContainer = activeItem.querySelector('.d-flex.justify-content-between');
                const existingBadge = badgeContainer.querySelector('.badge');
                
                if (existingBadge) {
                    existingBadge.className = 'badge bg-success';
                    existingBadge.innerHTML = '<i class="fas fa-check"></i> Completed';
                } else {
                    badgeContainer.innerHTML += '<span class="badge bg-success"><i class="fas fa-check"></i> Completed</span>';
                }
            }
            
            console.log('Successfully marked as completed');
            
            // Calculate updated completion count for the chapter
            const completedCount = currentFiles.filter(f => f.is_completed == 1 || f.is_completed === true).length;
            const totalCount = currentFiles.length;
            
            // Update file manager view with new progress
            if (folderId) {
                updateFileManagerItem(folderId, {
                    completed_count: completedCount,
                    total_count: totalCount
                });
                
                // Also update chapter UI if it exists
                updateChapterUI(folderId, {
                    completed_count: completedCount,
                    total_count: totalCount
                });
                
                // Update hierarchy data in memory
                updateHierarchyData(folderId, completedCount, totalCount, fileId, true);
            }
            
            // Update progress states
            calculateProgressStates();
            
            // Update dashboard stats
            await updateDashboardStats();
            
            // Show success toast
            showToast('Content marked as completed!', 'success');
            
            // Auto-advance to next slide after 800ms if not the last slide
            setTimeout(() => {
                if (currentIndex < currentFiles.length - 1) {
                    const carouselInstance = bootstrap.Carousel.getInstance(carouselElement);
                    if (carouselInstance) {
                        carouselInstance.next();
                    }
                } else {
                    // Last slide completed - check if all files are done
                    if (completedCount === totalCount && folderId) {
                        showToast('All content in this chapter completed!', 'success');
                    }
                }
            }, 800);
            
        } else {
            // ERROR - Restore original state
            markCompleteBtn.disabled = false;
            markCompleteBtn.dataset.saving = 'false';
            markCompleteBtn.innerHTML = originalHTML;
            markCompleteBtn.className = originalClasses;
            
            Swal.fire('Error', data.message || 'Failed to save', 'error');
        }
        
    } catch (error) {
        console.error('Error:', error);
        
        // ERROR - Restore original state
        markCompleteBtn.disabled = false;
        markCompleteBtn.dataset.saving = 'false';
        markCompleteBtn.innerHTML = originalHTML;
        markCompleteBtn.className = originalClasses;
        
        Swal.fire('Error', 'Network error occurred', 'error');
    }
}
// Update file item status in the list
         function updateFileItemStatus(fileId, isCompleted) {
        // Find the file item button and update it
        const fileButtons = document.querySelectorAll(`button[onclick*="viewMedia(${fileId}"]`);
        fileButtons.forEach(button => {
            if (isCompleted) {
                button.className = button.className.replace('btn-outline-primary', 'btn-success');
                const icon = button.querySelector('i');
                if (icon) {
                    icon.className = 'fas fa-check';
                }
                
                // Also update the status text
                const fileItem = button.closest('.file-item');
                if (fileItem) {
                    const smallText = fileItem.querySelector('.file-info small');
                    if (smallText) {
                        smallText.textContent = 'Completed';
                    }
                }
            }
        });
    }

// ============================================
// MODAL SETUP
// ============================================
function setupMediaModal() {
            const markCompleteBtn = document.getElementById('markCompleteBtn');
            if (markCompleteBtn) {
                markCompleteBtn.addEventListener('click', function() {
                    markMediaAsCompleted();
                });
            }

            // Close modal handler
            const modal = document.getElementById('mediaModal');
            if (modal) {
                 modal.addEventListener('hidden.bs.modal', function() {
            // Clean up when modal is closed
            currentFiles = [];
            currentFileIndex = 0;
            currentFolderId = null;

            // Stop any playing videos
            document.querySelectorAll('video').forEach(video => {
                video.pause();
                video.currentTime = 0;
            });
            
            // Stop Vimeo videos if any
            document.querySelectorAll('iframe').forEach(iframe => {
                if (iframe.src.includes('vimeo.com')) {
                    // Reset the iframe source to stop the video
                    const src = iframe.src;
                    iframe.src = src.replace('autoplay=1', 'autoplay=0');
                }
            });
            
            // Destroy carousel instance if exists
            if (carouselInstance) {
                carouselInstance.dispose();
                carouselInstance = null;
            }
        });
        
        // Also add event listener for when modal starts hiding
        modal.addEventListener('hide.bs.modal', function() {
            // Pause videos before modal starts hiding
            document.querySelectorAll('video').forEach(video => {
                video.pause();
            });
        });
            }
        }

// ============================================
// UTILITY FUNCTIONS
// ============================================
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function showToast(message, type = 'success') {
    const existingToasts = document.querySelectorAll('.custom-toast');
    existingToasts.forEach(toast => toast.remove());
    
    const toast = document.createElement('div');
    toast.className = `custom-toast toast-${type}`;
    toast.innerHTML = `
        <div class="toast-content">
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
            <span>${message}</span>
        </div>
    `;
    
    document.body.appendChild(toast);
    setTimeout(() => toast.classList.add('show'), 10);
    
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

function showLoading(chapterId, action) {
    const buttonId = `${action}_btn_${chapterId}`;
    const button = document.getElementById(buttonId);
    if (button) {
        button.setAttribute('data-original-html', button.innerHTML);
        button.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Loading...';
        button.disabled = true;
    }
}

function hideLoading(chapterId) {
            // Reset all buttons for this chapter
            const buttonIds = [
                `start_btn_${chapterId}`,
                `continue_btn_${chapterId}`,
                `restart_btn_${chapterId}`
            ];

            buttonIds.forEach(buttonId => {
                const button = document.getElementById(buttonId);
                if (button && button.hasAttribute('data-original-html')) {
                    button.innerHTML = button.getAttribute('data-original-html');
                    button.removeAttribute('data-original-html');
                    button.disabled = false;
                }
            });
        }

 function updateDashboardStats() {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch('/student/get-stats', {
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    console.log(data)
                    if (data.success) {
                        // Update Lessons Completed count with animation
                        const lessonsCompleted = document.querySelector('.stat-card:nth-child(1) h3');
                        if (lessonsCompleted) {
                            lessonsCompleted.classList.add('stat-updated');
                            lessonsCompleted.textContent = data.completed_lectures;
                            setTimeout(() => lessonsCompleted.classList.remove('stat-updated'), 500);
                        }

                        // Update Overall Progress with animation
                        const progressCircle = document.querySelector('.progress-ring circle:last-child');
                        const progressText = document.querySelector('.position-absolute h2');
                        const progressPercentage = data.completion_percentage;

                        if (progressCircle && progressText) {
                            // Add animation class
                            const progressContainer = document.querySelector('.progress-ring');
                            progressContainer.classList.add('progress-updated');

                            // Calculate new stroke-dashoffset
                            const newOffset = 390 - (390 * progressPercentage / 100);
                            progressCircle.style.strokeDashoffset = newOffset;
                            progressCircle.style.transition = 'stroke-dashoffset 1s ease-in-out';

                            // Update text with count-up animation
                            animateCountUp(progressText, progressPercentage);

                            // Remove animation class after animation
                            setTimeout(() => {
                                progressContainer.classList.remove('progress-updated');
                            }, 1000);
                        }
                    }
                })
                .catch(error => {
                    console.error('Error updating dashboard stats:', error);
                });
        }

        
        function cancelPlan() {
            Swal.fire({
                title: 'Are you sure?',
                text: "Do you want to cancel your current plan?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, cancel',
                cancelButtonText: 'No'
            }).then((result) => {
                if (result.isConfirmed) {

                    fetch("{{ route('student.cancel-plan') }}", {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document
                                    .querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content'),
                                'Accept': 'application/json'
                            }
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire(
                                    'Cancelled!',
                                    data.message,
                                    'success'
                                ).then(() => {
                                    window.location.href = data.redirect;
                                });
                            } else {
                                Swal.fire('Error', data.message, 'error');
                            }
                        })
                        .catch(() => {
                            Swal.fire('Error', 'Something went wrong', 'error');
                        });
                }
            });
        }

        // function upgradePlan(data) {
        //     Swal.fire({
        //         title: 'Proceed to upgrade?',
        //         text:'Please note: In case of any package upgrade or downgrade, the previously paid amount will not be adjusted, and the full fee for the newly selected package will be payable.',
        //         icon: 'question',
        //         showCancelButton: true,
        //         confirmButtonText: 'Upgrade',
        //         cancelButtonText: 'Cancel'
        //     }).then((result) => {
        //         if (result.isConfirmed) {
        //             window.location.href = `/student/ReNewPackage`;
        //         }
        //     });
        // }
        function upgradePlan(data) {
    Swal.fire({
        title: '<span style="font-size:20px;font-weight:600;">Proceed to upgrade?</span>',
        html: `
            <p class="include-title">
            <i class="fa-solid fa-triangle-exclamation"></i>
                <strong style="color:#d33;">Please note:</strong><br>
                In case of any <b>package upgrade or downgrade</b>, the 
                <span style="color:#d33;font-weight:600;">previously paid amount will NOT be adjusted</span>, 
                and the <b>full fee</b> for the newly selected package will be payable.
            </p>
        `,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: '<b>Upgrade</b>',
        cancelButtonText: 'Cancel',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#aaa'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = `/student/ReNewPackage`;
        }
    });
}


// Count-up animation for numbers
        function animateCountUp(element, targetValue) {
            const start = parseFloat(element.textContent.replace('%', ''));
            const end = Math.round(targetValue);
            const duration = 1000; // 1 second
            const startTime = performance.now();

            function update(currentTime) {
                const elapsed = currentTime - startTime;
                const progress = Math.min(elapsed / duration, 1);

                // Easing function
                const easeOutCubic = 1 - Math.pow(1 - progress, 3);
                const currentValue = Math.round(start + (end - start) * easeOutCubic);

                element.textContent = currentValue + '%';

                if (progress < 1) {
                    requestAnimationFrame(update);
                }
            }

            requestAnimationFrame(update);
        }

// Make functions globally available
window.startChapter = startChapter;
window.continueChapter = continueChapter;
window.restartChapter = restartChapter;
window.markMediaAsCompleted = markMediaAsCompleted;
window.cancelPlan = cancelPlan;
window.upgradePlan = upgradePlan;
window.toggleNode = toggleNode;

// Helper function for node clicks
function handleNodeClick(nodeId, isContentType) {
    if (isContentType === 'true' || isContentType === true) {
        // For content nodes, toggle chapter actions section
        const chapterActions = document.getElementById('chapter_actions_' + nodeId);
        if (chapterActions) {
            const isVisible = chapterActions.style.display !== 'none' && chapterActions.style.display !== '';
            chapterActions.style.display = isVisible ? 'none' : 'block';
            
            // Update chevron if exists
            const arrow = document.getElementById('arrow_' + nodeId);
            if (arrow) {
                if (!isVisible) {
                    arrow.classList.add('rotated');
                } else {
                    arrow.classList.remove('rotated');
                }
            }
        }
    }
}
window.handleNodeClick = handleNodeClick;

// ============================================
// FILE MANAGER FUNCTIONS
// ============================================

// Set view mode (grid or list)
function setViewMode(mode) {
    currentViewMode = mode;
    const gridBtn = document.getElementById('grid-view-btn');
    const listBtn = document.getElementById('list-view-btn');
    const content = document.getElementById('file-manager-content');
    
    if (gridBtn && listBtn) {
        if (mode === 'grid') {
            gridBtn.classList.add('active');
            listBtn.classList.remove('active');
        } else {
            listBtn.classList.add('active');
            gridBtn.classList.remove('active');
        }
    }
    
    // Reload current view with new mode
    renderCurrentFolder();
}

// Navigate to a folder
function navigateToFolder(folderId) {
    if (folderId === null) {
        // Navigate to root
        currentPath = [];
        renderCurrentFolder();
        updateBreadcrumb('All Content');
        return;
    }
    
    // Find the folder in hierarchy
    const folder = findFolderInHierarchy(fileManagerHierarchy, folderId);
    if (folder) {
        currentPath.push({
            id: folderId,
            name: folder.name,
            type: folder.type
        });
        renderCurrentFolder();
        updateBreadcrumb(folder.name);
    }
}

// Find folder in hierarchy recursively
function findFolderInHierarchy(items, folderId) {
    for (let item of items) {
        if (item.id == folderId) {
            return item;
        }
        if (item.children && item.children.length > 0) {
            const found = findFolderInHierarchy(item.children, folderId);
            if (found) return found;
        }
    }
    return null;
}

// Get current folder items based on path
function getCurrentFolderItems() {
    let items = fileManagerHierarchy;
    
    // Navigate through path
    for (let pathItem of currentPath) {
        const folder = findFolderInHierarchy(items, pathItem.id);
        if (folder && folder.children) {
            items = folder.children;
        } else {
            return [];
        }
    }
    
    return items;
}

// Render current folder content
function renderCurrentFolder() {
    const items = getCurrentFolderItems();
    const contentDiv = document.getElementById('file-manager-content');
    
    if (!contentDiv) return;
    
    if (currentViewMode === 'grid') {
        contentDiv.className = 'file-manager-grid';
        contentDiv.innerHTML = renderGridView(items);
    } else {
        contentDiv.className = 'file-manager-list';
        contentDiv.innerHTML = renderListView(items);
    }
}

// Render grid view HTML
function renderGridView(items) {
    if (items.length === 0) {
        return '<div class="col-12 text-center py-5"><i class="fas fa-folder-open text-muted fs-1 mb-3"></i><p class="text-muted">This folder is empty</p></div>';
    }
    
    let html = '<div class="row g-3">';
    items.forEach(item => {
        const hasChildren = item.children && item.children.length > 0;
        const hasFiles = item.files && item.files.length > 0;
        const isContentType = item.type === 'content';
        
        // Get icon
        const iconClasses = {
            'board': 'fas fa-school text-primary',
            'level': 'fas fa-layer-group text-info',
            'subject': 'fas fa-book text-danger',
            'feature': 'fas fa-star text-warning',
            'content': 'fas fa-folder text-warning'
        };
        const iconClass = iconClasses[item.type] || 'fas fa-folder text-warning';
        
        // Calculate progress
        let completedCount = 0;
        let totalCount = 0;
        let progressPercentage = 0;
        if (isContentType && hasFiles) {
            completedCount = item.files.filter(f => f.is_completed).length;
            totalCount = item.files.length;
            progressPercentage = totalCount > 0 ? (completedCount / totalCount) * 100 : 0;
        }
        
        html += `
            <div class="col-md-4 col-lg-3">
                <div class="file-manager-card" data-item-id="${item.id}">
                    <div class="file-manager-card-icon">
                        <i class="${iconClass}"></i>
                    </div>
                    <div class="file-manager-card-body">
                        <h6 class="file-manager-card-title" title="${item.name}">
                            ${item.name.length > 20 ? item.name.substring(0, 20) + '...' : item.name}
                        </h6>
                        ${isContentType && hasFiles ? `
                            <div class="file-manager-card-progress mb-2">
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar bg-success" style="width: ${progressPercentage}%"></div>
                                </div>
                                <small class="text-muted">${completedCount}/${totalCount}</small>
                            </div>
                        ` : hasChildren ? `
                            <small class="text-muted">${item.children.length} items</small>
                        ` : hasFiles ? `
                            <small class="text-muted">${item.files.length} files</small>
                        ` : `
                            <small class="text-muted">Empty</small>
                        `}
                    </div>
                    <div class="file-manager-card-actions">
                        ${hasChildren || (hasFiles && !isContentType) ? `
                            <button class="btn btn-sm btn-primary" onclick="navigateToFolder('${item.id}')">
                                <i class="fas fa-folder-open"></i> Open
                            </button>
                        ` : isContentType && hasFiles ? `
                            <div class="d-flex gap-1 flex-wrap justify-content-center">
                                ${completedCount == 0 ? 
                                    '<button class="btn btn-sm btn-warning" onclick="startChapter(' + item.id + ')" id="start_btn_' + item.id + '"><i class="fas fa-play me-1"></i> Start</button>' 
                                : completedCount == totalCount ? 
                                    '<button class="btn btn-sm btn-success" disabled><i class="fas fa-check me-1"></i> Completed</button>' +
                                    '<button class="btn btn-sm btn-outline-secondary" onclick="restartChapter(' + item.id + ')" id="restart_btn_' + item.id + '"><i class="fas fa-redo me-1"></i> Restart</button>'
                                : 
                                    '<button class="btn btn-sm btn-outline-secondary" onclick="restartChapter(' + item.id + ')" id="restart_btn_' + item.id + '"><i class="fas fa-redo me-1"></i> Restart</button>' +
                                    '<button class="btn btn-sm btn-info" onclick="continueChapter(' + item.id + ')" id="continue_btn_' + item.id + '"><i class="fas fa-play-circle me-1"></i> Continue</button>'
                                }
                            </div>
                        ` : ''}
                    </div>
                </div>
            </div>
        `;
    });
    html += '</div>';
    return html;
}

// Render list view HTML
function renderListView(items) {
    if (items.length === 0) {
        return '<div class="text-center py-5"><i class="fas fa-folder-open text-muted fs-1 mb-3"></i><p class="text-muted">This folder is empty</p></div>';
    }
    
    let html = `
        <div class="list-header d-none d-md-flex mb-2 p-2 border-bottom">
            <div class="col-md-4"><strong>Name</strong></div>
            <div class="col-md-2"><strong>Type</strong></div>
            <div class="col-md-2"><strong>Progress</strong></div>
            <div class="col-md-4"><strong>Actions</strong></div>
        </div>
    `;
    
    items.forEach(item => {
        const hasChildren = item.children && item.children.length > 0;
        const hasFiles = item.files && item.files.length > 0;
        const isContentType = item.type === 'content';
        
        const iconClasses = {
            'board': 'fas fa-school text-primary',
            'level': 'fas fa-layer-group text-info',
            'subject': 'fas fa-book text-danger',
            'feature': 'fas fa-star text-warning',
            'content': 'fas fa-folder text-warning'
        };
        const iconClass = iconClasses[item.type] || 'fas fa-folder text-warning';
        
        let completedCount = 0;
        let totalCount = 0;
        let progressPercentage = 0;
        if (isContentType && hasFiles) {
            completedCount = item.files.filter(f => f.is_completed).length;
            totalCount = item.files.length;
            progressPercentage = totalCount > 0 ? (completedCount / totalCount) * 100 : 0;
        }
        
        html += `
            <div class="file-manager-list-item d-flex align-items-center p-3 border-bottom" data-item-id="${item.id}">
                <div class="col-md-4 d-flex align-items-center">
                    <i class="${iconClass} me-2 fs-5"></i>
                    <div class="flex-grow-1" style="min-width: 0;">
                        <h6 class="mb-0 text-truncate" style="max-width: 200px;" title="${item.name}">${item.name}</h6>
                        ${isContentType && hasFiles ? `
                            <small class="text-muted">${completedCount}/${totalCount} content</small>
                        ` : hasChildren ? `
                            <small class="text-muted">${item.children.length} items</small>
                        ` : hasFiles ? `
                            <small class="text-muted">${item.files.length} files</small>
                        ` : ''}
                    </div>
                </div>
                <div class="col-md-2">
                    <span class="badge bg-secondary">${(item.type || 'content').charAt(0).toUpperCase() + (item.type || 'content').slice(1)}</span>
                </div>
                <div class="col-md-2">
                    ${isContentType && hasFiles ? `
                        <div class="progress" style="height: 20px;">
                            <div class="progress-bar bg-success" style="width: ${progressPercentage}%" role="progressbar" aria-valuenow="${progressPercentage}" aria-valuemin="0" aria-valuemax="100">
                                <small style="font-size: 11px;">${Math.round(progressPercentage)}%</small>
                            </div>
                        </div>
                    ` : '<span class="text-muted">-</span>'}
                </div>
                <div class="col-md-4">
                    ${hasChildren || (hasFiles && !isContentType) ? `
                        <button class="btn btn-sm btn-primary" onclick="navigateToFolder('${item.id}')">
                            <i class="fas fa-folder-open me-1"></i> Open
                        </button>
                    ` : isContentType && hasFiles ? `
                        <div class="d-flex gap-2 action-buttons" id="action_buttons_${item.id}">
                            ${completedCount == 0 ? 
                                '<button class="btn btn-sm btn-warning" onclick="startChapter(' + item.id + ')" id="start_btn_' + item.id + '"><i class="fas fa-play me-1"></i> Start</button>' 
                            : completedCount == totalCount ? 
                                '<button class="btn btn-sm btn-success" disabled><i class="fas fa-check me-1"></i> Completed</button>' +
                                '<button class="btn btn-sm btn-outline-secondary" onclick="restartChapter(' + item.id + ')" id="restart_btn_' + item.id + '"><i class="fas fa-redo me-1"></i> Restart</button>'
                            : 
                                '<button class="btn btn-sm btn-outline-secondary" onclick="restartChapter(' + item.id + ')" id="restart_btn_' + item.id + '"><i class="fas fa-redo me-1"></i> Restart</button>' +
                                '<button class="btn btn-sm btn-info" onclick="continueChapter(' + item.id + ')" id="continue_btn_' + item.id + '"><i class="fas fa-play-circle me-1"></i> Continue</button>'
                            }
                        </div>
                    ` : '<span class="text-muted">No actions</span>'}
                </div>
            </div>
        `;
    });
    
    return html;
}
// Update breadcrumb
function updateBreadcrumb(currentName) {
    const breadcrumbNav = document.getElementById('breadcrumb-nav');
    if (!breadcrumbNav) return;
    
    // Clear existing breadcrumbs except Home
    const homeItem = breadcrumbNav.querySelector('.breadcrumb-item:first-child');
    breadcrumbNav.innerHTML = '';
    if (homeItem) {
        breadcrumbNav.appendChild(homeItem);
    }
    
    // Add path items
    currentPath.forEach((pathItem, index) => {
        const li = document.createElement('li');
        li.className = 'breadcrumb-item';
        
        if (index === currentPath.length - 1) {
            li.className += ' active';
            li.setAttribute('aria-current', 'page');
            li.textContent = pathItem.name;
        } else {
            const a = document.createElement('a');
            a.href = '#';
            a.className = 'text-decoration-none';
            a.textContent = pathItem.name;
            a.onclick = function() {
                // Navigate back to this level
                currentPath = currentPath.slice(0, index + 1);
                renderCurrentFolder();
                updateBreadcrumb(pathItem.name);
                return false;
            };
            li.appendChild(a);
        }
        
        breadcrumbNav.appendChild(li);
    });
    
    // If no path, show "All Content"
    if (currentPath.length === 0) {
        const li = document.createElement('li');
        li.className = 'breadcrumb-item active';
        li.setAttribute('aria-current', 'page');
        li.textContent = 'All Content';
        breadcrumbNav.appendChild(li);
    }
}

// Update file manager item after chapter actions - IMPROVED VERSION
function updateFileManagerItem(chapterId, data) {
    const completedCount = data.completed_count || 0;
    const totalCount = data.total_count || 1;
    const progressPercentage = totalCount > 0 ? (completedCount / totalCount) * 100 : 0;
    
    // Find the card/list item in file manager view
    const card = document.querySelector(`.file-manager-card[data-item-id="${chapterId}"]`);
    const listItem = document.querySelector(`.file-manager-list-item[data-item-id="${chapterId}"]`);
    
    // Update grid card view
    if (card) {
        // Update progress bar
        const progressBar = card.querySelector('.file-manager-card-progress .progress-bar');
        if (progressBar) {
            progressBar.style.width = `${progressPercentage}%`;
        }
        
        // Update progress text
        const progressText = card.querySelector('.file-manager-card-progress small');
        if (progressText) {
            progressText.textContent = `${completedCount}/${totalCount}`;
        }
        
        // Update buttons
        const actionsDiv = card.querySelector('.file-manager-card-actions');
        if (actionsDiv) {
            let buttonsHTML = '<div class="d-flex gap-1 flex-wrap justify-content-center">';
            
            if (completedCount == 0) {
                buttonsHTML += `<button class="btn btn-sm btn-warning" onclick="startChapter(${chapterId})" id="start_btn_${chapterId}"><i class="fas fa-play me-1"></i> Start</button>`;
            } else if (completedCount == totalCount) {
                buttonsHTML += `<button class="btn btn-sm btn-success" disabled><i class="fas fa-check me-1"></i> Completed</button>`;
                buttonsHTML += `<button class="btn btn-sm btn-outline-secondary" onclick="restartChapter(${chapterId})" id="restart_btn_${chapterId}"><i class="fas fa-redo me-1"></i> Restart</button>`;
            } else {
                buttonsHTML += `<button class="btn btn-sm btn-outline-secondary" onclick="restartChapter(${chapterId})" id="restart_btn_${chapterId}"><i class="fas fa-redo me-1"></i> Restart</button>`;
                buttonsHTML += `<button class="btn btn-sm btn-info" onclick="continueChapter(${chapterId})" id="continue_btn_${chapterId}"><i class="fas fa-play-circle me-1"></i> Continue</button>`;
            }
            
            buttonsHTML += '</div>';
            actionsDiv.innerHTML = buttonsHTML;
        }
    }
    
    // Update list view
    if (listItem) {
        // Update progress bar
        const progressBar = listItem.querySelector('.progress-bar');
        if (progressBar) {
            progressBar.style.width = `${progressPercentage}%`;
            progressBar.textContent = `${Math.round(progressPercentage)}%`;
        }
        
        // Update progress text
        const progressSmall = listItem.querySelector('.col-md-2 small.text-muted');
        if (progressSmall) {
            progressSmall.textContent = `${completedCount}/${totalCount}`;
        }
        
        // Update the small text under the name
        const nameSmall = listItem.querySelector('.col-md-4 small');
        if (nameSmall) {
            nameSmall.textContent = `${completedCount}/${totalCount} completed`;
        }
        
        // Update buttons
        const actionsDiv = listItem.querySelector('.col-md-4 .action-buttons');
        if (actionsDiv) {
            let buttonsHTML = '';
            
            if (completedCount == 0) {
                buttonsHTML = `<button class="btn btn-sm btn-warning" onclick="startChapter(${chapterId})" id="start_btn_${chapterId}"><i class="fas fa-play me-1"></i> Start</button>`;
            } else if (completedCount == totalCount) {
                buttonsHTML = `<button class="btn btn-sm btn-success" disabled><i class="fas fa-check me-1"></i> Completed</button>`;
                buttonsHTML += `<button class="btn btn-sm btn-outline-secondary" onclick="restartChapter(${chapterId})" id="restart_btn_${chapterId}"><i class="fas fa-redo me-1"></i> Restart</button>`;
            } else {
                buttonsHTML = `<button class="btn btn-sm btn-outline-secondary" onclick="restartChapter(${chapterId})" id="restart_btn_${chapterId}"><i class="fas fa-redo me-1"></i> Restart</button>`;
                buttonsHTML += `<button class="btn btn-sm btn-info" onclick="continueChapter(${chapterId})" id="continue_btn_${chapterId}"><i class="fas fa-play-circle me-1"></i> Continue</button>`;
            }
            
            actionsDiv.innerHTML = buttonsHTML;
        }
    }
    
    // Also update the hierarchy data for future renders
    updateHierarchyData(chapterId, completedCount, totalCount);
}
// Update hierarchy data in memory
function updateHierarchyData(chapterId, completedCount, totalCount, fileId = null, isCompleted = false) {
    function updateItemInHierarchy(items) {
        for (let item of items) {
            if (item.id == chapterId && item.type === 'content' && item.files) {
                if (fileId !== null) {
                    // Update specific file completion status
                    const file = item.files.find(f => f.id == fileId);
                    if (file) {
                        file.is_completed = isCompleted ? 1 : 0;
                    }
                } else {
                    // Reset all files (for restart)
                    item.files.forEach(file => {
                        file.is_completed = false;
                    });
                }
                return true;
            }
            if (item.children && item.children.length > 0) {
                if (updateItemInHierarchy(item.children)) {
                    return true;
                }
            }
        }
        return false;
    }
    
    updateItemInHierarchy(fileManagerHierarchy);
}

// Make functions globally available
window.setViewMode = setViewMode;
window.navigateToFolder = navigateToFolder;
window.updateFileManagerItem = updateFileManagerItem;

// block right click
      
        
//         (function () {
//     'use strict';

//     // Method 1: Detect DevTools using size difference + RegExp trick
//     const devtools = {
//         isOpen: false,
//         orientation: null
//     };

//     const threshold = 160; // pixels difference threshold

//     function detectDevTools() {
//         const widthThreshold = window.outerWidth - window.innerWidth > threshold;
//         const heightThreshold = window.outerHeight - window.innerHeight > threshold;

//         if (widthThreshold || heightThreshold) {
//             if (!devtools.isOpen) {
//                 devtools.isOpen = true;
//                 blockPage();
//             }
//         } else {
//             devtools.isOpen = false;
//         }
//     }

//     // Most aggressive countermeasure when DevTools detected
//     function blockPage() {
//         // Option 1: Infinite debugger loop (very hard to bypass)
//         setInterval(() => {
//             debugger; // This freezes the page when DevTools is open
//         }, 100);

//         // Option 2: Break the page completely
//         document.body.innerHTML = `
//             <div style="position:fixed;top:0;left:0;width:100vw;height:100vh;background:#000;color:#f00;font-size:50px;text-align:center;z-index:999999;">
//                 <br><br>Developer Tools are not allowed!<br>
//                 This page has been disabled.
//             </div>
//         `;

//         // Option 3: Redirect or close tab
//         // window.location.href = "about:blank";
//         // Or just crash the renderer:
//         while (true) { /* infinite loop to hang the tab */ }
//     }

//     // Also detect via RegExp toString() trick (very reliable)
//     setInterval(() => {
//         const start = performance.now();
//         debugger;
//         if (performance.now() - start > 100) {
//             // DevTools was open (debugger paused execution)
//             blockPage();
//         }
//     }, 500);

//     // Detect console usage via toString() override
//     const originalToString = Function.prototype.toString;
//     Function.prototype.toString = function () {
//         if (this === console.log || this === console.clear || this === console.error) {
//             blockPage();
//         }
//         return originalToString.apply(this, arguments);
//     };

//     // Run detection frequently
//     setInterval(detectDevTools, 500);
//     window.addEventListener('resize', detectDevTools);
//     detectDevTools();
// })();
// document.addEventListener('contextmenu', function(e) {
//             e.preventDefault(); // Prevents the right-click menu from appearing
//         });
//         document.addEventListener('keydown', function(e) {
//             if (e.keyCode === 123) { // F12
//                 e.preventDefault(); // Prevent F12
//             }
//             if (e.ctrlKey && e.shiftKey && (e.keyCode === 73 || e.keyCode === 74)) { // Ctrl+Shift+I or Ctrl+Shift+J
//                 e.preventDefault(); // Prevent opening DevTools
//             }
//             if (e.ctrlKey && e.keyCode === 85) { // Ctrl+U
//                 e.preventDefault(); // Prevent viewing page source
//             }
//             // Block Ctrl + C (Copy)
//             if (e.ctrlKey && (e.key === 'c' || e.key === 'C')) {
//                 e.preventDefault();
//             }
//             // Block Ctrl + S (Save)
//             if (e.ctrlKey && (e.key === 's' || e.key === 'S')) {
//                 e.preventDefault();
//             }
//         });
    </script>
    <script>
document.addEventListener('keydown', e => {
    if (e.ctrlKey && (e.key === 'p' || e.key === 's' || e.key === 'a')) e.preventDefault();
});
document.addEventListener('contextmenu', e => e.preventDefault());

// PDF.js handles click prevention via canvas pointer-events: none
// No additional click prevention needed - canvas is non-interactive by default
</script>
@endpush
