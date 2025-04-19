import { CellManager, selectedCells } from "@js/merging.js";


const newCellManager = new CellManager();

const applied = document.getElementById('applied');
const showMessage = document.querySelector('.message');

document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('apply-borders').addEventListener('click', applyBorders);
    document.getElementById('apply-border-btn').addEventListener('click', applyBorderToAllCells);
    // document.getElementById('delete-borders').addEventListener('click', deleteBorders);
    document.getElementById('delete-borders-all').addEventListener('click', deleteBordersAllCells);

    document.getElementById('applyTopBorder').addEventListener('click', function() {
        applyTopRightBotLeftBorder('Top');
    });
    document.getElementById('applyRightBorder').addEventListener('click', function() {
        applyTopRightBotLeftBorder('Right');
    });
    document.getElementById('applyBotBorder').addEventListener('click', function() {
        applyTopRightBotLeftBorder('Bottom');
    });
    document.getElementById('applyLeftBorder').addEventListener('click', function() {
        applyTopRightBotLeftBorder('Left');
    });

    const caretButton = document.getElementById('caretButton');
    const dropdownMenu = document.getElementById('dropdownMenu');
    // const dropdownLabelButton = document.getElementById('apply-border-btn');
    const dropdownOptions = document.querySelectorAll('.dropdownOption');

    caretButton.addEventListener('click', () => {
        dropdownMenu.classList.toggle('hidden');
    });

    document.addEventListener('click', (e) => {
        if (!caretButton.contains(e.target)) {
        dropdownMenu.classList.add('hidden');
        }
    });

    dropdownOptions.forEach(option => {
        option.addEventListener('click', (e) => {
        // const selectedOption = e.target.getAttribute('data-value');
        // dropdownLabelButton.textContent = selectedOption;
        dropdownMenu.classList.add('hidden'); // Close the dropdown after selection
        });
    });
});



    
function applyBorders() {

    if (selectedCells.length === 0) return; // Ensure that there are selected cells

    const borderData = [];
    const rows = new Set();
    const cols = new Set();
    
    const cellAttr = document.getElementById('cell');
    // const teacherID = cellAttr.getAttribute('data-teacher-id');
    
    const parentTable = cellAttr.closest('table'); // Find the nearest table ancestor
    const tableID = parentTable.id;

    const table = document.querySelector(`#${tableID}`);
    const roomID = table.getAttribute('data-room-id');
    const teacherID = table.getAttribute('data-teacher-id');


    // Collect all the rows and columns covered by the selected cells
    for (const cell of selectedCells) {
        const row = parseInt(cell.dataset.row);
        const col = newCellManager.columnNameToIndex(cell.dataset.column);
        const rowspan = parseInt(cell.getAttribute('rowspan')) || 1;
        const colspan = parseInt(cell.getAttribute('colspan')) || 1;

        for (let r = row; r < row + rowspan; r++) {
            rows.add(r);
        }
        for (let c = col; c < col + colspan; c++) {
            cols.add(c);
        }
    }

    // Determine the boundaries of the selection
    const minRow = Math.min(...rows);
    const maxRow = Math.max(...rows);
    const minCol = Math.min(...cols);
    const maxCol = Math.max(...cols);

    // Loop through selected cells and apply black borders only to outer cells
    for (const cell of selectedCells) {
        const row = parseInt(cell.dataset.row);
        const col = newCellManager.columnNameToIndex(cell.dataset.column);
        const rowspan = parseInt(cell.getAttribute('rowspan')) || 1;
        const colspan = parseInt(cell.getAttribute('colspan')) || 1;

        const isTop = row === minRow;
        const isBottom = row + rowspan - 1 === maxRow;
        const isLeft = col === minCol;
        const isRight = col + colspan - 1 === maxCol;

        // Apply black borders
        if (isTop) cell.classList.remove('border-t', 'border-t-cursor');
        if (isBottom) cell.classList.remove('border-b', 'border-b-cursor');
        if (isLeft) cell.classList.remove('border-l', 'border-l-cursor');
        if (isRight) cell.classList.remove('border-r', 'border-r-cursor');

        // Apply Tailwind CSS border classes based on position
        if (isTop) cell.classList.add('border-t-2', 'border-t-black');
        if (isBottom) cell.classList.add('border-b-2', 'border-b-black');
        if (isLeft) cell.classList.add('border-l-2', 'border-l-black');
        if (isRight) cell.classList.add('border-r-2', 'border-r-black');

        // Only store the border data if the cell is an outer cell
        if (isTop || isBottom || isLeft || isRight) {
            const column = newCellManager.indexToColumnName(col);

            // Store the border data
            borderData.push({
                // applyBorderCells: "bordersCorner",
                table_id: tableID,
                teacher_id: teacherID,
                room_id: roomID,
                row: row,
                column: column,
                isTop: isTop,
                isBottom: isBottom,
                isLeft: isLeft,
                isRight: isRight
            });
        }
    }

    // Send the border data to the server via AJAX
    fetch('/save-borders', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ borders: borderData }),
    })
    .then(response => response.json())
    .then(data => {
        const msg = 'Borders Successfully Saved!';
        floatMessage(msg);
    })
    .catch(error => {
        console.error('Error saving borders:', error);
    });
};


