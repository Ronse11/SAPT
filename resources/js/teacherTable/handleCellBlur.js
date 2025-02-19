
import { isFormulaActiveState } from "./calculation.js";

export function handleCellBlur(event, tableId, initialContent) {

    if(isFormulaActiveState()) {
        return;
    }

    let cell = event.target;
    const table = document.querySelector(`#${tableId}`);
    // Remove the border when the cell loses focus
    cell.classList.remove('selected2');
    let id = cell.getAttribute('data-id');
    let row = cell.getAttribute('data-row');
    let column = cell.getAttribute('data-column');
    let content = cell.textContent.trim();
    let room_id = table.getAttribute('data-room-id');
    let student_name = table.querySelector(`td[data-row="${row}"][data-column="A"]`).dataset.roomStudent;
    let rowspan = cell.getAttribute('rowspan');
    let colspan = cell.getAttribute('colspan');
    let merged = colspan > 1;
    let dataFormula = cell.getAttribute('data-formula');


    // Debounce to prevent multiple rapid-fire requests
    clearTimeout(cell.blurTimeout);
    cell.blurTimeout = setTimeout(() => {
        // Deletion logic: if content is empty and there is an ID
        if (content.length === 0 && id && !dataFormula) {
            let url = `/delete-skills/${id}`;

            fetch(url, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ tableId: tableId }) // Include table ID in the payload for delete
            })
            .then(response => response.json())
            .then(data => {
                // if (data.mesage) {
                    cell.removeAttribute('data-id');
                    cell.textContent = ''; // Clear the cell content
                    console.log('deleted')
                // }
            })
            .catch(error => console.error('Error:', error));

            return; // Exit function after deletion to prevent further processing
        }

        // Prevent further processing if content contains '=' or hasn't changed
        if (content.includes('=') || content === initialContent || content === '') {
            return;
        }

        // Set up the payload for update or create
        let payload = {
            _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            content: content,
            room_id: room_id,
            // room_studentID: room_studentID,
            student_name: student_name,
            merged: merged,
            rowspan: rowspan,
            colspan: colspan,
            tableId: tableId // Verify if this.tableId is available in context
        };

        if (!id) {
            payload.row = row;
            payload.column = column;
        }

        // Set the correct URL depending on the presence of an ID
        let url = id ? `/update-content-cell/${id}` : '/create-skills';

        // Send the POST request
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(payload)
        })
        .then(response => response.json())
        .then(data => {
            // If there's no ID, update the cell with the returned ID from the server
            if (!id && data.id) {
                cell.setAttribute('data-id', data.id);
            }
        })
        .catch(error => {
            console.error("Error:", error);
        });
    }, 300); // 300ms delay for debouncing
}
