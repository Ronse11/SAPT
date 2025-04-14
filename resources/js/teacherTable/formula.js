import { evaluate } from 'mathjs';


// SHOWING OF COLUMNS AND CONTENT STARTS HERE!!!!!!!!!!!!!!!!!!!!!!!!!!!!
const showColRow = document.querySelector('input[name="showRowCol"]');
const showContent = document.querySelector('input[name="showContent"]');
const showFormula = document.querySelector('input[name="formulaInput"]');
const showFontSize = document.getElementById('showFontSize');
const formulaBox = document.getElementById('formulaBox');
const formulaTitle = document.getElementById('formulaTitle');

const table = document.querySelector('.main-table');
const tableId = table.id;
const roomId = table.getAttribute('data-room-id');
const currentTable = document.querySelector(`#main-table`);


document.querySelector('table').addEventListener('click', function(event) {
    const selectedCell = event.target.closest('td');

    
    if (!selectedCell) return; 
    const currentCol = selectedCell.getAttribute('data-column')?.trim();

    const selectedContent = `${currentCol}${selectedCell.getAttribute('data-row')?.trim() || ''}`;
    showColRow.value = selectedContent;

    showContent.value = selectedCell.textContent.trim();

    const fontSizeClass = [...selectedCell.classList].find(cls => cls.startsWith('text-') && cls.endsWith('px'));
    const fontSizeStyle = selectedCell.style.fontSize;
    const fontSize = fontSizeClass ? fontSizeClass.match(/(\d{1,2}|100)px/)[0] : fontSizeStyle || '';

    showFontSize.value = fontSize.replace('px', '');

    if (selectedCell.hasAttribute('data-formula')) {
        showFormula.value = selectedCell.getAttribute('data-formula').trim();
        formulaBox.classList.remove('hidden');
        formulaTitle.classList.add('hidden');
    } else {
        formulaBox.classList.add('hidden');
        formulaTitle.classList.remove('hidden');
        showFormula.value = '';
    }

    let previousUnitValue = null; 
    showFormula.addEventListener('focus', (event) => {
        previousUnitValue = event.target.value.trim();
    });

    showFormula.addEventListener('blur', handleGradeInputBlur);

    async function handleGradeInputBlur(event) {
        const currentValue = event.target.value.trim();

        if(currentValue == previousUnitValue) return;

        try {
            const response = await fetch("/apply-changed-formula", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ tableId, currentCol, currentValue, room_id: roomId })
            });
    
            if (!response.ok) {
                const errorText = await response.text();
                const errorData = JSON.parse(errorText);
                const errorMsg = errorData.errors?.changeName?.[0] || "An error occurred.";
                
                const errorCard = document.getElementById('error-changeName');
                const msgShow = document.querySelector('.errShow');
                errorCard.classList.remove('opacity-0', 'translate-y-[-50px]');
                msgShow.textContent = errorMsg;
                errorCard.classList.add('opacity-100', 'translate-y-0');
                errorCard.classList.remove('pointer-events-none');
                errorCard.classList.add('pointer-events-auto');

                setTimeout(() => {
                    errorCard.classList.remove('opacity-100', 'translate-y-0');
                    errorCard.classList.add('opacity-0', 'translate-y-[-50px]');
                    errorCard.classList.remove('pointer-events-auto');
                    errorCard.classList.add('pointer-events-none');
                }, 2000);
            } else {
                const data = await response.json();
                const successMsg = data.message;

                const successCard = document.getElementById('success-changeName');
                const headText = document.querySelector('.headText');
                successCard.classList.remove('opacity-0', 'translate-y-[-50px]');
                headText.textContent = successMsg;
                successCard.classList.add('opacity-100', 'translate-y-0');
                successCard.classList.remove('pointer-events-none');
                successCard.classList.add('pointer-events-auto');

                setTimeout(() => {
                    successCard.classList.remove('opacity-100', 'translate-y-0');
                    successCard.classList.add('opacity-0', 'translate-y-[-50px]');
                    successCard.classList.remove('pointer-events-auto');
                    successCard.classList.add('pointer-events-none');
                }, 2000);
            }

            const bulkUpdates = [];

            let rowsTable = table.querySelectorAll(`td[data-operation="operation"][data-column="A"]`);
            rowsTable.forEach(row => {
                const studentRows = row.getAttribute('data-row');
                const cell = document.querySelector(`[data-column='${currentCol}'][data-row="${studentRows}"]`);
                const formulaCells = currentTable.querySelectorAll(`td[data-formula][data-row="${studentRows}"]`);
                cell.setAttribute('data-formula', currentValue);
            
                const results = recalculateFormula(roomId, formulaCells);
            
                if (results.length) {
                    bulkUpdates.push(...results);
                }
            });
            


            if (bulkUpdates.length > 0) {
                // console.log(bulkUpdates);
                // fetch("/bulk-update-content-cell", {
                //     method: "POST",
                //     headers: {
                //         "Content-Type": "application/json",
                //         "X-CSRF-TOKEN": document.querySelector("meta[name='csrf-token']").getAttribute("content")
                //     },
                //     body: JSON.stringify({ cells: bulkUpdates, tableId: table.id })
                // })
                // .then(response => response.json())
                // .then(data => {
                //     console.log("Server Response:", data);
                //     if (data.ids) {
                //         data.ids.forEach((newId, index) => {
                //             let updatedCell = table.querySelector(
                //                 `td[data-row="${bulkUpdates[index].row}"][data-column="${bulkUpdates[index].column}"]`
                //             );
                //             if (updatedCell && !bulkUpdates[index].id) {
                //                 updatedCell.setAttribute("data-id", newId);
                //             }
                //         });
                //     }
                // })
                // .catch(error => console.error("Error:", error));
            }


        } catch (error) {
            console.error("Error:", error);
            alert("Something went wrong. Please try again.");
        }
    }



});
// SHOWING OF COLUMNS AND CONTENT ENDS HERE!!!!!!!!!!!!!!!!!!!!!!!!!!!!







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
    function recalculateFormula(roomId, formulaCells) {
        const results = [];
    
        formulaCells.forEach(cellFormula => {
            const rawFormula = cellFormula.getAttribute('data-formula');
            if (!rawFormula || !rawFormula.startsWith('=')) return;
    
            const row = cellFormula.getAttribute('data-row');
            let cleanedFormula = rawFormula.slice(1);
            let columns = [];
            let values = [];
    
            if (cleanedFormula.startsWith('SUM(') && cleanedFormula.endsWith(')')) {
                const range = cleanedFormula.slice(4, -1); // e.g. A:C
                columns = expandRange(range);
    
                columns.forEach(col => {
                    const targetCell = document.querySelector(`td[data-column="${col}"][data-row="${row}"]`);
                    const value = targetCell ? parseFloat(targetCell.textContent.trim()) || 0 : 0;
                    values.push(value);
                });
    
                const sum = values.reduce((a, b) => a + b, 0);
                const originalResult = sum.toFixed(2);
                const displayResult = Math.round(sum);
                cellFormula.textContent = displayResult;
    
                const id = cellFormula.getAttribute('data-id');
                const column = cellFormula.getAttribute('data-column');
                const studentCell = currentTable.querySelector(`td[data-row="${row}"][data-column="A"]`);
                const student_name = studentCell ? studentCell.getAttribute('data-room-student') : null;
    
                results.push({
                    id: id || null,
                    tableId: currentTable.id,
                    content: originalResult,
                    room_id: roomId,
                    student_name: student_name,
                    merged: false,
                    rowspan: 1,
                    colspan: 1,
                    row: row,
                    column: column
                });
    
            } else {
                // Handle basic math formulas like A*5, B+C
                columns = cleanedFormula.match(/[A-Z]{1,2}/g) || [];
                const scope = {};
    
                columns.forEach(col => {
                    const targetCell = document.querySelector(`td[data-column="${col}"][data-row="${row}"]`);
                    const value = targetCell ? parseFloat(targetCell.textContent.trim()) || 0 : 0;
                    scope[col] = value;
                });
    
                try {
                    const result = evaluate(cleanedFormula, scope);
                    const originalResult = result.toFixed(2);
                    const displayResult = Math.round(result);
                    cellFormula.textContent = displayResult;
    
                    const id = cellFormula.getAttribute('data-id');
                    const column = cellFormula.getAttribute('data-column');
                    const studentCell = currentTable.querySelector(`td[data-row="${row}"][data-column="A"]`);
                    const student_name = studentCell ? studentCell.getAttribute('data-room-student') : null;
    
                    results.push({
                        id: id || null,
                        tableId: currentTable.id,
                        content: originalResult,
                        room_id: roomId,
                        student_name: student_name,
                        merged: false,
                        rowspan: 1,
                        colspan: 1,
                        row: row,
                        column: column
                    });
                } catch (error) {
                    console.error(`Error evaluating formula: ${rawFormula}`, error);
                    cellFormula.textContent = 'ERR';
                }
            }
        });
    
        return results;
    }
    
    //RECALCULATION OF INPUTS ENDS HERE!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!