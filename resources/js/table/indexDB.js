import { evaluate } from 'mathjs';

$(document).ready(function() {

    $('#addButton').on('click', function() {
        performOperation('add');
    });
    $('#percentageButton').on('click', function() {
        performPercentage('Percentage');
    });
    $('#sumButton').on('click', function() {
        performSum('add');
    });

    $('#subtractButton').on('click', function() {
        performOperation('subtract');
    });

    $('#divideButton').on('click', function() {
        performOperation('divide');
    });

    $('#multiplyButton').on('click', function() {
        performOperation('multiply');
    });
    
});


    $(document).ready(function() {
        // Attach event listener to all table cells
        $('td').on('input', function() {
            let cell = $(this);
            let content = cell.text().trim();
            console.log(`Cell content: ${content}`);
            if (content === '=TOTAL') {
                cell.blur(); // Remove cursor from the cell
                let colIndex = cell.index();
                // Mark the first cell with =Total in the same column
                cell.attr('data-total-cell', 'true'); // Mark this cell as the total cell
                cell.addClass('blured');

                // Mark other cells in the same column as total cells
                $('tr').each(function() {
                    let totalCell = $(this).find('td').eq(colIndex);
                    if (totalCell[0] !== cell[0]) {
                        totalCell.attr('data-total-cell', 'true'); // Mark this cell as the total cell
                    }
                });
                alert('Please select cells to calculate.');
            } else {
                cell.removeAttr('data-total-cell'); // Remove the total cell marker
                cell.removeClass('blured'); // Remove blurred appearance
            }
        });
    });

    $(document).ready(function() {
        $(document).on('keydown', function(event) {
            let dot = $('td[data-total-cell="true"]');
            if(event.key === 'Enter') {
                if(dot.length > 1) {
                    performOperation('=TOTAL');
                    return;
                } 
            }
        });
    });


    function performOperation(operation) {
        let selectedCells = $('.selected');
        console.log(`Selected cells: ${selectedCells.length}`);

        // Find all total cells
        let totalCells = $('td[data-total-cell="true"]');
        console.log(`Total cells found: ${totalCells.length}`);

        if (totalCells.length === 0) {
            alert('Please include at least one cell with "=Total" to display the result.');
            return;
        }

        let calculationCells = selectedCells.not(totalCells);
        console.log(calculationCells + ' calculationCells');
        
        if (calculationCells.length === 0) {
            alert('Please select at least one cell in addition to the "=Total" cell.');
            return;
        }

        applySelectedToAllRows(calculationCells, totalCells);

        // Calculate the result for all rows
        applyResultToAllRows(operation, totalCells);

        // Clear all selections to allow the user to select new cells for the next operation
        clearSelections();
    }

    function applySelectedToAllRows(calculationCells, totalCells) {
        // Clear previous selections
        $('td.selected').removeClass('selected');

        // Apply new selections based on user input
        calculationCells.each(function() {
            let cellIndex = $(this).index();
            
            // Add the .selected class to corresponding cells in rows with data-operation attribute
            $('tr').each(function() {
                let cell = $(this).find('td').eq(cellIndex);
                if (cell.attr('data-operation')) {
                    cell.addClass('selected');
                }
            });
        });

        // Add the .selected class to the total cells in rows with data-operation attribute
        totalCells.each(function() {
            let totalCellIndex = $(this).index();
            $('tr').each(function() {
                let cell = $(this).find('td').eq(totalCellIndex);
                if (cell.attr('data-operation')) {
                    cell.addClass('selected');
                }
            });
        });
    }

    function applyResultToAllRows(operation) {
        // Process all rows that have the data-operation attribute
        $('tr').each(function() {
            let row = $(this);
            if (row.find('td[data-operation]').length > 0) {
                let totalCell = row.find('td[data-total-cell="true"]');
                processRow(row, operation, totalCell);
            }
        });
    }

    function processRow(row, operation, totalCell) {
        // Only process rows that have cells with the data-operation attribute
        let cells = row.find('td[data-operation]');
        if (cells.length === 0) {
            return;
        }

        // Find selected cells within this row
        let selectedCells = row.find('td.selected').not(totalCell);
        if (selectedCells.length < 1) {
            return;
        }

        let rowResult = 0;
        selectedCells.each(function(index) {
            let cell = $(this);
            let cellValue = parseFloat(cell.text()) || 0;
            if (isNaN(cellValue)) {
                return;
            }

            switch (operation) {
                case '=TOTAL':
                    rowResult = (index === 0) ? cellValue : rowResult + cellValue;
                    break;
                case 'subtract':
                    rowResult = (index === 0) ? cellValue : rowResult - cellValue;
                    break;
                case 'divide':
                    rowResult = (index === 0) ? cellValue : rowResult / cellValue;
                    break;
                case 'multiply':
                    rowResult = (index === 0) ? cellValue : rowResult * cellValue;
                    break;
            }
        });

        // Set the result in the total cell for the current row
        totalCell.text(rowResult);
        console.log(`Row result: ${rowResult}`);

        // Save the result to the database, excluding the total cell
        let dataPerform = {
            room_id: $(totalCell).attr('data-room-id'),
            student_id: $(totalCell).attr('data-room-studentid'),
            student_name: $(totalCell).attr('data-room-student'),
            room_studentID: $(totalCell).attr('data-room-studentID'),
            column: $(totalCell).attr('data-column'),
            row: $(totalCell).attr('data-row'),
            content: rowResult,
            rowspan: $(totalCell).attr('rowspan'),
            colspan: $(totalCell).attr('colspan'),
            tableId: 'main-table'
        };

        console.log(dataPerform);

        saveResultToDatabase(dataPerform);
    }

    function saveResultToDatabase(dataPerform) {
        $.ajax({
            url: '/create-skills',
            type: 'POST',
            contentType: 'application/json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
            data: JSON.stringify(dataPerform),
            success: function(response) {
                console.log(response.message);
            },
            error: function(error) {
                console.log('Error saving result:', error.responseJSON.message);
            }
        });
    }

