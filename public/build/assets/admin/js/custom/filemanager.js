// public/js/file-manager.js
document.addEventListener('DOMContentLoaded', function() {
    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Delete key
        if (e.key === 'Delete' && window.Livewire.find('file-manager').selectedItem) {
            if (confirm('Are you sure you want to delete this item?')) {
                window.Livewire.find('file-manager').deleteItem();
            }
        }
        
        // F2 for rename
        if (e.key === 'F2' && window.Livewire.find('file-manager').selectedItem) {
            window.Livewire.find('file-manager').$set('showRenameModal', true);
        }
        
        // Ctrl+N for new folder
        if (e.ctrlKey && e.key === 'n') {
            e.preventDefault();
            window.Livewire.find('file-manager').$set('showNewFolderModal', true);
        }
    });

    // Drag and drop support
    const dropZone = document.querySelector('.main-content');
    
    dropZone.addEventListener('dragover', function(e) {
        e.preventDefault();
        dropZone.classList.add('drag-over');
    });
    
    dropZone.addEventListener('dragleave', function() {
        dropZone.classList.remove('drag-over');
    });
    
    dropZone.addEventListener('drop', function(e) {
        e.preventDefault();
        dropZone.classList.remove('drag-over');
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            // Handle file upload via Livewire
            // You'll need to implement this based on your needs
        }
    });
});