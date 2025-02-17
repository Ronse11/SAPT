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






















// public function saveMergedCell(Request $request)
// {
//     try {
//         $userID = Auth::id();
//         $tableId = $request->tableID;

//         $tableModels = [
//             'main-table' => TableSkills::class,
//             'attendance-table' => TableAttendance::class
//         ];
        
//         if (!array_key_exists($tableId, $tableModels)) {
//             return response()->json(['message' => 'Invalid table ID.'], 400);
//         }

//         $model = $tableModels[$tableId];

//         $request->validate([
//             'content' => ['nullable'],
//             'hiddenCells' => ['array'] // Validate hidden cells array
//         ]);

//         // Save each hidden cell associated with this merge
//         foreach ($request->hiddenCells as $hiddenCell) {
//             $tableSkill = $model::create([
//                 'teacher_id' => $userID,
//                 'room_id' => $request->room_id,
//                 'student_room_id' => $request->student_id,
//                 'student_name' => $request->student_name,
//                 'row' => $hiddenCell['row'],
//                 'column' => $hiddenCell['column'],
//                 'content' => $hiddenCell['content'],
//                 'merged' => $hiddenCell['merged'],
//                 'rowspan' => $hiddenCell['rowspan'],
//                 'colspan' => $hiddenCell['colspan'],
//             ]);
//         }

//         return response()->json(['message' => 'Cell merge saved successfully.', 'id' => $tableSkill->id], 200);
//     } catch (\Exception $e) {
//         return response()->json(['message' => 'Error saving cell merge: ' . $e->getMessage()], 500);
//     }
// }






// function unmergeSelectedCells() {
//     if (selectedCells.length === 0) return;

//     const firstCell = selectedCells[0];
//     const rowspan = parseInt($(firstCell).attr('rowspan')) || 1;
//     const colspan = parseInt($(firstCell).attr('colspan')) || 1;
//     const startRow = parseInt($(firstCell).data('row'));
//     const startCol = columnNameToIndex($(firstCell).data('column'));

//     // Store content if needed
//     const mergedContent = $(firstCell).html().trim();

//     for (let row = startRow; row < startRow + rowspan; row++) {
//         for (let col = startCol; col < startCol + colspan; col++) {
//             const cellSelector = `.cursor-cell[data-row=${row}][data-column=${indexToColumnName(col)}]`;
//             const cell = $(cellSelector);

//             if (row === startRow && col === startCol) {
//                 $(firstCell).attr('rowspan', 1);
//                 $(firstCell).attr('colspan', 1);
//                 $(firstCell).removeAttr('data-merged');
//                 $(firstCell).show();
//                 $(firstCell).html(mergedContent); // Restore content
//             } else {
//                 cell.show();
//                 cell.removeClass('merged-hidden');
//                 cell.removeAttr('data-merged');
//                 cell.attr('rowspan', 1);
//                 cell.attr('colspan', 1);
//                 cell.html(''); // Clear content of the revealed cells
//             }
//         }
//     }

//     const id = $(firstCell).data('id');
//     $.ajax({
//         url: `/unmerged-cell/${id}`,
//         type: 'POST',
//         contentType: 'application/json',
//         headers: {
//             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
//         },
//         data: JSON.stringify({
//             row: startRow,
//             column: indexToColumnName(startCol),
//             content: mergedContent,
//             rowspan: 1,
//             colspan: 1,
//             merged: false
//         }),
//         success: function(data) {
//             console.log('Unmerge successful:', data.message);
//         },
//         error: function(error) {
//             console.log('Error during unmerge:', error.responseJSON.message);
//         },
//         complete: function() {
//             clearSelection();
//             // Optionally, provide user feedback here
//         }
//     });
// }











// function updateCellsAfterUnmerge(updatedCells) {
//     const table = $('#main-table');

//     updatedCells.forEach(cellData => {
//         // Ensure the row exists or create it
//         let row = table.find(`tr[data-row="${cellData.row}"]`);
//         if (row.length === 0) {
//             row = $('<tr>')
//                 .attr('data-row', cellData.row)
//                 .append($('<td>').addClass('w-5 row bg-sgcolorSub').text(cellData.row)); // Row number cell
//             table.append(row);
//         }

//         // Ensure the cell exists or create it
//         let cell = row.find(`.cursor-cell[data-column="${cellData.column}"]`);
//         if (cell.length === 0) {
//             // Insert the cell at the correct position based on column index
//             const columnIndex = columnNameToIndex(cellData.column); // Assuming this function exists
//             cell = $('<td>')
//                 .addClass('student-cell cursor-cell border border-cursor')
//                 .attr('contenteditable', 'true')
//                 .attr('data-id', cellData.id)
//                 .attr('data-row', cellData.row)
//                 .attr('data-column', cellData.column)
//                 .attr('data-room-id', cellData.room_id)
//                 .attr('data-room-studentID', cellData.student_id)
//                 .attr('data-room-student', cellData.student_name)
//                 .attr('data-teacher-id', cellData.teacher_id)
//                 .attr('data-original-content', cellData.content);

//             // Insert the cell at the correct position
//             if (columnIndex > 0) {
//                 row.children('td').eq(columnIndex - 1).after(cell);
//             } else {
//                 row.append(cell);
//             }

