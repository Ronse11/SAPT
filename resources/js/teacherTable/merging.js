import { handleCellBlur } from "@js/handleCellBlur.js";


export let selectedCells = [];

export class CellManager {
    constructor() {
        this.longPressTimer;
        // this.selectedCells = [];
        this.borderedCells = [];
        this.startCell = null;
        this.isDragging = false;
        this.initialCell = null;

    }

    columnNameToIndex(columnName) {
        if (!columnName) return -1;

        let index = 0;
        for (let i = 0; i < columnName.length; i++) {
            index = index * 26 + (columnName.charCodeAt(i) - 64);
        }
        return index - 1;
    }

    indexToColumnName(index) {
        let columnName = '';
        while (index >= 0) {
            columnName = String.fromCharCode((index % 26) + 65) + columnName;
            index = Math.floor(index / 26) - 1;
        }
        return columnName;
    }

    selectSingleCell(cell) {
        this.clearSelection();
        cell.classList.add('selected');
        selectedCells.push(cell);
        this.initialCell = cell;
        this.adjustBorders();
    }

    addCellToSelection(cell) {
        if (selectedCells.length === 0) {
            this.selectSingleCell(cell);
        } else {
            if (!selectedCells.includes(cell)) {
                $(cell).addClass('selected');
                selectedCells.push(cell);
            }
            this.adjustBorders();
        }
    }

    addCellToSelection2(cell) {
        if (!cell || !cell.dataset || !cell.dataset.row || !cell.dataset.column) {
            console.error('Cell is missing required attributes.');
            return;
        }
    
        if (!this.initialCell) {
            this.initialCell = cell;
            this.selectSingleCell(cell); // Select the first cell and set it as the initialCell
        } else {
            // Select range from the initialCell to the newly clicked cell
            this.selectRange(this.initialCell, cell);
        }
    
        this.adjustBorders();
    }
    

    selectRange(start, end) {
        this.clearSelection();
    
        // Get start and end positions
        let startRow = parseInt(start.dataset.row);
        let startCol = this.columnNameToIndex(start.dataset.column);
        let endRow = parseInt(end.dataset.row);
        let endCol = this.columnNameToIndex(end.dataset.column);
    
        // Return if the selection is invalid
        if (startCol === -1 || endCol === -1 || (startRow === endRow && endCol < startCol)) {
            return;
        }
    
        // Adjust boundaries to account for spans in any cells within the range
        let minRow = Math.min(startRow, endRow);
        let maxRow = Math.max(startRow, endRow);
        let minCol = Math.min(startCol, endCol);
        let maxCol = Math.max(startCol, endCol);
    
        // Expand selection range to include all spans
        for (let row = minRow; row <= maxRow; row++) {
            for (let col = minCol; col <= maxCol; col++) {
                const cellSelector = `.cursor-cell[data-row="${row}"][data-column="${this.indexToColumnName(col)}"]`;
                const cell = document.querySelector(cellSelector);
                if (cell) {
                    const cellColspan = parseInt(cell.getAttribute('colspan')) || 1;
                    const cellRowspan = parseInt(cell.getAttribute('rowspan')) || 1;
    
                    // Expand the boundaries to include spanned cells
                    if (cellColspan > 1 || cellRowspan > 1) {
                        maxRow = Math.max(maxRow, row + cellRowspan - 1);
                        maxCol = Math.max(maxCol, col + cellColspan - 1);
                    }
                }
            }
        }
    
        // Use a Set for selected cells to speed up lookup
        const selectedCellSet = new Set(selectedCells);
    
        // Batch DOM updates
        const newSelectedCells = [];
    
        // Iterate through all cells within the expanded selection range
        for (let row = minRow; row <= maxRow; row++) {
            for (let col = minCol; col <= maxCol; col++) {
                const baseCellSelector = `.cursor-cell[data-row="${row}"][data-column="${this.indexToColumnName(col)}"]`;
                const baseCell = document.querySelector(baseCellSelector);
    
                if (baseCell && !selectedCellSet.has(baseCell)) {
                    const cellColspan = parseInt(baseCell.getAttribute('colspan')) || 1;
                    const cellRowspan = parseInt(baseCell.getAttribute('rowspan')) || 1;
    
                    // Expand selection to cover the entire span
                    for (let i = 0; i < cellRowspan; i++) {
                        for (let j = 0; j < cellColspan; j++) {
                            const spannedCellSelector = `.cursor-cell[data-row="${row + i}"][data-column="${this.indexToColumnName(col + j)}"]`;
                            const spannedCell = document.querySelector(spannedCellSelector);
    
                            if (spannedCell && !selectedCellSet.has(spannedCell)) {
                                spannedCell.classList.add('selected');
                                newSelectedCells.push(spannedCell);
                            }
                        }
                    }
                }
            }
        }
    
        // Update selectedCells only once
        selectedCells.push(...newSelectedCells);
    
        this.adjustBorders();
    }
    
    

