// let selectedCells = [];
// let tableState = JSON.parse(localStorage.getItem('tableState')) || {};

// document.querySelectorAll('.cursor-cell').forEach(cell => {
//     cell.addEventListener('click', () => {
//         if (selectedCells.includes(cell)) {
//             cell.classList.remove('selected');
//             selectedCells = selectedCells.filter(selectedCell => selectedCell !== cell);
//         } else {
//             cell.classList.add('selected');
//             selectedCells.push(cell);
//         }
//     });
// });

// function mergeSelectedCells() {
//     if (selectedCells.length < 2) {
//         alert('Please select at least two cells to merge.');
//         return;
//     }

//     // Get the range of selected cells
//     const rows = selectedCells.map(cell => parseInt(cell.getAttribute('data-row')));
//     const cols = selectedCells.map(cell => cell.getAttribute('data-column').charCodeAt(0) - 65);

//     const minRow = Math.min(...rows);
//     const maxRow = Math.max(...rows);
//     const minCol = Math.min(...cols);
//     const maxCol = Math.max(...cols);

//     const rowspan = maxRow - minRow + 1;
//     const colspan = maxCol - minCol + 1;

//     const firstCell = selectedCells[0];
//     let mergedContent = '';

//     selectedCells.forEach(cell => {
//         mergedContent += cell.innerHTML + ' ';
//         if (cell !== firstCell) {
//             cell.style.display = 'none';
//         }
//         cell.classList.remove('selected');
//     });

//     firstCell.setAttribute('rowspan', rowspan);
//     firstCell.setAttribute('colspan', colspan);
//     firstCell.innerHTML = mergedContent.trim();

//     // Save the state
//     saveTableState();

//     selectedCells = [];
// }

// function saveTableState() {
//     tableState = {};
//     document.querySelectorAll('.cursor-cell').forEach(cell => {
//         const row = cell.getAttribute('data-row');
//         const col = cell.getAttribute('data-column');
//         tableState[`${row}-${col}`] = {
//             content: cell.innerHTML,
//             rowspan: cell.getAttribute('rowspan') || 1,
//             colspan: cell.getAttribute('colspan') || 1,
//             display: cell.style.display
//         };
//     });
//     localStorage.setItem('tableState', JSON.stringify(tableState));
// }

// function loadTableState() {
//     document.querySelectorAll('.cursor-cell').forEach(cell => {
//         const row = cell.getAttribute('data-row');
//         const col = cell.getAttribute('data-column');
//         const state = tableState[`${row}-${col}`];
//         if (state) {
//             cell.innerHTML = state.content;
//             cell.setAttribute('rowspan', state.rowspan);
//             cell.setAttribute('colspan', state.colspan);
//             cell.style.display = state.display;
//         }
//     });
// }

// document.addEventListener('DOMContentLoaded', () => {
//     loadTableState();
// });

