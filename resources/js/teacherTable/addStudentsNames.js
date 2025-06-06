import { selectedCells, CellManager } from "@js/merging.js";


const cellManager = new CellManager();

const applied = document.getElementById('applied');
const showMessage = document.querySelector('.message');

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

        let currentRow = startRow;
        let currentNumber = 1;

        const allName2Null = names.every(student => !student.name_2);

        names.forEach((student) => {
            const rowCells = document.querySelectorAll(`td[data-row="${currentRow}"]`);
            
            rowCells.forEach((cell) => {
                cell.setAttribute("data-operation", "operation");
                cell.removeAttribute("data-sum");
            });
            
            const firstCell = document.querySelector(`td[data-row="${currentRow}"][data-column="A"]`);
            if (firstCell) {
                firstCell.dataset.roomStudent = student.name_3;
            }
            
            const numberCell = document.querySelector(`td[data-row="${currentRow}"][data-column="${startColumn}"]`);
            if (numberCell) {
                numberCell.textContent = student.name_1 && student.name_1 !== '' ? student.name_1 : currentNumber;
                numberCell.classList.add('text-center');
            }
            
            let newCurrentCol = cellManager.columnNameToIndex(startColumn);
            newCurrentCol++;
            
            const cell2 = document.querySelector(`td[data-row="${currentRow}"][data-column="${cellManager.indexToColumnName(newCurrentCol)}"]`);
            if (cell2) {
                cell2.textContent = allName2Null ? student.name_3 : student.name_2;
                cell2.classList.add('text-start');
            }
            
            newCurrentCol++;
            const cell3 = document.querySelector(`td[data-row="${currentRow}"][data-column="${cellManager.indexToColumnName(newCurrentCol)}"]`);
            if (cell3) {
                cell3.textContent = allName2Null ? '' : student.name_3;
                if(allName2Null) {
                    cell3.classList.add('text-center');
                } else {
                    cell3.classList.add('text-start');
                }
            }
            
            currentRow++;
            currentNumber++;
        });
        

        updateUI(startRow, removeContainer, studentContainer);  

        floatMessage('Names loaded successfully.')
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
