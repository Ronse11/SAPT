document.addEventListener('DOMContentLoaded', () => {

    const impt = document.querySelector('.import');
    const showImpt = document.querySelector('.showImport');
    const cancelImport = document.querySelector('.cancelImport');
    
    impt.addEventListener('click', () => {
        showImpt.classList.toggle('hidden');
    });
    
    cancelImport.addEventListener('click', () => {
        showImpt.classList.toggle('hidden');
    });


    const importForm = document.getElementById('importHighlightedForm');
    const loadingScreen = document.getElementById('loadingScreen');

    if (importForm) {
        importForm.addEventListener('submit', () => {
            loadingScreen.classList.remove('hidden'); 
        });
    }

    // Hide loading screen if Laravel session returns a success or error message
    const successMessage = document.getElementById('success-message');
    const errorMessage = document.getElementById('error-message');

    if (successMessage || errorMessage) {
        loadingScreen.classList.add('hidden');
    }

});