    adjustBorders() {
        if (selectedCells.length === 0) return;

        const rows = [];
        const cols = [];

        selectedCells.forEach(cell => {
            const row = parseInt(cell.dataset.row);
            const col = this.columnNameToIndex(cell.dataset.column);
            const rowspan = parseInt(cell.getAttribute('rowspan')) || 1;
            const colspan = parseInt(cell.getAttribute('colspan')) || 1;

            for (let r = row; r < row + rowspan; r++) {
                rows.push(r);
            }
            for (let c = col; c < col + colspan; c++) {
                cols.push(c);
            }
        });

        const minRow = Math.min(...rows);
        const maxRow = Math.max(...rows);
        const minCol = Math.min(...cols);
        const maxCol = Math.max(...cols);

        selectedCells.forEach(cell => {
            const row = parseInt(cell.dataset.row);
            const col = this.columnNameToIndex(cell.dataset.column);
            const rowspan = parseInt(cell.getAttribute('rowspan')) || 1;
            const colspan = parseInt(cell.getAttribute('colspan')) || 1;

            const isTop = row === minRow;
            const isBottom = row + rowspan - 1 === maxRow;
            const isLeft = col === minCol;
            const isRight = col + colspan - 1 === maxCol;

            cell.classList.toggle('top-border', isTop);
            cell.classList.toggle('bottom-border', isBottom);
            cell.classList.toggle('left-border', isLeft);
            cell.classList.toggle('right-border', isRight);

            cell.classList.toggle('not-top', !isTop);
            cell.classList.toggle('not-bottom', !isBottom);
            cell.classList.toggle('not-left', !isLeft);
            cell.classList.toggle('not-right', !isRight);
        });
    }

    clearSelection() {
        if (selectedCells.length > 0) {
            selectedCells.forEach(cell => {
                cell.classList.remove('selected', 'selected2', 'not-top', 'not-bottom', 'not-left', 'not-right', 'top-border', 'bottom-border', 'left-border', 'right-border', 'added');
            });
            selectedCells.splice(0, selectedCells.length);
        }
    }

    // loadTableState() {
    //     $.ajax({
    //         url: '/load-table-state',
    //         method: 'GET',
    //         success: (data) => {
    //             if (!Array.isArray(data)) {
    //                 console.error('Invalid data format for fetchTableState:', data);
    //                 return;
    //             }
    //             console.log('Loaded table state (1):', data);
                
    //             this.updateTableState(data);
    
    //             // Trigger adjust borders to ensure restored cells have correct borders
    //             this.adjustBorders();
    //         },
    //         error: function(xhr, status, error) {
    //             console.error('Error loading table state:', error);
    //         }
    //     });
    // }

    async loadTableState() {
        try {
            const response = await fetch('/load-table-state');
            if (!response.ok) {
                throw new Error(`Error loading table state: ${response.statusText}`);
            }
    
            const data = await response.json();
    
            if (!Array.isArray(data)) {
                console.error('Invalid data format for fetchTableState:', data);
                return;
            }
    
            console.log('Loaded table state (1):', data);
    
            this.updateTableState(data);
    
            // Trigger adjust borders to ensure restored cells have correct borders
            this.adjustBorders();
        } catch (error) {
            console.error('Error loading table state:', error);
        }
    }
    
    
    updateTableState(data) {
        data.forEach(cell => {
            const selector = `.cursor-cell[data-row=${cell.row}][data-column=${cell.column}]`;
            const cellElement = $(selector);
            cellElement.html(cell.content);
            cellElement.attr('rowspan', cell.rowspan);
            cellElement.attr('colspan', cell.colspan);
            
            if (cell.merged) {
                cellElement.attr('data-merged', true);
                cellElement.hide();
            } else {
                cellElement.removeAttr('data-merged');
                cellElement.show();
            }
    
            // Apply selection class if necessary
            if (cell.selected) {
                cellElement.addClass('selected');
                selectedCells.push(cellElement[0]);  // Add to selectedCells array
            }
        });
    
        // Reapply borders after restoring the selection
        this.adjustBorders();
    }
    
}