function clearSelections() {
    // Clear all selected cells to reset for the next operation
    $('td.selected').removeClass('selected');
    $('td[data-total-cell]').removeClass('blured');
    $('td[data-ps-cell]').removeClass('blurred');

    // PARA SA TOTAL NGA CALCULATION
    $('td[data-total-cell]').removeAttr('data-total-cell');

    // PARA SA PERCENTAGE NGA CALCULATION
    $('td[data-ps-cell]').removeAttr('data-ps-cell');
    $('input[name="total"]').removeAttr('value').val('');
    $('input[name="percent"]').removeAttr('value').val('');
    $('input[name="plus"]').removeAttr('value').val('');

    $('input[name="totalScore"]').removeAttr('value');

    $('#formulaBox').addClass('hidden');
    $('#formulaTitle').removeClass('hidden');

    $('input[name="formulaInput"]').val('');

    isAdding = false;

}


// FOR SHOWING THE ROW AND COLUMN
let showColRow = $('input[name="showRowCol"]');
let showContent = $('input[name="showContent"]');

$('td').on('click', function() {
    let selectedCell = $(this);
    if (selectedCell[0]) {
        let selectedContent = selectedCell.attr('data-column').trim() + selectedCell.attr('data-row').trim();
        showColRow.attr('value', selectedContent);

        let selectedCellContent = selectedCell.text().trim();
        showContent.attr('value', selectedCellContent);

        return;
    }
});