//             console.log('New cell created:', cell[0]);  // Log the new cell element
//         }

//         // Update cell attributes
//         cell.attr('rowspan', cellData.rowspan);
//         cell.attr('colspan', cellData.colspan);
//         cell.removeAttr('data-merged');
//         cell.html(cellData.content);
//         cell.show();

//         console.log(`Updated cell - Row: ${cellData.row}, Column: ${cellData.column}, Rowspan: ${cellData.rowspan}, Colspan: ${cellData.colspan}, Content: ${cellData.content}`);
//     });
// }













// function mergeSelectedCells() {
//     if (selectedCells.length === 0) return;

//     const rows = selectedCells.map(cell => parseInt($(cell).data('row')));
//     const cols = selectedCells.map(cell => columnNameToIndex($(cell).data('column')));
    
//     const minRow = Math.min(...rows);
//     const maxRow = Math.max(...rows);
//     const minCol = Math.min(...cols);
//     const maxCol = Math.max(...cols);

//     const firstCell = selectedCells[0];
//     const rowspan = maxRow - minRow + 1;
//     const colspan = maxCol - minCol + 1;

//     let mergedContent = $(firstCell).html().trim();
//     const hiddenCells = [];

//     // Prepare the data to be saved for hidden cells
//     selectedCells.forEach((cell, index) => {
//         if (index > 0) {
//             const hiddenCellData = {
//                 row: $(cell).data('row'),
//                 column: $(cell).data('column'),
//                 content: $(cell).html().trim(),
//                 merged: true,
//                 hidden: true,
//                 rowspan: 1,
//                 colspan: 1
//             };
//             hiddenCells.push(hiddenCellData);
//             $(cell).addClass('merged-hidden').hide();
//         }
//         $(cell).removeClass('selected');
//     });

//     // Set attributes for the merged cell
//     $(firstCell).attr({
//         'rowspan': rowspan,
//         'colspan': colspan,
//         'data-merged': true
//     }).html(mergedContent);

//     // Prepare the data to save
//     const cellData = {
//         room_id: $(firstCell).data('room-id'),
//         student_id: $(firstCell).data('room-studentid'),
//         student_name: $(firstCell).data('room-student'),
//         column: $(firstCell).data('column'),
//         row: $(firstCell).data('row'),
//         content: mergedContent,
//         merged: true,
//         rowspan: rowspan,
//         colspan: colspan,
//         hiddenCells: hiddenCells, // Include hidden cells data
//         tableID: $(firstCell).closest('table').attr('id')
//     };

//     // Send data to the server
//     $.ajax({
//         url: `/save-merged-cell`,
//         type: 'POST',
//         contentType: 'application/json',
//         headers: {
//             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
//         },
//         data: JSON.stringify(cellData),
//         success: function(response) {
//             if (response.id) {
//                 $(firstCell).attr('data-id', response.id); // Update the merged cell with the ID from the server
//             }
//             console.log('Merge saved successfully:', response);
//         },
//         error: function(error) {
//             console.error('Error saving cell merge:', error.responseJSON.message);
//         }
//     });

//     selectedCells = [];
// }










// function unmergeSelectedCells() {
//     if (selectedCells.length === 0) return;

//     const firstCell = selectedCells[0];
//     const rowspan = parseInt($(firstCell).attr('rowspan')) || 1;
//     const colspan = parseInt($(firstCell).attr('colspan')) || 1;
//     const startRow = parseInt($(firstCell).data('row'));
//     const startCol = columnNameToIndex($(firstCell).data('column'));

//     // Prepare the request payload
//     const id = $(firstCell).data('id');
//     const payload = {
//         cell_id: id,
//         row: startRow,
//         column: indexToColumnName(startCol),
//         rowspan: rowspan,
//         colspan: colspan,
//         room_id: $(firstCell).data('room-id'),
//         student_id: $(firstCell).data('room-studentid'),
//         tableID: $(firstCell).closest('table').attr('id')
//     };

//     console.log('Payload:', payload);

//     $.ajax({
//         url: `/unmerge-cell/${id}`,
//         type: 'POST',
//         contentType: 'application/json',
//         headers: {
//             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
//         },
//         data: JSON.stringify(payload),
//         success: function(data) {
//             console.log('Unmerge successful:', data);

//             updateMainCell(firstCell);

//             if (data.updatedCells) {
//                 updateCellsAfterUnmerge(data.updatedCells);
//             } else {
//                 console.log('No updated cells received.');
//             }
//         },
//         error: function(error) {
//             console.log('Error during unmerge:', error.responseJSON ? error.responseJSON.message : error);
//         },
//         complete: function() {
//             clearSelection();
//         }
//     });
// }

// function updateMainCell(mainCell) {
//     $(mainCell).attr('rowspan', 1);
//     $(mainCell).attr('colspan', 1);
//     $(mainCell).removeAttr('data-merged');
//     console.log('Main cell updated:', mainCell);
// }

// function updateCellsAfterUnmerge(updatedCells) {
//     updatedCells.forEach(cellData => {
//         const cellSelector = `.cursor-cell[data-row="${cellData.row}"][data-column="${cellData.column}"]`;
//         const cell = $(cellSelector);

//         console.log(cellSelector + 'cellSelector')

