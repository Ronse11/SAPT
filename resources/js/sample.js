// let fontSize = 16; // Initial font size of the cells
// const minFontSize = 8; // Minimum font size for zoom out
// const maxFontSize = 32; // Maximum font size for zoom in

// function zoom(event) {
//     if (event.ctrlKey) {
//         event.preventDefault();
//         fontSize += event.deltaY * -0.5;
//         fontSize = Math.min(Math.max(minFontSize, fontSize), maxFontSize);
//         document.querySelectorAll('.main-table td').forEach(td => {
//             td.style.fontSize = `${fontSize}px`;
//         });
//     }
// }

// document.querySelector('.zoom-container').addEventListener('wheel', zoom, { passive: false });




let maxTableWidth = document.querySelector('.main-table').scrollWidth; // Maximum width of the table
let maxTableHeight = document.querySelector('.main-table').scrollHeight; // Maximum width of the table
let maxZoomOutWidth = window.innerWidth / maxTableWidth; // Maximum zoom out level
let maxZoomOutHeight = window.innerHeight / maxTableHeight; // Maximum zoom out level

let zoomLevel = 1; // Initial zoom level
const minZoomLevel = 0.5; // Minimum zoom level
const maxZoomLevel = 2; // Maximum zoom level


function zoom(event) {
    if (event.ctrlKey) {
        event.preventDefault();
        zoomLevel += event.deltaY * -0.01;
        zoomLevel = Math.min(Math.max(minZoomLevel, zoomLevel), maxZoomLevel);

        if (zoomLevel <= maxZoomOutWidth && maxZoomOutHeight) {
            zoomLevel = maxZoomOutWidth;
            zoomLevel = maxZoomOutHeight;
        }

        document.querySelector('.main-table').style.transform = `scale(${zoomLevel})`;
        document.querySelector('.main-table').style.transformOrigin = 'left top'; 
        

        // let parentWidth = document.querySelector('.main-table').parentElement.offsetWidth;
        let parentHeight = document.querySelector('.zoom-container').parentElement.offsetHeight;
        // document.querySelector('.main-table').style.width = `${parentWidth}px`;
        document.querySelector('.t-ble').style.height = `${parentHeight}px`; // Set table height to parent height
    }
}

document.querySelector('.zoom-container').addEventListener('wheel', zoom, { passive: false });



document.addEventListener('DOMContentLoaded', function() {
    // Create instances for both tables
    const mainTableHandler = new TableHandler('main-table');
    const attendanceTableHandler = new TableHandler('attendance-table');

    // Initialize the event listeners for both tables
    mainTableHandler.init();
    attendanceTableHandler.init();
});

class TableHandler {
    constructor(tableId) {
        this.tableId = tableId;
    }

    init() {
        document.querySelectorAll(`#${this.tableId} td[contenteditable=true]`).forEach(cell => {
            cell.addEventListener('blur', this.handleCellBlur.bind(this));
            cell.addEventListener('keydown', this.handleCellNavigation.bind(this));
        });
    }

    handleCellBlur(event) {
        let cell = event.target;
        let id = cell.getAttribute('data-id');
        let row = cell.getAttribute('data-row');
        let column = cell.getAttribute('data-column');
        let content = cell.textContent.trim();
        let room_id = cell.getAttribute('data-room-id');
        let student_name = cell.getAttribute('data-room-student');
        let room_studentID = cell.getAttribute('data-room-studentID');
        let rowspan = cell.getAttribute('rowspan');
        let colspan = cell.getAttribute('colspan');
        let dataID = cell.getAttribute('data-id');
        let merged = colspan > 1;

        if (content.includes('=')) {
            return;
        }

        if (content.length > 0) {
            let url = id ? `/update-content-cell/${id}` : '/create-skills';
            let payload = {
                _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                content: content,
                room_id: room_id,
                room_studentID: room_studentID,
                student_name: student_name,
                merged: merged,
                rowspan: rowspan,
                colspan: colspan,
                dataID: dataID,
                tableId: this.tableId 
            };
            if (!id) {
                payload.row = row;
                payload.column = column;
            }
            console.log(payload)

            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(payload)
            })
            .then(response => response.json())
            .then(data => {
                if (!id) {
                    cell.setAttribute('data-id', data.id);
                }
            })
            .catch(error => console.error('Error:', error));
        } else if (id) {
            let url = `/delete-skills/${id}`;

            fetch(url, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ tableId: this.tableId }) // Include table ID in the payload for delete
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    cell.removeAttribute('data-id');
                    cell.textContent = '';
                }
            })
            .catch(error => console.error('Error:', error));
        }
    }

    handleCellNavigation(event) {
        this.navigateWithArrowKeys(event);
    }

    navigateWithArrowKeys(event) {
        let cell = event.target;
        let row = parseInt(cell.getAttribute('data-row'));
        let column = cell.getAttribute('data-column').charCodeAt(0);
        let nextCell;

        switch (event.key) {
            case 'ArrowUp':
                do {
                    row--;
                    nextCell = document.querySelector(`td[data-row="${row}"][data-column="${String.fromCharCode(column)}"]`);
                } while (row > 0 && (!nextCell || nextCell.getAttribute('data-merged') === 'true'));
                break;
            case 'ArrowDown':
                do {
                    row++;
                    nextCell = document.querySelector(`td[data-row="${row}"][data-column="${String.fromCharCode(column)}"]`);
                } while (row <= 26 && (!nextCell || nextCell.getAttribute('data-merged') === 'true'));
                break;
            case 'ArrowLeft':
                do {
                    column--;
                    nextCell = document.querySelector(`td[data-row="${row}"][data-column="${String.fromCharCode(column)}"]`);
                } while (column >= 65 && (!nextCell || nextCell.getAttribute('data-merged') === 'true'));
                break;
            case 'ArrowRight':
                do {
                    column++;
                    nextCell = document.querySelector(`td[data-row="${row}"][data-column="${String.fromCharCode(column)}"]`);
                } while (column <= 90 && (!nextCell || nextCell.getAttribute('data-merged') === 'true'));
                break;
            case 'Enter':
                do {
                    row++;
                    nextCell = document.querySelector(`td[data-row="${row}"][data-column="${String.fromCharCode(column)}"]`);
                } while (row <= 26 && (!nextCell || nextCell.getAttribute('data-merged') === 'true'));
                break;
        }

        if (nextCell) {
            event.preventDefault();
            nextCell.focus();
        }
    }
}