// APPLYING BORDER TO ALL CELLS
// let borderedCells = [];

// Function to apply borders to selected cells
function applyBorderToAllCells() {

    if (selectedCells.length === 0) return;
    
    const borderData = [];
    const cellAttr = document.getElementById('cell');
    // const teacherID = cellAttr.getAttribute('data-teacher-id');

    const parentTable = cellAttr.closest('table'); 
    const tableID = parentTable.id;
    const table = document.querySelector(`#${tableID}`);
    const roomID = table.getAttribute('data-room-id');
    const teacherID = table.getAttribute('data-teacher-id');

    selectedCells.forEach(cell => {
        
        // Apply borders to all sides of the cell
        cell.classList.remove( 'border-t', 'border-b', 'border-l', 'border-r', 'border-t-cursor', 'border-b-cursor', 'border-l-cursor', 'border-r-cursor',);
        cell.classList.add( 'border-t-2', 'border-b-2', 'border-l-2', 'border-r-2', 'border-t-black', 'border-b-black', 'border-l-black', 'border-r-black',);

        // Add the cell to the list of bordered cells
        // if (!borderedCells.includes(cell)) {
        //     borderedCells.push(cell);
        // }

        const row = parseInt(cell.dataset.row);
        const col = newCellManager.columnNameToIndex(cell.dataset.column);
        const column = newCellManager.indexToColumnName(col);

        // Store the border data for all sides of the cell
        borderData.push({
            // applyBorderCells: "bordersAllCell",
            table_id: tableID,
            teacher_id: teacherID,
            room_id: roomID,
            row: row,
            column: column,
            isTop: true,
            isBottom: true,
            isLeft: true,
            isRight: true
        });
    });

    // Send the border data to the server via AJAX
    fetch('/save-borders', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ borders: borderData }),
    })
    .then(response => response.json())
    .then(data => {
        const msg = 'Borders Successfully Saved!';
        floatMessage(msg);
    })
    .catch(error => {
        console.error('Error saving borders:', error);
    });
}



// Reapply borders on page load by fetching the data from the server
// window.addEventListener('load', function() {

//     const cellAttr = document.getElementById('cell');
//     const roomID = cellAttr.getAttribute('data-room-id');

//     const parentTable = cellAttr.closest('table'); 
//     const tableID = parentTable.id;

//     fetch(`/get-borders/${roomID}/${tableID}`)
//     .then(response => {
//         if (response.status === 204 || response.status === 404) {
//             console.log("No borders found, stopping script execution.");
//             return;
//         }

//         return response.json();
//     })
//     .then(data => {

//         if (!data || !data.bordersAllCell) return;

//         // const borderData = data.borders || [];
//         const borderAllCellData = data.bordersAllCell || [];

//         const applyBordersToCells = (cellData, tableSelector) => {
//             for (const item of cellData) {
//                 const columnName = item.column;
//                 const table = document.querySelector(tableSelector);
//                 if (table) {
//                     const cell = table.querySelector(`[data-row="${item.row}"][data-column="${columnName}"]`);

//                     if (cell) {
//                         if (item.isTop) cell.classList.remove('border-t', 'border-t-cursor');
//                         if (item.isBottom) cell.classList.remove('border-b', 'border-b-cursor');
//                         if (item.isLeft) cell.classList.remove('border-l', 'border-l-cursor');
//                         if (item.isRight) cell.classList.remove('border-r', 'border-r-cursor');
//                         // Apply borders based on the provided data
//                         if (item.isTop) cell.classList.add('border-t-2', 'border-t-black');
//                         if (item.isBottom) cell.classList.add('border-b-2', 'border-b-black');
//                         if (item.isLeft) cell.classList.add('border-l-2', 'border-l-black');
//                         if (item.isRight) cell.classList.add('border-r-2', 'border-r-black');
//                     } else {
//                         console.error(`Cell not found at row ${item.row}, column ${columnName} in table ${tableSelector}`);
//                     }
//                 } else {
//                     console.error(`Table not found: ${tableSelector}`);
//                 }
//             }
//         };

