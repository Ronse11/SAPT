import { evaluate } from 'mathjs';

// import { getGradeEquivalent, formatGrade, getMidTermGrade, getFinTermGrade, getFinalRateGrade, getPassedOrFailed } from './ratingTable.js';

const tableRatings = document.querySelector('.rating-table')


export const formulaState = {
    isActive: false
};

export function activateFormula() {
    if (!formulaState.isActive) {
        formulaState.isActive = true;
    }
}

export function deactivateFormula() {
    if (formulaState.isActive) {
        formulaState.isActive = false;
    }
}

export function isFormulaActiveState() {
    return formulaState.isActive;
}



document.addEventListener('DOMContentLoaded', function () {
    const currentTable = document.querySelector('.main-table');
    const getTableID = currentTable.id;
    const table = document.querySelector(`#${getTableID}`);

    
    const getMidColumn = document.getElementById('MidGr.');
    const getFinColumn = document.getElementById('T.F.Gr.');



    let firstSelectedCell = null; // Store the first selected cell
    let formula = ''; // Store the entire formula string
    let columnReferences = []; // Store column references for calculation


    let isShiftKeyActive = false;
    let prevCol = null;
    
    //SELECTING OF CELL TO WHERE THE FORMULA WILL BE PERFORM STARTS HERE!!!!!!!!!!!!!!!!!
    table.addEventListener('input', function (event) {
        const cell = event.target.closest('.cursor-cell');
        const currentRow = cell.getAttribute('data-row');
        const operationA = table.querySelector(`[data-column="A"][data-operation="operation"][data-row="${currentRow}"]`);
        
        // If a cell is being edited and the first character typed is '='
        if (cell && cell.isContentEditable && cell.textContent.trim().startsWith('=')) {
            if (!firstSelectedCell) {
                firstSelectedCell = cell; // Set the first selected cell
                addFormulaInputListener(firstSelectedCell, operationA); // Enable formula input listener
                addingAdjClass(firstSelectedCell);
            }
        } 


    }, true);
    //SELECTING OF CELL TO WHERE THE FORMULA WILL BE PERFORM ENDS HERE!!!!!!!!!!!!!!!!!
    
    
    //HANDLING OF SELECTING ANOTHER CELLS FOR CALCULATION STARTS HERE!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
    function handleFocusin(event) {
        const cell = event.target.closest('.cursor-cell');
        
        if (firstSelectedCell && firstSelectedCell.textContent.trim().startsWith('=')) {
            const newCol = cell.dataset.column; // Get the column of the newly selected cell

    
            if (cell !== firstSelectedCell) {
                // Get the existing formula text after '='
                // let existingFormula = firstSelectedCell.textContent.trim().substring(1);
                const currentContent = firstSelectedCell.textContent.trim();
                let existingFormula;

                if(currentContent.startsWith('=')) {
                    const secondPart = currentContent.substring(1);

                    if(secondPart.startsWith('SUM')) {
                        existingFormula = currentContent.substring(3);

                        sumShift(newCol);
                        

                        table.addEventListener('keydown', handleKeydownShift);
                        table.addEventListener('keyup', handleKeyupShift);


                    } else {
                        existingFormula = currentContent.substring(1);
    
                        if (!existingFormula.endsWith(newCol)) {
                            // Append the new column identifier or symbol to the existing formula
                            firstSelectedCell.classList.add('highlight-border');

                            formula = `${existingFormula}${newCol}`;
                            
                            // firstSelectedCell.textContent = `=${formula}`; // Show the updated formula in the first selected cell
                            // columnReferences.push(newCol); // Add the column identifier for calculation
                            firstSelectedCell.textContent = `=${formula}`; // For regular formulas
        
                            columnReferences.push(newCol);
            
                        } 
                    }

                }

                
                // firstSelectedCell.focus();
                placeCaretAtEnd(firstSelectedCell); // Ensure the caret is at the end of the text

                firstSelectedCell.addEventListener('keydown', handleKeydownBoth);

            }
        }
    };
    table.addEventListener('focusin', handleFocusin, true);
    //HANDLING OF SELECTING ANOTHER CELLS FOR CALCULATION ENDS HERE!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

    //USING OF SHIFT FOR SELECTING OF CELLS STARTS HERE STARTS HERE!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
    function handleKeyupShift(event) {
        if(event.key === 'Shift') {
            isShiftKeyActive = false;
        }
    }

    function handleKeydownShift(event) {
        if(event.key === 'Shift') {
            isShiftKeyActive = true;
        }
    }

    function sumShift(newCol) {
        if(!firstSelectedCell) return '';
    
        if (!isShiftKeyActive) {
            prevCol = newCol;
            
            firstSelectedCell.textContent = `=SUM(${newCol}`;
            // Keep focus on the first selected cell
        } else {
            if(prevCol) {
                formula = `${prevCol}:${newCol}`;
                
                firstSelectedCell.textContent = `=SUM(${formula})`; // Update for SUM formula
            }
        }
    }
    //USING OF SHIFT FOR SELECTING OF CELLS STARTS HERE STARTS HERE!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

    
    //USING OF MOUSE FOR DRAGGING OF CELLS STARTS HERE STARTS HERE!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
    function initializeDragging() {
        let holdTimer;
        let isPress = false;
        let firstCell = null;
        let lastCol = null;
        let throttleTimer = null;
        const throttleLimit = 50;

        function handleMouseDown(event) {
            if (isPress) return;

            holdTimer = setTimeout(function () {
                const currentCell = event.target;
                firstCell = currentCell.dataset.column;
                isPress = true;
            }, 300);
        }

        function handleMouseMove(event) {
            if (!isPress || throttleTimer) return;

            throttleTimer = setTimeout(() => {
                const currentCol = event.target.dataset.column;

                if (lastCol !== currentCol) {
                    lastCol = currentCol;
                    const moveFormula = `${firstCell}:${lastCol}`;
                    firstSelectedCell.textContent = `=SUM(${moveFormula})`;
                    placeCaretAtEnd(firstSelectedCell);
                }

                throttleTimer = null;
            }, throttleLimit);
        }

        function handleMouseUp(event) {
            if (!isPress) {
                clearTimeout(holdTimer);
                return;
            }

            clearTimeout(holdTimer);
            isPress = false;

            const lastCol = event.target.dataset.column;
            formula = `${firstCell}:${lastCol}`;
            firstSelectedCell.textContent = `=SUM(${formula})`;
            placeCaretAtEnd(firstSelectedCell);
        }
        
        // Add event listeners
        table.addEventListener('mousedown', handleMouseDown);
        table.addEventListener('mousemove', handleMouseMove);
        table.addEventListener('mouseup', handleMouseUp);
    }
    //USING OF MOUSE FOR DRAGGING OF CELLS STARTS HERE ENDS HERE!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!



    function handleKeydownBoth(event) {
        if(!firstSelectedCell) return '';
        const currentContent = firstSelectedCell.textContent.trim() ?? '';
        const currentCell = event.target;

        if (event.key === 'Backspace') {
            if (currentContent.length <= 1) {
                removeAdjClass(firstSelectedCell);
                firstSelectedCell = null;
                formula = '';
                columnReferences = [];
                currentCell.classList.add('caret-transparent');
                currentCell.classList.remove('highlight-border');
                document.getElementById('hideTableButtons').classList.add('hidden');
            }
            if (currentContent.length <= 3) {
                formula = '';
            }
            return;
        } 
    }

    //INPUT OF FORMULA STARTS HERE STARTS HERE!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
    function addFormulaInputListener(cell, presentOperation) {
        if (!cell.hasInputListener) {
            cell.hasInputListener = true;

            // Define event handler functions
            function handleInput() {
                const content = cell.textContent.trim();

                if (content.startsWith('=')) {
                    const secondPart = content.substring(1);

                    document.getElementById('formulaBox').classList.remove('hidden');
                    document.getElementById('formulaTitle').classList.add('hidden');
                    document.getElementById('hideTableButtons').classList.remove('hidden');
                    if(secondPart.startsWith('SUM')) {
                        formula = content.substring(4);
                        initializeDragging();

                        // isMouseMove();
                        cell.classList.add('highlight-border');
                    } else {
                        // stopMouseMove();
                        formula = content.substring(1);
                    }
    
                    cell.classList.remove('caret-transparent');
                }
                
            }   
            

            function handleKeydown(event) {
                const content = cell.textContent.trim();

                if (event.key === 'Enter' && content.startsWith('=')) {
                    event.preventDefault();
                    document.getElementById('hideTableButtons').classList.add('hidden');
                    const newContent = content.substring(1);
                    if (newContent.startsWith('SUM')) {
                        if (presentOperation) {
                            calculateAndDisplaySum(content);
                        } else {
                            calculateTotal(content);
                        }
                        firstSelectedCell = null;
                    } else {
                        calculateAndDisplayResult(content);
                    }
                    resetState(cell);
                    activateFormula();
                    cell.classList.add('caret-transparent');

                    removeAdjClass(cell);
                }
            }

            function handleFocusout() {
                document.getElementById('formulaBox').classList.add('hidden');
                document.getElementById('formulaTitle').classList.remove('hidden');

                if (firstSelectedCell && firstSelectedCell.textContent.trim().startsWith('=')) {
                    firstSelectedCell.classList.add('highlight-border');
                } else {
                    deactivateFormula();
                    cell.removeEventListener('focusout', handleFocusout);
                }
            }

            // Add event listeners
            cell.addEventListener('input', handleInput);
            cell.addEventListener('keydown', handleKeydown);
            cell.addEventListener('keydown', handleKeydownBoth);
            cell.addEventListener('focusout', handleFocusout);
        }
    }
    //INPUT OF FORMULA STARTS HERE ENDS HERE!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!


    function addingAdjClass(cell) {
        let colIndex = columnLetterToIndex(cell.dataset.column);

        // Find the table that contains this cell
        let table = cell.closest('table');
        
        // Get all rows in the table
        let rows = table.rows;
        
        // Add the 'adjWidth' class to all cells in the same column
        for (let i = 0; i < rows.length; i++) {
            let cell = rows[i].cells[colIndex];
            if (cell) {
                cell.classList.add('adjWidth');
            }
        }
    }

    function removeAdjClass(cell) {
        let colIndex = columnLetterToIndex(cell.dataset.column);
                        
        // Find the table that contains this cell
        let table = cell.closest('table');
        
        // Get all rows in the table
        let rows = table.rows;
        
        // Add the 'adjWidth' class to all cells in the same column
        for (let i = 0; i < rows.length; i++) {
            let cell = rows[i].cells[colIndex];
            if (cell) {
                cell.classList.remove('adjWidth');
            }
        }
    }

    //CHANGING A:C TO A,B,C STARTS HERE!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
    function columnLetterToIndex(letter) {
        let index = 0;
        for (let i = 0; i < letter.length; i++) {
            index = index * 26 + (letter.charCodeAt(i) - 'A'.charCodeAt(0) + 1);
        }
        return index;
    }
    
    function columnIndexToLetter(index) {
        let letter = '';
        while (index > 0) {
            let remainder = (index - 1) % 26;
            letter = String.fromCharCode(remainder + 'A'.charCodeAt(0)) + letter;
            index = Math.floor((index - 1) / 26);
        }
        return letter;
    }
    
    function getColumnRange(range) {
        const [start, end] = range.split(':');
        const startIndex = columnLetterToIndex(start);
        const endIndex = columnLetterToIndex(end);
    
        const columns = [];
        for (let i = startIndex; i <= endIndex; i++) {
            columns.push(columnIndexToLetter(i));
        }
    
        return columns;
    }
    //CHANGING A:C TO A,B,C ENDS HERE!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

    //CALCULATION OF INPUTS USING "SUM without OPERATION" STARTS HERE!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
    function calculateTotal(content) {
        const formulaRegex = /^\(?[A-Z]{1,2}:[A-Z]{1,2}\)?$/;
        if(!formulaRegex.test(formula)) {
            firstSelectedCell.textContent = 'Invalid!';
            return;
        }

        if (firstSelectedCell && formula) {
            const firstSelectedCol = firstSelectedCell.dataset.column;
            const currentRow = firstSelectedCell.dataset.row;

            // Extract column references, including ranges (e.g., A:C, E:H)
            const columnMatch = formula.match(/[A-Z]{1,2}(:[A-Z]{1,2})?/g); 
            
            // recalculateFormula(content);

            if (!columnMatch) {
                alert('Invalid formula: No valid column or range found.');
                return;
            }
            
            
            if (columnMatch) {
                let columnReferences = [];
        
                columnMatch.forEach(range => {
                    if (range.includes(':')) {
                        // Handle column ranges
                        const columnsInRange = getColumnRange(range);
                        columnReferences = columnReferences.concat(columnsInRange);
                    } else {
                        // Handle individual columns
                        columnReferences.push(range);
                    }
                });

                const roomID = table.getAttribute('data-room-id');

                // let dataFormula = {
                //     tableId: table.id,
                //     room_id: roomID, 
                //     column: firstSelectedCell.dataset.column,
                //     formula: content
                // }
                // saveFormula(dataFormula);
                

                let tableName = firstSelectedCell.closest('table').id;
                const tableSelect = document.querySelector(`#${tableName}`);
        
                let dataBatch = [];
                const studentNameCache = {};
        
                let currentFormula = columnReferences;
                let rowFormula = currentFormula.join('+');
    
                for (const col of columnReferences) {
                    const cell = table.querySelector(`.cursor-cell[data-column="${col}"][data-row="${currentRow}"]`);
    
                    if (cell) {
                        let cellContent = cell.textContent.trim();
    
                        if (cellContent === "" || cellContent === "''") {
                            cellContent = "0";
                        } 
    
                        const cellValue = parseFloat(cellContent);
    
                        if (isNaN(cellValue)) {
                            alert(`Invalid: Cannot calculate a string or text type.`);
                            resetState(firstSelectedCell);
                            firstSelectedCell.textContent = 'Invalid!';
                            return;
                        }
    
                        rowFormula = rowFormula.replace(col, cellValue);  // Replace column identifier with the actual value
                    }
                }
    
                displayCalculation(dataBatch, rowFormula, firstSelectedCol, currentRow, content, roomID, studentNameCache, tableSelect, tableName);

                saveResultToDatabase(dataBatch);
            }
        }
    }
    //CALCULATION OF INPUTS USING "SUM without OPERATION" ENDS HERE!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

    //CALCULATION OF INPUTS USING "SUM" STARTS HERE!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
    function calculateAndDisplaySum(content) {
        const formulaRegex = /^\(?[A-Z]{1,2}:[A-Z]{1,2}\)?$/;
        if(!formulaRegex.test(formula)) {
            firstSelectedCell.textContent = 'Invalid!';
            return;
        }

        if (firstSelectedCell && formula) {
            const firstSelectedCol = firstSelectedCell.dataset.column;
        
            // Extract column references, including ranges (e.g., A:C, E:H)
            const columnMatch = formula.match(/[A-Z]{1,2}(:[A-Z]{1,2})?/g); 
            
            recalculateFormula(content);

            if (!columnMatch) {
                alert('Invalid formula: No valid column or range found.');
                return;
            }
            
            
            if (columnMatch) {
                let columnReferences = [];
        
                columnMatch.forEach(range => {
                    if (range.includes(':')) {
                        // Handle column ranges
                        const columnsInRange = getColumnRange(range);
                        columnReferences = columnReferences.concat(columnsInRange);
                    } else {
                        // Handle individual columns
                        columnReferences.push(range);
                    }
                });

                const roomID = table.getAttribute('data-room-id');

                let dataFormula = {
                    tableId: table.id,
                    room_id: roomID, 
                    column: firstSelectedCell.dataset.column,
                    formula: content
                }
                saveFormula(dataFormula);
                
                // Existing logic for handling rows and applying listeners
                const operationA = table.querySelectorAll('.cursor-cell[data-column="A"][data-operation="operation"]');
                const rowsToCalculate = new Set();

                operationA.forEach((operationCell) => {
                    rowsToCalculate.add(operationCell.dataset.row);
                });

                let tableName = firstSelectedCell.closest('table').id;
                const tableSelect = document.querySelector(`#${tableName}`);
        
                let dataBatch = [];
                const studentNameCache = {};
        
                for (const row of rowsToCalculate) {
                    let currentFormula = columnReferences;
                    let rowFormula = currentFormula.join('+');
        
                    for (const col of columnReferences) {
                        const cell = table.querySelector(`.cursor-cell[data-column="${col}"][data-row="${row}"]`);
        
                        if (cell) {
                            let cellContent = cell.textContent.trim();
        
                            if (cellContent === "" || cellContent === "''") {
                                cellContent = "0";
                            } 
        
                            const cellValue = parseFloat(cellContent);
        
                            if (isNaN(cellValue)) {
                                alert(`Invalid: Cannot calculate a string or text type.`);
                                resetState(firstSelectedCell);
                                firstSelectedCell.textContent = 'Invalid!';
                                return;
                            }
        
                            rowFormula = rowFormula.replace(col, cellValue);  // Replace column identifier with the actual value
                        }
                    }
        
                    displayCalculation(dataBatch, rowFormula, firstSelectedCol, row, content, roomID, studentNameCache, tableSelect, tableName);

                }
                saveResultToDatabase(dataBatch);
            }
        }
    }
    //CALCULATION OF INPUTS USING "SUM" ENDS HERE!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

    function hasUnbalancedParenthesis(str) {
        let balance = 0;
        for (let char of str) {
            if (char === '(') {
                balance++;
            } else if (char === ')') {
                balance--;
            }
        }
        if(balance !== 0) {
            firstSelectedCell.textContent = 'Invalid!ss';;
        } else {
            return formula;
        }
    }


    //CALCULATION OF INPUTS STARTS HERE!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
    function calculateAndDisplayResult(content) {
        formula = hasUnbalancedParenthesis(formula);
        const formulaRegex = /^[A-Za-z0-9]*[+\-*/^]$|^[A-Za-z0-9]+\s*$|^[A-Za-z0-9]*\s*[+\-*/^]{1,}$|^\s*[+\-*/^]$/&&/\b([A-Z]+\d+|\d+[A-Z]+)\b/;
        if(formulaRegex.test(formula)) {
            firstSelectedCell.textContent = 'Invalid!s';
            return;
        }

        if (firstSelectedCell && formula) {
            const firstSelectedCol = firstSelectedCell.dataset.column;
    
            // Extract column references (e.g., A, B, T, AA, AB, ..., AZ, BA, ..., CA, etc.) from the formula
            const columnMatch = formula.match(/[A-Z]{1,2}/g);

            
            recalculateFormula(content);
    
            if (columnMatch) {
                columnReferences = columnMatch; // Update column references based on user input
            }
    
            // Check if the formula contains only a math expression (e.g. "2+2")
            const containsColumns = columnReferences.length > 0;
            let rowFormula = formula;
            const roomID = table.getAttribute('data-room-id');

            let dataFormula = {
                tableId: table.id,
                room_id: roomID, 
                column: firstSelectedCell.dataset.column,
                formula: content
            }
            saveFormula(dataFormula);
    
            if (containsColumns) {
                const operationA = table.querySelectorAll('.cursor-cell[data-column="A"][data-operation="operation"]');
                const rowsToCalculate = new Set();

                operationA.forEach((operationCell) => {
                    rowsToCalculate.add(operationCell.dataset.row);
                });

                let tableName = firstSelectedCell.closest('table').id;
                const tableSelect = document.querySelector(`#${tableName}`);
                const roomID = tableSelect.getAttribute('data-room-id');

                let dataBatch = [];
                const studentNameCache = {};
    
                for (const row of rowsToCalculate) {
                    rowFormula = formula;
                    let allColumnsBlank = true;
    
                    for (const col of columnReferences) {
                        const cell = table.querySelector(`.cursor-cell[data-column="${col}"][data-row="${row}"]`);
    
                        if (cell) {
                            let cellContent = cell.textContent.trim();
    
                            // Check if cell is blank or contains an empty string
                            if (cellContent === "" || cellContent === "''") {
                                cellContent = "0";
                            } else {
                                allColumnsBlank = false;
                            }
    
                            const cellValue = parseFloat(cellContent);
    
                            if (isNaN(cellValue)) {
                                alert(`Invalid: Cannot calculate a string or text type.`);
                                resetState(firstSelectedCell);
                                firstSelectedCell.textContent = 'Invalid!';
                                return;
                            }
    
                            rowFormula = rowFormula.replace(col, cellValue); // Replace column identifier with the actual value
                        }
                    }
                    displayCalculation(dataBatch, rowFormula, firstSelectedCol, row, content, roomID, studentNameCache, tableSelect, tableName);
                    
                }
                saveResultToDatabase(dataBatch);

            } else {
                const operationA = table.querySelectorAll('.cursor-cell[data-column="A"][data-operation="operation"]');

                let dataBatch = [];
    
                try {
                    let result = evaluate(rowFormula);
                    result = result.toFixed(2);
                    // result = Math.round(result);
                    // result = Math.round(result * 100) / 100;
    
                    let tableName = firstSelectedCell.closest('table').id;
                    const tableSelect = document.querySelector(`#${tableName}`);
                    const roomID = tableSelect.getAttribute('data-room-id');

                    const studentNameCache = {};

                    operationA.forEach((operationCell) => {
                        const operationRow = operationCell.dataset.row;
                        const resultCell = table.querySelector(`.cursor-cell[data-column="${firstSelectedCol}"][data-row="${operationRow}"]`);

                        if(resultCell) {
                            resultCell.textContent = result; // Apply result to all matching cells
                            let rowCell = resultCell.dataset.row;
    
                            let studentName = studentNameCache[rowCell];
                            if (!studentName) {
                                studentName = tableSelect.querySelector(`td[data-row="${rowCell}"][data-column="A"]`).dataset.roomStudent;
                                studentNameCache[rowCell] = studentName;
                            }
        
                            let dataPerform = {
                                tableId: tableName,
                                room_id: roomID, 
                                student_name: studentName, 
                                column: resultCell.dataset.column,
                                row: rowCell,
                                content: result,
                                rowspan: resultCell.getAttribute('rowspan') || 1, 
                                colspan: resultCell.getAttribute('colspan') || 1,
                                formula: content
                            };
                            
                            dataBatch.push(dataPerform);
                        }
                    });
                    saveResultToDatabase(dataBatch);
                } catch (e) {
                    firstSelectedCell.textContent = 'Invalid!';
                }
            }
        }
    }
    //CALCULATION OF INPUTS ENDS HERE!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

    
    //DISPLAYING OF RESULTS AFTER CALCULATIONS STARTS HERE!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
    function displayCalculation(dataBatch, rowFormula, firstSelectedCol, row, content, roomID, studentNameCache, tableSelect, tableName) {
        try {
            let result;
            result = evaluate(rowFormula);
            result = result.toFixed(2);
            // result = Math.round(result);
            // result = Math.round(result * 100) / 100;

            const resultCell = table.querySelector(`.cursor-cell[data-column="${firstSelectedCol}"][data-row="${row}"]`);

            if (resultCell) {
                resultCell.textContent = result; // Set the calculated and rounded result
                let rowCell = resultCell.dataset.row;

                let studentName = studentNameCache[rowCell];
                if(!studentName) {
                    studentName = tableSelect.querySelector(`td[data-row="${rowCell}"][data-column="A"]`).dataset.roomStudent;
                    studentNameCache[rowCell] = studentName;
                }

                let dataPerform = {
                    tableId: tableName,
                    room_id: roomID, 
                    student_name: studentName, 
                    column: resultCell.dataset.column,
                    row: resultCell.dataset.row,
                    content: result,
                    rowspan: resultCell.getAttribute('rowspan') || 1, 
                    colspan: resultCell.getAttribute('colspan') || 1,
                    formula: content
                };

                dataBatch.push(dataPerform);
                
            }
        } catch (e) {
            firstSelectedCell.textContent = 'Invalidskie!';
        }
    }
    //DISPLAYING OF RESULTS AFTER CALCULATIONS STARTS HERE!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!


    function resetState(cell) {
        firstSelectedCell = null;
        formula = '';
        columnReferences = [];

        document.getElementById('formulaBox').classList.add('hidden');
        document.getElementById('formulaTitle').classList.remove('hidden');

        cell.classList.remove('highlight-border');
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

    //SAVING OF FORMULA STARTS HERE!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
    async function saveFormula(dataFormula) {
        try {
            const response = await fetch('/create-formulas', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: JSON.stringify(dataFormula),
            });
    
            if (response.ok) {
                const result = await response.json();
                // console.log('Data saved successfully:', result.formula);
    
                // Apply formula to all cells in the same column
                const column = result.column; // column number or identifier
                const formula = result.formula;
                
                const operationACache = [...table.querySelectorAll('[data-column="A"][data-operation="operation"]')];
                
                // Loop through and set the data-formula attribute
                operationACache.forEach(cell => {
                    const row = cell.dataset.row;
                    const cells = document.querySelector(`[data-column='${column}'][data-row='${row}']`);

                    cells.setAttribute('data-formula', formula);

                });
    
            } else {
                throw new Error('Network response was not ok');
            }
    
        } catch (error) {
            console.error('Error saving result:', error.message);
        }
    }
    //SAVING OF FORMULA ENDS HERE!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

    
    //SAVING OF CALCULATION STARTS HERE!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
    async function saveResultToDatabase(dataPerform) {
        try {
            const response = await fetch('/create-calculations', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: JSON.stringify({ dataPerform }),
            });

            
            if (response.ok) {
                const result = await response.json();

                if (Array.isArray(result.ids)) {
                    result.ids.forEach((item) => {
            
                        const cell = table.querySelector(`.cursor-cell[data-column="${item.column}"][data-row="${item.row}"]`);
                        
                        if (cell) {
                            // Set the cell's dataset id attribute
                            cell.dataset.id = item.id;
                        } else {
                            console.warn('Cell not found for column:', item.column, 'and row:', item.row);
                        }
                    });
                } else {
                    console.log('Unexpected response structure:', result);
                }

            } else {
                throw new Error('Network response was not ok');
            }
    
        } catch (error) {
            console.error('Error saving result:', error.message);
        }
    }
    //SAVING OF CALCULATION ENDS HERE!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
    
    
    //SHOWING OF FORMULA STARTS HERE!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
    if (window.formulas) {
        window.formulas.forEach(formulaData => {
            const column = formulaData.column;
            const formula = formulaData.formula;

            // console.log(formula);


            const operationA = table.querySelectorAll('.cursor-cell[data-column="A"][data-operation="operation"]');

            operationA.forEach((operationCell) => {
                const operationRow = operationCell.dataset.row;
                const cells = table.querySelector(`[data-column="${column}"][data-row="${operationRow}"]`);
                
                if(cells) {
                    cells.setAttribute('data-formula', formula);
                }
            });
            
            recalculateFormula(formula);
        });
    }
    //SHOWING OF FORMULA ENDS HERE!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
    

    //RECALCULATION OF INPUTS STARTS HERE!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
    function expandRange(formula) {
        let result = [];
        
        formula.replace(/([A-Z]{1,2}):([A-Z]{1,2})/g, function(match, start, end) {
            let expanded = [];
    
            // Handle single-letter ranges (e.g., A:C)
            if (start.length === 1 && end.length === 1) {
                for (let i = start.charCodeAt(0); i <= end.charCodeAt(0); i++) {
                    expanded.push(String.fromCharCode(i));
                }
            }
    
            // Handle double-letter ranges (e.g., AA:AZ)
            else if (start.length === 2 && end.length === 2) {
                let startFirst = start.charCodeAt(0), startSecond = start.charCodeAt(1);
                let endFirst = end.charCodeAt(0), endSecond = end.charCodeAt(1);
    
                for (let i = startFirst; i <= endFirst; i++) {
                    let secondStart = (i === startFirst) ? startSecond : 'A'.charCodeAt(0);
                    let secondEnd = (i === endFirst) ? endSecond : 'Z'.charCodeAt(0);
    
                    for (let j = secondStart; j <= secondEnd; j++) {
                        expanded.push(String.fromCharCode(i) + String.fromCharCode(j));
                    }
                }
            }
    
            result = result.concat(expanded);
            return expanded;
        });
    
        const individualColumns = formula.match(/[A-Z]{1,2}/g);
        if (individualColumns) {
            result = result.concat(individualColumns);
        }
    
        return [...new Set(result)];
    }

    
    //RECALCULATION OF INPUTS STARTS HERE!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
    function recalculateFormula(formula) {
        let recalculateTimeout;
        let recalculateTimeoutRating;
        clearTimeout(recalculateTimeout);
        clearTimeout(recalculateTimeoutRating);

        const operationACache = [...table.querySelectorAll('.cursor-cell[data-column="A"][data-operation="operation"]')];
    
        const formulaRegex = /if/;
        if(formulaRegex.test(formula)) {
            reCondition(formula);
    
            return;
        }
    
        let newFormula = null;
        const subFormula = formula.substring(1);
        if(subFormula.startsWith('SUM')) {
            newFormula = formula.substring(4);
            newFormula = expandRange(newFormula).join('+');
        } else {
            newFormula = formula.substring(1);
        }
    
        const columnMatch = newFormula.match(/[A-Z]{1,2}/g);
        let result;
        let involvedResult;
        let roundedResult;
        let originalContent = '';
        let originalContentLength = 0;
    
        const cellCache = new Map();

        if (columnMatch) {
            columnMatch.forEach(cols => {
                operationACache.forEach(operationCell => {
                    const operationRow = operationCell.dataset.row;
                    
                    const cacheKey = `${cols}-${operationRow}`;
                    if (!cellCache.has(cacheKey)) {
                        const involvedCell = table.querySelector(`[data-column='${cols}'][data-row='${operationRow}']`);
                        cellCache.set(cacheKey, involvedCell);
                    }
    
                    const involvedCell = cellCache.get(cacheKey);
                    if (!involvedCell) return;

                    const bulkUpdates = [];

                    involvedCell.addEventListener('keydown', function(event) {
                        const currentCell = event.target;
                        const tableId = currentCell.closest('table').id;
                        const id = currentCell.getAttribute('data-id');
                        const formulaId = currentCell.getAttribute('data-column');
                        const roomID = table.getAttribute('data-room-id');
                        const formula = currentCell.getAttribute('data-formula');
    
                        if(event.key === 'Backspace' && formula) {
                            currentCell.textContent = '';
                            if(currentCell.hasAttribute('data-formula')) {
                                currentCell.removeAttribute('data-formula');
    
                                let skillUrl = `/delete-skills/${id}`;
                                let formulaUrl = `/delete-formula/${formulaId}`;
                                
                                
                                Promise.all([
                                    fetch(skillUrl, {
                                        method: 'DELETE',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                        },
                                        body: JSON.stringify({ tableId: tableId })
                                    }),
                                    fetch(formulaUrl, {
                                        method: 'DELETE',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                        },
                                        body: JSON.stringify({ tableId: tableId, roomId: roomID, formula: formula })
                                    })
                                ])
                                .then(responses => Promise.all(responses.map(res => res.json())))
                                .then(data => {
                                    console.log('Skill and formula deleted:', data);
                                    currentCell.removeAttribute('data-id');
                                })
                                .catch(error => console.error('Error:', error));
                                
                            }
                        }
                    });
    
                    involvedCell.addEventListener('focus', function(event) {
                        let currentCell = event.target;
                        // Capture the initial content and its length before user makes any changes
                        originalContent = currentCell.textContent.trim();
                        originalContentLength = originalContent.length;
                    });
    
                    recalculateTimeout = setTimeout(() => {
                    involvedCell.addEventListener('blur', async(event) => {
                        let currentCell = event.target;
                        
                        const updatedContent = currentCell.textContent.trim();
                        const updatedContentLength = updatedContent.length;
    
                        if(originalContent !== updatedContent || originalContentLength !== updatedContentLength) {
                            const row = event.target.getAttribute('data-row');
                            const targetCell = event.target.getAttribute('data-column');
    
                            // let checkFormula = null;
    
                            // let subFormula = formula.substring(1);
                            // if (subFormula.startsWith('SUM')) {
                            //     checkFormula = formula.substring(4);
                            //     checkFormula = expandRange(checkFormula).join('+');
                            // } else {
                            //     checkFormula = formula.substring(1);
                            // }
    
    
                            
                            const formulaCells = [...table.querySelectorAll(`[data-formula='${formula}'][data-row='${row}']`)];
                            // Process all formula cells in a batch
                            const updates = [];
                            // const updatesInvolved = [];
                            
                            formulaCells.forEach(formulaCell => {
                                let updatedFormula = newFormula;
                                // const colsWithFormula = formulaCell.dataset.column;
                                // console.log(formulaCell);
    
                                columnMatch.forEach(colToReplace => {
                                    const cacheKey = `${row}-${colToReplace}`;
                                    if (!cellCache.has(cacheKey)) {
                                        const cellContent = table.querySelector(`[data-row='${row}'][data-column='${colToReplace}']`);
                                        cellCache.set(cacheKey, cellContent);
                                    }
                                    
                                    let cellContent = cellCache.get(cacheKey);
                                    
                                    if (!cellContent || cellContent.textContent === "") {
                                        updatedFormula = updatedFormula.replace(colToReplace, "0");
                                    } else {
                                        cellContent = cellContent.textContent.trim();
                                        updatedFormula = updatedFormula.replace(colToReplace, cellContent);
                                    }
                                });
    
                                try {
                                    result = evaluate(updatedFormula);
                                    result = result.toFixed(2); 
                                    // result = Math.round(result); 
                                    // result = Math.round(result * 100) / 100; 
                                    updates.push({ formulaCell, result });
                                } catch (error) {
                                    updates.push({ formulaCell, result: 'Invalid!' });
                                }
                            });
    
                            const roomID = table.getAttribute('data-room-id');
    
                            // Apply all updates to the DOM in one go (batch processing)
                            updates.forEach(({ formulaCell, result }) => {
                                formulaCell.textContent = result;
    
                                let id = formulaCell.getAttribute('data-id');
                                let row = formulaCell.getAttribute('data-row');
                                let column = formulaCell.getAttribute('data-column');
                                let studentCell = table.querySelector(`td[data-row="${row}"][data-column="A"]`);
                                let student_name = studentCell ? studentCell.getAttribute('data-room-student') : null;
    
                                let results = {
                                    id: id || null, // If no ID, it's an insert
                                    tableId: table.id,
                                    content: result,
                                    room_id: roomID,
                                    student_name: student_name,
                                    merged: false,
                                    rowspan: 1,
                                    colspan: 1,
                                    row: row,
                                    column: column
                                }

                                bulkUpdates.push(results);

                                
                                // const url = id ? `/update-content-cell/${id}` : '/save-calculated-cell';
                                // fetch(url, {            
                                // method: "POST",
                                // headers: {
                                //     'Content-Type': 'application/json',
                                //     'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                // },
                                // body: JSON.stringify(results)    
                                // })
                                // .then(response => response.json())
                                // .then(data => {
                                //     console.log("Server Response:", data);
                        
                                //     // If there's no ID, update the cell with the returned ID from the server
                                //     if (!id && data.ids) {
                                //         cell.setAttribute('data-id', data.ids);
                                //         console.log(data.ids);
    
                                //     }
                                // })
                                // .catch(error => {
                                //     console.error("Error:", error);
                                // });
                            });
    
                            // Involved cell calculation batch starts here
                            const involvedCols = [...table.querySelectorAll(`[data-formula][data-row='${row}']`)]
                            .filter(cell => !cell.dataset.formula.startsWith("if"));    
                            
                            involvedCols.forEach(includesCol => {
                                const targetFormula = includesCol.dataset.formula;
                                let checkInvolveFormula = null;

    
                                let subFormula = targetFormula.substring(1);
                                if (subFormula.startsWith('SUM')) {
                                    checkInvolveFormula = targetFormula.substring(4);
                                    checkInvolveFormula = expandRange(checkInvolveFormula).join('+');
                                } else {
                                    checkInvolveFormula = targetFormula.substring(1);
                                }
    
                                
                                // if (!checkInvolveFormula.includes(targetCell)) {
                                    // console.log(includesCol);
                                    let newInvolvedFormula = null;
                                    // const subFormula = targetFormula.substring(1);
                                    if (subFormula.startsWith('SUM')) {
                                        newInvolvedFormula = targetFormula.substring(4);
                                        newInvolvedFormula = expandRange(newInvolvedFormula).join('+');
                                    } else {
                                        newInvolvedFormula = targetFormula.substring(1);
                                    }
    
                                    // console.log(newInvolvedFormula);
    
                                    let updatednewInvolvedFormula = newInvolvedFormula;
                                    const matchTargetFormula = newInvolvedFormula.match(/[A-Z]{1,2}/g);
    
                                    matchTargetFormula.forEach(matchCol => {
                                        const cacheKey = `${row}-${matchCol}`;
                                        if (!cellCache.has(cacheKey)) {
                                            const getTargetCols = table.querySelector(`[data-row='${row}'][data-column='${matchCol}']`);
                                            cellCache.set(cacheKey, getTargetCols);
                                        }
                                        
                                        let getTargetCols = cellCache.get(cacheKey);

                                        if (!getTargetCols || getTargetCols.textContent === "") {
                                            updatednewInvolvedFormula = updatednewInvolvedFormula.replace(matchCol, "0");
                                        } else {
                                            // let getTargetCol = getTargetCols.textContent.trim();
                                            let getTargetCol = getTargetCols.getAttribute("data-original");

                                            updatednewInvolvedFormula = updatednewInvolvedFormula.replace(matchCol, getTargetCol);
                                        }
                                    });
    
                                    try {

                                        involvedResult = evaluate(updatednewInvolvedFormula);
                                        involvedResult = involvedResult.toFixed(2);
                                        roundedResult = Math.round(involvedResult);

                                        includesCol.textContent = roundedResult;
                                        includesCol.setAttribute("data-original", involvedResult);

                                    } catch (error) {
                                        includesCol.textContent = 'Invalidss!';
                                        return;
                                    }
    

                                    let id = includesCol.getAttribute('data-id');
                                    let involvedResults = {
                                        id: id || null,
                                        tableId: table.id,
                                        content: involvedResult,
                                        colspan: 1,
                                        rowspan: 1,
                                        merged: false
                                    }

                                    bulkUpdates.push(involvedResults);
                                    // fetch(`/update-content-cell`, {            
                                    // method: "POST",
                                    // headers: {
                                    //     'Content-Type': 'application/json',
                                    //     'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                    // },
                                    // body: JSON.stringify(involvedResults)    
                                    // })
                                    // .then(response => response.json())
                                    // .then(data => {
                                    //     console.log("Server Response:", data);
                            
                                    //     // If there's no ID, update the cell with the returned ID from the server
                                    //     if (!id && data.ids) {
                                    //         cell.setAttribute('data-id', data.ids);
                                    //     }
                                    // })
                                    // .catch(error => {
                                    //     console.error("Error:", error);
                                    // });
    
                                // }


                            });

                            // GETTING DATA FROM MAIN-TABLE TO RATING-TABLE
                            recalculateTimeoutRating = setTimeout(() => {
                                if(getMidColumn.value.trim() != '' || getFinColumn.value.trim() != '') {
                                    if(midTermCol || getMidColumn.value.trim()) {
                                        const getRatingMid = table.querySelector(`[data-column="${getMidColumn.value.trim()}"][data-row="${row}"]`);
                                        const getResultMid = getRatingMid.getAttribute('data-original');

                                        handleGradeInputBlur(row, getResultMid, 'mid');
                                    } 
                                    if (finTermCol || getFinColumn.value.trim()) {
                                        const getRatingFin = table.querySelector(`[data-column="${getFinColumn.value.trim()}"][data-row="${row}"]`);
                                        const getResultFin = getRatingFin.getAttribute('data-original');

                                        handleGradeInputBlur(row, getResultFin, 'final');
                                    }
                                }
                            }, 800);

                            if (bulkUpdates.length > 0) {
                                fetch("/bulk-update-content-cell", {
                                    method: "POST",
                                    headers: {
                                        "Content-Type": "application/json",
                                        "X-CSRF-TOKEN": document.querySelector("meta[name='csrf-token']").getAttribute("content")
                                    },
                                    body: JSON.stringify({ cells: bulkUpdates, tableId: table.id })
                                })
                                .then(response => response.json())
                                .then(data => {
                                    console.log("Server Response:", data);
                                    if (data.ids) {
                                        data.ids.forEach((newId, index) => {
                                            let updatedCell = table.querySelector(
                                                `td[data-row="${bulkUpdates[index].row}"][data-column="${bulkUpdates[index].column}"]`
                                            );
                                            if (updatedCell && !bulkUpdates[index].id) {
                                                updatedCell.setAttribute("data-id", newId);
                                            }
                                        });
                                    }
                                })
                                .catch(error => console.error("Error:", error));
                            }
                        }
                    });
                    }, 800); 
                });
            });
        }
    
    }   
    //RECALCULATION OF INPUTS ENDS HERE!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!


    const gradeState = {};

    // RECALCULATION OF RATING-TABLE!!!!!!!!!!!!!!!!!!!!!!!!!!!!
    async function handleGradeInputBlur(row, involvedResult, term) {
        const currentRow = row;
    
        let tableRating = null;
        tableRatings.forEach(table => {
            if (table.querySelector(`td[data-row='${currentRow}']`)) {
                tableRating = table;
            }
        });
    
        if (!tableRating) return; // Exit if no table found
    
        const roomId = tableRating.getAttribute('data-room-id');
        const teacherId = tableRating.getAttribute('data-teacher-id');
        const studentName = tableRating.querySelector(`td[data-column='#1'][data-row='${currentRow}']`);
    
        try {
            const columns = {
                midColumn: tableRating.querySelector(`td[data-column='MidGr.'][data-row='${currentRow}']`),
                midEqvColumn: tableRating.querySelector(`td[data-column='Mid.N.Eqv.'][data-row='${currentRow}']`),
                midFinalColumn: tableRating.querySelector(`td[data-column='Mid'][data-row='${currentRow}']`),
                tfColumn: tableRating.querySelector(`td[data-column='T.F.Gr.'][data-row='${currentRow}']`),
                tfEqvColumn: tableRating.querySelector(`td[data-column='F.N.Eqv.'][data-row='${currentRow}']`),
                tfFinalColumn: tableRating.querySelector(`td[data-column='Fin'][data-row='${currentRow}']`),
                finalEquivalent: tableRating.querySelector(`td[data-column='FR.Eqv'][data-row='${currentRow}']`),
                finalNumberEquivalent: tableRating.querySelector(`td[data-column='FR.N.Eqv'][data-row='${currentRow}']`),
                remarks: tableRating.querySelector(`td[data-column='Remarks'][data-row='${currentRow}']`)
            };
    
            if (!gradeState[currentRow]) {
                gradeState[currentRow] = { mid: null, final: null };
            }
    
            if (term === 'mid' && columns.midColumn && columns.midEqvColumn && columns.midFinalColumn) {
                const midColOrigValue = parseFloat(columns.midColumn.getAttribute("data-original")) || 0;
                if (midColOrigValue !== 0) {
                    columns.midColumn.textContent = Math.round(involvedResult);
                    columns.midColumn.setAttribute('data-original', involvedResult);
    
                    const showMidEqvCol = getGradeEquivalent(Math.round(midColOrigValue));
                    columns.midEqvColumn.textContent = showMidEqvCol;
                    columns.midEqvColumn.setAttribute('data-original', showMidEqvCol);
    
                    const showMidTermGrade = getMidTermGrade(involvedResult);
                    columns.midFinalColumn.textContent = showMidTermGrade.display;
                    columns.midFinalColumn.setAttribute('data-original', showMidTermGrade.value);
    
                    gradeState[currentRow].mid = showMidTermGrade;
                }
            }
    
            if (term === 'final' && columns.tfColumn && columns.tfEqvColumn && columns.tfFinalColumn) {
                const tfColOrigValue = parseFloat(columns.tfColumn.getAttribute("data-original")) || 0;
                if (tfColOrigValue !== 0) {
                    columns.tfColumn.textContent = Math.round(involvedResult);
                    columns.tfColumn.setAttribute('data-original', involvedResult);
    
                    const showTFEqvCol = getGradeEquivalent(Math.round(tfColOrigValue));
                    columns.tfEqvColumn.textContent = showTFEqvCol;
                    columns.tfEqvColumn.setAttribute('data-original', showTFEqvCol);
    
                    const showFinalTermGrade = getFinTermGrade(involvedResult);
                    columns.tfFinalColumn.textContent = showFinalTermGrade.display;
                    columns.tfFinalColumn.setAttribute('data-original', showFinalTermGrade.value);
    
                    gradeState[currentRow].final = showFinalTermGrade;
                }
            }
    
            if (columns.finalEquivalent && gradeState[currentRow].mid && gradeState[currentRow].final) {
                const finalRate = getFinalRateGrade(
                    gradeState[currentRow].mid.value,
                    gradeState[currentRow].final.value
                );
    
                columns.finalEquivalent.textContent = finalRate.display;
                columns.finalEquivalent.setAttribute('data-original', finalRate.value);
    
                if (columns.finalNumberEquivalent) {
                    const showFinGradeRate = getGradeEquivalent(parseFloat(finalRate.display));
                    columns.finalNumberEquivalent.textContent = showFinGradeRate;
                    columns.finalNumberEquivalent.setAttribute('data-original', showFinGradeRate);
    
                    const showRemarks = getPassedOrFailed(finalRate.display);
                    columns.remarks.textContent = showRemarks;
                    columns.remarks.setAttribute('data-original', showRemarks);
                }
            }
    
            const bulkUpdatedData = [];
            Object.keys(columns).forEach(key => {
                const columnElement = columns[key];
                if (columnElement) {
                    const content = columnElement.getAttribute('data-original');
                    if (content) {
                        bulkUpdatedData.push({
                            teacher_id: teacherId,
                            room_id: roomId,
                            student_name: studentName?.getAttribute("data-room-student") || 'Unknown',
                            column: columnElement.getAttribute("data-column"),
                            row: currentRow,
                            content: content,
                            merged: 0,
                            rowspan: 1,
                            colspan: 1
                        });
                    }
                }
            });
    
            if (bulkUpdatedData.length > 0) {
                await fetch("/save-number-grade", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
                    },
                    body: JSON.stringify({ grades: bulkUpdatedData })
                });
            }
    
        } catch (error) {
            console.error("Error:", error);
            alert("Something went wrong. Please try again.");
        }
    }
    


    
    // RECONDITION OF FORMULA STARTS HERE!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
    function reCondition(formula) {
        const operationACache = [...table.querySelectorAll('.cursor-cell[data-column="A"][data-operation="operation"]')];
        let originalContent = '';
        let originalContentLength = 0;
        const conditionMatch = formula.match(/if\(([^,]+),/);
        const matchTrue = formula.match(/if\(.+?,(.+?),.+\)/);
        const matchFalse = formula.match(/if\(.+?,.+?,(.+?)\)/);
        const condition = conditionMatch ? conditionMatch[1].trim() : null;
        const matchTrueContent = matchTrue ? matchTrue[1].trim() : null;
        const matchFalseContent = matchFalse ? matchFalse[1].trim() : null;
        let newFormula = condition;
        let result = null;
        const uniqueColumns = [];
    
        let resultFirstFontColor = null;
        let resultSecondFontColor = null;
        let resultFirstBgColor = null;
        let resultSecondBgColor = null;
    
        if(startRow) {
            const formulaData = [];
            const allFormula = table.querySelectorAll(`[data-formula][data-row='${startRow}']`);
            allFormula.forEach(element => {
                formulaData.push(element.dataset.formula);
            });
    
            function extractColumns(formula) {
                const columns = new Set();
                const regex = /(?<![A-Z])([A-Z]{1,2})(?![a-zA-Z0-9])/g; 
            
                let match;
                while ((match = regex.exec(formula)) !== null) {
                    columns.add(match[1]); // Add valid column name to the set
                }
                return columns;
            }
            
            // Combine results for all formulas
            const allColumns = new Set();
            formulaData.forEach(formula => {
                const columns = extractColumns(formula);
                columns.forEach(col => allColumns.add(col));
            });
            
            // Convert the set to an array and sort
            const columnsInFormula = Array.from(allColumns).sort();
            uniqueColumns.push(...columnsInFormula);
        }
    
        const cellCache = new Map();
    
        if(uniqueColumns) {
            uniqueColumns.forEach(cols => {
                operationACache.forEach(operationCell => {
                    const operationRow = operationCell.dataset.row;
                    const involvedCell = table.querySelector(`[data-column='${cols}'][data-row='${operationRow}']`);
    
                    involvedCell.addEventListener('focus', function(event) {
                        let currentCell = event.target;
    
                        originalContent = currentCell.textContent.trim();
                        originalContentLength = originalContent.length;
    
                    });
    
                    involvedCell.addEventListener('blur', (event) => {
                        let currentCell = event.target;
                        
                        const updatedContent = currentCell.textContent.trim();
                        const updatedContentLength = updatedContent.length;
    
                        if(originalContent !== updatedContent || originalContentLength !== updatedContentLength) {
                            const row = event.target.getAttribute('data-row');
                            // const targetCell = currentCell.getAttribute('data-column');
                    
                            const formulaCells = [...table.querySelectorAll(`[data-formula='${formula}'][data-row='${row}']`)];
                            const updates = [];
                            
                            formulaCells.forEach(formulaCell => {
                                let updatedFormula = newFormula;
                                const targetCell = formulaCell.getAttribute('data-column');
                                const colorsInCondition = table.querySelector(`[data-column='${targetCell}'][data-row='${startRow}']`);
    
                                
                                //getting the font colors used in color condition
                                const getFontColors = colorsInCondition.getAttribute('font-colors');
                                if(getFontColors) {
                                    const matchFirstFontColor = getFontColors.split(',');
                                    resultFirstFontColor = matchFirstFontColor ? matchFirstFontColor[0] : null;
                                    const matchSecondFontColor = getFontColors.split(',');
                                    resultSecondFontColor = matchSecondFontColor ? matchSecondFontColor[1] : null;
                                }
    
                                //getting the font colors used in color condition
                                const getBgColors = colorsInCondition.getAttribute('bg-colors');
                                if(getBgColors) {
                                    const matchFirstBgColor = getBgColors.split(',');
                                    resultFirstBgColor = matchFirstBgColor ? matchFirstBgColor[0] : null;
                                    const matchSecondBgColor = getBgColors.split(',');
                                    resultSecondBgColor = matchSecondBgColor ? matchSecondBgColor[1] : null;
                                }
    
                                // const colToReplace = formulaCell.dataset.column;
                                uniqueColumns.forEach(colToReplace => {
                                    const cacheKey = `${row}-${colToReplace}`;
                                    if (!cellCache.has(cacheKey)) {
                                        const cellContent = table.querySelector(`[data-row='${row}'][data-column='${colToReplace}']`);
                                        cellCache.set(cacheKey, cellContent);
                                    }
                                    
                                    let cellContent = cellCache.get(cacheKey);
    
                                    
                                    if (!cellContent || cellContent.textContent === "") {
                                        updatedFormula = updatedFormula.replace(colToReplace, '0');
                                    } else {
                                        cellContent = cellContent.textContent.trim();
                                        updatedFormula = updatedFormula.replace(colToReplace, cellContent);
                                    }
                                });
    
                                try {
                                    result = evaluate(updatedFormula);
    
                                    fontAndBgRecondition(formulaCell, result, resultFirstFontColor, resultSecondFontColor, resultFirstBgColor, resultSecondBgColor);
                                    
                                    result = result ? matchTrueContent : matchFalseContent;
                                    updates.push({ formulaCell, result });
                                } catch (error) {
                                    updates.push({ formulaCell, result: 'Invalids!' });
                                }
                            });
    
                            const roomID = table.getAttribute('data-room-id');
    
                            updates.forEach(({ formulaCell, result }) => {
                                formulaCell.textContent = result;
    
                                let id = formulaCell.getAttribute('data-id');
                                let row = formulaCell.getAttribute('data-row');
                                let column = formulaCell.getAttribute('data-column');
                                let studentCell = table.querySelector(`td[data-row="${row}"][data-column="A"]`);
                                let student_name = studentCell ? studentCell.getAttribute('data-room-student') : null;
    
                                let results = {
                                    tableId: table.id,
                                    content: result,
                                    room_id: roomID,
                                    student_name: student_name,
                                    merged: false,
                                    rowspan: 1,
                                    colspan: 1,
                                    row: row,
                                    column: column
                                }
                                
                                const url = id ? `/update-content-cell/${id}` : '/save-calculated-cell';
                                fetch(url, {            
                                method: "POST",
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                },
                                body: JSON.stringify(results)    
                                })
                                .then(response => response.json())
                                .then(data => {
                                    console.log("Server Response:", data);
                        
                                    // If there's no ID, update the cell with the returned ID from the server
                                    if (!id && data.ids) {
                                        cell.setAttribute('data-id', data.ids);
                                        console.log(data.ids);
                                    }
                                })
                                .catch(error => {
                                    console.error("Error:", error);
                                });
                            });
    
                            // const involvedCols = [...table.querySelectorAll(`[data-formula][data-row='${row}']`)];
    
                            // involvedCols.forEach(includesCol => {
                            //     const targetFormula = includesCol.dataset.formula;
                            //     let checkInvolveFormula = null;
    
                            //     let subFormula = targetFormula.substring(1);
                            //     if (subFormula.startsWith('SUM')) {
                            //         checkInvolveFormula = targetFormula.substring(4);
                            //         checkInvolveFormula = expandRange(checkInvolveFormula).join('+');
                            //     } else {
                            //         checkInvolveFormula = targetFormula.substring(1);
                            //     }
    
                                
                            //     if (!checkInvolveFormula.includes(targetCell)) {
                            //         // console.log(includesCol);
                            //         let newInvolvedFormula = null;
                            //         // const subFormula = targetFormula.substring(1);
                            //         if (subFormula.startsWith('SUM')) {
                            //             newInvolvedFormula = targetFormula.substring(4);
                            //             newInvolvedFormula = expandRange(newInvolvedFormula).join('+');
                            //         } else {
                            //             newInvolvedFormula = targetFormula.substring(1);
                            //         }
    
                            //         // console.log(newInvolvedFormula);
    
                            //         let updatednewInvolvedFormula = newInvolvedFormula;
                            //         const matchTargetFormula = newInvolvedFormula.match(/[A-Z]{1,2}/g);
    
                            //         matchTargetFormula.forEach(matchCol => {
                            //             const cacheKey = `${row}-${matchCol}`;
                            //             if (!cellCache.has(cacheKey)) {
                            //                 const getTargetCols = table.querySelector(`[data-row='${row}'][data-column='${matchCol}']`);
                            //                 cellCache.set(cacheKey, getTargetCols);
                            //             }
    
                            //             let getTargetCols = cellCache.get(cacheKey);
                            //             if (!getTargetCols || getTargetCols.textContent === "") {
                            //                 updatednewInvolvedFormula = updatednewInvolvedFormula.replace(matchCol, "0");
                            //             } else {
                            //                 getTargetCols = getTargetCols.textContent.trim();
                            //                 updatednewInvolvedFormula = updatednewInvolvedFormula.replace(matchCol, getTargetCols);
                            //             }
                            //         });
    
                            //         try {
                            //             involvedResult = evaluate(updatednewInvolvedFormula);
                            //             involvedResult = Math.round(involvedResult * 100) / 100; // Round to 2 decimal places
                            //             includesCol.textContent = involvedResult;
                            //             // updatesInvolved.push({includesCol, involvedResult});
                            //         } catch (error) {
                            //             // updatesInvolved.push({includesCol, involvedResult: 'result'});
                            //             includesCol.textContent = 'Invalid!';
                            //         }
    
                            //         // updatesInvolved.forEach(({includesCol, involvedResult}) => {
                            //         //     includesCol.textContent = involvedResult;
    
                            //         let id = includesCol.getAttribute('data-id');
                            //         let involvedResults = {
                            //             tableId: table.id,
                            //             content: involvedResult,
                            //             colspan: 1,
                            //             rowspan: 1,
                            //             merged: false
                            //         }
                            //             // console.log(id);
                            //         // });
    
                            //         fetch(`/update-content-cell/${id}`, {            
                            //         method: "POST",
                            //         headers: {
                            //             'Content-Type': 'application/json',
                            //             'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            //         },
                            //         body: JSON.stringify(involvedResults)    
                            //         })
                            //         .then(response => response.json())
                            //         .then(data => {
                            //             console.log("Server Response:", data);
                            
                            //             // If there's no ID, update the cell with the returned ID from the server
                            //             if (!id && data.ids) {
                            //                 cell.setAttribute('data-id', data.ids);
                            //             }
                            //         })
                            //         .catch(error => {
                            //             console.error("Error:", error);
                            //         });
    
                            //     }
                            // });
                        }
                    });
    
    
    
                });
            });
        }
    }
    // RECONDITION OF FORMULA ENDS HERE!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
    
    
    function fontAndBgRecondition(formulaCell, result, resultFirstFontColor, resultSecondFontColor, resultFirstBgColor, resultSecondBgColor) {
        const hasInline = formulaCell.style.color || formulaCell.style.backgroundColor;
        if( resultFirstFontColor || resultSecondFontColor) {
            if (hasInline) {
                formulaCell.style.color = '';
            }
            
            const fontColor = result ? resultFirstFontColor : resultSecondFontColor;
            formulaCell.style.color = fontColor;
        }
        // BG COLORS CONDITION                                                            
        if(resultFirstBgColor || resultSecondBgColor) {
            if (hasInline) {
                formulaCell.style.backgroundColor = '';
            }
    
            
            formulaCell.classList.forEach(cls => {
                if (cls.startsWith('bg-')) {
                    formulaCell.classList.remove(cls);
                }
            });
    
            const currentColor = result ? resultFirstBgColor : resultSecondBgColor;
    
            formulaCell.classList.add(`bg-${currentColor}`);
        }
    }


    const gradeRanges = [
        { min: 97, max: 100, grade: 1.0 },
        { min: 94, max: 96, grade: 1.25 },
        { min: 91, max: 93, grade: 1.50 },
        { min: 88, max: 90, grade: 1.75 },
        { min: 85, max: 87, grade: 2.0 },
        { min: 82, max: 84, grade: 2.25 },
        { min: 79, max: 81, grade: 2.50 },
        { min: 76, max: 78, grade: 2.75 },
        { min: 75, max: 75, grade: 3.0 },
        { min: 70, max: 74, grade: 4.0 },
        { min: 0, max: 69, grade: 5.0 }
    ];
    


    function getGradeEquivalent(score) {
        if (isNaN(score)) return "Invalid Score"; // Prevent errors for non-numeric input
        const range = gradeRanges.find(r => score >= r.min && score <= r.max);
        return range ? formatGrade(range.grade) : "Invalid Score";
    }
    
    function formatGrade(grade) {
        if (grade % 1 === 0) {
            return grade.toFixed(1); // Ensures 3.0, 2.0, 1.0
        } else if (grade * 100 % 10 === 0) {
            return grade.toFixed(2); // Ensures 2.50, 1.50, etc.
        }
        return grade; // Default case (if no formatting needed)
    }
    
    function getMidTermGrade(grade) {
        if (isNaN(grade)) return { display: "Invalid Grade", value: null };
    
        const originalValue = parseFloat(grade * 0.4).toFixed(2); 
        const roundedValue = Math.round(originalValue); 
    
        return { display: roundedValue, value: originalValue };
    }
    
    function getFinTermGrade(grade) {
        if (isNaN(grade)) return { display: "Invalid Grade", value: null };
    
        const originalValue = parseFloat(grade * 0.6).toFixed(2);
        const roundedValue = Math.round(originalValue);
    
        return { display: roundedValue, value: originalValue };
    }
    
    function getFinalRateGrade(mid, fin) {
    
        const value = parseFloat(mid) + parseFloat(fin);
        const originalValue = parseFloat(value).toFixed(2);
        const roundedValue = Math.round(originalValue);
    
        return { display: roundedValue, value: originalValue };
    }
    
    function getPassedOrFailed(grade) {
        if(grade >= 75) {
            return 'Passed';
        } else {
            return '';
        }
    }

    
});









// ULOBRAHON ANG FONT AND BG COLOR NGA GA CHANGE KAY WALA GA CHANGE ANG COLOR PAG TAPOS APPLY O GAMIT SANG IF COLOR NGA BUTTON!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!




