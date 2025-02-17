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

//     // Apply the current selections to all rows with the data-operation attribute
//     applySelectedToAllRows(selectedCells);

//     // Get the newly selected cells after propagation
//     selectedCells = $('.selected');

//     // Calculate the result based on the currently selected cells
//     applyResultToAllRows(operation, selectedCells);

//     // Clear all selections to allow the user to select new cells for the next operation
//     clearSelections();
// }

// let lastSelectedColumnIndex = -1;

// function applySelectedToAllRows(selectedCells) {
//     // Clear previous selections
//     $('td.selected').removeClass('selected');

//     // Apply new selections based on user input
//     selectedCells.each(function () {
//         let cellIndex = $(this).index();
//         lastSelectedColumnIndex = cellIndex; // Track the last selected column index

//         // Add the .selected class to corresponding cells in rows with data-operation attribute
//         $('tr').each(function () {
//             let cell = $(this).find('td').eq(cellIndex);
//             if (cell.attr('data-operation')) {
//                 cell.addClass('selected');
//             }
//         });
//     });
// }

// function applyResultToAllRows(operation, selectedCells) {
//     // Process only the rows that have selected cells
//     let rowsProcessed = new Set();
//     selectedCells.each(function () {
//         let row = $(this).closest('tr');
//         if (!rowsProcessed.has(row[0])) {
//             processRow(row, operation);
//             rowsProcessed.add(row[0]);
//         }
//     });
// }

// function processRow(row, operation) {
//     // Only process rows that have cells with the data-operation attribute
//     let cells = row.find('td[data-operation]');
//     if (cells.length === 0) {
//         return;
//     }

//     // Find selected cells within this row
//     let selectedCells = row.find('td.selected');
//     if (selectedCells.length < 2) {
//         return;
//     }

//     let rowResult = 0;
//     selectedCells.each(function (index) {
//         let cell = $(this);
//         let cellValue = parseFloat(cell.text()) || 0;
//         if (isNaN(cellValue)) {
//             return;
//         }

//         switch (operation) {
//             case 'add':
//                 rowResult = (index === 0) ? cellValue : rowResult + cellValue;
//                 break;
//             case 'subtract':
//                 rowResult = (index === 0) ? cellValue : rowResult - cellValue;
//                 break;
//             case 'divide':
//                 rowResult = (index === 0) ? cellValue : rowResult / cellValue;
//                 break;
//             case 'multiply':
//                 rowResult = (index === 0) ? cellValue : rowResult * cellValue;
//                 break;
//         }
//     });

//     // Set the result in the last selected column for the current row
//     $(selectedCells[selectedCells.length - 1]).text(rowResult);


//     // Save the result to the database
//     let dataPerform = {
//         room_id: $(selectedCells[0]).attr('data-room-id'),
//         student_id: $(selectedCells[0]).attr('data-room-studentid'),
//         student_name: $(selectedCells[0]).attr('data-room-student'),
//         room_studentID: $(selectedCells[0]).attr('data-room-studentID'),
//         column: $(selectedCells[selectedCells.length - 1]).attr('data-column'),
//         row: row.index(),
//         content: rowResult,
//         rowspan: $(selectedCells[0]).attr('rowspan'),
//         colspan: $(selectedCells[0]).attr('colspan')
//     };

//     console.log(dataPerform)

//     saveResultToDatabase(dataPerform);
// }

// function saveResultToDatabase(dataPerform) {
//     $.ajax({
//         url: '/create-skills',
//         type: 'POST',
//         contentType: 'application/json',
//         headers: {
//             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
//         },
//         data: JSON.stringify(dataPerform),
//         success: function(response) {
//             console.log(response.message);
//         },
//         error: function(error) {
//             console.log('Error saving result:', error.responseJSON.message);
//         }
//     });
// }

// function clearSelections() {
//     // Clear all selected cells to reset for the next operation
//     $('td.selected').removeClass('selected');
// }
































































// document.addEventListener('DOMContentLoaded', function() {
//     const mainTableHandler = new TableHandler('main-table');
//     const attendanceTableHandler = new TableHandler('attendance-table');

//     mainTableHandler.init();
//     attendanceTableHandler.init();

//     loadFromIndexedDB(); // Load data from IndexedDB

//     let isDataUnsaved = false;

//     // Set the flag when data is modified
//     document.querySelectorAll('td[contenteditable=true]').forEach(cell => {
//         cell.addEventListener('input', function() {
//             isDataUnsaved = true;
//         });
//     });

