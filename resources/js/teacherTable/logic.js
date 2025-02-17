import { evaluate } from "mathjs";

document.addEventListener('DOMContentLoaded', function() {
    const table = document.querySelector('.main-table');
    const ifCondition = document.getElementById('ifCondition');
    const logic = document.getElementById('logic');
    const closeIf = document.getElementById('closeIf');
    const cancelIf = document.getElementById('cancelIf');
    const condition = document.getElementById('condition');
    const tableId = table.id;
    const roomID = table.getAttribute('data-room-id');;


    const draggable = document.getElementById('draggable');
    const header = document.getElementById('header');

    const ifTrueInput = document.getElementById('resultTrue');
    const ifFalseInput = document.getElementById('resultFalse');


    let offsetX = 0, offsetY = 0, mouseX = 0, mouseY = 0, isDragging = false;
    let currentX = 0, currentY = 0;  // To keep track of the current position of the div
    let selectedCell = null;
    let currentCondition = '';
    let storeCondition = '';
    let latestTrue = null;
    let latestFalse = null;
    

    header.addEventListener('mousedown', function(e) {
        isDragging = true;
        mouseX = e.clientX;
        mouseY = e.clientY;
        const rect = draggable.getBoundingClientRect();
        offsetX = mouseX - rect.left;
        offsetY = mouseY - rect.top;
    });

    document.addEventListener('mousemove', function(e) {
        if (isDragging) {
            const x = e.clientX - offsetX;
            const y = e.clientY - offsetY;

            // Calculate the new position relative to its current position
            const deltaX = e.clientX - mouseX;
            const deltaY = e.clientY - mouseY;

            currentX += deltaX;
            currentY += deltaY;

            draggable.style.transform = `translate(${currentX}px, ${currentY}px)`;

            mouseX = e.clientX;
            mouseY = e.clientY;
        }
    });

    document.addEventListener('mouseup', function() {
        isDragging = false;
    });

    ifCondition.addEventListener('click', conditionFunction);

    closeIf.addEventListener('click', () => {
        reset(table); 
        currentX = 0;
        currentY = 0;
    });
    
    cancelIf.addEventListener('click', () => {
        reset(table);
        currentX = 0;
        currentY = 0;
    });
    



    function conditionFunction() {
        selectedCell = document.querySelector('td.selected');
        
        if(selectedCell) {
            performCondition(selectedCell);
        } else {
            alert('No Selected Cell!');
        }

    }
 
    //PERFORMING OF CONDITION STARTS HERE!!!! 
    function performCondition(selectedCell) {
        if (selectedCell && selectedCell.textContent === '') {
            logic.classList.remove('hidden');

            selectedCell.textContent = 'if()';

            table.addEventListener('focusin', performingCondition);

            condition.addEventListener('input', conditionValue);

            condition.addEventListener('blur', (event) => {
                clickedOk();
            });

        } else {
            alert('Selected cell should not have content!')
        }
    }

    const conditionValue = (event) => {
        storeCondition = event.target.value;
                
        selectedCell.textContent = `if(${storeCondition})`;
    };

    const updateIfTrueInput = (event) => {
        latestTrue = event.target.value;
        selectedCell.textContent = `if(${storeCondition},${latestTrue})`;
    };

    const updateIfFalseInput = (event) => {
        latestFalse = event.target.value;
        selectedCell.textContent = `if(${storeCondition},${latestTrue},${latestFalse})`;
    };
    function performingCondition(event) {
        const cursorCell = event.target;
    
        if (cursorCell && cursorCell !== selectedCell) {
            const newCol = cursorCell.dataset.column;
    
            currentCondition = newCol;
    
    
            ifTrueInput.addEventListener('input', updateIfTrueInput);
            ifFalseInput.addEventListener('input', updateIfFalseInput);
    
            // Update the condition value and check math symbols
            const mathSymbols = /[+\-*<>/=]$/;
            condition.value = mathSymbols.test(storeCondition)
                ? `${storeCondition}${currentCondition}`
                : currentCondition;
    
            placeCaretAtEnd(condition);
        }
    }
    

    function clickedOk() {
        const ifOk = document.getElementById('ifOk');
        // const table = document.getElementById('main-table');
        const conditionInput = document.getElementById('condition');
        const ifTrueInput = document.getElementById('resultTrue');
        const ifFalseInput = document.getElementById('resultFalse');
        
        // Helper function to evaluate conditions
        function evaluateCondition(cellA, cellB, operator) {
            const valueA = typeof cellA === 'number' ? cellA : parseFloat(cellA.innerText || cellA.value) || 0;
            const valueB = typeof cellB === 'number' ? cellB : parseFloat(cellB.innerText || cellB.value) || 0;
            switch (operator) {
                case '>': return valueA > valueB;
                case '<': return valueA < valueB;
                case '>=': return valueA >= valueB;
                case '<=': return valueA <= valueB;
                case '==': return valueA === valueB;
                case '!=': return valueA !== valueB;
                default: console.error('Invalid operator'); return false;
            }
        }

        // Helper function to update result cell
        function updateResultCell(resultCol, resultText) {
            resultCol.textContent = resultText;
        }

        // Main click event handler
        function onClickEvent(event) {
            const latestCondition = selectedCell.textContent;
            const selectedColumn = selectedCell.dataset.column;
            const operationACache = [...table.querySelectorAll('[data-column="A"][data-operation="operation"]')];
            const latestTrue = ifTrueInput.value.trim();
            const latestFalse = ifFalseInput.value.trim();
            
            const conditionPattern = /\s*([A-Z]{1,2}|\d+)\s*([><=]{1,2})\s*([A-Z]{1,2}|\d+)\s*/gi;
            const matchCondition = [...latestCondition.matchAll(conditionPattern)];

            let columnFormula = {
                tableId: tableId,
                room_id: roomID, 
                column: selectedColumn,
                formula: latestCondition
            }
            saveFormula(columnFormula);
            reCondition(latestCondition);

            let saveData = [];
            
            matchCondition.forEach(([_, colA, operator, colB]) => {
                operationACache.forEach(operationCol => {
                    const operationRow = operationCol.dataset.row;
                    
                    const cellA = !isNaN(colA) ? parseFloat(colA) : table.querySelector(`[data-column='${colA}'][data-row='${operationRow}']`);
                    const cellB = !isNaN(colB) ? parseFloat(colB) : table.querySelector(`[data-column='${colB}'][data-row='${operationRow}']`);
                    const resultCol = table.querySelector(`[data-column='${selectedColumn}'][data-row='${operationRow}']`);
                    const nameCells = table.querySelector(`[data-column='A'][data-row='${operationRow}']`);
                    const studentName = nameCells.dataset.roomStudent;

                    
                    if (cellA !== null && cellB !== null) {
                        const result = evaluateCondition(cellA, cellB, operator);
                        const resultText = result ? latestTrue : latestFalse;
                        updateResultCell(resultCol, resultText);
                        
                        let dataPerform = {
                            tableId: tableId,
                            room_id: roomID, 
                            student_name: studentName, 
                            column: resultCol.dataset.column,
                            row: resultCol.dataset.row,
                            content: resultText,
                            rowspan: resultCol.getAttribute('rowspan') || 1, 
                            colspan: resultCol.getAttribute('colspan') || 1,
                        };
                        saveData.push(dataPerform);
                    }
                });
            });
            
            saveResult(saveData);
            logic.classList.add('hidden');
            draggable.removeAttribute('style');
            conditionInput.value = '';  // Clear the condition field
            selectedCell = null;
            reset(table);
        }

        ifOk.addEventListener('click', onClickEvent);
    }

    //SAVING OF FORMULA ENDS HERE!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
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
                console.log('Data saved successfully:', result.formula);
    
                // Apply formula to all cells in the same column
                const column = result.column; // column number or identifier
                const formula = result.formula;
                
                // Select all cells in the specific column (e.g., using data-column attribute)
                const cells = document.querySelectorAll(`[data-column='${column}']`);
                
                // Loop through and set the data-formula attribute
                cells.forEach(cell => {
                    cell.setAttribute('data-formula', formula);
                });
    
            } else {
                throw new Error('Network response was not ok');
            }
    
        } catch (error) {
            console.error('Error saving result:', error.message);
        }
    }
    //SAVING OF FORMULA ENDS HERE!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

    async function saveResult(dataPerform) {
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

    function placeCaretAtEnd(selectedCell) {
        const range = document.createRange();
        const sel = window.getSelection();
        range.selectNodeContents(selectedCell);
        range.collapse(false);
        sel.removeAllRanges();
        sel.addRange(range);
        selectedCell.focus();
    }

    function reset(table) {
        logic.classList.add('hidden'); // Hide the condition modal
        draggable.removeAttribute('style'); // Reset draggable styles
    
        if (selectedCell) {
            selectedCell.textContent = ''; // Reset the cell content
            selectedCell.classList.remove('selected'); // Remove the selected class (if applicable)
        }
    
        storeCondition = null; // Clear the condition string
        condition.value = ''; 
        latestFalse = null;
        latestTrue = null;

        ifTrueInput.value = '';
        ifFalseInput.value = '';
    
        // Detach listeners
        ifTrueInput.removeEventListener('input', updateIfTrueInput);
        ifFalseInput.removeEventListener('input', updateIfFalseInput);
        table.removeEventListener('focusin', performingCondition);
        condition.removeEventListener('input', conditionValue);
    
        // Clear the reference to the selected cell
        selectedCell = null;
    }


        // RECONDITION OF FORMULA STARTS HERE!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
        function reCondition(formula) {
            console.log(formula);
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
        
            let resultFirstFontColor = null;
            let resultSecondFontColor = null;
            let resultFirstBgColor = null;
            let resultSecondBgColor = null;

            const columnsCache = [];
        
            const cellCache = new Map();

            const getAllFormula = [...table.querySelectorAll(`[data-formula][data-row='${startRow}']`)]
            let allColumns = null;
            getAllFormula.forEach(cols => {
                const formula = cols.getAttribute('data-formula');
                
                if (!formula) return;

                if(formula.startsWith('=SUM')) {
                    const newFormula = formula.substring(4);
                    allColumns = [...newFormula.match(/(?:[A-Z]{1,2})/g)];
                } else {
                    allColumns = [...formula.match(/(?:[A-Z]{1,2})/g)];
                }
            
                if (formula.trim().toLowerCase().startsWith('if')) {
                    const firstCommaIndex = formula.indexOf(',');
                    if (firstCommaIndex !== -1) {
                        const conditionPart = formula.slice(0, firstCommaIndex);
                        const conditionalColumns = [...conditionPart.match(/(?:[A-Z]{1,2})/g)];
                        
                        columnsCache.push(...conditionalColumns);
                    }
                } else {
                    columnsCache.push(...allColumns);
                }
            });

            if(formula.startsWith('=SUM')) {
                console.log(formula);
                const newFormula = formula.substring(4);
                allColumns = [...newFormula.match(/(?:[A-Z]{1,2})/g)];
            } else {
                allColumns = [...formula.match(/(?:[A-Z]{1,2})/g)];
            }
        
            if (formula.trim().toLowerCase().startsWith('if')) {
                const firstCommaIndex = formula.indexOf(',');
                if (firstCommaIndex !== -1) {
                    const conditionPart = formula.slice(0, firstCommaIndex);
                    const conditionalColumns = [...conditionPart.match(/(?:[A-Z]{1,2})/g)];
                    
                    columnsCache.push(...conditionalColumns);
                }
            } else {
                columnsCache.push(...allColumns);
            }
            
            const uniqueColumns = [...new Set(columnsCache)];

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
                                    updates.push({ formulaCell, result: 'Invalidss!' });
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
                        }
                    });
    
                });
            });
                        
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


});




// DAPAT ANG TANAN NGA MAY FORMULA INVOLVED PARA MAG GANA BISAN DIIN LANG KO MAG BLUR!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!