//         if (cell.length) {
//             cell.attr('rowspan', cellData.rowspan);
//             cell.attr('colspan', cellData.colspan);
//             cell.removeAttr('data-merged');
//             cell.html(cellData.content);
//             cell.show();

//             console.log(`Updated cell - Row: ${cellData.row}, Column: ${cellData.column}, Rowspan: ${cellData.rowspan}, Colspan: ${cellData.colspan}, Content: ${cellData.content}`);
//         } else {
//             console.log('Cell not found for:', cellData);
//         }
//     });
// }





















// function unmergeSelectedCells() {
//     if (selectedCells.length === 0) return;

//     const firstCell = selectedCells[0];
//     const rowspan = parseInt($(firstCell).attr('rowspan')) || 1;
//     const colspan = parseInt($(firstCell).attr('colspan')) || 1;
//     const startRow = parseInt($(firstCell).data('row'));
//     const startCol = columnNameToIndex($(firstCell).data('column'));

//     const mergedContent = $(firstCell).html().trim();

//     // Unmerge cells and reset attributes
//     for (let row = startRow; row < startRow + rowspan; row++) {
//         for (let col = startCol; col < startCol + colspan; col++) {
//             const cellSelector = `.cursor-cell[data-row=${row}][data-column=${indexToColumnName(col)}]`;
//             const cell = $(cellSelector);

//             if (row === startRow && col === startCol) {
//                 $(firstCell).removeAttr('rowspan colspan data-merged data-hidden');
//                 $(firstCell).show().html(mergedContent);
//             } else {
//                 cell.show();
//                 cell.removeAttr('data-merged data-hidden rowspan colspan');
//                 cell.html(''); // Clear content of the revealed cells
//             }
//         }
//     }

//     // Send the unmerge request
//     const id = $(firstCell).data('id');
//     const payload = {
//         cell_id: id,
//         row: startRow,
//         column: indexToColumnName(startCol),
//         rowspan: rowspan,
//         colspan: colspan,
//         room_id: $(firstCell).data('room-id'),
//         student_id: $(firstCell).data('room-studentid'),
//         tableID: $(firstCell).closest('table').attr('id')
//     };

//     $.ajax({
//         url: `/unmerge-cell/${id}`,
//         type: 'POST',
//         contentType: 'application/json',
//         headers: {
//             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
//         },
//         data: JSON.stringify(payload),
//         success: function(data) {
//             if (data.updatedCells) {
//                 updateCellsAfterUnmerge(data.updatedCells, data.cellAttr);
//             }
//         },
//         error: function(error) {
//             restorePreviousCellState(firstCell, rowspan, colspan);
//         },
//         complete: function() {
//             clearSelection();
//         }
//     });
// }

// function updateCellsAfterUnmerge(updatedCells, cellAttr) {
//     clearSelection();
//     selectedCells = [];

//     updatedCells.forEach(cellData => {
//         const table = document.getElementById(cellAttr.table_id);
//         const row = table.querySelectorAll('tr')[cellData.row]; 

//         let cellSelector = `.cursor-cell[data-row="${cellData.row}"][data-column="${cellData.column}"]`;
//         let cell = row.querySelector(cellSelector);

//         const tableID = table.getAttribute('id');
//         let rowCount = tableID === 'main-table' ? 5 : 2;

//         let operation = cellData.row <= rowCount ? 'data-sum' : 'data-operation';

//         if (!cell) {
//             cell = document.createElement('td');
//             cell.className = 'student-cell cursor-cell border border-cursor';
//             cell.contentEditable = 'true';
//             cell.dataset.id = cellData.id;
//             cell.dataset.row = cellData.row;
//             cell.dataset.column = cellData.column;
//             cell.dataset.roomId = cellAttr.room_id;
//             cell.dataset.roomStudent = cellAttr.data_room_student;
//             cell.dataset.roomStudentid = cellAttr.data_room_studentid;
//             cell.dataset.teacherId = cellAttr.teacher_id;
//             cell.dataset.originalContent = cellData.content || '';
//             cell.setAttribute(operation, 'operation');

//             if (cellData.rowspan && cellData.rowspan > 1) {
//                 cell.setAttribute('rowspan', cellData.rowspan);
//             }

//             if (cellData.colspan && cellData.colspan > 1) {
//                 cell.setAttribute('colspan', cellData.colspan);
//             }

//             let inserted = false;
//             Array.from(row.children).forEach(existingCell => {
//                 const currentColIndex = columnNameToIndex(existingCell.dataset.column);
//                 const newColIndex = columnNameToIndex(cellData.column);

//                 if (newColIndex < currentColIndex && !inserted) {
//                     row.insertBefore(cell, existingCell);
//                     inserted = true;
//                 }
//             });

//             if (!inserted) {
//                 row.appendChild(cell);
//             }
//         }

//         cell.removeAttribute('data-merged data-hidden');
//         cell.setAttribute('rowspan', cellData.rowspan || 1);
//         cell.setAttribute('colspan', cellData.colspan || 1);
//         cell.innerHTML = cellData.content;

//         selectedCells.push(cell);

//     });
    
//     adjustBorders();
// }










































































// import { handleCellBlur } from "@js/handleCellBlur.js";
// // import { column } from "mathjs";

// export let selectedCells = [];

// export function columnNameToIndex(columnName) {
//     if (!columnName) return -1;