//     // Show warning if there are unsaved changes
//     window.addEventListener('beforeunload', function(e) {
//         if (isDataUnsaved) {
//             const confirmationMessage = 'You have unsaved changes. Do you really want to leave?';
//             // e.returnValue = confirmationMessage;
//             return confirmationMessage;
//         }
//     });

//     // Save data when the "Save" button is clicked
//     document.getElementById('saveButton').addEventListener('click', function() {
//         saveData();
//     });

//     // Optionally save data before the page unloads
//     window.addEventListener('unload', function() {
//         if (isDataUnsaved) {
//             saveToServer().then(() => {
//                 clearIndexedDB(); // Only clear IndexedDB after saving to server
//             }).catch(error => console.error('Error saving data to server:', error));
//         }
//     });

//     // Function to save data to the server
//     function saveData() {
//         saveToServer().then(() => {
//             isDataUnsaved = false; // Reset the flag after saving
//             alert('Data saved successfully!');
//         }).catch(error => console.error('Error saving data to server:', error));
//     }
// });

// class TableHandler {
//     constructor(tableId) {
//         this.tableId = tableId;
//     }

//     init() {
//         document.querySelectorAll(`#${this.tableId} td[contenteditable=true]`).forEach(cell => {
//             cell.addEventListener('blur', this.handleCellBlur.bind(this));
//             cell.addEventListener('keydown', this.handleCellNavigation.bind(this));
//         });
//     }

//     handleCellBlur(event) {
//         let cell = event.target;
//         let row = cell.getAttribute('data-row');
//         let column = cell.getAttribute('data-column');
//         let content = cell.textContent.trim();
//         let tableId = this.tableId;
    
//         // Construct a unique ID based on the table, row, and column
//         let uniqueId = `${tableId}_${row}_${column}`;
    
//         // Other attributes
//         let room_id = cell.getAttribute('data-room-id');
//         let student_name = cell.getAttribute('data-room-student');
//         let room_studentID = cell.getAttribute('data-room-studentID');
//         let rowspan = cell.getAttribute('rowspan');
//         let colspan = cell.getAttribute('colspan');
//         let merged = colspan > 1;
    
//         if (content.length > 0) {
//             let payload = {
//                 id: uniqueId,  // Use uniqueId here
//                 content: content,
//                 room_id: room_id,
//                 room_studentID: room_studentID,
//                 student_name: student_name,
//                 merged: merged,
//                 rowspan: rowspan,
//                 colspan: colspan,
//                 tableId: tableId,
//                 row: row,
//                 column: column
//             };
    
//             storeInIndexedDB(payload);
//         } else {
//             deleteFromIndexedDB(uniqueId);
//         }
//     }
    

//     handleCellNavigation(event) {
//         this.navigateWithArrowKeys(event);
//     }

//     navigateWithArrowKeys(event) {
//         let cell = event.target;
//         let row = parseInt(cell.getAttribute('data-row'));
//         let column = cell.getAttribute('data-column').charCodeAt(0);
//         let nextCell;

//         switch (event.key) {
//             case 'ArrowUp':
//                 do {
//                     row--;
//                     nextCell = document.querySelector(`td[data-row="${row}"][data-column="${String.fromCharCode(column)}"]`);
//                 } while (row > 0 && (!nextCell || nextCell.getAttribute('data-merged') === 'true'));
//                 break;
//             case 'ArrowDown':
//                 do {
//                     row++;
//                     nextCell = document.querySelector(`td[data-row="${row}"][data-column="${String.fromCharCode(column)}"]`);
//                 } while (row <= 26 && (!nextCell || nextCell.getAttribute('data-merged') === 'true'));
//                 break;
//             case 'ArrowLeft':
//                 do {
//                     column--;
//                     nextCell = document.querySelector(`td[data-row="${row}"][data-column="${String.fromCharCode(column)}"]`);
//                 } while (column >= 65 && (!nextCell || nextCell.getAttribute('data-merged') === 'true'));
//                 break;
//             case 'ArrowRight':
//                 do {
//                     column++;
//                     nextCell = document.querySelector(`td[data-row="${row}"][data-column="${String.fromCharCode(column)}"]`);
//                 } while (column <= 90 && (!nextCell || nextCell.getAttribute('data-merged') === 'true'));
//                 break;
//             case 'Enter':
//                 do {
//                     row++;
//                     nextCell = document.querySelector(`td[data-row="${row}"][data-column="${String.fromCharCode(column)}"]`);
//                 } while (row <= 26 && (!nextCell || nextCell.getAttribute('data-merged') === 'true'));
//                 break;
//         }

