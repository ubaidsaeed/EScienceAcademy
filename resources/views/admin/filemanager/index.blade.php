@extends('layouts.admin.app')
@can('view file manage')
@push('style')
@vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- Dropify CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/dropify@0.2.2/dist/css/dropify.min.css">
    <!-- Dropify JS -->
    <script src="https://cdn.jsdelivr.net/npm/dropify@0.2.2/dist/js/dropify.min.js"></script>
    {{-- <script src="https://cdn.tailwindcss.com"></script> --}}
    <!-- Vimeo Player API -->
    <script src="https://player.vimeo.com/api/player.js"></script>
    <style>
        .item-card {
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .item-card:hover {
            transform: translateY(-2px);
        }

        .delete-btn {
            cursor: pointer;
        }

        .delete-btn:hover {
            transform: scale(1.1);
        }

        .context-menu-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            width: 100%;
            padding: 0.5rem 1rem;
            text-align: left;
            transition: background-color 0.2s;
        }

        .context-menu-item:hover {
            background-color: rgba(0, 0, 0, 0.05);
        }

        .dark .context-menu-item:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        /* Dropify Custom Styles */
        .dropify-wrapper {
            border: 2px dashed #d1d5db;
            border-radius: 0.5rem;
            background-color: #f9fafb;
        }

        .dark .dropify-wrapper {
            border-color: #4b5563;
            background-color: #1f2937;
        }

        .dropify-wrapper:hover {
            border-color: #3b82f6;
        }

        .dropify-wrapper .dropify-message span.file-icon {
            color: #6b7280;
        }

        .dark .dropify-wrapper .dropify-message span.file-icon {
            color: #9ca3af;
        }

        .dropify-wrapper .dropify-message p {
            color: #374151;
        }

        .dark .dropify-wrapper .dropify-message p {
            color: #d1d5db;
        }

        .dropify-wrapper.has-preview .dropify-preview {
            background-color: #ffffff;
        }

        .dark .dropify-wrapper.has-preview .dropify-preview {
            background-color: #1f2937;
        }

        .dropify-wrapper .dropify-clear {
            color: #ef4444;
        }

        .dropify-wrapper .dropify-clear:hover {
            background-color: rgba(239, 68, 68, 0.1);
        }

        /* Drag and Drop Styles */
        .drag-over {
            border: 2px dashed #3b82f6 !important;
            background-color: rgba(59, 130, 246, 0.1) !important;
        }

        .drag-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(59, 130, 246, 0.2);
            border: 3px dashed #3b82f6;
            z-index: 9999;
            display: none;
            align-items: center;
            justify-content: center;
            pointer-events: none;
        }

        .drag-overlay.active {
            display: flex;
        }

        .drag-overlay-content {
            background: white;
            padding: 2rem;
            border-radius: 0.5rem;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            text-align: center;
        }

        .dark .drag-overlay-content {
            background: #1f2937;
            color: white;
        }

        .upload-progress {
            margin-top: 1rem;
            padding: 1rem;
            background: #f3f4f6;
            border-radius: 0.5rem;
            display: none;
        }

        .dark .upload-progress {
            background: #374151;
        }

        #uploadLoaderOverlay {
            backdrop-filter: blur(4px);
        }

        #uploadLoaderOverlay .animate-spin {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }
            to {
                transform: rotate(360deg);
            }
        }
    </style>
@endpush
@section('content')
  <div class="app-content main-content">
        <div class="side-app">
    <div class="">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">File Manager</h1>
            <p class="text-gray-600 dark:text-gray-400">Manage your files and folders with ease</p>
        </div>

        <!-- Toolbar -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4 mb-4">
            <div class="flex flex-wrap items-center gap-3">
                <button id="createFolderBtn" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    New Folder
                </button>
                <button id="uploadBtn" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                    </svg>
                    Upload Files
                </button>
                <button id="uploadVimeoBtn" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg transition-colors flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                    </svg>
                    Add Vimeo Video
                </button>
                <button id="toolbarPasteBtn" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg transition-colors flex items-center gap-2 hidden" onclick="pasteItem()">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                    Paste
                </button>
                <div class="ml-auto flex items-center gap-2">
                    <button id="viewGridBtn" class="p-2 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                        </svg>
                    </button>
                    <button id="viewListBtn" class="p-2 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Vimeo Upload Area -->
        <div id="vimeoUploadSection" class="hidden mb-4">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Add Vimeo Video</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Enter a Vimeo video URL to add it to your file manager</p>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Vimeo Video URL</label>
                        <input type="url" id="vimeoUrlInput" placeholder="https://vimeo.com/123456789" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    </div>
                    <div class="flex gap-3">
                        <button id="vimeoSubmitBtn" class="px-6 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg transition-colors">
                            Add Video
                        </button>
                        <button id="vimeoCancelBtn" class="px-6 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Breadcrumb -->
        <div id="breadcrumb" class="mb-4 flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
            <button class="hover:text-gray-900 dark:hover:text-white transition-colors" onclick="loadFolder(null)">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
            </button>
        </div>

        <!-- Dropify Upload Area -->
        <div id="uploadSection" class="hidden mb-4">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Upload Files (Images and PDFs only)</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Accepted formats: JPEG, PNG, GIF, WEBP, BMP, SVG, TIFF, ICO, PDF</p>
                <div id="dropifyContainer">
                    <input type="file" id="dropifyInput" class="dropify" data-height="200" multiple accept="image/*,application/pdf" />
                </div>
                <div class="mt-4 flex gap-3">
                    <button id="uploadSubmitBtn" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                        Upload Files
                    </button>
                    <button id="uploadCancelBtn" class="px-6 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors">
                        Cancel
                    </button>
                </div>
            </div>
        </div>

        <!-- Drag and Drop Overlay -->
        <div id="dragOverlay" class="drag-overlay">
            <div class="drag-overlay-content">
                <svg class="w-16 h-16 mx-auto text-blue-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                </svg>
                <p class="text-xl font-semibold">Drop files or folders here to upload</p>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">Supports folders with subfolders and files</p>
            </div>
        </div>

        <!-- Upload Loader Overlay -->
        <div id="uploadLoaderOverlay" class="hidden fixed inset-0 bg-black bg-opacity-50 z-[10000] flex items-center justify-center">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-8 max-w-md w-full mx-4">
                <div class="text-center">
                    <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mb-4"></div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Uploading Files</h3>
                    <p id="uploadLoaderStatus" class="text-sm text-gray-600 dark:text-gray-400 mb-4">Processing files and folders...</p>
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2 mb-2">
                        <div id="uploadLoaderProgressBar" class="bg-blue-600 h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                    </div>
                    <p id="uploadLoaderProgressText" class="text-xs text-gray-500 dark:text-gray-500">0%</p>
                </div>
            </div>
        </div>

        <!-- File Manager Content -->
        <div id="fileManagerContainer" class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div id="loading" class="text-center py-8">
                <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                <p class="mt-2 text-gray-600 dark:text-gray-400">Loading...</p>
            </div>
            <div id="fileManagerContent" class="hidden">
                <div id="itemsContainer" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                    <!-- Items will be loaded here -->
                </div>
            </div>
            <div id="emptyState" class="hidden text-center py-12">
                <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                </svg>
                <p class="text-gray-600 dark:text-gray-400">This folder is empty</p>
                <p class="text-sm text-gray-500 dark:text-gray-500 mt-2">Drag and drop files or folders here to upload</p>
            </div>
            <div id="uploadProgress" class="upload-progress hidden">
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center gap-2">
                        <div id="uploadLoader" class="inline-block animate-spin rounded-full h-4 w-4 border-b-2 border-blue-600"></div>
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Uploading...</span>
                    </div>
                    <span id="uploadProgressText" class="text-sm text-gray-600 dark:text-gray-400">0%</span>
                </div>
                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                    <div id="uploadProgressBar" class="bg-blue-600 h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                </div>
                <p id="uploadStatus" class="text-xs text-gray-600 dark:text-gray-400 mt-2"></p>
            </div>
        </div>
    </div>

    <!-- Context Menu -->
    <div id="contextMenu" class="hidden fixed bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg py-2 z-50 min-w-[200px]">
        <button class="context-menu-item" onclick="copyItem()">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
            </svg>
            Copy
        </button>
        <button class="context-menu-item" onclick="cutItem()">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
            </svg>
            Cut
        </button>
        <button id="pasteBtn" class="context-menu-item hidden" onclick="pasteItem()">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
            </svg>
            Paste
        </button>
        <div class="border-t border-gray-200 dark:border-gray-700 my-1"></div>
        <button class="context-menu-item" onclick="renameItem()">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
            </svg>
            Rename
        </button>
        <button class="context-menu-item" onclick="viewItem()">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
            </svg>
            View
        </button>
        <button id="downloadMenuItem" class="context-menu-item hidden" onclick="downloadItem()">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
            </svg>
            Download
        </button>
        <button class="context-menu-item" onclick="showProperties()">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            Properties
        </button>
        <div class="border-t border-gray-200 dark:border-gray-700 my-1"></div>
        <button class="context-menu-item text-red-600 dark:text-red-400" onclick="deleteItem()">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
            </svg>
            Delete
        </button>
    </div>

    <!-- View Modal -->
    <div id="viewModal" class="hidden fixed inset-0 bg-black bg-opacity-75 z-50 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg max-w-4xl w-full max-h-[90vh] overflow-auto">
            <div class="flex justify-between items-center p-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white" id="viewModalTitle">View File</h3>
                <button onclick="closeViewModal()" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="p-4" id="viewModalContent">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>

    <!-- Properties Modal -->
    <div id="propertiesModal" class="hidden fixed inset-0 bg-black bg-opacity-75 z-50 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg max-w-2xl w-full">
            <div class="flex justify-between items-center p-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Properties</h3>
                <button onclick="closePropertiesModal()" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="p-6" id="propertiesModalContent">
                <!-- Properties will be loaded here -->
            </div>
        </div>
    </div>
    </div>
    </div>

