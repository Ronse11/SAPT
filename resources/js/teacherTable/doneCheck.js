document.addEventListener('DOMContentLoaded', () => {
    const tableId = document.querySelector('.main-table').id;
    const table = document.getElementById(`${tableId}`);
    const colHeaders = table.querySelectorAll('.colHeader');
    const roomId = table.getAttribute('data-room-id');
    
    colHeaders.forEach((col) => {
        col.addEventListener('click', async (event) => {
            const currentCol = col.textContent.trim();

            let data = {
                tableId: tableId,
                column: currentCol
            };

            await saveResultToDatabase(data, col);
        });
    });
    
    async function saveResultToDatabase(dataPerform, col) {
        try {
            const response = await fetch(`/saved-done-check/${roomId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: JSON.stringify({dataPerform}),
            });
    
            const result = await response.json();  
    
            if (!response.ok) {
                throw new Error(result.message || "Failed to save data");
            }

            col.classList.toggle('bg-sgcolorSub');
            col.classList.toggle('bg-green-200');

        } catch (error) {
            console.error("Error saving data:", error);
        }
    }
    
});