// GETTING THE SUM OF EACH ROWS
function getTotals() {
    $('tr').each(function() {
        let sum = 0;
        let totalCell = $(this).find('.total-cell');
                
        // Iterate over each editable cell in the row
        $(this).find('td[chosen-column="column"]').each(function() {
            // Get the content of the cell and add it to the sum if it's a number
            let cellContent = $(this).text();
            if (!isNaN(cellContent) && cellContent.trim() !== "") {
                sum += parseFloat(cellContent);
            }
        });


    
        // Update the total cell with the calculated sum
        if (totalCell.length) {
            if(sum === 0) {
                sum = '';
            }             
            totalCell.text(sum);         
        }
    });
}


// GETTING THE PS OF EACH ROWS
function getPS() {
    $('tr').each(function() {
        let ps = 0;
        let totalPS = $(this).find('.total-ps');
                
        // Iterate over each editable cell in the row
        $(this).find('td[chosen-ps="column"]').each(function() {
            // Get the content of the cell and add it to the sum if it's a number
            let cellContent = $(this).text();
            // Get the total of the HIGHEST POSSIBLE SCORE
            $('tr').find('td[chosen-total-number="column"]').each(function() {
                let total = $(this).text();
                if (!isNaN(cellContent) && cellContent.trim() !== "") {
                    ps = (parseFloat(cellContent) / parseFloat(total)) * 100;
                }
            });
        });
    
        // Update the total cell with the calculated sum
        if (totalPS.length) {
            const tPs = ps.toString().split('.');
            if(tPs.length === 1 || tPs[1].length === 1) {
                totalPS.text(ps);
            } else {
                totalPS.text(ps.toFixed(2));
            }
        }
    });
}

// GETTING THE WS OF EACH ROWS
function getWS() {
    $('tr').each(function() {
        let ws = 0;
        let totalWS = $(this).find('.total-ws');
                
        // Iterate over each editable cell in the row
        $(this).find('td[chosen-ws="column"]').each(function() {
            // Get the content of the cell and add it to the sum if it's a number
            let cellContent = $(this).text();
            $('tr').find('td[chosen-percent-quiz="column"]').each(function() {
                let wholePercent = $(this).text();
                let percentQuiz = parseFloat(wholePercent) / 100;
                if (!isNaN(cellContent) && cellContent.trim() !== "") {
                    ws = parseFloat(cellContent) * percentQuiz;
                }
            });
        });
    
        // Update the total cell with the calculated sum
        if (totalWS.length) {
            const tWS = ws.toString().split('.');
            if(tWS.length === 1 || tWS[1].length === 1) {
                totalWS.text(ws);
            } else {
                totalWS.text(ws.toFixed(2));
            }
        }
    });
}

