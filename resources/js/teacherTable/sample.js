import { handleCellBlur } from '@js/handleCellBlur.js';


// EDITING THE HEADERS LIKE C/P/A in RATINGS TABLE
document.querySelectorAll('td[contenteditable]').forEach(function(td) {
    td.addEventListener('focus', function() {
        if (td.textContent === td.getAttribute('data-placeholder')) {
            td.textContent = '';
            td.classList.remove('placeholder');
        }
    });

    td.addEventListener('blur', function() {
        if (td.textContent.trim() === '') {
            td.textContent = td.getAttribute('data-placeholder');
            td.classList.add('placeholder');
        }
    });

    // Initialize placeholder text
    if (td.textContent.trim() === '') {
        td.textContent = td.getAttribute('data-placeholder');
        td.classList.add('placeholder');
    }
});
// ENDDDDDD



// ZOOM-IN AND OUT START!
// let zoomLevel = 1;
// const minZoomLevel = 0.5;
// const maxZoomLevel = 2;
// let mainTable, zoomContainer, maxTableWidth, maxTableHeight, maxZoomOutWidth, maxZoomOutHeight;

// // Defer layout-related calculations until after page load and only when needed
// function calculateTableDimensions() {
//     mainTable = document.querySelector('.main-table');
//     zoomContainer = document.querySelector('.zoom-container');

//     // Defer scrollWidth and scrollHeight calculation to avoid reflow on load
//     maxTableWidth = mainTable.scrollWidth;
//     maxTableHeight = mainTable.scrollHeight;
//     maxZoomOutWidth = window.innerWidth / maxTableWidth;
//     maxZoomOutHeight = window.innerHeight / maxTableHeight;
// }

// function zoom(event) {
//     if (event.ctrlKey) {
//         event.preventDefault();

//         if (!mainTable || !zoomContainer) {
//             // If dimensions haven't been calculated yet, do so now
//             calculateTableDimensions();
//         }

//         // Get the current cursor position relative to the zoomContainer
//         const rect = zoomContainer.getBoundingClientRect();
//         const cursorX = event.clientX - rect.left;
//         const cursorY = event.clientY - rect.top;

//         // Calculate the offset of the cursor relative to the table's scroll position
//         const offsetX = (cursorX + zoomContainer.scrollLeft) / zoomLevel;
//         const offsetY = (cursorY + zoomContainer.scrollTop) / zoomLevel;

//         // Adjust the zoom level
//         zoomLevel += event.deltaY * -0.01;
//         zoomLevel = Math.min(Math.max(minZoomLevel, zoomLevel), maxZoomLevel);

//         // Handle max zoom out based on table dimensions
//         if (zoomLevel <= maxZoomOutWidth && zoomLevel <= maxZoomOutHeight) {
//             zoomLevel = Math.min(maxZoomOutWidth, maxZoomOutHeight);
//         }

//         requestAnimationFrame(() => {
//             // Apply the zoom scaling
//             mainTable.style.transform = `scale(${zoomLevel})`;
//             mainTable.style.transformOrigin = 'left top';

//             // Recalculate the scroll positions to maintain the cursor's position
//             const newScrollLeft = offsetX * zoomLevel - cursorX;
//             const newScrollTop = offsetY * zoomLevel - cursorY;

//             // Apply the new scroll positions
//             zoomContainer.scrollLeft = newScrollLeft;
//             zoomContainer.scrollTop = newScrollTop;

//             let parentHeight = zoomContainer.parentElement.offsetHeight;
//             document.querySelector('.t-ble').style.height = `${parentHeight}px`;
//         });
//     }
// }

// // Add zoom functionality on wheel event
// document.querySelector('.zoom-container').addEventListener('wheel', zoom, { passive: false });
// ZOOM-IN AND OUT END!



// Optionally use requestIdleCallback to calculate dimensions when browser is idle
// requestIdleCallback(() => {
//     calculateTableDimensions();
// });



document.addEventListener('DOMContentLoaded', function() {
    // Create instances for both tables
    const mainTableHandler = new TableHandler('main-table');
    const attendanceTableHandler = new TableHandler('attendance-table');
    const ratingTableHandler = new TableHandler('rating-table');

    // Initialize the event listeners for both tables
    mainTableHandler.init();
    attendanceTableHandler.init();
    ratingTableHandler.init();
});

class TableHandler {
    constructor(tableId) {
        this.tableId = tableId;
        this.initialContent = '';
        this.lastSelectedCell = null;
    }