// WILL SHOW THE FORMULA TO BOTS SIDES
$(document).ready(function() {
    let syncEnabled = false;

    $('#formula').on('input', function() {
        let value = $(this).val();
        console.log(value + 'BLURRED')

        if (!syncEnabled && value.startsWith('=')) {
            syncEnabled = true;
            console.log(syncEnabled)
        }

        if (syncEnabled) {
            $('#formulaInput').val(value);
        }
    });

    $('#formulaInput').on('input', function() {
        if (syncEnabled) {
            let value = $(this).val();
            console.log(value + "FORMULAINOUT")
            $('#formula').val(value);
        }
    });
});



let content;
let isAdding = false;

// GETTING THE PS SCORE
$(document).ready(function() {
    let selectedCells = [];
    let formulaInput = $('input[name="formulaInput"]');
    let lastSelectedCell = '';

    $('td').on('input', function() {
        let cell = $(this);
        content = cell.text().trim();

        if (content === '=') {
            let colIndex = cell.index();
            cell.attr('data-ps-cell', 'true');
            cell.addClass('blurred');
            $('#formulaBox').removeClass('hidden');
            $('#formulaTitle').addClass('hidden');

            $('tr').each(function() {
                let totalCell = $(this).find('td').eq(colIndex);
                if (totalCell[0] !== cell[0]) {
                    totalCell.attr('data-ps-cell', 'true');
                }
            });

            $('td').on('click.selectCell', function(event) {
                let selectedCell = $(this);
                if (cell.attr('data-ps-cell') === 'true') {
                    let cellReference = selectedCell.attr('data-column') + (selectedCell.closest('tr').index() + 1);
                    
                    if (!isAdding) {
                        formulaInput.val(cellReference);
                        lastSelectedCell = cellReference;
                    } else {
                        let currentFormula = formulaInput.val();
                        let regex = /[+\-*/()]/;
                        let lastChar = currentFormula.slice(-1);
                        if (regex.test(lastChar)) {
                            formulaInput.val(currentFormula + cellReference);
                            selectedCells.push(cellReference);
                        } else {
                            formulaInput.val(currentFormula.replace(lastSelectedCell, cellReference));
                        }
                        lastSelectedCell = cellReference;
                    }

                    // Ensure the input field stays focused and cursor is at the end
                    setTimeout(() => {
                        formulaInput.focus();
                        formulaInput[0].setSelectionRange(formulaInput.val().length, formulaInput.val().length);
                        console.trace(' INDEX setTimeout triggered');
                    }, 0);
                }
            });

            formulaInput.on('input', function() {
                let regex = /[+\-*/()]/;
                let formu = formulaInput.val();
                isAdding = regex.test(formu);
            });

            $(document).on('keypress', function(event) {
                if (event.key === 'Enter') {
                    event.preventDefault();

                    $('td').off('click.selectCell');

                    selectedCells = [];
                    formulaInput.val('');
                }
            });

        } else {
            $('tr td').removeAttr('data-ps-cell');
            cell.removeClass('blurred');

            $('#formulaBox').addClass('hidden');
            $('#formulaTitle').removeClass('hidden');

            formulaInput.val('');
            selectedCells = [];
            $('td').off('click.selectCell');

            isAdding = false;
        }
    });

    $(document).on('keydown', function(event) {
        let dot = $('td[data-ps-cell="true"]');
        if (event.key === 'Enter') {
            if (dot.length > 1) {
                let formula = $('input[name="formulaInput"]').val().trim();
                performPercentage(formula);
                return;
            } 
        }
    });
});




function performPercentage(formula) {
    let selectedCells = $('.selected');

    // FORMULA
    // let formula = $('input[name="formula"]').val().trim();

    // Find all total cells
    let totalCells = $('td[data-ps-cell="true"]');

    if (totalCells.length === 0) {
        alert('Please include at least one cell with "=PCT" to display the result.');
        return;
    }

    let calculationCells = selectedCells.not(totalCells);
    
    if (calculationCells.length === 0) {
        alert('Please select at least one cell in addition to the "=PCT" cell.');
        return;
    }

    applySelectedToAllRowsPS(calculationCells, totalCells);

    // Calculate the result for all rows
    applyResultToAllRowsPS(formula);

    // Clear all selections to allow the user to select new cells for the next operation
    clearSelections();
}