// GETTING THE PS OF EXAM ROWS
function getPsExam() {
    $('tr').each(function() {
        let psExam = 0;
        let totalPsExam = $(this).find('.total-ps-exam');
                
        // Iterate over each editable cell in the row
        $(this).find('td[chosen-ps-exam="column"]').each(function() {
            let cellScore = $(this).text();
            // Get the total of the HIGHEST POSSIBLE SCORE
            $('tr').find('td[chosen-total-score="column"]').each(function() {
                let totalScore = $(this).text();
                if (!isNaN(cellScore) && cellScore.trim() !== "") {
                    psExam = (parseFloat(cellScore) / totalScore) * 100;
                }
            });
        });

        // Update the total cell with the calculated sum
        if (totalPsExam.length) {
            const exam = psExam.toString().split('.');
            if(exam.length === 1 || exam[1].length === 1) {
                totalPsExam.text(psExam);
            } else {
                totalPsExam.text(psExam.toFixed(2));
            }
        }
    });
}

// GETTING THE WS OF WRITTEN IN EXAM
function getWsExam() {
    $('tr').each(function() {
        let wsExam = 0;
        let totalWsExam = $(this).find('.total-ws-exam');
                
        // Iterate over each editable cell in the row
        $(this).find('td[chosen-ws-exam="column"]').each(function() {
            let cellContent = $(this).text();
            $('tr').find('td[chosen-percent-exam="column"]').each(function() {
                let wholePercent = $(this).text();
                let percentExam = parseFloat(wholePercent) / 100;

                if (!isNaN(cellContent) && cellContent.trim() !== "") {
                    wsExam = parseFloat(cellContent) * percentExam;
                }
            });
        });
    
        // Update the total cell with the calculated sum
        if (totalWsExam.length) {
            const tWsExam = wsExam.toString().split('.');
            if(tWsExam.length === 1 || tWsExam[1].length === 1) {
                totalWsExam.text(wsExam);
            } else {
                totalWsExam.text(wsExam.toFixed(2));
            }
        }
    });
}

// GETTING THE KNOWLEDGE PERCENT
function getKExam() {
    $('tr').each(function() {
        let knowledge = 0;
        let totalKnowledge = $(this).find('.total-knowledge');
        let cKnowledge;
        let qKnowledge;
        let eKnowledge;
        let secondFifty;
        let firstFifty;

        $('tr').find('td[chosen-percent-knowledge="column"]').each(function() {
            cKnowledge = parseFloat($(this).text());
        });

        $(this).find('td[quiz-ws-knowledge="column"]').each(function() {
            qKnowledge = parseFloat($(this).text());
        });

        $(this).find('td[exam-ws-knowledge="column"]').each(function() {
            eKnowledge = parseFloat($(this).text());
        });

        $('tr').find('td[chosen-percent-exam="column"]').each(function() {
            secondFifty = parseFloat($(this).text());
        });
        
        $('tr').find('td[chosen-percent-quiz="column"]').each(function() {
            firstFifty = parseFloat($(this).text());
        });

        $(this).find('.total-knowledge').each(function() {    
            if (!isNaN(qKnowledge)) {                
                knowledge = ((qKnowledge + eKnowledge) / (firstFifty + secondFifty)) * cKnowledge;
            }
        });
        
        // Update the total cell with the calculated sum
        if (totalKnowledge.length) {
            const tKnowledge = knowledge.toString().split('.');
            if(tKnowledge.length === 1 || tKnowledge[1].length === 1) {
                totalKnowledge.text(knowledge);
            } else {
                totalKnowledge.text(knowledge.toFixed(2));
            }
        }
        
    });
}


    
$(document).ready(function() {
    // QUIZZES
    getTotals();
    getPS();
    getWS();

    // WRITTEN  EXAM
    getPsExam();
    getWsExam();

    // KNOWLEDGE PERCENT
    getKExam();
            
    // RECALCULATE THE DATA WHENEVER THE ROWS OF QUIZZES IS CHANGED
    $('td[chosen-column="column"]').on('input', function() {
        // QUIZZES
        getTotals();
        getPS();
        getWS();
    });
    $('td[chosen-percent-quiz="column"]').on('input', function() {
        // QUIZZES
        getWS();
    });

    // RECALCULATE THE DATA WHENEVER THE ROWS OF EXAM SCORE IS CHANGED
    $('td[chosen-ps-exam="column"]').on('input', function() {
        // WRITTEN  EXAM
        getPsExam();
        getWsExam();
        getKExam();
    });
    $('td[chosen-percent-exam="column"]').on('input', function() {
        // WRITTEN  EXAM
        getWsExam();
    });
});


$(document).ready(function() {
    $('.student-cell').hover(function() {
        $(this).toggleClass('active');
    });
});
$(document).ready(function() {
    $('.high-cell').hover(function() {
        $(this).toggleClass('active');
    });
});
$(document).ready(function() {
    $('.percent-cell').hover(function() {
        $(this).toggleClass('active');
    });
});
$(document).ready(function() {
    $('.percent-total-cell').hover(function() {
        $(this).toggleClass('active');
    });
});












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