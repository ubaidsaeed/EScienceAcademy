<div class="container mx-auto p-6 max-w-4xl">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
        <h2 class="text-2xl font-bold mb-6 text-gray-800 dark:text-white">Large File Uploader</h2>

        <!-- Upload Form -->
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Select File (up to 6GB)
            </label>
            <input 
                type="file" 
                wire:model="file"
                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-blue-900 dark:file:text-blue-300"
                {{ $isUploading ? 'disabled' : '' }}
            >
            @error('file')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <!-- Upload Progress -->
        @if ($isUploading || $uploadStatus !== 'idle')
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 mb-6">
                <!-- File Info -->
                <div class="flex justify-between items-center mb-4">
                    <div>
                        <h3 class="font-semibold text-gray-800 dark:text-white">{{ $fileName }}</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            {{ number_format($fileSize / 1024 / 1024, 2) }} MB
                        </p>
                    </div>
                    <div class="text-right">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                            {{ $uploadStatus === 'completed' ? 'bg-green-100 text-green-800' : 
                               ($uploadStatus === 'failed' ? 'bg-red-100 text-red-800' : 
                               'bg-blue-100 text-blue-800') }}">
                            {{ ucfirst($uploadStatus) }}
                        </span>
                    </div>
                </div>

                <!-- Progress Bar -->
                <div class="mb-4">
                    <div class="flex justify-between text-sm text-gray-600 dark:text-gray-400 mb-2">
                        <span>Progress: {{ number_format($uploadProgress, 1) }}%</span>
                        <span>Chunk: {{ $uploadedChunks }}/{{ $totalChunks }}</span>
                    </div>
                    <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-3">
                        <div 
                            class="bg-blue-600 h-3 rounded-full transition-all duration-300 ease-in-out"
                            style="width: {{ $uploadProgress }}%"
                        ></div>
                    </div>
                </div>

                <!-- Upload Stats -->
                <div class="grid grid-cols-3 gap-4 text-sm">
                    <div class="text-center">
                        <p class="text-gray-500 dark:text-gray-400">Speed</p>
                        <p class="font-semibold text-gray-800 dark:text-white">
                            {{ $uploadSpeed > 0 ? formatBytes($uploadSpeed) . '/s' : 'Calculating...' }}
                        </p>
                    </div>
                    <div class="text-center">
                        <p class="text-gray-500 dark:text-gray-400">Time Remaining</p>
                        <p class="font-semibold text-gray-800 dark:text-white">
                            {{ $timeRemaining > 0 ? formatTime($timeRemaining) : 'Calculating...' }}
                        </p>
                    </div>
                    <div class="text-center">
                        <p class="text-gray-500 dark:text-gray-400">Status</p>
                        <p class="font-semibold text-gray-800 dark:text-white capitalize">
                            {{ $uploadStatus }}
                        </p>
                    </div>
                </div>

                <!-- Cancel Button -->
                @if ($isUploading)
                    <div class="mt-4 text-center">
                        <button 
                            wire:click="cancelUpload"
                            wire:confirm="Are you sure you want to cancel the upload?"
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors"
                        >
                            Cancel Upload
                        </button>
                    </div>
                @endif
            </div>
        @endif

        <!-- Error Message -->
        @if ($errorMessage)
            <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-red-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-red-800 dark:text-red-200 font-medium">Error:</span>
                </div>
                <p class="mt-1 text-red-700 dark:text-red-300 text-sm">{{ $errorMessage }}</p>
            </div>
        @endif

        <!-- Success Message -->
        @if (session()->has('message'))
            <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-green-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-green-800 dark:text-green-200 font-medium">Success!</span>
                </div>
                <p class="mt-1 text-green-700 dark:text-green-300 text-sm">{{ session('message') }}</p>
            </div>
        @endif
    </div>
</div>

@script
<script>
    Livewire.on('uploadProgressUpdated', (data) => {
        // Progress updates are handled automatically by Livewire
        console.log('Upload progress:', data);
    });

    Livewire.on('uploadCompleted', (uploadId) => {
        // Optional: Add any completion handling here
        console.log('Upload completed:', uploadId);
    });

    Livewire.on('uploadFailed', (error) => {
        console.error('Upload failed:', error);
    });
</script>
@endscript