//     let index = 0;
//     for (let i = 0; i < columnName.length; i++) {
//         index = index * 26 + (columnName.charCodeAt(i) - 64);
//     }
//     return index - 1;
// }

// export function indexToColumnName(index) {
//     let columnName = '';
//     while (index >= 0) {
//         columnName = String.fromCharCode((index % 26) + 65) + columnName;
//         index = Math.floor(index / 26) - 1;
//     }
//     return columnName;
// }

// // MANG ENCAPSULATE BWAS CODE OKIIIIIIIIIIIIIIIIIISSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSS??????????????????????????
// document.addEventListener('DOMContentLoaded', function() {

// let longPressTimer;
// let isDragging = false;
// let startCell = null;
// let initialCell = null;

// $(document).ready(function() {
//     loadTableState();

//     $('.cursor-cell').off('mousedown').on('mousedown', function(event) {
//         startCell = this;
//         longPressTimer = setTimeout(() => {
//             isDragging = true;
//             addCellToSelection(this);
//         }, 500); // Long press time threshold in ms
//     });

//     $(document).off('mousemove').on('mousemove', function(event) {
//         if (isDragging) {
//             let target = $(event.target).closest('.cursor-cell')[0];
//             if (target && target !== startCell) {
//                 selectRange(startCell, target);
//             }
//         }
//     });

//     $(document).off('mouseup').on('mouseup', function(event) {
//         clearTimeout(longPressTimer);
//         if (isDragging) {
//             isDragging = false;
//             startCell = null;
//         }
//     });

//     $('.cursor-cell').off('click').on('click', function(event) {
//         if (event.shiftKey) {
//             addCellToSelection2(this);
//         } else {
//             selectSingleCell(this); // Select single cell and set it as the initial cell
//             initialCell = this;
//         }
//     });
    
//     $('#mergeButton').off('click').on('click', mergeSelectedCells);
//     // $('#unmergeButton').off('click').on('click', unmergeSelectedCells);

// });

// // ANG ULOBRAHON MO BWAS IS DAPAT MA SAVE NI NGA MGA BORDERS SERVER SIDE KAY PARA MANGIN PERSISTENT OKIIIIIIIIIIIIISSSSSSSSSSSSSSSSSSSSSS?????????????
// // Array to keep track of permanently bordered cells !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
// let borderedCells = [];

// // Function to apply borders to selected cells
// function applyBorder() {
//     selectedCells.forEach(cell => {
//         // Add a CSS class to the cell to apply the border
//         cell.classList.add('bordered');
//         // Add the cell to the list of bordered cells
//         if (!borderedCells.includes(cell)) {
//             borderedCells.push(cell);
//         }
//     });

//     // Save the bordered cells to the backend
//     // saveBorderedCells();
// }

// // Event listener for the "Apply Border" button
// document.getElementById('apply-border-btn').addEventListener('click', applyBorder);

// // !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!1
// function selectSingleCell(cell) {
//     clearSelection(); // Clear any previous selections
//     $(cell).addClass('selected');
//     selectedCells = [cell]; // Update the selectedCells array to contain only this cell
//     initialCell = cell; // Set the initial cell to the currently selected cell
//     adjustBorders(); // Adjust the borders to reflect the current selection
// }

// function addCellToSelection(cell) {
//     if (selectedCells.length === 0) {
//         selectSingleCell(cell);
//     } else {
//         if (!selectedCells.includes(cell)) {
//             $(cell).addClass('selected');
//             selectedCells.push(cell);
//         }
//         adjustBorders();
//     }
// }
// function addCellToSelection2(cell) {
//     // Ensure the cell has the correct attributes
//     if (!cell || !cell.dataset || !cell.dataset.row || !cell.dataset.column) {
//         console.error('Cell is missing required attributes.');
//         return;
//     }

//     if (!initialCell) {
//         // If no initial cell is set, use the current cell as the initial cell
//         initialCell = cell;
//         selectSingleCell(cell);
//     } else {
//         // Use selectRange to select from the initial cell to the current cell
//         selectRange(initialCell, cell);
//         initialCell = cell; // Update initialCell to the current cell
//     }

//     adjustBorders(); // Adjust borders after modifying the selection
// }


// function selectRange(start, end) {
//     clearSelection(); // Clear any previous selections

//     const startRow = parseInt(start.dataset.row);
//     const startCol = columnNameToIndex(start.dataset.column);
//     const endRow = parseInt(end.dataset.row);
//     const endCol = columnNameToIndex(end.dataset.column);

//     if (startCol === -1 || endCol === -1 || (startRow === endRow && endCol < startCol)) {
//         return;
//     }

//     let minRow = Math.min(startRow, endRow);
//     let maxRow = Math.max(startRow, endRow);
//     let minCol = Math.min(startCol, endCol);
//     let maxCol = Math.max(startCol, endCol);

//     // Adjust boundaries to include full merged cells within the range
//     for (let row = minRow; row <= maxRow; row++) {
//         for (let col = minCol; col <= maxCol; col++) {
//             const cell = document.querySelector(`.cursor-cell[data-row="${row}"][data-column="${indexToColumnName(col)}"]`);
//             if (cell) {
//                 const rowspan = parseInt(cell.getAttribute('rowspan')) || 1;
//                 const colspan = parseInt(cell.getAttribute('colspan')) || 1;

