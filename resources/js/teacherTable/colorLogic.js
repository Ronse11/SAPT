import { column, evaluate } from "mathjs";

document.addEventListener('DOMContentLoaded', function() {
    const ifCondition = document.getElementById('ifConditionColor');
    const logic = document.getElementById('logicColor');
    const closeIf = document.getElementById('closeIfColor');
    const cancelIf = document.getElementById('cancelIfColor');
    const condition = document.getElementById('conditionColor');
    const table = document.querySelector('.main-table');
    const tableId = table.id;
    const roomID = table.getAttribute('data-room-id');
    const teacherID = table.getAttribute('data-teacher-id');

    const draggable = document.getElementById('draggableColor');
    const header = document.getElementById('headerColor');

    const ifTrueInput = document.getElementById('resultTrueColor');
    const ifFalseInput = document.getElementById('resultFalseColor');

    const ifOk = document.getElementById('ifOkColor');

    let isClicked = false;  

    // Color and Font Color
    // If True Color Font
    const ifCaretTrueColorFont = document.getElementById('ifCaretTrueColorFont');
    const dropdownIfTrueColorFont = document.getElementById('dropdownIfTrueColorFont');
    const dropdownTrueOptionColorFont = document.querySelectorAll('.dropdownTrueOptionColorFont');
    const showingIfTrueFontColor = document.getElementById('showingIfTrueFontColor');
    let selectedTrueColorFont = null;
    ifCaretTrueColorFont.addEventListener('click', (e) => {
        dropdownIfTrueColorFont.classList.toggle('hidden');
    });
    document.addEventListener('click', (e) => {
        if (!ifCaretTrueColorFont.contains(e.target)) {
            dropdownIfTrueColorFont.classList.add('hidden');
        }
    });
    // If True Color Background
    const ifCaretTrueColor = document.getElementById('ifCaretTrueColor');
    const dropdownIfTrueColor = document.getElementById('dropdownIfTrueColor');
    const dropdownTrueOptionColors = document.querySelectorAll('.dropdownTrueOptionColors');
    const showingIfTrueColor = document.getElementById('showingIfTrueColor');
    let selectedTrueColor = null;
    ifCaretTrueColor.addEventListener('click', (e) => {
        dropdownIfTrueColor.classList.toggle('hidden');
    });
    document.addEventListener('click', (e) => {
        if (!ifCaretTrueColor.contains(e.target)) {
            dropdownIfTrueColor.classList.add('hidden');
        }
    });

    // If False Color Font
    const ifCaretFalseColorFont = document.getElementById('ifCaretFalseColorFont');
    const dropdownIfFalseColorFont = document.getElementById('dropdownIfFalseColorFont');
    const dropdownFalseOptionColorFont = document.querySelectorAll('.dropdownFalseOptionColorFont');
    const showingIfFalseFontColor = document.getElementById('showingIfFalseFontColor');
    let selectedFalseColorFont = null;
    ifCaretFalseColorFont.addEventListener('click', (e) => {
        dropdownIfFalseColorFont.classList.toggle('hidden');
    });
    document.addEventListener('click', (e) => {
        if (!ifCaretFalseColorFont.contains(e.target)) {
            dropdownIfFalseColorFont.classList.add('hidden');
        }
    });
    //If False Color Background
    const ifCaretFalseColor = document.getElementById('ifCaretFalseColor');
    const dropdownIfFalseColor = document.getElementById('dropdownIfFalseColor');
    const showingIfFalseColor = document.getElementById('showingIfFalseColor');
    const dropdownFalseOptionColors = document.querySelectorAll('.dropdownFalseOptionColors');
    let selectedFalseColor = null;
    ifCaretFalseColor.addEventListener('click', (e) => {
        dropdownIfFalseColor.classList.toggle('hidden');
    });
    document.addEventListener('click', (e) => {
        if (!ifCaretFalseColor.contains(e.target)) {
            dropdownIfFalseColor.classList.add('hidden');
        }
    });

    // Applying Font Color
    dropdownTrueOptionColorFont.forEach(options => {
        options.addEventListener('click', (e) => {
            selectedTrueColorFont = e.target.getAttribute('data-shade');
            isClicked = true;
            
            // applyFontColors(selectedTrueColor);
            dropdownIfFalseColor.classList.add('hidden'); 

            const hasInline = showingIfTrueFontColor.style.color;
        
            if (hasInline) {
                showingIfTrueFontColor.style.color = '';
            }

            showingIfTrueFontColor.style.color = selectedTrueColorFont;
            showingIfTrueFontColor.setAttribute('data-shade', selectedTrueColorFont);

        });
        options.addEventListener('mouseover', (e) => {
            const selectedOption = e.target.getAttribute('data-shade');
            isClicked = false;

            const hasInline = ifTrueInput.style.color;
        
            if (hasInline) {
                ifTrueInput.style.color = '';
            }
    
            ifTrueInput.style.color = (`${selectedOption}`);
        });

        options.addEventListener('mouseout', () => {
            if (isClicked) return;

            const hasInline = ifTrueInput.style.color;
        
            if (hasInline) {
                ifTrueInput.style.color = '';
            }
    
            ifTrueInput.style.color = '';
        });
    });

    dropdownFalseOptionColorFont.forEach(options => {
        options.addEventListener('click', (e) => {
            selectedFalseColorFont = e.target.getAttribute('data-shade');
            isClicked = true;
            
            // applyFontColors(selectedTrueColor);
            dropdownIfFalseColor.classList.add('hidden'); 

            const hasInline = showingIfFalseFontColor.style.color;
        
            if (hasInline) {
                showingIfFalseFontColor.style.color = '';
            }

            showingIfFalseFontColor.style.color = selectedFalseColorFont;
            showingIfFalseFontColor.setAttribute('data-shade', selectedFalseColorFont);

        });
        options.addEventListener('mouseover', (e) => {
            const selectedOption = e.target.getAttribute('data-shade');
            isClicked = false;

            const hasInline = ifFalseInput.style.color;
        
            if (hasInline) {
                ifFalseInput.style.color = '';
            }
    
            ifFalseInput.style.color = (`${selectedOption}`);
        });

        options.addEventListener('mouseout', () => {
            if (isClicked) return;

            const hasInline = ifFalseInput.style.color;
        
            if (hasInline) {
                ifFalseInput.style.color = '';
            }
    
            ifFalseInput.style.color = '';
        });
    });

    // Applying Background Color
    dropdownTrueOptionColors.forEach(options => {
        options.addEventListener('click', (e) => {
            isClicked = true;
            selectedTrueColor = e.target.getAttribute('data-shade');

            dropdownIfTrueColor.classList.add('hidden'); 

            const bgClass = Array.from(showingIfTrueColor.classList).find(className => className.startsWith('bg-'));
            if (bgClass) {
                showingIfTrueColor.classList.remove(bgClass);
                showingIfTrueColor.classList.add(`bg-${selectedTrueColor}`);
                showingIfTrueColor.setAttribute('data-shade', selectedTrueColor);
            }

        });
        
        options.addEventListener('mouseover', (e) => {
            isClicked = false;
            const selectedOption = e.target.getAttribute('data-shade');

                ifTrueInput.classList.forEach(cls => {
                    if (cls.startsWith('bg-')) {
                        ifTrueInput.classList.remove(cls);
                    }
                });
        
                const hasInline = ifTrueInput.style.backgroundColor;
        
                if(hasInline) {
                    ifTrueInput.style.backgroundColor = '';
                }
        
                ifTrueInput.classList.add(`bg-${selectedOption}`);
        });

        options.addEventListener('mouseout', (e) => {
            if (isClicked) return;
            
            ifTrueInput.classList.forEach(cls => {
                if (cls.startsWith('bg-')) {
                    ifTrueInput.classList.remove(cls);
                }
            });

        });
    });

    dropdownFalseOptionColors.forEach(options => {

        // const classToRemoveAndAdd = [
        //     'border-t', 'border-b', 'border-l', 'border-r', 'border-t-cursor', 'border-b-cursor', 'border-l-cursor', 'border-r-cursor'
        // ];

        options.addEventListener('click', (e) => {
            isClicked = true;
            selectedFalseColor = e.target.getAttribute('data-shade');

            dropdownColorMenu.classList.add('hidden'); 

            const bgClass = Array.from(showingIfFalseColor.classList).find(className => className.startsWith('bg-'));
            if (bgClass) {
                showingIfFalseColor.classList.remove(bgClass);
                showingIfFalseColor.classList.add(`bg-${selectedFalseColor}`);
                showingIfFalseColor.setAttribute('data-shade', selectedFalseColor);
            }

        });
        
        options.addEventListener('mouseover', (e) => {
            isClicked = false;
            const selectedOption = e.target.getAttribute('data-shade');

                ifFalseInput.classList.forEach(cls => {
                    if (cls.startsWith('bg-')) {
                        ifFalseInput.classList.remove(cls);
                    }
                });
        
                const hasInline = ifFalseInput.style.backgroundColor;
        
                if(hasInline) {
                    ifFalseInput.style.backgroundColor = '';
                }
        
                ifFalseInput.classList.add(`bg-${selectedOption}`);
        });

        options.addEventListener('mouseout', (e) => {
            if (isClicked) return;
            
            ifFalseInput.classList.forEach(cls => {
                if (cls.startsWith('bg-')) {
                    ifFalseInput.classList.remove(cls);
                }
            });

        });
    });

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

    ifCondition.addEventListener('click', performCondition);

    closeIf.addEventListener('click', (e) => {
        reset(table); 
        currentX = 0;
        currentY = 0;
    });
    cancelIf.addEventListener('click', (e) => {
        reset(table);
        currentX = 0;
        currentY = 0;
    });

 
    //PERFORMING OF CONDITION STARTS HERE!!!! 
    function performCondition() {
        logic.classList.remove('hidden');
        // const table = document.getElementById('main-table');

        table.addEventListener('focusin', performingCondition);

        condition.addEventListener('input', (event) => {
            storeCondition = event.target.value;
        });

        placeCaretAtEnd(condition);
    }

    function performingCondition(event) {
        const cursorCell = event.target;
        // selectedCell = document.querySelector('td.selected');

        if (cursorCell && cursorCell !== selectedCell) {
            const newCol = cursorCell.dataset.column;
            
            const formula = cursorCell.getAttribute('data-formula');
            
            const matchTrue = formula.match(/if\(.+?,(.+?),.+\)/);
            const matchFalse = formula.match(/if\(.+?,.+?,(.+?)\)/);
            const matchTrueContent = matchTrue ? matchTrue[1].trim() : null;
            const matchFalseContent = matchFalse ? matchFalse[1].trim() : null;
            
            condition.value = `${newCol}=="${matchTrueContent}"`;
            ifTrueInput.value = `${matchTrueContent}`;
            ifFalseInput.value = `${matchFalseContent}`;

            ifOk.addEventListener('click', (event) => {
                clickedOk(cursorCell);
            });
        }
    }

    function clickedOk(cell) {
        // const table = document.getElementById('main-table');
        const ifTrueInput = document.getElementById('resultTrueColor');
        const ifFalseInput = document.getElementById('resultFalseColor');

        const conditionValue = condition.value;
        const selectedColumn = cell.dataset.column;
        const operationACache = [...table.querySelectorAll('.cursor-cell[data-column="A"][data-operation="operation"]')];
        const latestTrue = ifTrueInput.value.trim();
        const latestFalse = ifFalseInput.value.trim();
        
        const conditionPattern = /\b([A-Z]{1,2})\b(?=\s*[><=!]{1,2})|(?<=[><=!]{1,2}\s*)\b([A-Z]{1,2})\b/g;
        const matchCondition = [...conditionValue.matchAll(conditionPattern)].map(match => match[1] || match[2]);
        
        // let columnFormula = {
        //     tableId: tableId,
        //     room_id: roomID, 
        //     column: selectedColumn,
        //     formula: latestCondition
        // }
        // saveFormula(columnFormula);
        
        let saveData = [];
        let fontColor = null;
        let currentColor = null;
        let isType = null;
        let type = null;
        let twoColorExist = null;
        let whatType = null;
        let fontColorSet = null;
        let bgColorSet = null;

        let allColorSet = null;
        
        matchCondition.forEach((column) => {


            operationACache.forEach(operationCol => {
                const operationRow = operationCol.dataset.row;

                // SETTING THE FONT-COLORS AND BG-COLORS ATTRIBUTES
                const handleConditionColors = table.querySelector(`[data-column='${column}'][data-row='${operationRow}']`);

                if(selectedTrueColorFont || selectedFalseColorFont) {
                    fontColorSet = `${selectedTrueColorFont},${selectedFalseColorFont}`;
                    handleConditionColors.setAttribute('font-colors', fontColorSet);
                }
                if(selectedTrueColor || selectedFalseColor) {
                    bgColorSet = `${selectedTrueColor},${selectedFalseColor}`;
                    handleConditionColors.setAttribute('bg-colors', bgColorSet);
                }
                // ENDS HERE!
                
                const firstCell = table.querySelector(`[data-column='${column}'][data-row='${operationRow}']`);
                const columnContent = firstCell.textContent.trim();

                const resultCol = table.querySelector(`[data-column='${selectedColumn}'][data-row='${operationRow}']`);
                const nameCells = table.querySelector(`[data-column='A'][data-row='${operationRow}']`);
                const studentName = nameCells.dataset.roomStudent;

                const [left, right] = conditionValue.split('==').map(part => part.trim());  

                const isColumnOnLeft = left === column;
                const textValueOfCondition = isColumnOnLeft ? right : left;
            
                const positionOfColumn = isColumnOnLeft ? `"${columnContent}"`==`${textValueOfCondition}` : `${textValueOfCondition}`==`"${columnContent}"`

                if (firstCell !== null) {
                    const result = evaluate(positionOfColumn);

                    if(firstCell) {
                        const hasInline = firstCell.style.color || firstCell.style.backgroundColor;
                        if( selectedTrueColorFont || selectedFalseColorFont) {
                            if (hasInline) {
                                firstCell.style.color = '';
                            }
                    
                            fontColor = result ? selectedTrueColorFont : selectedFalseColorFont;
                            firstCell.style.color = fontColor;
                        }
        
                        
                        if(selectedTrueColor || selectedFalseColor) {
                            // const classToRemove = [
                            //     'border-t', 'border-b', 'border-l', 'border-r', 'border-t-cursor', 'border-b-cursor', 'border-l-cursor', 'border-r-cursor'
                            // ];

                            if (hasInline) {
                                firstCell.style.backgroundColor = '';
                            }

                            
                            firstCell.classList.forEach(cls => {
                                if (cls.startsWith('bg-')) {
                                    // firstCell.classList.remove( ...classToRemove );
                                    firstCell.classList.remove(cls);
                                }
                            });

                            currentColor = result ? selectedTrueColor : selectedFalseColor;

                            firstCell.classList.add(`bg-${currentColor}`);
                        }

                        if(fontColor && currentColor) {
                            type = 'font&bg';
                            twoColorExist = `${fontColor}&${currentColor}`;
                        }

                        isType = fontColor ? 'font' : 'bg';
                        whatType = fontColor ? fontColor : currentColor;
                    }

                    const resultText = result ? latestTrue : latestFalse;
                    const whatColor = twoColorExist ? twoColorExist : whatType;
                    // updateResultCell(resultCol, resultText);

                    if(fontColorSet && bgColorSet) {
                        allColorSet = `${fontColorSet}&${bgColorSet}`;
                    } else {
                        allColorSet = fontColorSet ? fontColorSet : bgColorSet; 
                    }
                    
                    let dataPerform = {
                        table_id: tableId,
                        room_id: roomID, 
                        teacher_id: teacherID,
                        column: resultCol.dataset.column,
                        row: resultCol.dataset.row,
                        content: resultText,
                        color_name: allColorSet,
                        type: type ? type : isType
                    };
                    saveData.push(dataPerform);
                }
            });

        });
        console.log(saveData);
        saveResult(saveData);
        // YOU NEED TO FIX THE SAVING OF DATA, SPECEFICALLY IN SAVING THE FONT AND BACKGROUND COLORS, THEY SHOULD BE CHANGED WHEN THE USER CHANGE THE CONDITION!!!!!!!!!!!!!!1
        
        reset(table);
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

    function saveResult(dataPerform) {
        fetch('/save-colors', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ colors: dataPerform }),
        })
        .then(response => response.json())
        .then(data => {
            console.log('Colors Successfully Saved!', data);
        })
        .catch(error => {
            console.log('Error Saving Colors:', error);
        })
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
        logic.classList.add('hidden');
        draggable.removeAttribute('style');
        ifTrueInput.value = '';
        ifFalseInput.value = '';
        storeCondition = null;
        condition.value = '';
        table.removeEventListener('focusin', performingCondition);
        selectedCell = null;

        // Input
        ifTrueInput.value = '';
        ifFalseInput.value = '';

        // Input
        condition.value = ''; 
        ifTrueInput.value = '';
        ifFalseInput.value = '';
        // Reset Color visually
        ifTrueInput.style.color = '';
        ifFalseInput.style.color = '';
        ifTrueInput.classList.remove(`bg-${selectedTrueColor}`);
        ifFalseInput.classList.remove(`bg-${selectedFalseColor}`);
        // Chosen Color to be Reset
        selectedTrueColorFont = null;
        selectedFalseColorFont = null;
        selectedTrueColor = null;
        selectedFalseColor = null;
    }


});