    init() {
        document.querySelectorAll(`#${this.tableId} td[contenteditable=true]`).forEach(cell => {
            cell.addEventListener('focus', this.handleCellFocus.bind(this));
            cell.addEventListener('input', this.handleCellInput.bind(this));
            cell.addEventListener('blur', (event) => handleCellBlur(event, this.tableId, this.initialContent));
            cell.addEventListener('keydown', this.handleCellNavigation.bind(this));
            cell.addEventListener('click', (event) => this.handleCellClick(event));
        });
    }

    handleCellFocus(event) {
        let cell = event.target;
        this.initialContent = cell.textContent.trim();
        cell.classList.add('selected2'); 
        placeCaretAtEnd(cell);
    }

    handleCellInput(event) {
        let cell = event.target;
        cell.classList.add('selected', 'top-border', 'bottom-border', 'left-border', 'right-border');
    }

    handleCellNavigation(event) {
        this.navigateWithArrowKeys(event);
    }

    navigateWithArrowKeys(event) {
        if (event.key === 'Shift') return;

        let cell = event.target;
        let row = parseInt(cell.getAttribute('data-row'));
        let columnLetter = cell.getAttribute('data-column');
        let column = this.columnToIndex(columnLetter);
        let nextCell;

        // Remove border classes from the current cell
        cell.classList.remove('selected', 'top-border', 'bottom-border', 'left-border', 'right-border');

        switch (event.key) {
            case 'ArrowUp':
                do {
                    row--;
                    nextCell = document.querySelector(`td[data-row="${row}"][data-column="${this.indexToColumn(column)}"]`);
                } while (row > 0 && (!nextCell || nextCell.getAttribute('data-merged') === 'true'));
                break;
            case 'ArrowDown':
                do {
                    row++;
                    nextCell = document.querySelector(`td[data-row="${row}"][data-column="${this.indexToColumn(column)}"]`);
                } while (row <= 70 && (!nextCell || nextCell.getAttribute('data-merged') === 'true'));
                break;
            case 'ArrowLeft':
                do {
                    column--;
                    nextCell = document.querySelector(`td[data-row="${row}"][data-column="${this.indexToColumn(column)}"]`);
                } while (column > 0 && (!nextCell || nextCell.getAttribute('data-merged') === 'true'));
                break;
            case 'ArrowRight':
                do {
                    column++;
                    nextCell = document.querySelector(`td[data-row="${row}"][data-column="${this.indexToColumn(column)}"]`);
                } while (column <= 104 && (!nextCell || nextCell.getAttribute('data-merged') === 'true'));
                break;
        }

        if (nextCell) {
            event.preventDefault();
            nextCell.focus();

            // Add border classes to the newly selected cell
            nextCell.classList.add('selected', 'top-border', 'bottom-border', 'left-border', 'right-border');

            this.lastSelectedCell = nextCell;
        }
    }

    columnToIndex(column) {
        let index = 0;
        for (let i = 0; i < column.length; i++) {
            index = index * 26 + (column.charCodeAt(i) - 64);
        }
        return index;
    }

    indexToColumn(index) {
        let column = "";
        while (index > 0) {
            let remainder = (index - 1) % 26;
            column = String.fromCharCode(65 + remainder) + column;
            index = Math.floor((index - 1) / 26);
        }
        return column;
    }

    handleCellClick(event) {
        let clickedCell = event.target.closest('td');
        if (!clickedCell) return;

        // Remove border classes from the last selected cell
        if (this.lastSelectedCell) {
            this.lastSelectedCell.classList.remove('selected', 'top-border', 'bottom-border', 'left-border', 'right-border');
        }

        // Add border classes to the clicked cell
        clickedCell.classList.add('selected', 'top-border', 'bottom-border', 'left-border', 'right-border');

        // Update last selected cell
        this.lastSelectedCell = clickedCell;
    }
    
    
}

function placeCaretAtEnd(el) {
    const range = document.createRange();
    const sel = window.getSelection();
    range.selectNodeContents(el);
    range.collapse(false); // Move the caret to the end of the text
    sel.removeAllRanges();
    sel.addRange(range);
    el.focus();
}

export default TableHandler;


// document.addEventListener('DOMContentLoaded', function() {
//     // Create instances for both tables
//     const mainTableHandler = new TableHandler('main-table');
//     const attendanceTableHandler = new TableHandler('attendance-table');