@endsection
@push('script')
    <script>
        let currentFolderId = null;
        let selectedItem = null;
        let viewMode = 'grid';
        let clipboard = null; // {type: 'copy'|'cut', item: {...}, itemType: 'file'|'folder'}

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            setupEventListeners();
            setupDragAndDrop();
            loadFolder(null);
            updatePasteButton();
        });

        let dropifyInstance = null;

        function setupEventListeners() {
            // Create folder button
            document.getElementById('createFolderBtn').addEventListener('click', createFolder);

            // Upload button - show upload section
            document.getElementById('uploadBtn').addEventListener('click', showUploadSection);

            // Upload submit button
            document.getElementById('uploadSubmitBtn').addEventListener('click', handleDropifyUpload);

            // Upload cancel button
            document.getElementById('uploadCancelBtn').addEventListener('click', hideUploadSection);

            // Vimeo upload button
            document.getElementById('uploadVimeoBtn').addEventListener('click', showVimeoUploadSection);

            // Vimeo submit button
            document.getElementById('vimeoSubmitBtn').addEventListener('click', handleVimeoUpload);

            // Vimeo cancel button
            document.getElementById('vimeoCancelBtn').addEventListener('click', hideVimeoUploadSection);

            // View mode buttons
            document.getElementById('viewGridBtn').addEventListener('click', () => setViewMode('grid'));
            document.getElementById('viewListBtn').addEventListener('click', () => setViewMode('list'));

            // Context menu
            document.addEventListener('click', () => {
                document.getElementById('contextMenu').classList.add('hidden');
            });

            // Close context menu on escape
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    document.getElementById('contextMenu').classList.add('hidden');
                    document.getElementById('viewModal').classList.add('hidden');
                    document.getElementById('propertiesModal').classList.add('hidden');
                }
                // Keyboard shortcuts
                if (e.ctrlKey || e.metaKey) {
                    if (e.key === 'c' && selectedItem) {
                        e.preventDefault();
                        copyItem();
                    } else if (e.key === 'x' && selectedItem) {
                        e.preventDefault();
                        cutItem();
                    } else if (e.key === 'v' && clipboard) {
                        e.preventDefault();
                        pasteItem();
                    }
                }
            });
        }

        function setupDragAndDrop() {
            const container = document.getElementById('fileManagerContainer');
            const overlay = document.getElementById('dragOverlay');
            let dragCounter = 0;

            // Prevent default drag behaviors only on the container
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                container.addEventListener(eventName, (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                }, false);
            });

            // Handle drag enter
            container.addEventListener('dragenter', (e) => {
                e.preventDefault();
                e.stopPropagation();
                dragCounter++;
                if (e.dataTransfer.items && e.dataTransfer.items.length > 0) {
                    overlay.classList.add('active');
                    container.classList.add('drag-over');
                }
            });

            // Handle drag over
            container.addEventListener('dragover', (e) => {
                e.preventDefault();
                e.stopPropagation();
                if (e.dataTransfer.items && e.dataTransfer.items.length > 0) {
                    overlay.classList.add('active');
                    container.classList.add('drag-over');
                }
            });

            // Handle drag leave
            container.addEventListener('dragleave', (e) => {
                e.preventDefault();
                e.stopPropagation();
                dragCounter--;
                if (dragCounter === 0) {
                    overlay.classList.remove('active');
                    container.classList.remove('drag-over');
                }
            });

            // Handle drop
            container.addEventListener('drop', async (e) => {
                e.preventDefault();
                e.stopPropagation();
                dragCounter = 0;
                overlay.classList.remove('active');
                container.classList.remove('drag-over');

                console.log('Drop event triggered', e.dataTransfer);

                // Try to get items first (for folder support)
                const items = e.dataTransfer.items;
                const files = e.dataTransfer.files;

                if (items && items.length > 0) {
                    console.log('Processing items:', items.length);
                    await handleDragAndDrop(items);
                } else if (files && files.length > 0) {
                    console.log('Processing files:', files.length);
                    // Fallback: process files directly
                    await handleDragAndDropFiles(files);
                } else {
                    console.warn('No items or files found in drop event');
                    alert('No files found. Please try again.');
                }
            });
        }

        async function handleDragAndDropFiles(files) {
            const progressDiv = document.getElementById('uploadProgress');
            const progressBar = document.getElementById('uploadProgressBar');
            const progressText = document.getElementById('uploadProgressText');
            const statusText = document.getElementById('uploadStatus');
            const loaderOverlay = document.getElementById('uploadLoaderOverlay');
            const loaderStatus = document.getElementById('uploadLoaderStatus');
            const loaderProgressBar = document.getElementById('uploadLoaderProgressBar');
            const loaderProgressText = document.getElementById('uploadLoaderProgressText');

            // Show both progress bar and loader overlay
            progressDiv.classList.remove('hidden');
            loaderOverlay.classList.remove('hidden');
            progressBar.style.width = '0%';
            progressText.textContent = '0%';
            loaderProgressBar.style.width = '0%';
            loaderProgressText.textContent = '0%';
            statusText.textContent = 'Processing files...';
            loaderStatus.textContent = 'Processing files...';

            try {
                const fileArray = Array.from(files);
                const totalFiles = fileArray.length;
                let processedFiles = 0;
                let skippedFiles = 0;

                if (totalFiles === 0) {
                    statusText.textContent = 'No files found to upload';
                    loaderStatus.textContent = 'No files found to upload';
                    setTimeout(() => {
                        progressDiv.classList.add('hidden');
                        loaderOverlay.classList.add('hidden');
                    }, 2000);
                    return;
                }

                statusText.textContent = `Uploading ${totalFiles} file(s)...`;
                loaderStatus.textContent = `Uploading ${totalFiles} file(s)...`;

                for (const file of fileArray) {
                    try {
                        loaderStatus.textContent = `Uploading ${file.name}... (${processedFiles + 1} of ${totalFiles})`;
                        const uploadResult = await uploadSingleFile(file, currentFolderId);
                        if (!uploadResult) {
                            skippedFiles++;
                        }
                        
                        processedFiles++;
                        const progress = Math.round((processedFiles / totalFiles) * 100);
                        progressBar.style.width = progress + '%';
                        progressText.textContent = progress + '%';
                        loaderProgressBar.style.width = progress + '%';
                        loaderProgressText.textContent = progress + '%';
                        statusText.textContent = `Uploading ${processedFiles} of ${totalFiles} file(s)...`;
                    } catch (error) {
                        console.error('Error processing file:', file.name, error);
                        skippedFiles++;
                        processedFiles++;
                    }
                }

                const successCount = totalFiles - skippedFiles;
                if (skippedFiles > 0) {
                    statusText.textContent = `Uploaded ${successCount} file(s), skipped ${skippedFiles} invalid file(s)`;
                    loaderStatus.textContent = `Uploaded ${successCount} file(s), skipped ${skippedFiles} invalid file(s)`;
                } else {
                    statusText.textContent = `Successfully uploaded ${successCount} file(s)!`;
                    loaderStatus.textContent = `Successfully uploaded ${successCount} file(s)!`;
                }
                progressBar.style.width = '100%';
                progressText.textContent = '100%';
                loaderProgressBar.style.width = '100%';
                loaderProgressText.textContent = '100%';

                setTimeout(() => {
                    progressDiv.classList.add('hidden');
                    loaderOverlay.classList.add('hidden');
                    loadFolder(currentFolderId);
                }, 2000);

            } catch (error) {
                console.error('Error handling drag and drop files:', error);
                statusText.textContent = 'Error uploading files: ' + (error.message || 'Unknown error');
                loaderStatus.textContent = 'Error uploading files: ' + (error.message || 'Unknown error');
                progressBar.style.width = '0%';
                progressText.textContent = 'Error';
                loaderProgressBar.style.width = '0%';
                loaderProgressText.textContent = 'Error';
                
                setTimeout(() => {
                    progressDiv.classList.add('hidden');
                    loaderOverlay.classList.add('hidden');
                }, 3000);
            }
        }

        async function handleDragAndDrop(items) {
            const progressDiv = document.getElementById('uploadProgress');
            const progressBar = document.getElementById('uploadProgressBar');
            const progressText = document.getElementById('uploadProgressText');
            const statusText = document.getElementById('uploadStatus');
            const loaderOverlay = document.getElementById('uploadLoaderOverlay');
            const loaderStatus = document.getElementById('uploadLoaderStatus');
            const loaderProgressBar = document.getElementById('uploadLoaderProgressBar');
            const loaderProgressText = document.getElementById('uploadLoaderProgressText');

            // Show both progress bar and loader overlay
            progressDiv.classList.remove('hidden');
            loaderOverlay.classList.remove('hidden');
            progressBar.style.width = '0%';
            progressText.textContent = '0%';
            loaderProgressBar.style.width = '0%';
            loaderProgressText.textContent = '0%';
            statusText.textContent = 'Processing files and folders...';
            loaderStatus.textContent = 'Processing files and folders...';

            try {
                const fileStructure = {};
                let totalFiles = 0;
                let processedFiles = 0;
                let skippedFiles = 0;

                // Process all items
                for (let i = 0; i < items.length; i++) {
                    const item = items[i];
                    
                    if (item.kind === 'file') {
                        const entry = item.webkitGetAsEntry ? item.webkitGetAsEntry() : null;
                        
                        if (entry) {
                            // Process directory entry (supports folders)
                            await processEntry(entry, fileStructure, '');
                        } else {
                            // Fallback for browsers that don't support webkitGetAsEntry
                            const file = item.getAsFile();
                            if (file) {
                                if (!fileStructure['']) {
                                    fileStructure[''] = [];
                                }
                                fileStructure[''].push(file);
                            }
                        }
                    }
                }

                // Count total files
                function countFiles(structure) {
                    let count = 0;
                    for (const path in structure) {
                        count += structure[path].length;
                    }
                    return count;
                }

                totalFiles = countFiles(fileStructure);

                if (totalFiles === 0) {
                    statusText.textContent = 'No valid files found to upload';
                    loaderStatus.textContent = 'No valid files found to upload';
                    setTimeout(() => {
                        progressDiv.classList.add('hidden');
                        loaderOverlay.classList.add('hidden');
                    }, 2000);
                    return;
                }

                // Upload files with folder structure
                statusText.textContent = `Uploading ${totalFiles} file(s)...`;
                loaderStatus.textContent = `Uploading ${totalFiles} file(s)...`;
                
                // Create a cache for folder IDs to avoid duplicate API calls
                const folderCache = {};
                
                for (const folderPath in fileStructure) {
                    const files = fileStructure[folderPath];
                    
                    for (const file of files) {
                        try {
                            loaderStatus.textContent = `Uploading ${file.name}... (${processedFiles + 1} of ${totalFiles})`;
                            
                            // Create folder structure if needed
                            let targetFolderId = currentFolderId;
                            
                            if (folderPath) {
                                const pathParts = folderPath.split('/').filter(p => p);
                                const cacheKey = pathParts.join('/');
                                
                                // Check cache first
                                if (folderCache[cacheKey]) {
                                    targetFolderId = folderCache[cacheKey];
                                } else {
                                    // Build folder path incrementally
                                    let currentPath = '';
                                    for (const folderName of pathParts) {
                                        currentPath = currentPath ? `${currentPath}/${folderName}` : folderName;
                                        
                                        if (!folderCache[currentPath]) {
                                            targetFolderId = await getOrCreateFolder(folderName, targetFolderId);
                                            folderCache[currentPath] = targetFolderId;
                                        } else {
                                            targetFolderId = folderCache[currentPath];
                                        }
                                    }
                                }
                            }

                            // Upload file
                            const uploadResult = await uploadSingleFile(file, targetFolderId);
                            if (!uploadResult) {
                                skippedFiles++;
                            }
                            
                            processedFiles++;
                            const progress = Math.round((processedFiles / totalFiles) * 100);
                            progressBar.style.width = progress + '%';
                            progressText.textContent = progress + '%';
                            loaderProgressBar.style.width = progress + '%';
                            loaderProgressText.textContent = progress + '%';
                            statusText.textContent = `Uploading ${processedFiles} of ${totalFiles} file(s)...`;
                        } catch (error) {
                            console.error('Error processing file:', file.name, error);
                            skippedFiles++;
                            processedFiles++;
                        }
                    }
                }

                const successCount = totalFiles - skippedFiles;
                if (skippedFiles > 0) {
                    statusText.textContent = `Uploaded ${successCount} file(s), skipped ${skippedFiles} invalid file(s)`;
                    loaderStatus.textContent = `Uploaded ${successCount} file(s), skipped ${skippedFiles} invalid file(s)`;
                } else {
                    statusText.textContent = `Successfully uploaded ${successCount} file(s)!`;
                    loaderStatus.textContent = `Successfully uploaded ${successCount} file(s)!`;
                }
                progressBar.style.width = '100%';
                progressText.textContent = '100%';
                loaderProgressBar.style.width = '100%';
                loaderProgressText.textContent = '100%';

                // Reload folder after a short delay
                setTimeout(() => {
                    progressDiv.classList.add('hidden');
                    loaderOverlay.classList.add('hidden');
                    loadFolder(currentFolderId);
                }, 2000);

            } catch (error) {
                console.error('Error handling drag and drop:', error);
                statusText.textContent = 'Error uploading files: ' + (error.message || 'Unknown error');
                loaderStatus.textContent = 'Error uploading files: ' + (error.message || 'Unknown error');
                progressBar.style.width = '0%';
                progressText.textContent = 'Error';
                loaderProgressBar.style.width = '0%';
                loaderProgressText.textContent = 'Error';
                
                setTimeout(() => {
                    progressDiv.classList.add('hidden');
                    loaderOverlay.classList.add('hidden');
                }, 3000);
            }
        }

        async function processEntry(entry, fileStructure, currentPath) {
            if (entry.isFile) {
                return new Promise((resolve, reject) => {
                    entry.file((file) => {
                        try {
                            if (!fileStructure[currentPath]) {
                                fileStructure[currentPath] = [];
                            }
                            fileStructure[currentPath].push(file);
                            resolve();
                        } catch (error) {
                            console.error('Error processing file entry:', error);
                            reject(error);
                        }
                    }, (error) => {
                        console.error('Error reading file:', error);
                        reject(error);
                    });
                });
            } else if (entry.isDirectory) {
                const reader = entry.createReader();
                const dirPath = currentPath ? `${currentPath}/${entry.name}` : entry.name;
                
                return new Promise((resolve, reject) => {
                    const readEntries = () => {
                        reader.readEntries((entries) => {
                            if (entries.length === 0) {
                                resolve();
                            } else {
                                const promises = entries.map(subEntry => {
                                    return processEntry(subEntry, fileStructure, dirPath).catch(error => {
                                        console.error('Error processing entry:', error);
                                        return null; // Continue processing other entries
                                    });
                                });
                                Promise.all(promises).then(() => {
                                    readEntries(); // Continue reading
                                }).catch(error => {
                                    console.error('Error reading directory entries:', error);
                                    reject(error);
                                });
                            }
                        }, (error) => {
                            console.error('Error reading directory:', error);
                            reject(error);
                        });
                    };
                    readEntries();
                });
            } else {
                return Promise.resolve();
            }
        }

        async function getOrCreateFolder(folderName, parentId) {
            try {
                // First, check if folder exists
                const response = await fetch(`/filemanager/items?folder_id=${parentId || ''}`);
                const data = await response.json();
                
                const existingFolder = data.folders.find(f => f.name === folderName);
                if (existingFolder) {
                    return existingFolder.id;
                }

                // Create new folder
                const createResponse = await fetch('/filemanager/folder', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        name: folderName,
                        parent_id: parentId
                    })
                });

                const createData = await createResponse.json();
                if (createData.success) {
                    return createData.folder.id;
                }
                
                return parentId;
            } catch (error) {
                console.error('Error creating folder:', error);
                return parentId;
            }
        }

        async function uploadSingleFile(file, folderId) {
            // Validate file type (images and PDFs only)
            const isValidImage = file.type.startsWith('image/');
            const isValidPdf = file.type === 'application/pdf';

            if (!isValidImage && !isValidPdf) {
                console.warn(`Skipping file ${file.name}: Only images and PDFs are allowed. File type: ${file.type}`);
                return false;
            }

            const formData = new FormData();
            formData.append('files[]', file);
            if (folderId) {
                formData.append('folder_id', folderId);
            }

            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]');
                if (!csrfToken) {
                    console.error('CSRF token not found');
                    return false;
                }

                console.log(`Uploading file: ${file.name} to folder: ${folderId || 'root'}`);
                
                const response = await fetch('/filemanager/upload', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken.content
                    },
                    body: formData
                });

                if (!response.ok) {
                    console.error('Upload response not OK:', response.status, response.statusText);
                    const errorText = await response.text();
                    console.error('Error response:', errorText);
                    return false;
                }

                const data = await response.json();
                if (!data.success) {
                    console.error('Error uploading file:', file.name, data.message || 'Unknown error');
                    return false;
                }
                
                console.log(`Successfully uploaded: ${file.name}`);
                return true;
            } catch (error) {
                console.error('Error uploading file:', file.name, error);
                return false;
            }
        }

        function showUploadSection() {
            const uploadSection = document.getElementById('uploadSection');
            uploadSection.classList.remove('hidden');

            // Destroy existing instance if any
            if (dropifyInstance) {
                dropifyInstance.dropify('destroy');
                dropifyInstance = null;
            }

            // Clear container and recreate input
            const container = document.getElementById('dropifyContainer');
            container.innerHTML =
                '<input type="file" id="dropifyInput" class="dropify" data-height="200" multiple accept="image/*,application/pdf" />';

            // Initialize Dropify with multiple file support
            dropifyInstance = $('#dropifyInput').dropify({
                messages: {
                    'default': 'Drag and drop images or PDF files here or click to select',
                    'replace': 'Drag and drop or click to replace',
                    'remove': 'Remove',
                    'error': 'Ooops, something wrong happened.'
                }
            });

            // Add client-side validation
            document.getElementById('dropifyInput').addEventListener('change', function(e) {
                const files = Array.from(e.target.files);
                const invalidFiles = [];

                files.forEach(file => {
                    const isValidImage = file.type.startsWith('image/');
                    const isValidPdf = file.type === 'application/pdf';

                    if (!isValidImage && !isValidPdf) {
                        invalidFiles.push(file.name);
                    }
                });

                if (invalidFiles.length > 0) {
                    alert('Only image files and PDF documents are allowed. Invalid files:\n' + invalidFiles.join(
                        '\n'));
                    // Clear invalid files
                    const validFiles = files.filter(file => {
                        return file.type.startsWith('image/') || file.type === 'application/pdf';
                    });

                    const dt = new DataTransfer();
                    validFiles.forEach(file => dt.items.add(file));
                    e.target.files = dt.files;

                    // Reinitialize Dropify with valid files
                    if (dropifyInstance) {
                        dropifyInstance.dropify('destroy');
                    }
                    dropifyInstance = $('#dropifyInput').dropify({
                        messages: {
                            'default': 'Drag and drop images or PDF files here or click to select',
                            'replace': 'Drag and drop or click to replace',
                            'remove': 'Remove',
                            'error': 'Ooops, something wrong happened.'
                        }
                    });
                }
            });
        }

        function hideUploadSection() {
            const uploadSection = document.getElementById('uploadSection');
            uploadSection.classList.add('hidden');

            // Destroy Dropify instance
            if (dropifyInstance) {
                dropifyInstance.dropify('destroy');
                dropifyInstance = null;
            }

            // Clear container
            const container = document.getElementById('dropifyContainer');
            container.innerHTML =
                '<input type="file" id="dropifyInput" class="dropify" data-height="200" multiple accept="image/*,application/pdf" />';
        }

        function showVimeoUploadSection() {
            const vimeoSection = document.getElementById('vimeoUploadSection');
            vimeoSection.classList.remove('hidden');
            document.getElementById('vimeoUrlInput').value = '';
        }

        function hideVimeoUploadSection() {
            const vimeoSection = document.getElementById('vimeoUploadSection');
            vimeoSection.classList.add('hidden');
            document.getElementById('vimeoUrlInput').value = '';
        }

        async function handleVimeoUpload() {
            const vimeoUrl = document.getElementById('vimeoUrlInput').value.trim();

            if (!vimeoUrl) {
                alert('Please enter a Vimeo video URL');
                return;
            }

            // Basic URL validation
            if (!vimeoUrl.includes('vimeo.com')) {
                alert('Please enter a valid Vimeo URL');
                return;
            }

            const submitBtn = document.getElementById('vimeoSubmitBtn');
            const originalText = submitBtn.textContent;
            submitBtn.disabled = true;
            submitBtn.textContent = 'Adding Video...';

            try {
                const response = await fetch('/filemanager/upload-vimeo', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        vimeo_url: vimeoUrl,
                        folder_id: currentFolderId
                    })
                });

                const data = await response.json();
                if (data.success) {
                    loadFolder(currentFolderId);
                    hideVimeoUploadSection();
                    alert(data.message || 'Vimeo video added successfully!');
                } else {
                    alert('Error adding Vimeo video: ' + (data.message || 'Unknown error'));
                }
            } catch (error) {
                console.error('Error uploading Vimeo video:', error);
                alert('Error uploading Vimeo video');
            } finally {
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
            }
        }

        async function loadFolder(folderId) {
            currentFolderId = folderId;
            document.getElementById('loading').classList.remove('hidden');
            document.getElementById('fileManagerContent').classList.add('hidden');
            document.getElementById('emptyState').classList.add('hidden');

            try {
                const response = await fetch(`/filemanager/items?folder_id=${folderId || ''}`);
                const data = await response.json();

                // Load breadcrumb
                await loadBreadcrumb(folderId);

                // Render items
                renderItems(data.folders, data.files);

                document.getElementById('loading').classList.add('hidden');
            } catch (error) {
                console.error('Error loading folder:', error);
                document.getElementById('loading').classList.add('hidden');
                alert('Error loading folder');
            }
        }

        async function loadBreadcrumb(folderId) {
            try {
                const response = await fetch(`/filemanager/breadcrumb?folder_id=${folderId || ''}`);
                const breadcrumb = await response.json();

                const breadcrumbEl = document.getElementById('breadcrumb');
                breadcrumbEl.innerHTML =
                    '<button class="hover:text-gray-900 dark:hover:text-white transition-colors" onclick="loadFolder(null)"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg></button>';

                breadcrumb.forEach(item => {
                    const span = document.createElement('span');
                    span.innerHTML = ' / ';
                    breadcrumbEl.appendChild(span);

                    const button = document.createElement('button');
                    button.textContent = item.name;
                    button.className = 'hover:text-gray-900 dark:hover:text-white transition-colors';
                    button.onclick = () => loadFolder(item.id);
                    breadcrumbEl.appendChild(button);
                });
            } catch (error) {
                console.error('Error loading breadcrumb:', error);
            }
        }

        function renderItems(folders, files) {
            const container = document.getElementById('itemsContainer');
            container.innerHTML = '';

            if (folders.length === 0 && files.length === 0) {
                document.getElementById('emptyState').classList.remove('hidden');
                return;
            }

            document.getElementById('fileManagerContent').classList.remove('hidden');
            document.getElementById('emptyState').classList.add('hidden');

            // Render folders
            folders.forEach(folder => {
                const item = createFolderItem(folder);
                container.appendChild(item);
            });

            // Render files
            files.forEach(file => {
                const item = createFileItem(file);
                container.appendChild(item);
            });
        }

        function createFolderItem(folder) {
            const div = document.createElement('div');
            div.className = 'item-card group cursor-pointer relative';
            div.dataset.type = 'folder';
            div.dataset.id = folder.id;
            div.onclick = (e) => {
                if (!e.target.closest('.delete-btn')) {
                    loadFolder(folder.id);
                }
            };
            div.oncontextmenu = (e) => showContextMenu(e, folder, 'folder');

            div.innerHTML = `
                <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4 mb-2 flex items-center justify-center h-32 relative">
                    <svg class="w-12 h-12 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
                    </svg>
                    <button class="delete-btn absolute top-2 right-2 bg-red-500 hover:bg-red-600 text-white rounded-full p-1.5 opacity-0 group-hover:opacity-100 transition-opacity shadow-lg" onclick="deleteItemById(${folder.id}, 'folder', '${folder.name.replace(/'/g, "\\'")}')" title="Delete folder">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
                </div>
                <p class="text-sm font-medium text-gray-900 color-change truncate dark-mode"  title="${folder.name}" style="color:black">${folder.name}</p>
            `;

            return div;
        }

        function createFileItem(file) {
            const div = document.createElement('div');
            div.className = 'item-card group cursor-pointer relative';
            div.dataset.type = 'file';
            div.dataset.id = file.id;
            div.onclick = (e) => {
                if (!e.target.closest('.delete-btn')) {
                    selectedItem = {
                        ...file,
                        type: 'file'
                    };
                    viewItem();
                }
            };
            div.oncontextmenu = (e) => showContextMenu(e, file, 'file');

            const icon = getFileIcon(file);
            const size = formatFileSize(file.size);
            const safeFileName = file.original_name.replace(/'/g, "\\'");

            div.innerHTML = `
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 mb-2 flex items-center justify-center h-32 relative">
                    ${icon}
                    <button class="delete-btn absolute top-2 right-2 bg-red-500 hover:bg-red-600 text-white rounded-full p-1.5 opacity-0 group-hover:opacity-100 transition-opacity shadow-lg z-10" onclick="deleteItemById(${file.id}, 'file', '${safeFileName}')" title="Delete file">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
                </div>
                <p class="text-sm font-medium text-gray-900 dark:text-white truncate" title="${file.original_name}">${file.original_name}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400">${size}</p>
            `;

            return div;
        }

        function getFileIcon(file) {
            // Check if it's a Vimeo video
            if (file.is_vimeo) {
                // First try downloaded thumbnail, then Vimeo URL
                if (file.path && file.path.includes('vimeo')) {
                    const thumbnailUrl = file.path.startsWith('storage/') ? `/${file.path}` : `/storage/${file.path}`;
                    return `<img src="${thumbnailUrl}" alt="${file.original_name}" class="max-w-full max-h-full object-contain rounded" onerror="this.onerror=null; this.src='${file.vimeo_thumbnail_url || ''}'">`;
                } else if (file.vimeo_thumbnail_url) {
                    return `<img src="${file.vimeo_thumbnail_url}" alt="${file.original_name}" class="max-w-full max-h-full object-contain rounded">`;
                } else {
                    return `<svg class="w-12 h-12 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                    </svg>`;
                }
            } else if (file.mime_type && file.mime_type.startsWith('image/')) {
                const imageUrl = file.path.startsWith('storage/') ? `/${file.path}` : `/storage/${file.path}`;
                return `<img src="${imageUrl}" alt="${file.original_name}" class="max-w-full max-h-full object-contain rounded">`;
            } else if (file.mime_type === 'application/pdf') {
                return `<svg class="w-12 h-12 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                </svg>`;
            } else {
                return `<svg class="w-12 h-12 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>`;
            }
        }

        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
        }

        function setViewMode(mode) {
            viewMode = mode;
            const container = document.getElementById('itemsContainer');
            if (mode === 'grid') {
                container.className = 'grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4';
            } else {
                container.className = 'space-y-2';
            }
            loadFolder(currentFolderId);
        }

        async function createFolder() {
            const name = prompt('Enter folder name:');
            if (!name) return;

            try {
                const response = await fetch('/filemanager/folder', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        name: name,
                        parent_id: currentFolderId
                    })
                });

                const data = await response.json();
                if (data.success) {
                    loadFolder(currentFolderId);
                } else {
                    alert('Error creating folder');
                }
            } catch (error) {
                console.error('Error creating folder:', error);
                alert('Error creating folder');
            }
        }

        async function handleDropifyUpload() {
            const fileInput = document.getElementById('dropifyInput');
            const files = fileInput.files;

            if (files.length === 0) {
                alert('Please select at least one file to upload');
                return;
            }

            // Validate file types (images and PDFs only)
            const invalidFiles = [];
            const validFiles = [];

            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                const isValidImage = file.type.startsWith('image/');
                const isValidPdf = file.type === 'application/pdf';

                if (isValidImage || isValidPdf) {
                    validFiles.push(file);
                } else {
                    invalidFiles.push(file.name);
                }
            }

            if (invalidFiles.length > 0) {
                alert('Only image files and PDF documents are allowed.\n\nInvalid files:\n' + invalidFiles.join('\n'));
                return;
            }

            if (validFiles.length === 0) {
                alert('Please select at least one valid file (image or PDF) to upload');
                return;
            }

            const formData = new FormData();
            for (let i = 0; i < validFiles.length; i++) {
                formData.append('files[]', validFiles[i]);
            }
            if (currentFolderId) {
                formData.append('folder_id', currentFolderId);
            }

            // Show loading state
            const submitBtn = document.getElementById('uploadSubmitBtn');
            const originalText = submitBtn.textContent;
            submitBtn.disabled = true;
            submitBtn.textContent = 'Uploading...';

            try {
                const response = await fetch('/filemanager/upload', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: formData
                });

                const data = await response.json();
                if (data.success) {
                    loadFolder(currentFolderId);
                    hideUploadSection();
                    alert(`Successfully uploaded ${files.length} file(s)!`);
                } else {
                    alert('Error uploading files: ' + (data.message || 'Unknown error'));
                }
            } catch (error) {
                console.error('Error uploading files:', error);
                alert('Error uploading files');
            } finally {
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
            }
        }

        function showContextMenu(e, item, type) {
            e.preventDefault();
            e.stopPropagation();
            selectedItem = {
                ...item,
                type
            };

            const menu = document.getElementById('contextMenu');
            menu.classList.remove('hidden');
            menu.style.left = e.pageX + 'px';
            menu.style.top = e.pageY + 'px';

            // Show/hide download option for files only
            const downloadMenuItem = document.getElementById('downloadMenuItem');
            if (type === 'file') {
                downloadMenuItem.classList.remove('hidden');
            } else {
                downloadMenuItem.classList.add('hidden');
            }

            updatePasteButton();
        }

        function updatePasteButton() {
            const pasteBtn = document.getElementById('pasteBtn');
            const toolbarPasteBtn = document.getElementById('toolbarPasteBtn');
            if (clipboard) {
                pasteBtn.classList.remove('hidden');
                toolbarPasteBtn.classList.remove('hidden');
            } else {
                pasteBtn.classList.add('hidden');
                toolbarPasteBtn.classList.add('hidden');
            }
        }

        async function copyItem() {
            if (!selectedItem) return;
            document.getElementById('contextMenu').classList.add('hidden');

            try {
                const response = await fetch('/filemanager/copy', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        type: selectedItem.type,
                        id: selectedItem.id
                    })
                });

                const data = await response.json();
                if (data.success) {
                    clipboard = {
                        type: 'copy',
                        item: data.item,
                        itemType: selectedItem.type
                    };
                    updatePasteButton();
                    alert('Item copied to clipboard');
                }
            } catch (error) {
                console.error('Error copying item:', error);
                alert('Error copying item');
            }
        }

        async function cutItem() {
            if (!selectedItem) return;
            document.getElementById('contextMenu').classList.add('hidden');

            try {
                const response = await fetch('/filemanager/copy', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        type: selectedItem.type,
                        id: selectedItem.id
                    })
                });

                const data = await response.json();
                if (data.success) {
                    clipboard = {
                        type: 'cut',
                        item: data.item,
                        itemType: selectedItem.type
                    };
                    updatePasteButton();
                    alert('Item cut to clipboard');
                }
            } catch (error) {
                console.error('Error cutting item:', error);
                alert('Error cutting item');
            }
        }

        async function pasteItem() {
            if (!clipboard) return;
            document.getElementById('contextMenu').classList.add('hidden');

            try {
                const response = await fetch('/filemanager/paste', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        type: clipboard.itemType,
                        id: clipboard.item.id,
                        target_folder_id: currentFolderId,
                        operation: clipboard.type
                    })
                });

                const data = await response.json();
                if (data.success) {
                    loadFolder(currentFolderId);
                    if (clipboard.type === 'cut') {
                        clipboard = null;
                        updatePasteButton();
                    }
                    alert(data.message || 'Item pasted successfully');
                } else {
                    alert('Error pasting item: ' + (data.message || 'Unknown error'));
                }
            } catch (error) {
                console.error('Error pasting item:', error);
                alert('Error pasting item');
            }
        }

        async function viewItem() {
            if (!selectedItem) return;
            document.getElementById('contextMenu').classList.add('hidden');

            if (selectedItem.type === 'folder') {
                loadFolder(selectedItem.id);
                return;
            }

            // For files, show preview
            const modal = document.getElementById('viewModal');
            const modalTitle = document.getElementById('viewModalTitle');
            const modalContent = document.getElementById('viewModalContent');

            modalTitle.textContent = selectedItem.original_name || selectedItem.name;
            modalContent.innerHTML =
                '<div class="text-center py-8"><div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div><p class="mt-2 text-gray-600 dark:text-gray-400">Loading...</p></div>';
            modal.classList.remove('hidden');

            try {
                // Check if it's a Vimeo video
                if (selectedItem.is_vimeo && selectedItem.vimeo_id) {
                    // Fetch access token from backend
                    const embedResponse = await fetch(`/filemanager/vimeo-embed/${selectedItem.id}`);
                    const embedData = await embedResponse.json();

                    if (embedData.success) {
                        const videoId = embedData.video_id || selectedItem.vimeo_id;
                        const accessToken = embedData.access_token || embedData.embed_url?.split('access_token=')[1]
                            ?.split('&')[0];

                        // Create player container with unique ID
                        const playerId = 'vimeo-player-' + Date.now();
                        modalContent.innerHTML =
                            `<div id="${playerId}" style="width: 100%; height: 70vh; background: #000;"></div>`;

                        // Use iframe with access token - this is the most reliable method for "Hide from Vimeo"
                        const iframeUrl =
                            `https://player.vimeo.com/video/${videoId}${accessToken ? '?access_token=' + encodeURIComponent(accessToken) : ''}`;

                        // Create iframe element
                        const iframe = document.createElement('iframe');
                        iframe.src = iframeUrl;
                        iframe.className = 'w-full h-[70vh] border-0 rounded';
                        iframe.setAttribute('frameborder', '0');
                        iframe.setAttribute('allow', 'autoplay; fullscreen; picture-in-picture');
                        iframe.setAttribute('allowfullscreen', '');
                        iframe.style.width = '100%';
                        iframe.style.height = '70vh';
                        iframe.style.minHeight = '500px';

                        // Replace container with iframe    
                        
                        const container = document.getElementById(playerId);
                        if (container) {
                            container.parentNode.replaceChild(iframe, container);
                        } else {
                            modalContent.innerHTML = '';
                            modalContent.appendChild(iframe);
                        }
                    } else {
                        // Show error message
                        modalContent.innerHTML =
                            `<div class="text-center py-8"><p class="text-red-600 dark:text-red-400 mb-4">${embedData.message || 'Error loading video'}</p><p class="text-sm text-gray-600 dark:text-gray-400">Please check:</p><ul class="text-sm text-gray-600 dark:text-gray-400 mt-2 text-left max-w-md mx-auto"><li>1. Your VIMEO_ACCESS_TOKEN is set in .env</li><li>2. The token has "private" scope enabled</li><li>3. The video's embed settings allow embedding (set to "Anywhere")</li></ul></div>`;
                    }
                } else {
                    const fileUrl = `/filemanager/view/${selectedItem.id}`;
                    const mimeType = selectedItem.mime_type || '';

                    if (mimeType.startsWith('image/')) {
                        modalContent.innerHTML =
                            `<img src="${fileUrl}" alt="${selectedItem.original_name}" class="max-w-full h-auto mx-auto">`;
                    } else if (mimeType === 'application/pdf') {
                        modalContent.innerHTML = `<iframe src="${fileUrl}" class="w-full h-[70vh] border-0"></iframe>`;
                    } else if (mimeType.startsWith('text/')) {
                        const response = await fetch(fileUrl);
                        const text = await response.text();
                        modalContent.innerHTML =
                            `<pre class="bg-gray-100 dark:bg-gray-900 p-4 rounded overflow-auto max-h-[70vh] text-sm">${escapeHtml(text)}</pre>`;
                    } else {
                        modalContent.innerHTML =
                            `<div class="text-center py-8"><p class="text-gray-600 dark:text-gray-400 mb-4">Preview not available for this file type.</p><a href="/filemanager/download/${selectedItem.id}" class="inline-block px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700" download>Download File</a></div>`;
                    }
                }
            } catch (error) {
                console.error('Error viewing file:', error);
                modalContent.innerHTML = '<div class="text-center py-8 text-red-600">Error loading file preview</div>';
            }
        }