//                 maxRow = Math.max(maxRow, row + rowspan - 1);
//                 maxCol = Math.max(maxCol, col + colspan - 1);
//             }
//         }
//     }

//     for (let row = minRow; row <= maxRow; row++) {
//         for (let col = minCol; col <= maxCol; col++) {
//             const cell = document.querySelector(`.cursor-cell[data-row="${row}"][data-column="${indexToColumnName(col)}"]`);
//             if (cell && !selectedCells.includes(cell)) {
//                 cell.classList.add('selected');
//                 selectedCells.push(cell);
//             }
//         }
//     }

//     adjustBorders(); // Adjust borders based on the new selection
// }


// function clearSelection() {
//     if (selectedCells.length > 0) {
//         for (const cell of selectedCells) {
//             cell.classList.remove('selected', 'selected2', 'not-top', 'not-bottom', 'not-left', 'not-right', 'top-border', 'bottom-border', 'left-border', 'right-border', 'added');
//         }
//         selectedCells = [];
//     }
// }

// function adjustBorders() {
//     if (selectedCells.length === 0) return;

//     // Initialize arrays to hold the min and max positions
//     const rows = [];
//     const cols = [];

//     // Loop through selected cells and calculate the range considering merged cells
//     for (const cell of selectedCells) {
//         const row = parseInt(cell.dataset.row);
//         const col = columnNameToIndex(cell.dataset.column);
//         const rowspan = parseInt(cell.getAttribute('rowspan')) || 1;
//         const colspan = parseInt(cell.getAttribute('colspan')) || 1;

//         // Add all the rows and columns covered by the merged cells to the arrays
//         for (let r = row; r < row + rowspan; r++) {
//             rows.push(r);
//         }
//         for (let c = col; c < col + colspan; c++) {
//             cols.push(c);
//         }
//     }

//     // Determine the boundaries of the selection
//     const minRow = Math.min(...rows);
//     const maxRow = Math.max(...rows);
//     const minCol = Math.min(...cols);
//     const maxCol = Math.max(...cols);

//     // Loop through selected cells and adjust borders
//     for (const cell of selectedCells) {
//         const row = parseInt(cell.dataset.row);
//         const col = columnNameToIndex(cell.dataset.column);
//         const rowspan = parseInt(cell.getAttribute('rowspan')) || 1;
//         const colspan = parseInt(cell.getAttribute('colspan')) || 1;

//         // Determine if the cell is at the edges of the selection
//         const isTop = row === minRow;
//         const isBottom = row + rowspan - 1 === maxRow;
//         const isLeft = col === minCol;
//         const isRight = col + colspan - 1 === maxCol;

//         // Apply classes to set borders based on the position within the selected range
//         cell.classList.toggle('top-border', isTop);
//         cell.classList.toggle('bottom-border', isBottom);
//         cell.classList.toggle('left-border', isLeft);
//         cell.classList.toggle('right-border', isRight);

//         // Apply classes for internal borders to hide overlapping borders
//         cell.classList.toggle('not-top', !isTop);
//         cell.classList.toggle('not-bottom', !isBottom);
//         cell.classList.toggle('not-left', !isLeft);
//         cell.classList.toggle('not-right', !isRight);
//     }
// }



// // Mag MERGE KA ULIT SA NA UNMERGE WALA GA GANA OR GA UPDATE ANG MAIN CELLS, MO NI KALAYUHON MO BWASSSSSSSSSSSSSSSS!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
// async function mergeSelectedCells() {
//     if (selectedCells.length === 0) return;

//     // const hasContent = selectedCells.some(cell => cell.innerHTML.trim() !== ' ');
//     // if (hasContent) {
//     //     alert("Cannot merge. Some of the selected cells already contain content.");
//     //     return;
//     // }

//     const rows = selectedCells.map(cell => parseInt(cell.dataset.row));
//     const cols = selectedCells.map(cell => columnNameToIndex(cell.dataset.column));

//     const minRow = Math.min(...rows);
//     const maxRow = Math.max(...rows);
//     const minCol = Math.min(...cols);
//     const maxCol = Math.max(...cols);

//     const firstCell = selectedCells[0];
//     const rowspan = maxRow - minRow + 1;
//     const colspan = maxCol - minCol + 1;

//     let mergedContent = firstCell.innerHTML.trim();
//     const overlappedCells = [];

//     selectedCells.forEach((cell, index) => {
//         if (index > 0) {
//             const overlappedCellData = {
//                 row: cell.dataset.row,
//                 column: cell.dataset.column,
//                 content: cell.innerHTML.trim(),
//                 merged: true,
//                 hidden: true,
//                 rowspan: 1,
//                 colspan: 1
//             };
//             overlappedCells.push(overlappedCellData);

//             cell.innerHTML = '';
//             cell.dataset.merged = 'true';
//             cell.dataset.hidden = 'true';
//             cell.style.display = 'none';
//         }
//         cell.classList.remove('selected');
//     });

//     // Update the main cell attributes correctly
//     firstCell.setAttribute('rowspan', rowspan);
//     firstCell.setAttribute('colspan', colspan);
//     firstCell.dataset.merged = 'true';
//     firstCell.innerHTML = mergedContent;