document.addEventListener('DOMContentLoaded', function() {

let selectedCells = [];
let longPressTimer;
let isDragging = false;
let startCell = null;

$(document).ready(function() {
    loadTableState();

    $('.cursor-cell').on('mousedown', function(event) {
        startCell = this;
        longPressTimer = setTimeout(() => {
            isDragging = true;
            addCellToSelection(this);
        }, 500); // Long press time threshold in ms
    });

    $(document).on('mousemove', function(event) {
        if (isDragging) {
            let target = $(event.target).closest('.cursor-cell')[0];
            if (target && target !== startCell) {
                selectRange(startCell, target);
            }
        }
    });

    $(document).on('mouseup', function(event) {
        clearTimeout(longPressTimer);
        if (isDragging) {
            isDragging = false;
            startCell = null;
        }
    });

    $('.cursor-cell').on('click', function(event) {
        if (event.shiftKey) {
            addCellToSelection2(this);
        } else {
            selectSingleCell(this);
        }
    });
    

    $('#mergeButton').on('click', function() {
        mergeSelectedCells();
    });

    $('#unmergeButton').on('click', function() {
        unmergeSelectedCells();
    });
});



function selectSingleCell(cell) {
    clearSelection();
    $(cell).addClass('selected');
    selectedCells.push(cell);
    adjustBorders();
}

function addCellToSelection(cell) {
    if (selectedCells.length === 0) {
        selectSingleCell(cell);
    } else {
        if (!selectedCells.includes(cell)) {
            $(cell).addClass('selected');
            selectedCells.push(cell);
        }
        adjustBorders();
    }
}
function addCellToSelection2(cell) {
    if (selectedCells.length === 0) {
        selectSingleCell(cell);
    } else {
        if (!selectedCells.includes(cell)) {
            $(cell).addClass('selected added');
            selectedCells.push(cell);
        }
    }
}

function columnNameToIndex(columnName) {
    if (!columnName || columnName.length === 0) return -1; // Return an invalid index for empty or undefined column names

    let index = 0;
    for (let i = 0; i < columnName.length; i++) {
        index *= 26;
        index += columnName.charCodeAt(i) - ('A'.charCodeAt(0) - 1);
    }
    return index - 1; // Convert to zero-based index
}


function selectRange(start, end) {
    clearSelection();

    const startRow = parseInt($(start).data('row'));
    const startCol = columnNameToIndex($(start).data('column'));
    const endRow = parseInt($(end).data('row'));
    const endCol = columnNameToIndex($(end).data('column'));

    if (startCol === -1 || endCol === -1) {
        console.error('Invalid column name');
        return;
    }

    const minRow = Math.min(startRow, endRow);
    const maxRow = Math.max(startRow, endRow);
    const minCol = Math.min(startCol, endCol);
    const maxCol = Math.max(startCol, endCol);

    for (let row = minRow; row <= maxRow; row++) {
        for (let col = minCol; col <= maxCol; col++) {
            const cell = $(`.cursor-cell[data-row=${row}][data-column=${indexToColumnName(col)}]`);
            if (cell.length) {
                cell.addClass('selected');
                selectedCells.push(cell[0]);
            }
        }
    }

    adjustBorders();
}
function indexToColumnName(index) {
    let columnName = '';
    while (index >= 0) {
        columnName = String.fromCharCode((index % 26) + 'A'.charCodeAt(0)) + columnName;
        index = Math.floor(index / 26) - 1;
    }
    return columnName;
}

function clearSelection() {
    selectedCells.forEach(cell => {
        $(cell).removeClass('selected selected2 not-top not-bottom not-left not-right top-border bottom-border left-border right-border added');
    });
    selectedCells = [];
}

function adjustBorders() {
    if (selectedCells.length === 0) return;

    const rows = selectedCells.map(cell => parseInt($(cell).data('row')));
    const cols = selectedCells.map(cell => columnNameToIndex($(cell).data('column')));

    const minRow = Math.min(...rows);
    const maxRow = Math.max(...rows);
    const minCol = Math.min(...cols);
    const maxCol = Math.max(...cols);

    selectedCells.forEach(cell => {
        const row = parseInt($(cell).data('row'));
        const col = columnNameToIndex($(cell).data('column'));

        if (col === -1) {
            alert('Invalid column name in cell');
            return;
        }

        if (row > minRow) $(cell).addClass('not-top');
        if (row < maxRow) $(cell).addClass('not-bottom');
        if (col > minCol) $(cell).addClass('not-left');
        if (col < maxCol) $(cell).addClass('not-right');

        if (row === minRow) $(cell).addClass('top-border');
        if (row === maxRow) $(cell).addClass('bottom-border');
        if (col === minCol) $(cell).addClass('left-border');
        if (col === maxCol) $(cell).addClass('right-border');
    });
}


//----------------------------------------------------------------------------------------------------------
// IndexedDB for Saving Applied Colors
// const dbName = "CellColorsDB";
// const storeName = "colors";

// if ('storage' in navigator && 'persist' in navigator.storage) {
//     navigator.storage.persist().then(persistent => {
//         if (persistent) {
//             console.log("Storage will not be cleared except by explicit user action");
//         } else {
//             console.log("Storage may be cleared by the UA under storage pressure.");
//         }
//     });
// }

// function openDB() {
//     return new Promise((resolve, reject) => {
//         const request = indexedDB.open(dbName, 1);

//         request.onupgradeneeded = (event) => {
//             const db = event.target.result;
//             if (!db.objectStoreNames.contains(storeName)) {
//                 db.createObjectStore(storeName, { keyPath: "id" });
//             }
//         };

//         request.onsuccess = (event) => {
//             resolve(event.target.result);
//         };

//         request.onerror = (event) => {
//             reject(event.target.error);
//         };
//     });
// }

// function saveCellColor(roomId, row, column, color) {
//     openDB().then((db) => {
//         const transaction = db.transaction(storeName, "readwrite");
//         const store = transaction.objectStore(storeName);
//         store.put({ id: `${roomId}-${row}-${column}`, roomId, row, column, color });

//         transaction.oncomplete = () => {
//             db.close();
//         };

//         transaction.onerror = (event) => {
//             console.error("Error saving cell color:", event.target.error);
//             db.close();
//         };
//     }).catch((error) => {
//         console.error("Error opening IndexedDB:", error);
//     });
// }

// function deleteCellColor(roomId, row, column) {
//     openDB().then((db) => {
//         const transaction = db.transaction(storeName, "readwrite");
//         const store = transaction.objectStore(storeName);
//         store.delete(`${roomId}-${row}-${column}`);

//         transaction.oncomplete = () => {
//             db.close();
//         };

//         transaction.onerror = (event) => {
//             console.error("Error deleting cell color:", event.target.error);
//             db.close();
//         };
//     }).catch((error) => {
//         console.error("Error opening IndexedDB:", error);
//     });
// }

// function loadCellColors(roomId) {
//     openDB().then((db) => {
//         const transaction = db.transaction(storeName, "readonly");
//         const store = transaction.objectStore(storeName);
//         const request = store.openCursor();

//         request.onsuccess = (event) => {
//             const cursor = event.target.result;
//             if (cursor) {
//                 const cell = cursor.value;
//                 if (cell.roomId === roomId) {
//                     $(`.cursor-cell[data-room-id=${cell.roomId}][data-row=${cell.row}][data-column=${cell.column}]`).css('background-color', cell.color);
//                 }
//                 cursor.continue();
//             }
//             db.close();
//         };

//         request.onerror = (event) => {
//             console.error("Error fetching cell colors:", event.target.error);
//             db.close();
//         };
//     }).catch((error) => {
//         console.error("Error opening IndexedDB:", error);
//     });
// }

// $(document).ready(function() {
//     const roomId = $("#main-table").data("room-id");

//     loadCellColors(roomId);

//     let selectedColor = "#ff0000";

//     $("#colorPicker").on("input", function() {
//         selectedColor = $(this).val();
//     });

//     $("#applyColor").on("click", function() {
//         selectedCells.forEach(cell => {
//             const row = $(cell).data("row");
//             const column = $(cell).data("column");
//             $(cell).css("background-color", selectedColor);
//             saveCellColor(roomId, row, column, selectedColor);
//         });
//     });

//     $("#deleteColor").on("click", function() {
//         selectedCells.forEach(cell => {
//             const row = $(cell).data("row");
//             const column = $(cell).data("column");
//             $(cell).css("background-color", ""); // Reset to default background color
//             deleteCellColor(roomId, row, column);
//         });
//     });

//     $(document).on("click", ".cursor-cell", function(event) {
//         if (event.ctrlKey || event.metaKey) {
//             addCellToSelection(this);
//         } else {
//             selectSingleCell(this);
//         }
//     });

//     $(document).on("mousedown", ".cursor-cell", function(event) {
//         if (event.shiftKey) {
//             const startCell = selectedCells[0];
//             if (startCell) {
//                 selectRange(startCell, this);
//             }
//         } else {
//             selectSingleCell(this);
//         }
//     });
// });
//----------------------------------------------------------------------------------------------------------




// function performOperation(operation) {
//     let selectedCells = $('.selected');

//     if (selectedCells.length < 2) {
//         alert('Please select at least two cells to perform an operation.');
//         return;
//     }

//     let studentName = $(selectedCells[0]).attr('data-operation');
    
//     if (!studentName) {
//         alert("Can't Perform Operation!");
//         return;
//     }

//     applyResultToAllRows(operation);
// }

// function applyResultToAllRows(operation) {
//     $('tr').each(function() {
//         let row = $(this);
//         let cells = row.find('td[data-operation]');
//         let selectedCells = $('.selected');


//         console.log(cells   )
        
//         if (cells.length < 2) {
//             return;
//         }

//         let rowResult = 0;
//         for (let i = 0; i < selectedCells.length - 1; i++) { // Exclude the last cell initially
//             let cellValue = parseFloat($(cells[i]).text()) || 0;
//             if (isNaN(cellValue)) {
//                 continue; // Skip if the cell value is not a number
//             }

//             switch (operation) {
//                 case 'add':
//                     rowResult += cellValue;
//                     break;
//                 case 'subtract':
//                     rowResult -= cellValue;
//                     break;
//                 case 'multiply':
//                     rowResult *= cellValue;
//                     break;
//             }
//         }

//         $(cells[selectedCells.length - 1]).text(rowResult); // Update the last cell with the result
//     });
// }


// $(document).ready(function() {
//     $('.cursor-cell').click(function() {
//         $(this).toggleClass('selected');
//     });
    
//     // Example to call the function, you might want to replace it with your actual logic
//     $('#performOperationButton').click(function() {
//         performOperation('add'); // Replace 'add' with the actual operation
//     });
// });




function mergeSelectedCells() {
    // let cont = mergedContent.trim();
    // if(!cont) {
    //     alert('Cant Merged without Content');
    //     return;
    // }
    if (selectedCells.length === 0) return;

    const rows = selectedCells.map(cell => parseInt($(cell).data('row')));
    const cols = selectedCells.map(cell => $(cell).data('column').charCodeAt(0) - 65);

    const minRow = Math.min(...rows);
    const maxRow = Math.max(...rows);
    const minCol = Math.min(...cols);
    const maxCol = Math.max(...cols);

    const rowspan = maxRow - minRow + 1;
    const colspan = maxCol - minCol + 1;

    const firstCell = selectedCells[0];
    let mergedContent = '';

    // Collect all ids
    const ids = selectedCells.map(cell => {
        const id = $(cell).attr('data-id'); // Use attr() to get the attribute value
        console.log('Cell data-id:', id); // Debugging statement
        return id;
    }).filter(id => id !== undefined && id !== "");

    selectedCells.forEach(cell => {
        const cellContent = $(cell).html();
        console.log('Cell content:', cellContent); // Debugging statement
        mergedContent += cellContent + ' ';
        if (cell !== firstCell) {
            $(cell).addClass('merged-hidden'); // Apply a class to hide visually
            $(cell).attr('data-merged', true); // Mark cell as merged
        }
        $(cell).removeClass('selected');
    });

    // Set attributes for the merged cell
    $(firstCell).attr('rowspan', rowspan);
    $(firstCell).attr('colspan', colspan);
    $(firstCell).html(mergedContent.trim());
    $(firstCell).attr('data-merged', true); // Mark cell as merged

    if (ids.length >= 0) {
        $(firstCell).attr('data-id', ids[0]); // Use the first id from the merged cells
    } else {
        $(firstCell).removeAttr('data-id'); // Remove data-id if no ids are available
    }

    console.log('Merged cell data-id:', $(firstCell).attr('data-id')); // Debugging statement
    

    const cellData = {
        room_id: $(firstCell).attr('data-room-id'),
        student_id: $(firstCell).attr('data-room-studentid'),
        student_name: $(firstCell).attr('data-room-student'),
        column: $(firstCell).attr('data-column'),
        row: $(firstCell).attr('data-row'),
        content: mergedContent.trim(),
        merged: true,
        rowspan: rowspan,
        colspan: colspan,
        dataID: $(firstCell).attr('data-id'),
        tableID: $(firstCell).closest('table').attr('id') // Get the table ID
    };

    console.log(cellData)

    console.log('Data ID:', $(firstCell).attr('data-id'));
    console.log('Cell Data:', cellData);

    let id = $(firstCell).attr('data-id');

    $.ajax({
        url: id ? `/update-merged-cell/${id}` : `/save-merged-cell`,
        type: 'POST',
        contentType: 'application/json',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        },
        data: JSON.stringify(cellData),
        success: function(response) {
            console.log(response.message);
            if (response.id) {
                // Update the cell with the new ID
                $(firstCell).attr('data-id', response.id);
            }
            console.log(response.id)
        },
        error: function(error) {
            console.log('Error saving cell merge:', error.responseJSON.message);
        }
    });
    selectedCells = [];
}


$(document).ready(function() {
    // Ensure all merged cells with no content have a visible character
    $('td[data-merged="true"]').each(function() {
        if (!$(this).text().trim()) {
            $(this).text('\u200B'); // Zero-width space (invisible but prevents cell from disappearing)
        }
    });
});



function loadTableState() {
    $.ajax({
        url: '/load-table-state',
        method: 'GET',
        success: function(data) {
            if (!Array.isArray(data)) {
                console.error('Invalid data format for fetchTableState:', data);
                return;
            }
            console.log('Loaded table state:', data); // Debugging

            updateTableState(data);

        },
        error: function(xhr, status, error) {
            console.error('Error loading table state:', error);
        }
    });
}


function updateTableState(data) {
    data.forEach(cell => {
        const selector = `.cursor-cell[data-row=${cell.row}][data-column=${cell.column}]`;
        const cellElement = $(selector);
        cellElement.html(cell.content);
        cellElement.attr('rowspan', cell.rowspan);
        cellElement.attr('colspan', cell.colspan);
        if (cell.merged) {
            cellElement.attr('data-merged', true);
            cellElement.hide();
            console.log(merged + "well")
        } else {
            cellElement.removeAttr('data-merged');
            cellElement.show();
        }
    });
}

function unmergeSelectedCells() {
    if (selectedCells.length === 0) return;

    const firstCell = selectedCells[0];
    let id = $(firstCell).data('id');

    const rowIndex = parseInt($(firstCell).data('row'));
    const colIndex = $(firstCell).data('column').charCodeAt(0) - 65;

    // Immediately update the cell UI
    updateCellAfterUnmerge(firstCell);

    // Send unmerge request to the server
    $.ajax({
        url: `/unmerged-cell/${id}`,
        type: 'POST',
        contentType: 'application/json',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        },
        data: JSON.stringify({
            row: rowIndex,
            column: String.fromCharCode(65 + colIndex),
            content: $(firstCell).html().trim(),
            rowspan: 1,
            colspan: 1,
            merged: false
        }),
        success: function(data) {
            console.log('Unmerge response:', data.message);
            // window.location.reload();
        },
        error: function(error) {
            console.log('Error saving cell unmerge:', error.responseJSON.message);
            revertCellAfterUnmerge(firstCell);
        },
        complete: function() {
            clearSelection(); // Clear selection after operation
        }
    });
}

