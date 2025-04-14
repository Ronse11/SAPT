document.addEventListener('DOMContentLoaded', () => {
    const nameInput = document.getElementById('changeName');
    let oldName = null;
    nameInput.addEventListener('focus', (event) => {
        oldName = event.target.value.trim();
    });
    
    
    nameInput.addEventListener('blur', async(event) => {
        const newName = event.target.value.trim();

        if(oldName == newName) return;

        try {
            const response = await fetch('/new-name', {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    "X-Requested-With": "XMLHttpRequest"
                },
                body: JSON.stringify({changeName: newName})
            });

            if (!response.ok) {
                const errorText = await response.text();
                const errorData = JSON.parse(errorText);
                const errorMsg = errorData.errors?.changeName?.[0] || "An error occurred.";
                
                const errorCard = document.getElementById('error-changeName');
                const msgShow = document.querySelector('.errShow');
                errorCard.classList.remove('opacity-0', 'translate-y-[-50px]');
                msgShow.textContent = errorMsg;
                errorCard.classList.add('opacity-100', 'translate-y-0');
                errorCard.classList.remove('pointer-events-none');
                errorCard.classList.add('pointer-events-auto');

                setTimeout(() => {
                    errorCard.classList.remove('opacity-100', 'translate-y-0');
                    errorCard.classList.add('opacity-0', 'translate-y-[-50px]');
                    errorCard.classList.remove('pointer-events-auto');
                    errorCard.classList.add('pointer-events-none');
                }, 2000);

            } else {
                const data = await response.json();
                const successMsg = data.message;

                const successCard = document.getElementById('success-changeName');
                const msgShow = document.querySelector('.succShow');
                successCard.classList.remove('opacity-0', 'translate-y-[-50px]');
                msgShow.textContent = successMsg;
                successCard.classList.add('opacity-100', 'translate-y-0');
                successCard.classList.remove('pointer-events-none');
                successCard.classList.add('pointer-events-auto');

                setTimeout(() => {
                    successCard.classList.remove('opacity-100', 'translate-y-0');
                    successCard.classList.add('opacity-0', 'translate-y-[-50px]');
                    successCard.classList.remove('pointer-events-auto');
                    successCard.classList.add('pointer-events-none');
                }, 2000);
            }

        } catch (error) {
            alert(error.message);
        }
        
    });
});