//     // Initialize the event listeners for both tables
//     mainTableHandler.init();
//     attendanceTableHandler.init();
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
//             let url = id ? `/update-content-cell/${id}` : '/create-skills';
//             let payload = {
//                 _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
//                 content: content,
//                 room_id: room_id,
//                 room_studentID: room_studentID,
//                 student_name: student_name,
//                 merged: merged,
//                 rowspan: rowspan,
//                 colspan: colspan,
//                 dataID: dataID,
//                 tableId: this.tableId 
//             };
//             if (!id) {
//                 payload.row = row;
//                 payload.column = column;
//             }
//             console.log(payload)

//             fetch(url, {
//                 method: 'POST',
//                 headers: {
//                     'Content-Type': 'application/json'
//                 },
//                 body: JSON.stringify(payload)
//             })
//             .then(response => response.json())
//             .then(data => {
//                 if (!id) {
//                     cell.setAttribute('data-id', data.id);
//                 }
//             })
//             .catch(error => console.error('Error:', error));
//         } else if (id) {
//             let url = `/delete-skills/${id}`;

//             fetch(url, {
//                 method: 'DELETE',
//                 headers: {
//                     'Content-Type': 'application/json',
//                     'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
//                 },
//                 body: JSON.stringify({ tableId: this.tableId }) // Include table ID in the payload for delete
//             })
//             .then(response => response.json())
//             .then(data => {
//                 if (data.success) {
//                     cell.removeAttribute('data-id');
//                     cell.textContent = '';
//                 }
//             })
//             .catch(error => console.error('Error:', error));
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











// GETTING THE SUM OF EACH ROWS
// function getTotals() {
//     $('tr').each(function() {
//         let sum = 0;
//         let totalCell = $(this).find('.total-cell');
                
//         // Iterate over each editable cell in the row
//         $(this).find('td[chosen-column="column"]').each(function() {
//             // Get the content of the cell and add it to the sum if it's a number
//             let cellContent = $(this).text();
//             if (!isNaN(cellContent) && cellContent.trim() !== "") {
//                 sum += parseFloat(cellContent);
//             }
//         });


    
//         // Update the total cell with the calculated sum
//         if (totalCell.length) {
//             if(sum === 0) {
//                 sum = '';
//             }             
//             totalCell.text(sum);         
//         }
//     });
// }


// // GETTING THE PS OF EACH ROWS
// function getPS() {
//     $('tr').each(function() {
//         let ps = 0;
//         let totalPS = $(this).find('.total-ps');
                
//         // Iterate over each editable cell in the row
//         $(this).find('td[chosen-ps="column"]').each(function() {
//             // Get the content of the cell and add it to the sum if it's a number
//             let cellContent = $(this).text();
//             // Get the total of the HIGHEST POSSIBLE SCORE
//             $('tr').find('td[chosen-total-number="column"]').each(function() {
//                 let total = $(this).text();
//                 if (!isNaN(cellContent) && cellContent.trim() !== "") {
//                     ps = (parseFloat(cellContent) / parseFloat(total)) * 100;
//                 }
//             });
//         });
    
//         // Update the total cell with the calculated sum
//         if (totalPS.length) {
//             const tPs = ps.toString().split('.');
//             if(tPs.length === 1 || tPs[1].length === 1) {
//                 totalPS.text(ps);
//             } else {
//                 totalPS.text(ps.toFixed(2));
//             }
//         }
//     });
// }

// // GETTING THE WS OF EACH ROWS
// function getWS() {
//     $('tr').each(function() {
//         let ws = 0;
//         let totalWS = $(this).find('.total-ws');
                
//         // Iterate over each editable cell in the row
//         $(this).find('td[chosen-ws="column"]').each(function() {
//             // Get the content of the cell and add it to the sum if it's a number
//             let cellContent = $(this).text();
//             $('tr').find('td[chosen-percent-quiz="column"]').each(function() {
//                 let wholePercent = $(this).text();
//                 let percentQuiz = parseFloat(wholePercent) / 100;
//                 if (!isNaN(cellContent) && cellContent.trim() !== "") {
//                     ws = parseFloat(cellContent) * percentQuiz;
//                 }
//             });
//         });
    
//         // Update the total cell with the calculated sum
//         if (totalWS.length) {
//             const tWS = ws.toString().split('.');
//             if(tWS.length === 1 || tWS[1].length === 1) {
//                 totalWS.text(ws);
//             } else {
//                 totalWS.text(ws.toFixed(2));
//             }
//         }
//     });
// }

// // GETTING THE PS OF EXAM ROWS
// function getPsExam() {
//     $('tr').each(function() {
//         let psExam = 0;
//         let totalPsExam = $(this).find('.total-ps-exam');
                