function applySelectedToAllRowsPS(calculationCells, totalCells) {
    // Clear previous selections
    $('td.selected').removeClass('selected');

    // Apply new selections based on user input
    calculationCells.each(function() {
        let cellIndex = $(this).index();
        
        // Add the .selected class to corresponding cells in rows with data-operation attribute
        $('tr').each(function() {
            let cell = $(this).find('td').eq(cellIndex);
            if (cell.attr('data-operation')) {
                cell.addClass('selected');
            }
        });
    });

    // Add the .selected class to the total cells in rows with data-operation attribute
    totalCells.each(function() {
        let totalCellIndex = $(this).index();
        $('tr').each(function() {
            let cell = $(this).find('td').eq(totalCellIndex);
            if (cell.attr('data-operation')) {
                cell.addClass('selected');
            }
        });
    });
}

function applyResultToAllRowsPS(formula) {
    // Process all rows that have the data-operation attribute
    $('tr').each(function() {
        let row = $(this);
        if (row.find('td[data-operation]').length > 0) {
            let totalCell = row.find('td[data-ps-cell="true"]');
            processRowPS(row, totalCell, formula);
        }
    });
}

function processRowPS(row, totalCell, formula) {
    // STARTTTTTTTTTTTTTTTTTT
    if (typeof formula !== 'string') {
        alert('Invalid formula input.');
        return;
    }

    // GETS THE FORMULA
    let getFormula = formula;
    let cellReferences = formula.match(/[A-Z]+[0-9]+/g);

    if (!cellReferences) {
        return;
    }

    cellReferences.forEach(ref => {
        let column = ref.match(/[A-Z]+/)[0];
        let cell = row.find(`td[data-column="${column}"]`);
        let cellValue = parseFloat(cell.text()) || 0;
        getFormula = getFormula.replace(ref, cellValue);
    });

    // ENDDDDDDDDDDDDDDDD


    // Only process rows that have cells with the data-operation attribute
    // let cells = row.find('td[data-operation]');
    // if (cells.length === 0) {
    //     return;
    // }

    // // Find selected cells within this row
    // let selectedCells = row.find('td.selected').not(totalCell);
    // if (selectedCells.length < 1) {
    //     return;
    // }

    // let total = parseFloat($('input[name="total"]').val());
    // let percent = parseFloat($('input[name="percent"]').val());
    // let plus = parseFloat($('input[name="plus"]').val());
    // console.log(total + 'Total');

    // let rowResult = 0;
    // let tempResult = 0;
    // selectedCells.each(function(index) {
    //     let cell = $(this);
    //     let cellValue = parseFloat(cell.text()) || 0;

    //     console.log(cellValue + 'CELLlL')

    //     switch (operation) {
    //         case '=':
    //             if(selectedCells.length > 1) {
    //                 tempResult = (index === 0) ? cellValue : tempResult + cellValue;
    //                 rowResult = (tempResult / total) * percent + plus;
    //                 rowResult = evaluate();

    //             } else {
    //                 rowResult = (cellValue / total) * percent + plus;
    //             }
    //             break;
    //     }
    // });
    // console.log(rowResult + 'well')

    // ANSWER FOR PS
    
    let result = evaluate(getFormula);

    const ps = result.toString().split('.');
    if(ps.length === 1 || ps[1].length === 1) {
        totalCell.text(result);
    } else {
        totalCell.text(result.toFixed(2));
    }

    // Save the result to the database, excluding the total cell
    let dataPerform = {
        room_id: $(totalCell).attr('data-room-id'),
        student_id: $(totalCell).attr('data-room-studentid'),
        student_name: $(totalCell).attr('data-room-student'),
        room_studentID: $(totalCell).attr('data-room-studentID'),
        column: $(totalCell).attr('data-column'),
        row: $(totalCell).attr('data-row'),
        content: ps.length === 1 || ps[1].length === 1 ? result : result.toFixed(2),
        rowspan: $(totalCell).attr('rowspan'),
        colspan: $(totalCell).attr('colspan'),
        tableId: 'main-table'
    };

    console.log(dataPerform);

    // saveResultToDatabase(dataPerform);
}