function updateCellAfterUnmerge(cell) {
    const originalContent = $(cell).html().trim();

    // Reset the cell's rowspan, colspan, and content
    $(cell).attr('rowspan', 1);
    $(cell).attr('colspan', 1);
    $(cell).removeAttr('data-merged');
    $(cell).html(originalContent);
    // $(cell).removeClass('merged-hidden'); // Show previously hidden merged cells
    $('.cursor-cell[data-merged="true"]').show();
}

function revertCellAfterUnmerge(cell) {
    // Revert the cell's state to its previous merged state
    $(cell).attr('rowspan', 1);
    $(cell).attr('colspan', 1);
    $(cell).attr('data-merged', true);
    $(cell).addClass('merged-hidden'); // Re-hide the previously merged cells
}


});

















// CARET
document.addEventListener('DOMContentLoaded', (event) => {
    const editableCells = document.querySelectorAll('.cursor-cell');

    editableCells.forEach(cell => {
        cell.addEventListener('focus', () => {
            cell.classList.remove('typing');
        });

        cell.addEventListener('blur', () => {
            if(cell.textContent.trim() === '') {
                cell.classList.remove('typing');
            }
        });

        cell.addEventListener('input', () => {
            cell.classList.add('typing');
        });
    });
});

// const table = document.querySelector('.zoom-container');
// const header = document.querySelector('.t-head');

// table.addEventListener ('scroll', () => {
//     header.classList.add('sticky', table.scrollY > 80);
// });