//         // Iterate over each editable cell in the row
//         $(this).find('td[chosen-ps-exam="column"]').each(function() {
//             let cellScore = $(this).text();
//             // Get the total of the HIGHEST POSSIBLE SCORE
//             $('tr').find('td[chosen-total-score="column"]').each(function() {
//                 let totalScore = $(this).text();
//                 if (!isNaN(cellScore) && cellScore.trim() !== "") {
//                     psExam = (parseFloat(cellScore) / totalScore) * 100;
//                 }
//             });
//         });

//         // Update the total cell with the calculated sum
//         if (totalPsExam.length) {
//             const exam = psExam.toString().split('.');
//             if(exam.length === 1 || exam[1].length === 1) {
//                 totalPsExam.text(psExam);
//             } else {
//                 totalPsExam.text(psExam.toFixed(2));
//             }
//         }
//     });
// }

// // GETTING THE WS OF WRITTEN IN EXAM
// function getWsExam() {
//     $('tr').each(function() {
//         let wsExam = 0;
//         let totalWsExam = $(this).find('.total-ws-exam');
                
//         // Iterate over each editable cell in the row
//         $(this).find('td[chosen-ws-exam="column"]').each(function() {
//             let cellContent = $(this).text();
//             $('tr').find('td[chosen-percent-exam="column"]').each(function() {
//                 let wholePercent = $(this).text();
//                 let percentExam = parseFloat(wholePercent) / 100;

//                 if (!isNaN(cellContent) && cellContent.trim() !== "") {
//                     wsExam = parseFloat(cellContent) * percentExam;
//                 }
//             });
//         });
    
//         // Update the total cell with the calculated sum
//         if (totalWsExam.length) {
//             const tWsExam = wsExam.toString().split('.');
//             if(tWsExam.length === 1 || tWsExam[1].length === 1) {
//                 totalWsExam.text(wsExam);
//             } else {
//                 totalWsExam.text(wsExam.toFixed(2));
//             }
//         }
//     });
// }

// // GETTING THE KNOWLEDGE PERCENT
// function getKExam() {
//     $('tr').each(function() {
//         let knowledge = 0;
//         let totalKnowledge = $(this).find('.total-knowledge');
//         let cKnowledge;
//         let qKnowledge;
//         let eKnowledge;
//         let secondFifty;
//         let firstFifty;

//         $('tr').find('td[chosen-percent-knowledge="column"]').each(function() {
//             cKnowledge = parseFloat($(this).text());
//         });

//         $(this).find('td[quiz-ws-knowledge="column"]').each(function() {
//             qKnowledge = parseFloat($(this).text());
//         });

//         $(this).find('td[exam-ws-knowledge="column"]').each(function() {
//             eKnowledge = parseFloat($(this).text());
//         });

//         $('tr').find('td[chosen-percent-exam="column"]').each(function() {
//             secondFifty = parseFloat($(this).text());
//         });
        
//         $('tr').find('td[chosen-percent-quiz="column"]').each(function() {
//             firstFifty = parseFloat($(this).text());
//         });

//         $(this).find('.total-knowledge').each(function() {    
//             if (!isNaN(qKnowledge)) {                
//                 knowledge = ((qKnowledge + eKnowledge) / (firstFifty + secondFifty)) * cKnowledge;
//             }
//         });
        
//         // Update the total cell with the calculated sum
//         if (totalKnowledge.length) {
//             const tKnowledge = knowledge.toString().split('.');
//             if(tKnowledge.length === 1 || tKnowledge[1].length === 1) {
//                 totalKnowledge.text(knowledge);
//             } else {
//                 totalKnowledge.text(knowledge.toFixed(2));
//             }
//         }
        
//     });
// }


    
// $(document).ready(function() {
//     // QUIZZES
//     getTotals();
//     getPS();
//     getWS();

//     // WRITTEN  EXAM
//     getPsExam();
//     getWsExam();

//     // KNOWLEDGE PERCENT
//     getKExam();
            
//     // RECALCULATE THE DATA WHENEVER THE ROWS OF QUIZZES IS CHANGED
//     $('td[chosen-column="column"]').on('input', function() {
//         // QUIZZES
//         getTotals();
//         getPS();
//         getWS();
//     });
//     $('td[chosen-percent-quiz="column"]').on('input', function() {
//         // QUIZZES
//         getWS();
//     });

//     // RECALCULATE THE DATA WHENEVER THE ROWS OF EXAM SCORE IS CHANGED
//     $('td[chosen-ps-exam="column"]').on('input', function() {
//         // WRITTEN  EXAM
//         getPsExam();
//         getWsExam();
//         getKExam();
//     });
//     $('td[chosen-percent-exam="column"]').on('input', function() {
//         // WRITTEN  EXAM
//         getWsExam();
//     });
// });