//     const cellData = {
//         room_id: firstCell.dataset.roomId,
//         student_id: firstCell.dataset.roomStudentid,
//         student_name: firstCell.dataset.roomStudent,
//         column: firstCell.dataset.column,
//         row: firstCell.dataset.row,
//         content: mergedContent,
//         merged: true,
//         rowspan: rowspan,
//         colspan: colspan,
//         hiddenCells: overlappedCells,
//         tableID: firstCell.closest('table').id
//     };

//     console.log('how');

//     try {
//         const response = await fetch('/save-merged-cell', {
//             method: 'POST',
//             headers: {
//                 'Content-Type': 'application/json',
//                 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
//             },
//             body: JSON.stringify(cellData)
//         });

//         const result = await response.json();

//         if (result.id) {
//             firstCell.dataset.id = result.id;
//         }
//         console.log(response)
//     } catch (error) {
//         console.error('Error saving cell merge:', error.message);
//     }

//     selectedCells = [];
// }


// $(document).ready(function() {
//     // Ensure all merged cells with no content have a visible character
//     $('td[data-merged="true"]').each(function() {
//         if (!$(this).text().trim()) {
//             $(this).text('\u200B'); // Zero-width space (invisible but prevents cell from disappearing)
//         }
//     });
// });



// function loadTableState() {
//     $.ajax({
//         url: '/load-table-state',
//         method: 'GET',
//         success: function(data) {
//             if (!Array.isArray(data)) {
//                 console.error('Invalid data format for fetchTableState:', data);
//                 return;
//             }
//             console.log('Loaded table state (1):', data);
            
//             updateTableState(data);

//             // Trigger adjust borders to ensure restored cells have correct borders
//             adjustBorders();
//         },
//         error: function(xhr, status, error) {
//             console.error('Error loading table state:', error);
//         }
//     });
// }



// function updateTableState(data) {
//     data.forEach(cell => {
//         const selector = `.cursor-cell[data-row=${cell.row}][data-column=${cell.column}]`;
//         const cellElement = $(selector);
//         cellElement.html(cell.content);
//         cellElement.attr('rowspan', cell.rowspan);
//         cellElement.attr('colspan', cell.colspan);
        
//         if (cell.merged) {
//             cellElement.attr('data-merged', true);
//             cellElement.hide();
//         } else {
//             cellElement.removeAttr('data-merged');
//             cellElement.show();
//         }

//         // Apply selection class if necessary
//         if (cell.selected) {
//             cellElement.addClass('selected');
//             selectedCells.push(cellElement[0]);  // Add to selectedCells array
//         }
//     });

//     // Reapply borders after restoring the selection
//     adjustBorders();
// }

// document.getElementById('unmergeButton').addEventListener('click', async function() {
//     // Show the loading message
//     const loadingMessage = document.querySelector('.loadingMessage');
//     // loadingMessage.style.display = 'flex';
//     loadingMessage.classList.remove('hidden');

//     try {
//         // Call your unmerge function
//         await unmergeSelectedCells();
//     } catch (error) {
//         console.error('Error during unmerge:', error);
//     } finally {
//         // Hide the loading message
//         // loadingMessage.style.display = 'none';
//         loadingMessage.classList.add('hidden');
//     }
// });


// async function unmergeSelectedCells() {
//     if (selectedCells.length === 0) return;
    
//     const firstCell = selectedCells[0];
//     const rowspan = parseInt(firstCell.getAttribute('rowspan')) || 1;
//     const colspan = parseInt(firstCell.getAttribute('colspan')) || 1;
//     const startRow = parseInt(firstCell.dataset.row);
//     const startCol = columnNameToIndex(firstCell.dataset.column);

//     const mergedContent = firstCell.innerHTML.trim();

//     // Unmerge cells and reset attributes
//     for (let row = startRow; row < startRow + rowspan; row++) {
//         for (let col = startCol; col < startCol + colspan; col++) {
//             const cellSelector = `.cursor-cell[data-row="${row}"][data-column="${indexToColumnName(col)}"]`;
//             const cell = document.querySelector(cellSelector);

//             if (row === startRow && col === startCol) {
//                 firstCell.removeAttribute('rowspan');
//                 firstCell.removeAttribute('colspan');
//                 firstCell.removeAttribute('data-merged');
//                 firstCell.removeAttribute('data-hidden');
//                 firstCell.style.display = '';
//                 firstCell.innerHTML = mergedContent;
//             } else {
//                 if (cell) {
//                     cell.style.display = '';
//                     cell.innerHTML = '';
//                     cell.removeAttribute('data-merged');
//                     cell.removeAttribute('data-hidden');
//                     cell.removeAttribute('rowspan');
//                     cell.removeAttribute('colspan');
//                 }
//             }
//         }
//     }

//     // Send the unmerge request
//     const id = firstCell.dataset.id;
//     const payload = {
//         cell_id: id,
//         row: startRow,
//         column: indexToColumnName(startCol),
//         rowspan,
//         colspan,
//         room_id: firstCell.dataset.roomId,
//         student_id: firstCell.dataset.roomStudentid,
//         tableID: firstCell.closest('table').id
//     };

//     console.log('HowmanyTimes')