//         // Apply borders to specific tables
//         // applyBordersToCells(borderData, `#${data.tableID}`);
//         applyBordersToCells(borderAllCellData, `#${data.tableID}`);
//     })
//     .catch(error => {
//         console.error('Error fetching borders:', error);
//     });
// });



function deleteBordersAllCells() {

    if (selectedCells.length === 0) return;
 
    const deleteData = [];
    const cellAttr = document.getElementById('cell');

    const parentTable = cellAttr.closest('table'); 
    const tableID = parentTable.id;
    const table = document.querySelector(`#${tableID}`);
    const roomID = table.getAttribute('data-room-id');

    selectedCells.forEach(cell => {

        const row = parseInt(cell.dataset.row);
        const col = newCellManager.columnNameToIndex(cell.dataset.column);
        const column = newCellManager.indexToColumnName(col);

        // Store the border data for all sides of the cell
        deleteData.push({
            table_id: tableID,
            room_id: roomID,
            row: row,
            column: column
        });
    });

    // Send the delete data to the server via AJAX
    fetch('/delete-borders-all-cells', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ borders: deleteData }),
    })
    .then(response => response.json())
    .then(data => {
        const msg = 'Borders Deleted Successfully!';
        floatMessage(msg);

        // Remove the borders visually
        for (const borderInfo of data.borders) {
            const cell = selectedCells.find(c => 
                parseInt(c.dataset.row) === borderInfo.row &&
                newCellManager.columnNameToIndex(c.dataset.column) === newCellManager.columnNameToIndex(borderInfo.column)
            );
            
            if (cell) {
                const hasBackground = [...cell.classList].some(cls => cls.startsWith('bg-')) || cell.style.backgroundColor;
                
                // Clear previous borders
                cell.classList.remove('border-t-2', 'border-b-2', 'border-l-2', 'border-r-2', 
                                      'border-t-black', 'border-b-black', 'border-l-black', 'border-r-black');
                // Apply new borders based on background class
                if (!hasBackground) {
                    cell.classList.add('border-t', 'border-b', 'border-l', 'border-r', 
                                       'border-t-cursor', 'border-b-cursor', 'border-l-cursor', 'border-r-cursor');
                }
                
                // Clear inline styles if set
                ['borderTop', 'borderBottom', 'borderLeft', 'borderRight'].forEach(border => {
                    cell.style[border] = '';
                });
            }
        }
        

    })
    .catch(error => {
        console.error('Error deleting borders:', error);
    });
}


function applyTopRightBotLeftBorder(position) {

    const borderDataPosition = [];
    const cellAttr = document.getElementById('cell');
    // const teacherID = cellAttr.getAttribute('data-teacher-id');

    const parentTable = cellAttr.closest('table'); 
    const tableID = parentTable.id;
    const table = document.querySelector(`#${tableID}`);
    const roomID = table.getAttribute('data-room-id');
    const teacherID = table.getAttribute('data-teacher-id');

    selectedCells.forEach(cell => {
        
        // Apply borders to all sides of the cell
        switch(position) {
            case 'Top':
                cell.classList.remove( 'border-t', 'border-t-cursor');
                cell.classList.add( 'border-t-2', 'border-t-black');
                break;
            case 'Right':
                cell.classList.remove( 'border-r', 'border-r-cursor');
                cell.classList.add( 'border-r-2', 'border-r-black');
                break;
            case 'Bottom':
                cell.classList.remove( 'border-b', 'border-b-cursor');
                cell.classList.add( 'border-b-2', 'border-b-black');
                break;
            case 'Left':
                cell.classList.remove( 'border-l', 'border-l-cursor');
                cell.classList.add( 'border-l-2', 'border-l-black');
                break;
            default:
                console.log('Unknow Position!')
        }

        const row = parseInt(cell.dataset.row);
        const col = newCellManager.columnNameToIndex(cell.dataset.column);
        const column = newCellManager.indexToColumnName(col);

        // Store the border data for all sides of the cell
        borderDataPosition.push({
            table_id: tableID,
            teacher_id: teacherID,
            room_id: roomID,
            row: row,
            column: column,
            isTop: ('Top' == position) ? true : false,
            isBottom: ('Bottom' == position) ? true : false,
            isLeft: ('Left' == position) ? true : false,
            isRight: ('Right' == position) ? true : false
        });
    });

    fetch('/save-borders-position-cells', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ borders: borderDataPosition }),
    })
    .then(response => response.json())
    .then(data => {
        const msg = 'Borders Successfully Saved!';
        floatMessage(msg);
    })
    .catch(error => {
        console.error('Error saving borders:', error);
    });
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