//         if (nextCell) {
//             event.preventDefault();
//             nextCell.focus();
//         }
//     }
// }

// // IndexedDB Utility Functions
// function openIndexedDB() {
//     return new Promise((resolve, reject) => {
//         let request = indexedDB.open('StudentDataDB', 1);

//         request.onupgradeneeded = function(event) {
//             let db = event.target.result;
//             db.createObjectStore('data', { keyPath: 'id', autoIncrement: true });
//         };

//         request.onsuccess = function(event) {
//             resolve(event.target.result);
//         };

//         request.onerror = function(event) {
//             reject('Error opening IndexedDB:', event.target.errorCode);
//         };
//     });
// }

// function storeInIndexedDB(data) {
//     openIndexedDB().then(db => {
//         let transaction = db.transaction(['data'], 'readwrite');
//         let objectStore = transaction.objectStore('data');
//         objectStore.put(data);  // The `put` method will update or add the data
//     }).catch(error => console.error('Error storing data in IndexedDB:', error));
// }

// function deleteFromIndexedDB(id) {
//     openIndexedDB().then(db => {
//         let transaction = db.transaction(['data'], 'readwrite');
//         let objectStore = transaction.objectStore('data');
//         objectStore.delete(Number(id));
//     }).catch(error => console.error('Error deleting data from IndexedDB:', error));
// }

// function loadFromIndexedDB() {
//     openIndexedDB().then(db => {
//         let transaction = db.transaction(['data'], 'readonly');
//         let objectStore = transaction.objectStore('data');
//         let request = objectStore.getAll();

//         request.onsuccess = function(event) {
//             let allData = event.target.result;
//             allData.forEach(data => {
//                 let cell = document.querySelector(`td[data-row="${data.row}"][data-column="${data.column}"]`);
//                 if (cell) {
//                     cell.textContent = data.content;
//                     cell.setAttribute('data-id', data.id);
//                 }
//             });
//         };
//     }).catch(error => console.error('Error loading data from IndexedDB:', error));
// }

// function saveToServer() {
//     return openIndexedDB().then(db => {
//         return new Promise((resolve, reject) => {
//             let transaction = db.transaction(['data'], 'readonly');
//             let objectStore = transaction.objectStore('data');
//             let request = objectStore.getAll();

//             request.onsuccess = function(event) {
//                 let allData = event.target.result;

//                 if (allData.length > 0) {
//                     fetch('/save-all-data', {
//                         method: 'POST',
//                         headers: {
//                             'Content-Type': 'application/json',
//                             'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
//                         },
//                         body: JSON.stringify(allData)
//                     })
//                     .then(response => response.json())
//                     .then(data => {
//                         if (data.success) {
//                             clearIndexedDB(); // Clear IndexedDB after successful save
//                             resolve();
//                         } else {
//                             reject('Server responded with an error.');
//                         }
//                     })
//                     .catch(error => reject(error));
//                 } else {
//                     resolve(); // No data to save
//                 }
//             };

//             request.onerror = function(event) {
//                 reject('Error reading data from IndexedDB:', event.target.errorCode);
//             };
//         });
//     }).catch(error => Promise.reject('Error opening IndexedDB:', error));
// }


// function clearIndexedDB() {
//     return openIndexedDB().then(db => {
//         return new Promise((resolve, reject) => {
//             let transaction = db.transaction(['data'], 'readwrite');
//             let objectStore = transaction.objectStore('data');
//             let request = objectStore.clear();

//             request.onsuccess = function() {
//                 resolve();
//             };

//             request.onerror = function(event) {
//                 reject('Error clearing IndexedDB:', event.target.errorCode);
//             };
//         });
//     });
// }















































// let maxTableWidth = document.querySelector('.main-table').scrollWidth; // Maximum width of the table
// let maxTableHeight = document.querySelector('.main-table').scrollHeight; // Maximum width of the table
// let maxZoomOutWidth = window.innerWidth / maxTableWidth; // Maximum zoom out level
// let maxZoomOutHeight = window.innerHeight / maxTableHeight; // Maximum zoom out level

// let zoomLevel = 1; // Initial zoom level
// const minZoomLevel = 0.5; // Minimum zoom level
// const maxZoomLevel = 2; // Maximum zoom level