// $(document).ready(function() {
//     $('.student-cell').hover(function() {
//         $(this).toggleClass('active');
//     });
// });
// $(document).ready(function() {
//     $('.high-cell').hover(function() {
//         $(this).toggleClass('active');
//     });
// });
// $(document).ready(function() {
//     $('.percent-cell').hover(function() {
//         $(this).toggleClass('active');
//     });
// });
// $(document).ready(function() {
//     $('.percent-total-cell').hover(function() {
//         $(this).toggleClass('active');
//     });
// });












// function handleCellBlur(event) {
//     let cell = event.target;
//     let id = cell.getAttribute('data-id');
//     let row = cell.getAttribute('data-row');
//     let column = cell.getAttribute('data-column');
//     let content = cell.textContent.trim();
//     let room_id = cell.getAttribute('data-room-id');
//     let student_name = cell.getAttribute('data-room-student');
//     let student_room_id = cell.getAttribute('data-student-room-id');
//     let rowspan = cell.getAttribute('rowspan') || 1;
//     let colspan = cell.getAttribute('colspan') || 1;
//     let display = window.getComputedStyle(cell).display;
//     let merged = cell.hasAttribute('data-merged') ? cell.getAttribute('data-merged') : false;



//     if (content.length > 0) {
//         let url = id ? `/update-skills/${id}` : '/create-skills';
//         let payload = {
//             _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
//             content: content,
//             room_id: room_id,
//             student_room_id: student_room_id,
//             student_name: student_name,
//             rowspan: rowspan,
//             colspan: colspan,
//             display: display,
//             merged: merged
//         };
//         if (!id) {
//             payload.row = row;
//             payload.column = column;
//         }

//         console.log('Payload:', payload);
//         console.log('URL:', url);
//         console.log('ID:', id);

//         fetch(url, {
//             method: 'POST',
//             headers: {
//                 'Content-Type': 'application/json'
//             },
//             body: JSON.stringify(payload)
//         })
//         .then(response => response.json())
//         .then(data => {
//             console.log('Response:', data);
//             if (!id) {
//                 cell.setAttribute('data-id', data.id);
//             }
//         })
//         .catch(error => console.error('Error:', error));

//     } else if (id) {

//         let url = `/delete-skills/${id}`;

//         fetch(url, {
//             method: 'DELETE',
//             headers: {
//                 'Content-Type': 'application/json',
//                 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
//             }
//         })
//         .then(response => {
//             if (!response.ok) {
//                 throw new Error('Network response was not ok ' + response.statusText);
//             }
//             return response.json();
//         })
//         .then(data => {
//             console.log('Delete Response:', data);
//             if (data.success) {
//                 cell.removeAttribute('data-id');
//                 cell.textContent = '';
//             }
//         })
//         .catch(error => console.error('Error:', error));
//     }
// }

















// NAVIGATION WITH CELLS

// function navigateWithArrowKeys(event) {
//     let cell = event.target;
//     let row = parseInt(cell.getAttribute('data-row'));
//     let column = cell.getAttribute('data-column').charCodeAt(0);
//     let nextCell;
//     let found = false;

//     switch (event.key) {
//         case 'ArrowUp':
//             while (!found && row > 1) {
//                 row--;
//                 nextCell = document.querySelector(`td[data-row="${row}"][data-column="${String.fromCharCode(column)}"]`);
//                 if (nextCell) {
//                     found = true;
//                 }
//             }
//             break;
//         case 'ArrowDown':
//             while (!found && row < 26) {
//                 row++;
//                 nextCell = document.querySelector(`td[data-row="${row}"][data-column="${String.fromCharCode(column)}"]`);
//                 if (nextCell) {
//                     found = true;
//                 }
//             }
//             break;
//         case 'ArrowLeft':
//             while (!found && column > 65) {
//                 column--;
//                 nextCell = document.querySelector(`td[data-row="${row}"][data-column="${String.fromCharCode(column)}"]`);
//                 if (nextCell) {
//                     found = true;
//                 }
//             }
//             break;
//         case 'ArrowRight':
//             while (!found && column < 90) {
//                 column++;
//                 nextCell = document.querySelector(`td[data-row="${row}"][data-column="${String.fromCharCode(column)}"]`);
//                 if (nextCell) {
//                     found = true;
//                 }
//             }
//             break;
//     }

//     if (nextCell) {
//         event.preventDefault();
//         nextCell.focus();
//     }
// }