// $(document).ready(function() {
//     $('td').on('input', function() {
//         let cell = $(this);
//         let content = cell.text().trim();


//         if (content === '=TOTAL(SUM)') {
//             cell.blur(); 
//             let colIndex = cell.index();

//             cell.attr('data-sum-cell', 'true');
//             cell.addClass('blurred'); 


//             $('tr').each(function() {
//                 let totalCell = $(this).find('td').eq(colIndex);
//                 if (totalCell[0] !== cell[0]) {
//                     totalCell.attr('data-sum-cell', 'true'); 
//                 }
//             });

//             alert('Please select cell to calculate the Percentage.');
//         } else {
//             cell.removeAttr('data-sum-cell'); 
//             cell.removeClass('blurred');
//             total.removeAttr('value');
//             percent.removeAttr('value');
//             plus.removeAttr('value');
//         }
//     });
// });

// $(document).ready(function() {
//     $(document).on('keydown', function(event) {
//         let dot = $('td[data-sum-cell="true"]');
//         if(event.key === 'Enter') {
//             if(dot.length > 1) {
//                 performSumRow('=TOTAL(SUM)');
//                 return;
//             } 
//         }
//     });
// });
















function performSum(operation) {
    let selectedCells = $('.selected');

    if (selectedCells.length < 2) {
        alert('Please select at least two cells to perform an operation.');
        return;
    }

    let studentName = $(selectedCells[0]).attr('data-sum');
    
    if (!studentName) {
        alert("Can't Perform Operation!");
        return;
    }

    applyResultToRow(operation);
}


function applyResultToRow(operation) {
    // Get all table rows
    let rows = $('tr');

    // Process remaining rows
    rows.each(function(index) {
        processSum($(this), operation);
    });
}

function processSum(row, operation) {
    // Only process rows that have cells with the data-operation attribute
    let cells = row.find('td[data-sum]');
    if (cells.length === 0) {
        return;
    }

    // Find selected cells within this row
    let selectedCells = row.find('td.selected');
    if (selectedCells.length < 2) {
        return;
    }

    let rowResult = 0;
    selectedCells.each(function(index) {
        let cell = $(this);
        let cellValue = parseFloat(cell.text()) || 0;
        if (isNaN(cellValue)) {
            return;
        }

        switch (operation) {
            case 'add':
                rowResult = (index === 0) ? cellValue : rowResult + cellValue;
                break;
            case 'subtract':
                rowResult = (index === 0) ? cellValue : rowResult - cellValue;
                break;
            case 'divide':
                rowResult = (index === 0) ? cellValue : rowResult / cellValue;
                break;
            case 'multiply':
                rowResult = (index === 0) ? cellValue : rowResult * cellValue;
                break;
        }
    });

    // Set the result in the last selected column for the current row
    $(selectedCells[selectedCells.length - 1]).text(rowResult);
}












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


        // let formula = content.replace(/=PCT\((.*)\)/, '$1');

        // let total;
        // let percent;
        // let plus;
        // let regex = /\((\d+)\/(\d+)\)/;
        // let regex1 = /\((\d+\/\d+)\)\*(\d+)/;
        // let regex2 = /\+(\d+)/; 
        // let match = formula.match(regex);
        // let match1 = formula.match(regex1);
        // let match2 = formula.match(regex2);
        // if (match && match1) {
        //     total = parseFloat(match[2]);
        //     percent = parseFloat(match1[2]);
        //     plus = parseFloat(match2[1]);
        //     console.log(total + 'total')
        //     console.log(percent + 'total1')
        //     console.log(plus + 'total2')
        // }
        // console.log(formula)
























        // CAN BE USED FOR SUM