//     try {
//         const response = await fetch(`/unmerge-cell/${id}`, {
//             method: 'POST',
//             headers: {
//                 'Content-Type': 'application/json',
//                 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
//             },
//             body: JSON.stringify(payload)
//         });
        
//         const data = await response.json();
//         if (data.updatedCells) {
//             updateCellsAfterUnmerge(data.updatedCells, data.cellAttr);
//         }
//     } catch (error) {
//         restorePreviousCellState(firstCell, rowspan, colspan);
//     } finally {
//         // Remove the loading message
//         const loadingMessage = document.getElementById('loadingMessage');
//         if (loadingMessage) {
//             loadingMessage.remove();
//         }
//         clearSelection();
//     }
// }



// function updateCellsAfterUnmerge(updatedCells, cellAttr) {
//     clearSelection(); // Clear previous selections
//     selectedCells = [];

//     updatedCells.forEach(cellData => {
//         const table = document.getElementById(cellAttr.table_id);
//         const row = table.querySelectorAll('tr')[cellData.row];
//         let cellSelector = `.cursor-cell[data-row="${cellData.row}"][data-column="${cellData.column}"]`;
//         let cell = row.querySelector(cellSelector);

//         if (!cell) {
//             cell = createNewCell(cellData, row, cellAttr);
//         }
//         //  else {
//         //     // Update the existing cell properties
//         //     restoreOriginalState(cell, cellData, cellAttr);
//         // }
        
//         // Update the cell properties
//         cell.removeAttribute('data-merged data-hidden');
//         cell.setAttribute('rowspan', cellData.rowspan || 1);
//         cell.setAttribute('colspan', cellData.colspan || 1);
//         cell.innerHTML = cellData.content || '';

//         // Add the cell to the selectedCells array
//         selectedCells.push(cell);

//     });
//     // Reattach event listeners to the updated cells
//     reattachEventListeners();

//     adjustBorders(); // Adjust borders after selection
// }

// function reattachEventListeners() {
//     // Reattach mousedown event
//     $('.cursor-cell').off('mousedown').on('mousedown', function(event) {
//         startCell = this;
//         longPressTimer = setTimeout(() => {
//             isDragging = true;
//             addCellToSelection(this);
//         }, 500); // Long press time threshold in ms
//     });

//     // Reattach click event
//     $('.cursor-cell').off('click').on('click', function(event) {
//         if (event.shiftKey) {
//             addCellToSelection2(this);
//         } else {
//             selectSingleCell(this); // Select single cell and set it as the initial cell
//             initialCell = this;
//         }
//     });

//     // Reattach mousemove event
//     $(document).off('mousemove').on('mousemove', function(event) {
//         if (isDragging) {
//             let target = $(event.target).closest('.cursor-cell')[0];
//             if (target && target !== startCell) {
//                 selectRange(startCell, target);
//             }
//         }
//     });

//     // Reattach mouseup event
//     $(document).off('mouseup').on('mouseup', function(event) {
//         clearTimeout(longPressTimer);
//         if (isDragging) {
//             isDragging = false;
//             startCell = null;
//         }
//     });

//     // Reattach other event listeners if needed
//     $('#mergeButton').off('click').on('click', mergeSelectedCells);
//     $('#unmergeButton').off('click').on('click', unmergeSelectedCells);
// }

// // function restoreOriginalState(cell, cellData, cellAttr) {
// //     // Remove merged-related attributes
// //     cell.removeAttribute('data-merged');
// //     cell.removeAttribute('data-hidden');
    
// //     // Restore attributes
// //     cell.setAttribute('rowspan', cellData.rowspan || 1);
// //     cell.setAttribute('colspan', cellData.colspan || 1);
// //     cell.innerHTML = cellData.content || '';

// //     // Reattach event listeners
// //     reattachEventListeners(cell, cellAttr);

// // }


// function createNewCell(cellData, row, cellAttr) {
//     const cell = document.createElement('td');
//     cell.id = 'cell';
//     cell.className = 'student-cell cursor-cell border border-cursor';
//     cell.contentEditable = 'true';
//     cell.dataset.id = cellData.id || "";
//     cell.dataset.row = cellData.row;
//     cell.dataset.column = cellData.column;
//     cell.dataset.roomId = cellAttr.room_id;
//     cell.dataset.roomStudent = cellAttr.data_room_student;
//     cell.dataset.roomStudentid = cellAttr.data_room_studentid;
//     cell.dataset.teacherId = cellAttr.teacher_id;
//     cell.dataset.originalContent = cellData.content || '';

//     if (cellData.rowspan && cellData.rowspan > 1) {
//         cell.setAttribute('rowspan', cellData.rowspan);
//     }

//     if (cellData.colspan && cellData.colspan > 1) {
//         cell.setAttribute('colspan', cellData.colspan);
//     }

//     cell.onblur = (event) => {
//         handleCellBlur(event, cellAttr.table_id, cellData.content || '');
//     };

//     insertCellInRow(row, cell, cellData.column);
//     return cell;
// }

// function insertCellInRow(row, cell, newColumn) {
//     let inserted = false;
//     Array.from(row.children).forEach(existingCell => {
//         const currentColIndex = columnNameToIndex(existingCell.dataset.column);
//         const newColIndex = columnNameToIndex(newColumn);