const cellManager = new CellManager();

// MANG ENCAPSULATE BWAS CODE OKIIIIIIIIIIIIIIIIIISSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSS??????????????????????????
document.addEventListener('DOMContentLoaded', function() {

    // $(document).ready(function() {
        cellManager.loadTableState();

        $('.cursor-cell').off('mousedown').on('mousedown', function(event) {
            cellManager.startCell = this;
            cellManager.longPressTimer = setTimeout(() => {
                cellManager.isDragging = true;
                cellManager.addCellToSelection(this);
            }, 200); // Long press time threshold in ms
        });

        $(document).off('mousemove').on('mousemove', function(event) {
            if (cellManager.isDragging) {
                let target = $(event.target).closest('.cursor-cell')[0];
                if (target && target !== cellManager.startCell) {
                    cellManager.selectRange(cellManager.startCell, target);
                }
            }
        });

        $(document).off('mouseup').on('mouseup', function(event) {
            clearTimeout(cellManager.longPressTimer);
            if (cellManager.isDragging) {
                cellManager.isDragging = false;
                cellManager.startCell = null;
            }
        });

        $('.cursor-cell').off('click').on('click', function(event) {
            if (event.shiftKey) {
                cellManager.addCellToSelection2(this);
            } else {
                cellManager.selectSingleCell(this); // Select single cell and set it as the initial cell
                cellManager.initialCell = this;
            }
        });
        
        $('#mergeButton').off('click').on('click', function() {
            mergeSelectedCells();
        });
        // $('#unmergeButton').off('click').on('click', unmergeSelectedCells);
    });
// });

// MERGING START HERE!
async function mergeSelectedCells() {

    if (selectedCells.length === 0) return;

    const rows = selectedCells.map(cell => parseInt(cell.dataset.row));
    const cols = selectedCells.map(cell => cellManager.columnNameToIndex(cell.dataset.column));

    const minRow = Math.min(...rows);
    const maxRow = Math.max(...rows);
    const minCol = Math.min(...cols);
    const maxCol = Math.max(...cols);

    const firstCell = selectedCells[0];
    const rowspan = maxRow - minRow + 1;
    const colspan = maxCol - minCol + 1;

    let mergedContent = firstCell.innerHTML.trim();
    let tableName = firstCell.closest('table').id;
    const table = document.querySelector(`#${tableName}`);
    let row = firstCell.dataset.row;
    let studentName = table.querySelector(`td[data-row="${row}"][data-column="A"]`).dataset.roomStudent;
    const roomID = table.getAttribute('data-room-id');

    const overlappedCells = [];

    selectedCells.forEach((cell, index) => {
        if (index > 0) {
            const overlappedCellData = {
                row: cell.dataset.row,
                column: cell.dataset.column,
                content: cell.innerHTML.trim(),
                merged: true,
                hidden: true,
                rowspan: 1,
                colspan: 1
            };
            overlappedCells.push(overlappedCellData);

            cell.innerHTML = '';
            cell.dataset.merged = 'true';
            cell.dataset.hidden = 'true';
            cell.style.display = 'none';
        }
        cell.classList.remove('selected');
    });

    // Update the main cell attributes correctly
    firstCell.setAttribute('rowspan', rowspan);
    firstCell.setAttribute('colspan', colspan);
    firstCell.dataset.merged = 'true';
    firstCell.innerHTML = mergedContent;

    const cellData = {
        room_id: roomID,
        student_name: studentName,
        column: firstCell.dataset.column,
        row: row,
        content: mergedContent,
        merged: true,
        rowspan: rowspan,
        colspan: colspan,
        hiddenCells: overlappedCells,
        tableID: tableName
    };

    try {
        const response = await fetch('/save-merged-cell', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(cellData)
        });

        const result = await response.json();

        if (result.id) {
            firstCell.dataset.id = result.id;
        }
    } catch (error) {
        console.error('Error saving cell merge:', error.message);
    }

    selectedCells = [];
}