//         let content;
// let isAdding = false;
// let isShiftPressed = false;
// let startCell = null;

// $(document).ready(function() {
//     let selectedCells = [];
//     let formulaInput = $('input[name="formulaInput"]');
//     let lastSelectedCell = '';

//     $(document).on('keydown', function(event) {
//         if (event.key === 'Shift') {
//             isShiftPressed = true;
//         }
//     });

//     $(document).on('keyup', function(event) {
//         if (event.key === 'Shift') {
//             isShiftPressed = false;
//         }
//     });

//     $('td').on('input', function() {
//         let cell = $(this);
//         content = cell.text().trim();

//         if (content === '=') {
//             let colIndex = cell.index();
//             cell.attr('data-ps-cell', 'true');
//             cell.addClass('blurred');
//             $('#formulaBox').removeClass('hidden');
//             $('#formulaTitle').addClass('hidden');

//             $('tr').each(function() {
//                 let totalCell = $(this).find('td').eq(colIndex);
//                 if (totalCell[0] !== cell[0]) {
//                     totalCell.attr('data-ps-cell', 'true');
//                 }
//             });

//             $('td').on('click.selectCell', function(event) {
//                 let selectedCell = $(this);
//                 let cellReference = selectedCell.attr('data-column') + (selectedCell.closest('tr').index() + 1);

//                 if (isShiftPressed && startCell) {
//                     let startCellColumn = startCell.attr('data-column');
//                     let startCellRow = startCell.closest('tr').index() + 1;
//                     let endCellColumn = selectedCell.attr('data-column');
//                     let endCellRow = selectedCell.closest('tr').index() + 1;

//                     let rangeReference = `${startCellColumn}${startCellRow}:${endCellColumn}${endCellRow}`;
//                     formulaInput.val(rangeReference);
//                     isShiftPressed = false;
//                 } else {
//                     startCell = selectedCell;
//                     if (!isAdding) {
//                         formulaInput.val(cellReference);
//                         lastSelectedCell = cellReference;
//                     } else {
//                         let currentFormula = formulaInput.val();
//                         let regex = /[+\-*/()]/;
//                         let lastChar = currentFormula.slice(-1);
//                         if (regex.test(lastChar)) {
//                             formulaInput.val(currentFormula + cellReference);
//                             selectedCells.push(cellReference);
//                         } else {
//                             formulaInput.val(currentFormula.replace(lastSelectedCell, cellReference));
//                         }
//                         lastSelectedCell = cellReference;
//                     }
//                 }
//             });

//             formulaInput.on('input', function() {
//                 let regex = /[+\-*/()]/;
//                 let formu = formulaInput.val();
//                 isAdding = regex.test(formu);
//             });

//             $(document).on('keypress', function(event) {
//                 if (event.key === 'Enter') {
//                     event.preventDefault();

//                     $('td').off('click.selectCell');

//                     selectedCells = [];
//                     formulaInput.val('');
//                 }
//             });
            
//             // alert('Please select cell to calculate the Percentage.');
//         } else {
//             $('tr td').removeAttr('data-ps-cell');
//             cell.removeClass('blurred');

//             $('#formulaBox').addClass('hidden');
//             $('#formulaTitle').removeClass('hidden');

//             formulaInput.val('');
//             selectedCells = [];
//             $('td').off('click.selectCell');

//             isAdding = false;

//         }
//     });

//     $(document).on('keydown', function(event) {
//         let dot = $('td[data-ps-cell="true"]');
//         if(event.key === 'Enter') {
//             if(dot.length > 1) {
//                 let formula = $('input[name="formulaInput"]').val().trim();
//                 performPercentage(formula);
//                 return;
//             } 
//         }
//     });
    
// });