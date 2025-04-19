document.addEventListener('DOMContentLoaded', function() {
    setupDeleteConfirmation();
    setupMoveConfirmation();
});

function setupMoveConfirmation() {
    const moveModal = document.getElementById('moveConfirmationModal');
    const moveForm = document.getElementById('moveForm');
    const moveItemIdInput = document.getElementById('moveItemId');
    const moveItemTypeInput = document.getElementById('moveItemType');
    const folderSelect = document.getElementById('folderSelect');
    const cancelMoveBtn = document.getElementById('cancelMoveBtn');
    const confirmMoveBtn = document.getElementById('confirmMoveBtn');
    
    const routeData = document.getElementById('route-data');
    // Get all move buttons
    const moveButtons = document.querySelectorAll('.move-item-btn');
    
    // Add click event to all move buttons
    moveButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Get the item ID and type from data attributes
            const itemId = this.getAttribute('data-id');
            const itemType = this.getAttribute('data-type');

            // Set values in the form
            moveItemIdInput.value = itemId;
            moveItemTypeInput.value = itemType;
            
            // Set the form action based on the item type
            if (itemType === 'room') {
                moveForm.action = routeData.getAttribute('data-teacher-room-move');
            } 
            
            // Show the modal
            moveModal.classList.remove('hidden');
        });
    });
    
    // Hide modal when clicking cancel
    cancelMoveBtn.addEventListener('click', function() {
        moveModal.classList.add('hidden');
        folderSelect.selectedIndex = 0;
    });
    
    // Handle the move confirmation
    confirmMoveBtn.addEventListener('click', function() {
        if (folderSelect.value) {
            moveForm.submit();
        }
    });
    
    // Hide modal when clicking outside
    moveModal.addEventListener('click', function(e) {
        if (e.target.classList.contains('modal-overlay')) {
            moveModal.classList.add('hidden');
            folderSelect.selectedIndex = 0;
        }
    });
}

function setupDeleteConfirmation() {
    const modal = document.getElementById('deleteConfirmationModal');
    const overlay = document.querySelector('.modal-overlay');
    const cancelBtn = document.getElementById('cancelDeleteBtn');
    const confirmBtn = document.getElementById('confirmDeleteBtn');
    let currentDeleteForm = null;

    // Use event delegation to catch all delete form submissions
    document.addEventListener('submit', function(e) {
        const form = e.target.closest('.delete-confirm-form');
        if (form) {
            e.preventDefault();
            currentDeleteForm = form;
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }
    });

    function closeModal() {
        modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
        currentDeleteForm = null;
    }

    cancelBtn.addEventListener('click', closeModal);
    overlay.addEventListener('click', closeModal);

    confirmBtn.addEventListener('click', function() {
        if (currentDeleteForm) {
            currentDeleteForm.removeEventListener('submit', preventDefault);
            currentDeleteForm.submit();
        }
        closeModal();
    });

    // Handle ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
            closeModal();
        }
    });

}

function preventDefault(e) {
    e.preventDefault();
}