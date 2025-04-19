
import { isFormulaActiveState } from "./calculation.js";
// import { isFormulaActiveStateRating } from "./ratingTable.js";

const applied = document.getElementById('appliedError');
const showMessage = document.querySelector('.messageError');

export function handleCellBlur(event, tableId, initialContent) {

    if(isFormulaActiveState()) {
        return;
    }

    // if(isFormulaActiveStateRating()) {
    //     return;
    // }

    let cell = event.target;
    let student_name = null;
    let rowOfTotalScore = null;
    let totalScore = null;
    let maxRowStudent = 0;
    const table = document.querySelector(`#${tableId}`);
    if (table.id === 'main-table') {
        rowOfTotalScore = document.getElementById('rowOfTotalScore').getAttribute('value');
    }
    // Remove the border when the cell loses focus
    cell.classList.remove('selected2');
    let id = cell.getAttribute('data-id');
    let row = parseInt(cell.getAttribute('data-row'));
    let column = cell.getAttribute('data-column');
    let content = cell.textContent.trim();
    if (table.id == 'rating-table') {
        student_name = table.querySelector(`td[data-row="${row}"][data-column="#1"]`).dataset.roomStudent;

    } else {
        student_name = table.querySelector(`td[data-row="${row}"][data-column="A"]`).dataset.roomStudent;

    }
    let room_id = table.getAttribute('data-room-id');
    let rowspan = cell.getAttribute('rowspan');
    let colspan = cell.getAttribute('colspan');
    let merged = colspan > 1;
    let dataFormula = cell.getAttribute('data-formula');
    
    cell.setAttribute('data-original', content);

    console.log(rowOfTotalScore);
    
    if (rowOfTotalScore !== '' && table.id === 'main-table') {
        totalScore = table.querySelector(`td[data-row="${parseInt(rowOfTotalScore)}"][data-column="${column}"]`).textContent.trim();
        maxRowStudent = parseInt(totalNumberOfStudents) + rowOfTotalScore;
    }


    clearTimeout(cell.blurTimeout);
    cell.blurTimeout = setTimeout(() => {
        // Check if the Content is Greter than Total Score
        if(totalScore && row > rowOfTotalScore && row <= maxRowStudent && table.id === 'main-table') {
            
            if(isNaN(content)) {
                floatMessage('Please enter a valid number!');
                cell.textContent = '';
                return;
            }
            
            if(parseFloat(content) > parseFloat(totalScore)) {
                floatMessage('Input Score is Higher than Total Score!');
                cell.textContent = '';
                return;
            }
        }

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
        if (content.includes('=') || content === initialContent || content === '' || cell.hasAttribute("data-p")) {
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
            if (!id && data.id) {
                cell.setAttribute('data-id', data.id);
            }
        })
        .catch(error => {
            floatMessage('Error saving cell content!');
        });
    }, 500);
}


function floatMessage(msg) {
    showMessage.textContent = msg;
    applied.classList.remove('opacity-0', '-translate-x-[-15rem]');
    applied.classList.add('opacity-100', 'translate-y-0');
    applied.classList.remove('pointer-events-none');
    applied.classList.add('pointer-events-auto');
    setTimeout(() => {
        applied.classList.remove('opacity-100', 'translate-y-0');
        applied.classList.add('opacity-0', '-translate-x-[-15rem]');
        applied.classList.remove('pointer-events-auto');
        applied.classList.add('pointer-events-none');
    }, 2000);
}