let vimeoPlayer = null;
function closeViewModal() {
    const modal = document.getElementById('viewModal');
    modal.classList.add('hidden');
    
    // Pause the Vimeo iframe
    const iframe = modal.querySelector('iframe[src*="vimeo.com"]');
    if (iframe) {
        try {
            // Post pause message to iframe
            iframe.contentWindow.postMessage('{"method":"pause"}', '*');
        } catch (error) {
            console.error('Error pausing iframe video:', error);
        }
    }
    
    // Also handle Vimeo Player API instance
    if (vimeoPlayer) {
        try {
            vimeoPlayer.pause().then(() => {
                console.log('Vimeo video paused');
            }).catch(error => {
                console.error('Error pausing Vimeo player:', error);
            });
        } catch (error) {
            console.error('Error accessing Vimeo player:', error);
        }
        vimeoPlayer = null;
    }
}
    //     function closeViewModal() {
    //         document.getElementById('viewModal').classList.add('hidden');
    //         if (vimeoPlayer) {
    //     try {
    //         vimeoPlayer.pause().then(() => {
    //             console.log('Vimeo video paused');
    //         }).catch(error => {
    //             console.error('Error pausing Vimeo player:', error);
    //         });
    //     } catch (error) {
    //         console.error('Error accessing Vimeo player:', error);
    //     }
    //     vimeoPlayer = null;
    // }
    //     }

        function downloadItem() {
            if (!selectedItem || selectedItem.type !== 'file') return;
            document.getElementById('contextMenu').classList.add('hidden');

            // Open download in new tab
            window.open(`/filemanager/download/${selectedItem.id}`, '_blank');
        }

        async function showProperties() {
            if (!selectedItem) return;
            document.getElementById('contextMenu').classList.add('hidden');

            const modal = document.getElementById('propertiesModal');
            const modalContent = document.getElementById('propertiesModalContent');

            modalContent.innerHTML =
                '<div class="text-center py-8"><div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div><p class="mt-2 text-gray-600 dark:text-gray-400">Loading...</p></div>';
            modal.classList.remove('hidden');

            try {
                const response = await fetch(`/filemanager/properties?type=${selectedItem.type}&id=${selectedItem.id}`);
                const data = await response.json();

                if (data.success) {
                    const props = data.properties;
                    let html = '<div class="space-y-4">';

                    for (const [key, value] of Object.entries(props)) {
                        const label = key.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
                        html += `
                            <div class="flex border-b border-gray-200 dark:border-gray-700 pb-2">
                                <div class="w-1/3 font-medium text-gray-700 dark:text-gray-300">${label}:</div>
                                <div class="w-2/3 text-gray-900 dark:text-white">${value || 'N/A'}</div>
                            </div>
                        `;
                    }

                    html += '</div>';
                    modalContent.innerHTML = html;
                } else {
                    modalContent.innerHTML =
                    '<div class="text-center py-8 text-red-600">Error loading properties</div>';
                }
            } catch (error) {
                console.error('Error loading properties:', error);
                modalContent.innerHTML = '<div class="text-center py-8 text-red-600">Error loading properties</div>';
            }
        }

        function closePropertiesModal() {
            document.getElementById('propertiesModal').classList.add('hidden');
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        async function renameItem() {
            if (!selectedItem) return;

            const newName = prompt('Enter new name:', selectedItem.name || selectedItem.original_name);
            if (!newName) return;

            try {
                const response = await fetch('/filemanager/rename', {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        type: selectedItem.type,
                        id: selectedItem.id,
                        name: newName
                    })
                });

                const data = await response.json();
                if (data.success) {
                    loadFolder(currentFolderId);
                    document.getElementById('contextMenu').classList.add('hidden');
                } else {
                    alert('Error renaming item');
                }
            } catch (error) {
                console.error('Error renaming item:', error);
                alert('Error renaming item');
            }
        }

        async function deleteItem() {
            if (!selectedItem) return;

            let confirmMessage = '';
            if (selectedItem.type === 'folder') {
                confirmMessage =
                    `Are you sure you want to delete the folder "${selectedItem.name}"?\n\nThis will permanently delete:\n- The folder itself\n- All files inside the folder (including images)\n- All subfolders and their contents\n\nThis action cannot be undone!`;
            } else {
                confirmMessage =
                    `Are you sure you want to delete "${selectedItem.name || selectedItem.original_name}"?\n\nThis action cannot be undone!`;
            }

            if (!confirm(confirmMessage)) {
                return;
            }

            try {
                const endpoint = selectedItem.type === 'folder' ?
                    `/filemanager/folder/${selectedItem.id}` :
                    `/filemanager/file/${selectedItem.id}`;

                const response = await fetch(endpoint, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                const data = await response.json();
                if (data.success) {
                    loadFolder(currentFolderId);
                    document.getElementById('contextMenu').classList.add('hidden');
                    alert(data.message || 'Item deleted successfully');
                } else {
                    alert('Error deleting item: ' + (data.message || 'Unknown error'));
                }
            } catch (error) {
                console.error('Error deleting item:', error);
                alert('Error deleting item');
            }
        }

        // Direct delete function for delete buttons
        async function deleteItemById(id, type, name) {
            event.stopPropagation();

            let confirmMessage = '';
            if (type === 'folder') {
                confirmMessage =
                    `Are you sure you want to delete the folder "${name}"?\n\nThis will permanently delete:\n- The folder itself\n- All files inside the folder\n- All subfolders and their contents\n\nThis action cannot be undone!`;
            } else {
                confirmMessage = `Are you sure you want to delete "${name}"?\n\nThis action cannot be undone!`;
            }

            if (!confirm(confirmMessage)) {
                return;
            }

            try {
                const endpoint = type === 'folder' ?
                    `/filemanager/folder/${id}` :
                    `/filemanager/file/${id}`;

                const response = await fetch(endpoint, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                const data = await response.json();
                if (data.success) {
                    loadFolder(currentFolderId);
                    alert(data.message || 'Item deleted successfully');
                } else {
                    alert('Error deleting item: ' + (data.message || 'Unknown error'));
                }
            } catch (error) {
                console.error('Error deleting item:', error);
                alert('Error deleting item');
            }
        }
    </script>
@endpush
@endcan