// function zoom(event) {
//     if (event.ctrlKey) {
//         event.preventDefault();
//         zoomLevel += event.deltaY * -0.01;
//         zoomLevel = Math.min(Math.max(minZoomLevel, zoomLevel), maxZoomLevel);

//         if (zoomLevel <= maxZoomOutWidth && maxZoomOutHeight) {
//             zoomLevel = maxZoomOutWidth;
//             zoomLevel = maxZoomOutHeight;
//         }

//         document.querySelector('.main-table').style.transform = `scale(${zoomLevel})`;
//         document.querySelector('.main-table').style.transformOrigin = 'left top'; 
        

//         // let parentWidth = document.querySelector('.main-table').parentElement.offsetWidth;
//         let parentHeight = document.querySelector('.zoom-container').parentElement.offsetHeight;
//         // document.querySelector('.main-table').style.width = `${parentWidth}px`;
//         document.querySelector('.t-ble').style.height = `${parentHeight}px`; // Set table height to parent height
//     }
// }

// document.querySelector('.zoom-container').addEventListener('wheel', zoom, { passive: false });

// // THE PROBLEM IS THAT, IT DOES NOT SAVE ALL THE DATA THAT USER TYPE TO THE INDEXDB, IT ONLY SAVE THE LAST INPUT....
// document.addEventListener('DOMContentLoaded', function() {
//     const mainTableHandler = new TableHandler('main-table');
//     const attendanceTableHandler = new TableHandler('attendance-table');

//     mainTableHandler.init();
//     attendanceTableHandler.init();

//     window.addEventListener('beforeunload', function(e) {
//         saveToServer();
//     });

//     // Save data when the "Save" button is clicked
//     document.getElementById('saveButton').addEventListener('click', function() {
//         saveToServer().then(() => {
//             alert("Save Successfully!")
//             clearIndexedDB(); // Only clear IndexedDB after saving to server
//         }).catch(error => console.error('Error saving data to server:', error));
//     });

//     clearIndexedDB() // Clear IndexedDB when the page loads
//         .then(() => {
//             loadFromIndexedDB(); // Load data from IndexedDB after clearing
//         })
//         .catch(error => console.error('Error clearing IndexedDB:', error));
// });

// class TableHandler {
//     constructor(tableId) {
//         this.tableId = tableId;
//     }

//     init() {
//         document.querySelectorAll(`#${this.tableId} td[contenteditable=true]`).forEach(cell => {
//             cell.addEventListener('blur', this.handleCellBlur.bind(this));
//             cell.addEventListener('keydown', this.handleCellNavigation.bind(this));
//         });
//     }

//     handleCellBlur(event) {
//         let cell = event.target;
//         let id = cell.getAttribute('data-id');
//         let row = cell.getAttribute('data-row');
//         let column = cell.getAttribute('data-column');
//         let content = cell.textContent.trim();
//         let room_id = cell.getAttribute('data-room-id');
//         let student_name = cell.getAttribute('data-room-student');
//         let room_studentID = cell.getAttribute('data-room-studentID');
//         let rowspan = cell.getAttribute('rowspan');
//         let colspan = cell.getAttribute('colspan');
//         let dataID = cell.getAttribute('data-id');
//         let merged = colspan > 1;

//         if (content.includes('=')) {
//             return;
//         }

//         if (content.length > 0) {
//             let payload = {
//                 id: id,
//                 content: content,
//                 room_id: room_id,
//                 room_studentID: room_studentID,
//                 student_name: student_name,
//                 merged: merged,
//                 rowspan: rowspan,
//                 colspan: colspan,
//                 dataID: dataID,
//                 tableId: this.tableId,
//                 row: row,
//                 column: column
//             };

//             storeInIndexedDB(payload);
//         } else if (id) {
//             deleteFromIndexedDB(id);
//         }
//     }

//     handleCellNavigation(event) {
//         this.navigateWithArrowKeys(event);
//     }

//     navigateWithArrowKeys(event) {
//         let cell = event.target;
//         let row = parseInt(cell.getAttribute('data-row'));
//         let column = cell.getAttribute('data-column').charCodeAt(0);
//         let nextCell;