//         if (newColIndex < currentColIndex && !inserted) {
//             row.insertBefore(cell, existingCell);
//             inserted = true;
//         }
//     });

//     if (!inserted) {
//         row.appendChild(cell);
//     }
// }

// function restorePreviousCellState(mainCell, previousRowspan, previousColspan) {
//     $(mainCell).attr('rowspan', previousRowspan);
//     $(mainCell).attr('colspan', previousColspan);
//     $(mainCell).attr('data-merged', true);
//     console.log('Main cell restored:', mainCell);
// }



// // const newCell = cell.cloneNode(true); 
// // cell.replaceWith(newCell); 
// // applyEventListeners(newCell); 

// function applyEventListeners(cell) {

//     cell.addEventListener('blur', (event) => {
//         const tableId = cell.closest('table').id; // Assuming the table's ID is needed
//         handleCellBlur(event, tableId);
//     });

//     $(cell).on('mousedown', function(event) {
//         startCell = this;
//         longPressTimer = setTimeout(() => {
//             isDragging = true;
//             addCellToSelection(this);
//         }, 500); // Long press time threshold in ms
//     });

//     $(cell).on('click', function(event) {
//         if (event.shiftKey) {
//             addCellToSelection2(this);
//         } else {
//             selectSingleCell(this); // Select single cell and set it as the initial cell
//             initialCell = this;
//         }
//     });

// }






// });

















// // CARET
// document.addEventListener('DOMContentLoaded', (event) => {
//     const editableCells = document.querySelectorAll('.cursor-cell');

//     editableCells.forEach(cell => {
//         cell.addEventListener('focus', () => {
//             cell.classList.remove('typing');
//         });

//         cell.addEventListener('blur', () => {
//             if(cell.textContent.trim() === '') {
//                 cell.classList.remove('typing');
//             }
//         });

//         cell.addEventListener('input', () => {
//             cell.classList.add('typing');
//         });
//     });
// });





















































































// let content;
// let isAdding = false;

// // Wait for DOM content to load
// document.addEventListener('DOMContentLoaded', () => {
//     let selectedCells = [];
//     const formulaInput = document.querySelector('input[name="formulaInput"]');
//     let lastSelectedCell = '';

//     document.querySelectorAll('td').forEach(cell => {
//         cell.addEventListener('input', () => {
//             content = cell.textContent.trim();

//             if (content === '=') {
//                 const colIndex = Array.from(cell.parentNode.children).indexOf(cell);
//                 cell.dataset.psCell = 'true';
//                 cell.classList.add('blurred');
//                 document.getElementById('formulaBox').classList.remove('hidden');
//                 document.getElementById('formulaTitle').classList.add('hidden');

//                 document.querySelectorAll('tr').forEach(row => {
//                     const totalCell = row.children[colIndex];
//                     if (totalCell !== cell) {
//                         totalCell.dataset.psCell = 'true';
//                     }
//                 });

//                 document.querySelectorAll('td').forEach(selectedCell => {
//                     selectedCell.addEventListener('click', event => {
//                         if (cell.dataset.psCell === 'true') {
//                             const cellReference = selectedCell.dataset.column + (Array.from(selectedCell.closest('tr').children).indexOf(selectedCell) + 1);
                            
//                             if (!isAdding) {
//                                 formulaInput.value = cellReference;
//                                 lastSelectedCell = cellReference;
//                             } else {
//                                 let currentFormula = formulaInput.value;
//                                 const regex = /[+\-*/()]/;
//                                 const lastChar = currentFormula.slice(-1);
//                                 if (regex.test(lastChar)) {
//                                     formulaInput.value = currentFormula + cellReference;
//                                     selectedCells.push(cellReference);
//                                 } else {
//                                     formulaInput.value = currentFormula.replace(lastSelectedCell, cellReference);
//                                 }
//                                 lastSelectedCell = cellReference;
//                             }

//                             // Focus the input field and move cursor to the end
//                             setTimeout(() => {
//                                 formulaInput.focus();
//                                 formulaInput.setSelectionRange(formulaInput.value.length, formulaInput.value.length);
//                             }, 0);
//                         }
//                     });
//                 });

//                 formulaInput.addEventListener('input', () => {
//                     const regex = /[+\-*/()]/;
//                     const formu = formulaInput.value;
//                     isAdding = regex.test(formu);
//                 });

//                 document.addEventListener('keypress', event => {
//                     if (event.key === 'Enter') {
//                         event.preventDefault();
//                         selectedCells = [];
//                         formulaInput.value = '';
//                     }
//                 });
//             } else {
//                 document.querySelectorAll('tr td').forEach(td => td.removeAttribute('data-ps-cell'));
//                 cell.classList.remove('blurred');
//                 document.getElementById('formulaBox').classList.add('hidden');
//                 document.getElementById('formulaTitle').classList.remove('hidden');
//                 formulaInput.value = '';
//                 selectedCells = [];
//                 isAdding = false;
//             }
//         });
//     });

//     document.addEventListener('keydown', event => {
//         if (event.key === 'Enter') {
//             const dot = document.querySelectorAll('td[data-ps-cell="true"]');
//             if (dot.length > 1) {
//                 const formula = document.querySelector('input[name="formulaInput"]').value.trim();
//                 performPercentage(formula);
//             }
//         }
//     });
// });