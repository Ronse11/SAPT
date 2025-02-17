// CLICKING THREE DOT
document.addEventListener('DOMContentLoaded', function() {
    let currentVisibleMenu = null;

    document.querySelectorAll('.three-dot').forEach(button => {
        button.addEventListener('click', function(event) {
            event.stopPropagation();

            const roomDiv = this.closest('.class-item');
            const delButton = roomDiv.querySelector('.delButton');

            if (currentVisibleMenu && currentVisibleMenu !== delButton) {
                currentVisibleMenu.classList.add('hidden');
            }

            if (delButton) {
                delButton.classList.toggle('hidden');
                if (!delButton.classList.contains('hidden')) {
                    currentVisibleMenu = delButton;
                } else {
                    currentVisibleMenu = null;
                }
            }
        });
    });

    document.addEventListener('click', function() {
        if (currentVisibleMenu) {
            currentVisibleMenu.classList.add('hidden');
            currentVisibleMenu = null;
        }
    });
});