//         switch (event.key) {
//             case 'ArrowUp':
//                 do {
//                     row--;
//                     nextCell = document.querySelector(`td[data-row="${row}"][data-column="${String.fromCharCode(column)}"]`);
//                 } while (row > 0 && (!nextCell || nextCell.getAttribute('data-merged') === 'true'));
//                 break;
//             case 'ArrowDown':
//                 do {
//                     row++;
//                     nextCell = document.querySelector(`td[data-row="${row}"][data-column="${String.fromCharCode(column)}"]`);
//                 } while (row <= 26 && (!nextCell || nextCell.getAttribute('data-merged') === 'true'));
//                 break;
//             case 'ArrowLeft':
//                 do {
//                     column--;
//                     nextCell = document.querySelector(`td[data-row="${row}"][data-column="${String.fromCharCode(column)}"]`);
//                 } while (column >= 65 && (!nextCell || nextCell.getAttribute('data-merged') === 'true'));
//                 break;
//             case 'ArrowRight':
//                 do {
//                     column++;
//                     nextCell = document.querySelector(`td[data-row="${row}"][data-column="${String.fromCharCode(column)}"]`);
//                 } while (column <= 90 && (!nextCell || nextCell.getAttribute('data-merged') === 'true'));
//                 break;
//             case 'Enter':
//                 do {
//                     row++;
//                     nextCell = document.querySelector(`td[data-row="${row}"][data-column="${String.fromCharCode(column)}"]`);
//                 } while (row <= 26 && (!nextCell || nextCell.getAttribute('data-merged') === 'true'));
//                 break;
//         }

//         if (nextCell) {
//             event.preventDefault();
//             nextCell.focus();
//         }
//     }
// }

// // IndexedDB Utility Functions
// function openIndexedDB() {
//     return new Promise((resolve, reject) => {
//         let request = indexedDB.open('StudentDataDB', 1);

//         request.onupgradeneeded = function(event) {
//             let db = event.target.result;
//             db.createObjectStore('data', { keyPath: 'id', autoIncrement: true });
//         };

//         request.onsuccess = function(event) {
//             resolve(event.target.result);
//         };

//         request.onerror = function(event) {
//             reject('Error opening IndexedDB:', event.target.errorCode);
//         };
//     });
// }

// function storeInIndexedDB(data) {
//     openIndexedDB().then(db => {
//         let transaction = db.transaction(['data'], 'readwrite');
//         let objectStore = transaction.objectStore('data');
//         objectStore.put(data);
//     }).catch(error => console.error('Error storing data in IndexedDB:', error));
// }

// function deleteFromIndexedDB(id) {
//     openIndexedDB().then(db => {
//         let transaction = db.transaction(['data'], 'readwrite');
//         let objectStore = transaction.objectStore('data');
//         objectStore.delete(Number(id));
//     }).catch(error => console.error('Error deleting data from IndexedDB:', error));
// }

// function loadFromIndexedDB() {
//     openIndexedDB().then(db => {
//         let transaction = db.transaction(['data'], 'readonly');
//         let objectStore = transaction.objectStore('data');
//         let request = objectStore.getAll();

//         request.onsuccess = function(event) {
//             let allData = event.target.result;
//             allData.forEach(data => {
//                 let cell = document.querySelector(`td[data-row="${data.row}"][data-column="${data.column}"]`);
//                 if (cell) {
//                     cell.textContent = data.content;
//                     cell.setAttribute('data-id', data.id);
//                 }
//             });
//         };
//     }).catch(error => console.error('Error loading data from IndexedDB:', error));
// }

// function saveToServer() {
//     openIndexedDB().then(db => {
//         let transaction = db.transaction(['data'], 'readonly');
//         let objectStore = transaction.objectStore('data');
//         let request = objectStore.getAll();

//         request.onsuccess = function(event) {
//             let allData = event.target.result;

//             console.log(allData);

//             if (allData.length > 0) {
//                 fetch('/save-all-data', {
//                     method: 'POST',
//                     headers: {
//                         'Content-Type': 'application/json',
//                         'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
//                     },
//                     body: JSON.stringify(allData)
//                 })
//                 .then(response => response.json())
//                 .then(data => {
//                     if (data.success) {
//                         clearIndexedDB();
//                     }
//                 })
//                 .catch(error => console.error('Error saving data to server:', error));
//             }
//         };
//     }).catch(error => console.error('Error opening IndexedDB:', error));
// }

// function clearIndexedDB() {
//     return openIndexedDB().then(db => {
//         return new Promise((resolve, reject) => {
//             let transaction = db.transaction(['data'], 'readwrite');
//             let objectStore = transaction.objectStore('data');
//             let request = objectStore.clear();

//             request.onsuccess = function() {
//                 resolve();
//             };

//             request.onerror = function(event) {
//                 reject('Error clearing IndexedDB:', event.target.errorCode);
//             };
//         });
//     });
// }