// $(document).ready(function() {
//     // Ensure all merged cells with no content have a visible character
//     $('td[data-merged="true"]').each(function() {
//         if (!$(this).text().trim()) {
//             $(this).text('\u200B'); // Zero-width space (invisible but prevents cell from disappearing)
//             console.log('ksdf')
//         }
//     });
// });

// UNMERGING START HERE!
document.getElementById('unmergeButton').addEventListener('click', async function() {
    // Show the loading message
    const loadingMessage = document.querySelector('.loadingScreen');
    // loadingMessage.style.display = 'flex';
    loadingMessage.classList.remove('hidden');

    try {
        // Call your unmerge function
        await unmergeSelectedCells();
    } catch (error) {
        console.error('Error during unmerge:', error);
    } finally {
        // Hide the loading message
        // loadingMessage.style.display = 'none';
        loadingMessage.classList.add('hidden');
    }
});

// UNMERGING FUNCTION!
async function unmergeSelectedCells() {
    if (selectedCells.length === 0) return;
    
    const firstCell = selectedCells[0];
    const rowspan = parseInt(firstCell.getAttribute('rowspan')) || 1;
    const colspan = parseInt(firstCell.getAttribute('colspan')) || 1;
    const startRow = parseInt(firstCell.dataset.row);
    const startCol = cellManager.columnNameToIndex(firstCell.dataset.column);

    const mergedContent = firstCell.innerHTML.trim();

    // Unmerge cells and reset attributes
    for (let row = startRow; row < startRow + rowspan; row++) {
        for (let col = startCol; col < startCol + colspan; col++) {
            const cellSelector = `.cursor-cell[data-row="${row}"][data-column="${cellManager.indexToColumnName(col)}"]`;
            const cell = document.querySelector(cellSelector);

            if (row === startRow && col === startCol) {
                firstCell.removeAttribute('rowspan');
                firstCell.removeAttribute('colspan');
                firstCell.removeAttribute('data-merged');
                firstCell.removeAttribute('data-hidden');
                firstCell.style.display = '';
                firstCell.innerHTML = mergedContent;
            } else {
                if (cell) {
                    cell.style.display = '';
                    cell.innerHTML = '';
                    cell.removeAttribute('data-merged');
                    cell.removeAttribute('data-hidden');
                    cell.removeAttribute('rowspan');
                    cell.removeAttribute('colspan');
                }
            }
        }
    }

    let tableName = firstCell.closest('table').id;
    const table = document.querySelector(`#${tableName}`);
    const roomID = table.getAttribute('data-room-id');

    // Send the unmerge request
    const id = firstCell.dataset.id;
    const payload = {
        cell_id: id,
        row: startRow,
        column: cellManager.indexToColumnName(startCol),
        rowspan,
        colspan,
        room_id: roomID,
        student_id: firstCell.dataset.roomStudentid,
        tableID: firstCell.closest('table').id
    };

    try {
        const response = await fetch(`/unmerge-cell/${id}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(payload)
        });
        
        const data = await response.json();
        if (data.updatedCells) {
            updateCellsAfterUnmerge(data.updatedCells, data.cellAttr);
        }
    } catch (error) {
        restorePreviousCellState(firstCell, rowspan, colspan);
    } finally {
        // Remove the loading message
        const loadingMessage = document.getElementById('loadingScreen');
        if (loadingMessage) {
            loadingMessage.remove();
        }
        cellManager.clearSelection();
    }
}

function updateCellsAfterUnmerge(updatedCells, cellAttr) {
    cellManager.clearSelection(); // Clear previous selections
    selectedCells = [];

    updatedCells.forEach(cellData => {
        const table = document.getElementById(cellAttr.table_id);
        const row = table.querySelectorAll('tr')[cellData.row];
        let cellSelector = `.cursor-cell[data-row="${cellData.row}"][data-column="${cellData.column}"]`;
        let cell = row.querySelector(cellSelector);
        const roomID = table.getAttribute('data-room-id');

        if (!cell) {
            cell = createNewCell(cellData, row, cellAttr, roomID);
        }
        
        // Update the cell properties
        cell.removeAttribute('data-merged data-hidden');
        cell.setAttribute('rowspan', cellData.rowspan || 1);
        cell.setAttribute('colspan', cellData.colspan || 1);
        cell.innerHTML = cellData.content || '';

        // Add the cell to the selectedCells array
        selectedCells.push(cell);

    });
    // Reattach event listeners to the updated cells
    reattachEventListeners();

    cellManager.adjustBorders(); // Adjust borders after selection
}

function reattachEventListeners() {
    // Reattach mousedown event
    $('.cursor-cell').off('mousedown').on('mousedown', function(event) {
        cellManager.startCell = this;
        cellManager.longPressTimer = setTimeout(() => {
            cellManager.isDragging = true;
            cellManager.addCellToSelection(this);
        }, 200); // Long press time threshold in ms
    });

    // Reattach click event
    $('.cursor-cell').off('click').on('click', function(event) {
        if (event.shiftKey) {
            cellManager.addCellToSelection2(this);
        } else {
            cellManager.selectSingleCell(this); // Select single cell and set it as the initial cell
            cellManager.initialCell = this;
        }
    });

    // Reattach mousemove event
    $(document).off('mousemove').on('mousemove', function(event) {
        if (cellManager.isDragging) {
            let target = $(event.target).closest('.cursor-cell')[0];
            if (target && target !== cellManager.startCell) {
                cellManager.selectRange(cellManager.startCell, target);
            }
        }
    });

    // Reattach mouseup event
    $(document).off('mouseup').on('mouseup', function(event) {
        clearTimeout(cellManager.longPressTimer);
        if (cellManager.isDragging) {
            cellManager.isDragging = false;
            cellManager.startCell = null;
        }
    });

    // Reattach other event listeners if needed
    $('#mergeButton').off('click').on('click', mergeSelectedCells);
    $('#unmergeButton').off('click').on('click', unmergeSelectedCells);
}

function createNewCell(cellData, row, cellAttr, roomID) {
    const cell = document.createElement('td');
    cell.id = 'cell';
    cell.className = 'student-cell cursor-cell border border-cursor';
    cell.contentEditable = 'true';
    cell.dataset.id = cellData.id || "";
    cell.dataset.row = cellData.row;
    cell.dataset.column = cellData.column;
    cell.dataset.roomId = roomID;
    // cell.dataset.roomStudent = cellAttr.data_room_student;
    // cell.dataset.teacherId = cellAttr.teacher_id;
    cell.dataset.originalContent = cellData.content || '';

    if (cellData.rowspan && cellData.rowspan > 1) {
        cell.setAttribute('rowspan', cellData.rowspan);
    }

    if (cellData.colspan && cellData.colspan > 1) {
        cell.setAttribute('colspan', cellData.colspan);
    }

    cell.onblur = (event) => {
        handleCellBlur(event, cellAttr.table_id, cellData.content || '');
    };

    insertCellInRow(row, cell, cellData.column);
    return cell;
}

function insertCellInRow(row, cell, newColumn) {
    let inserted = false;
    Array.from(row.children).forEach(existingCell => {
        const currentColIndex = cellManager.columnNameToIndex(existingCell.dataset.column);
        const newColIndex = cellManager.columnNameToIndex(newColumn);

        if (newColIndex < currentColIndex && !inserted) {
            row.insertBefore(cell, existingCell);
            inserted = true;
        }
    });

    if (!inserted) {
        row.appendChild(cell);
    }
}

function restorePreviousCellState(mainCell, previousRowspan, previousColspan) {
    $(mainCell).attr('rowspan', previousRowspan);
    $(mainCell).attr('colspan', previousColspan);
    $(mainCell).attr('data-merged', true);
    console.log('Main cell restored:', mainCell);
}



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
