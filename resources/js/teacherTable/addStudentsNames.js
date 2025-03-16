import { selectedCells, CellManager } from "@js/merging.js";


const cellManager = new CellManager();

document.addEventListener('DOMContentLoaded', function() {
    if( !document.getElementById('pushNames')) return;

    document.getElementById('pushNames').addEventListener('click', selectCell);
});

function selectCell() {
    if (selectedCells.length === 0) return;

    const startCell = selectedCells[0];
    const parentTable = startCell.closest('table');
    const tableId = parentTable.id;

    const table = document.querySelector(`#${tableId}`);
    const roomID = table.getAttribute('data-room-id');

    const startRow = parseInt(startCell.getAttribute('data-row'));
    const startColumn = startCell.getAttribute('data-column');
    // const tableId = document.getElementById('main-table').getAttribute('data-table');

    const removeContainer = document.getElementById('removeContainer');
    const studentContainer = document.getElementById('studentContainer');


    // Prepare the data to send to the backend
    const dataToSend = {
        table_id: tableId,
        room_id: roomID,
        start_row: startRow,
        start_column: cellManager.columnNameToIndex(startColumn)
    };

    console.log(dataToSend);

    fetch('/push-names', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(dataToSend),
    })
    .then(response => response.json())
    .then(data => {

        console.log('Data Received:', data);

        const names = data.names;

        console.log(names);

        let currentRow = startRow;
        let currentNumber = 1;

        names.forEach((student) => {
            const rowCells = document.querySelectorAll(`td[data-row="${currentRow}"]`);
        
            rowCells.forEach((cell) => {
                cell.setAttribute("data-operation", "operation");  // Set data-operation attribute
                cell.removeAttribute("data-sum");  // Remove data-sum attribute
            });
        
            const firstCell = document.querySelector(`td[data-row="${currentRow}"][data-column="A"]`);
            if (firstCell) {
                firstCell.dataset.roomStudent = student.name_3;
            } else {
                console.warn(`First column cell not found at row ${currentRow}`);
            }
        
            const numberCell = document.querySelector(`td[data-row="${currentRow}"][data-column="${startColumn}"]`);
            if (numberCell) {
                numberCell.textContent = currentNumber;
                numberCell.classList.add('text-center');
            } else {
                console.warn(`Number cell not found at row ${currentRow}, column ${startColumn}`);
            }
        
            let newCurrentCol = cellManager.columnNameToIndex(startColumn);
            newCurrentCol++;
        
            const cell2 = document.querySelector(`td[data-row="${currentRow}"][data-column="${cellManager.indexToColumnName(newCurrentCol)}"]`);
            if (cell2) {
                cell2.textContent = student.name_2;
                cell2.classList.add('text-start');
            } else {
                console.warn(`Cell2 not found at row ${currentRow}, column ${newCurrentCol}`);
            }
        
            newCurrentCol++;
        
            const cell3 = document.querySelector(`td[data-row="${currentRow}"][data-column="${cellManager.indexToColumnName(newCurrentCol)}"]`);
            if (cell3) {
                cell3.textContent = student.name_3;
                cell3.classList.add('text-start');
            } else {
                console.warn(`Cell3 not found at row ${currentRow}, column ${newCurrentCol}`);
            }
        
            currentRow++;
            currentNumber++;
        });
        

        updateUI(startRow, removeContainer, studentContainer);  

    })
    .catch(error => {
        console.error('Error saving data:', error);
    });
}

// UPDATING BUTTON OF STUDENT AND REMOVE
function updateUI(startRow, removeContainer, studentContainer) {
    if (startRow > 0) {
        // Show the remove container, hide the student container
        removeContainer.classList.remove('hidden');
        studentContainer.classList.add('hidden');
    }
}
















































// import { CellManager, selectedCells } from "@js/merging.js";


// const newCellManager = new CellManager();

// document.addEventListener('DOMContentLoaded', function() {
//     document.getElementById('pushNames').addEventListener('click', selectCell);
// });

// function selectCell() {
//     if (selectedCells.length === 0) return;

//     // Get the first selected cell's row and column as the starting point
//     const startCell = selectedCells[0];
//     const startRow = parseInt(startCell.getAttribute('data-row'));
//     const startColumn = startCell.getAttribute('data-column');

//     console.log(startCell)
//     console.log(startRow, startColumn)

    // Fetch student names from the server
    // fetch('/push-names', {
    //     method: 'GET',
    //     headers: {
    //         'Content-Type': 'application/json',
    //         'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    //     }
    // })
    // .then(response => response.json())
    // .then(studentNames => {
    //     let currentRow = startRow;

    //     studentNames.forEach((name) => {
    //         // Calculate the cell position for each student name
    //         const cell = document.querySelector(`td[data-row="${currentRow}"][data-column="${startColumn}"]`);

    //         if (cell) {
    //             cell.textContent = name;

    //             // Move to the next row
    //             currentRow++;
    //         }
    //     });

    // })
    // .catch(error => {
    //     console.error('Error fetching student names:', error);
    //     alert('An error occurred while retrieving student names. Please try again.');
    // });
// }
