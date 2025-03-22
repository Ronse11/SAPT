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
    
                    involvedCell.addEventListener('blur', (event) => {
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
                                        let roundedResult = Math.round(involvedResult);

                                        includesCol.textContent = roundedResult;
                                        includesCol.removeAttribute("data-original");
                                        includesCol.setAttribute("data-original", involvedResult);
                                    } catch (error) {
                                        includesCol.textContent = 'Invalidss!';
                                        return;
                                    }
    

                                    let id = includesCol.getAttribute('data-id');
                                    let involvedResults = {
                                        tableId: table.id,
                                        content: involvedResult,
                                        colspan: 1,
                                        rowspan: 1,
                                        merged: false
                                    }

                                    // MAKE ALL THE UPDATE AND INSERTION OF DATAS INTO BULK FO FASTER SAVING INTO DATABASE!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
    
                                    fetch(`/update-content-cell/${id}`, {            
                                    method: "POST",
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                    },
                                    body: JSON.stringify(involvedResults)    
                                    })
                                    .then(response => response.json())
                                    .then(data => {
                                        console.log("Server Response:", data);
                            
                                        // If there's no ID, update the cell with the returned ID from the server
                                        if (!id && data.ids) {
                                            cell.setAttribute('data-id', data.ids);
                                        }
                                    })
                                    .catch(error => {
                                        console.error("Error:", error);
                                    });
    
                                // }


                            });
                        }
                    });
                });
            });
        }











































        
    // let pendingUpdates = new Map();
    // let bulkUpdateTimeout = null;
    
    // async function updateRowsInBulk() {
    //     if (pendingUpdates.size === 0) return;
    
    //     const bulkGradeData = [];
    
    //     pendingUpdates.forEach((row, rowId) => {
    //         const columns = {
    //             midColumn: row.querySelector("td[data-column='MidGr.']"),
    //             midEqvColumn: row.querySelector("td[data-column='Mid.N.Eqv.']"),
    //             midFinalColumn: row.querySelector("td[data-column='Mid']"),
    //             tfColumn: row.querySelector("td[data-column='T.F.Gr.']"),
    //             tfEqvColumn: row.querySelector("td[data-column='F.N.Eqv.']"),
    //             tfFinalColumn: row.querySelector("td[data-column='Fin']"),
    //             finalEquivalent: row.querySelector("td[data-column='FR.Eqv']"),
    //             finalNumberEquivalent: row.querySelector("td[data-column='FR.N.Eqv']"),
    //             remarks: row.querySelector("td[data-column='Remarks']")
    //         };
    
    //         const midColOrigValue = columns.midColumn ? parseFloat(columns.midColumn.getAttribute("data-original")) || '' : '';
    //         const tfColOrigValue = columns.tfColumn ? parseFloat(columns.tfColumn.getAttribute("data-original")) || '' : '';
    
    //         let showMidTermGrade = null;
    //         let showFinalTermGrade = null;
    
    //         if (columns.midColumn && columns.midEqvColumn && columns.midFinalColumn && midColOrigValue !== '') {
    //             columns.midEqvColumn.textContent = getGradeEquivalent(Math.round(midColOrigValue));
    //             showMidTermGrade = getMidTermGrade(midColOrigValue);
    //             columns.midFinalColumn.textContent = showMidTermGrade.display;
    //         }
    
    //         if (columns.tfColumn && columns.tfEqvColumn && columns.tfFinalColumn && tfColOrigValue !== '') {
    //             columns.tfEqvColumn.textContent = getGradeEquivalent(Math.round(tfColOrigValue));
    //             showFinalTermGrade = getFinTermGrade(tfColOrigValue);
    //             columns.tfFinalColumn.textContent = showFinalTermGrade.display;
    //         }
    
    //         let finalRate = null;
    //         if (columns.finalEquivalent && showMidTermGrade && showFinalTermGrade) {
    //             finalRate = getFinalRateGrade(showMidTermGrade.value, showFinalTermGrade.value);
    //             columns.finalEquivalent.textContent = finalRate;
    //             if (columns.finalNumberEquivalent) {
    //                 columns.finalNumberEquivalent.textContent = getGradeEquivalent(parseFloat(finalRate));
    //                 columns.remarks.textContent = getPassedOrFailed(Math.round(finalRate));
    //             }
    //         }
    
    //         const getStudent = row.querySelector("td[data-column='#1']");
    //         const student = getStudent.getAttribute("data-room-student");
    
    //         Object.keys(columns).forEach(key => {
    //             const columnElement = columns[key];
    //             if (columnElement) {
    //                 const content = columnElement.textContent.trim();
    //                 if (content) {
    //                     bulkGradeData.push({
    //                         teacher_id: teacherId,
    //                         room_id: roomId,
    //                         student_name: student,
    //                         column: columnElement.getAttribute("data-column"),
    //                         row: rowId,
    //                         content: content,
    //                         merged: 0,
    //                         rowspan: 1,
    //                         colspan: 1
    //                     });
    //                 }
    //             }
    //         });
    //     });
    
    //     pendingUpdates.clear(); // Clear after processing
    
    //     if (bulkGradeData.length > 0) {
    //         console.log("Bulk Data:", bulkGradeData);
    //         await fetch("/save-number-grade", {
    //             method: "POST",
    //             headers: {
    //                 "Content-Type": "application/json",
    //                 "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
    //             },
    //             body: JSON.stringify({ grades: bulkGradeData })
    //         });
    //     } else {
    //         console.log("No data to save.");
    //     }
    // }
    
    // // MutationObserver to track changes and batch updates
    // let observer = new MutationObserver(mutations => {
    //     mutations.forEach(mutation => {
    //         if (mutation.type === "childList") {
    //             let column = mutation.target;
    //             let row = column.closest("tr");
    //             if (row) {
    //                 const rowId = row.querySelector("td[data-column='MidGr.']")?.getAttribute("data-row") ||
    //                               row.querySelector("td[data-column='T.F.Gr.']")?.getAttribute("data-row");
    
    //                 if (rowId) {
    //                     pendingUpdates.set(rowId, row);
    
    //                     // Wait 500ms before bulk processing (prevents excessive API calls)
    //                     clearTimeout(bulkUpdateTimeout);
    //                     bulkUpdateTimeout = setTimeout(updateRowsInBulk, 500);
    //                 }
    //             }
    //         }
    //     });
    // });
    
    // // Attach observer to MidGr. and T.F.Gr. columns
    // ["MidGr.", "T.F.Gr."].forEach(columnName => {
    //     document.querySelectorAll(`td[data-column='${columnName}']`).forEach(column => {
    //         observer.observe(column, { childList: true, subtree: true });
    //     });
    // });