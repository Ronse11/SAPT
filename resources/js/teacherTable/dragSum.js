// function recalculateFormula(formula) {
//     let newFormula = null;
//     const subFormula = formula.substring(1);
//     if(subFormula.startsWith('SUM')) {
//         newFormula = formula.substring(4);
//         newFormula = expandRange(newFormula).join('+');
//         console.log(newFormula);
//     } else {
//         newFormula = formula.substring(1);
//         console.log(newFormula);
//     }

//     const columnMatch = newFormula.match(/[A-Z]{1,2}/g);

//     console.log(columnMatch);
    
//     const operationA = table.querySelectorAll('.cursor-cell[data-column="A"][data-operation="operation"]');
    
//     if (columnMatch) {
//         columnMatch.forEach(col => {

//             let result;
            
//             operationA.forEach(operationCell => {

//                 const operationRow = operationCell.dataset.row;
//                 const involvedCell = table.querySelector(`[data-column='${col}'][data-row='${operationRow}']`);
//                 if (!involvedCell) return;

//                 if (involvedCell) {
//                     involvedCell.addEventListener('input', (event) => {
//                         const row = event.target.getAttribute('data-row');
//                         console.log(row);
                        
//                         // Query all formulas in the current row that match the given formula
//                         const formulaCells = table.querySelectorAll(`[data-formula="${formula}"][data-row='${row}']`);
                        
//                         formulaCells.forEach(currentFormula => {
//                             let updatedFormula = newFormula;
                            
//                             // Loop through the columns involved in the formula
//                             columnMatch.forEach(colToReplace => {
//                                 let cellContent = table.querySelector(`[data-row='${row}'][data-column='${colToReplace}']`);

//                                 // If the cell is empty or doesn't exist, replace it with 0
//                                 if (!cellContent || cellContent.textContent === "") {
//                                     updatedFormula = updatedFormula.replace(colToReplace, "0");
//                                 } else {
//                                     cellContent = cellContent.textContent.trim();
//                                     console.log(cellContent);
//                                     updatedFormula = updatedFormula.replace(colToReplace, cellContent);
//                                 }
//                             });

//                             // Evaluate the updated formula and set the result in the current formula cell
//                             try {
//                                 result = evaluate(updatedFormula);
//                                 result = Math.round(result * 100) / 100; // Round to 2 decimal places
//                                 currentFormula.textContent = result;
//                             } catch (error) {
//                                 currentFormula.textContent = 'Invalid!';
//                             }
//                         });
//                     });
//                 }


                
//                 // Store initial content and its length when the cell gains focus
//                 let originalContent = '';
//                 let originalContentLength = 0;

//                 involvedCell.addEventListener('focus', function(event) {
//                     let currentCell = event.target;
//                     // Capture the initial content and its length before user makes any changes
//                     originalContent = currentCell.textContent.trim();
//                     originalContentLength = originalContent.length;
//                 });

//                 involvedCell.addEventListener('blur', function(event) {
//                     let currentCell = event.target;
//                     console.log('Mo');
                    
//                     // Get the updated content and its length after the user has made changes
//                     const updatedContent = currentCell.textContent.trim();
//                     const updatedContentLength = updatedContent.length;
                    
                    
//                     // Additional logic if content or length changed (e.g., making a fetch request)
//                     if (originalContent !== updatedContent || originalContentLength !== updatedContentLength) {
//                         const row = event.target.getAttribute('data-row');
//                         const currentFormula = table.querySelectorAll(`[data-formula="${formula}"][data-row='${row}']`);
//                         // IMPROVE THE UPDATING OF RESULTS, AMO NI ANG ULOBRAHON BWASSS!!!!!!!!!!! PATI PAG PA OPTIMIZE KABILIGAN SANG RECALCULATEFORMULA FUNCTION!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
//                         currentFormula.forEach((dataId) => {
//                             let id = dataId.getAttribute('data-id');
//                             console.log(id);
//                             console.log('Start')
//                             console.log(result)
//                             console.log(id)

//                             let results = {
//                                 tableId: table.id,
//                                 content: result,
//                                 colspan: 1,
//                                 rowspan: 1,
//                                 merged: false
//                             }
                            
//                             console.log(results);
                            
//                             fetch(`/update-content-cell/${id}`, {            
//                             method: "POST",
//                             headers: {
//                                 'Content-Type': 'application/json',
//                                 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
//                             },
//                             body: JSON.stringify(results)    
//                             })
//                             .then(response => response.json())
//                             .then(data => {
//                                 console.log("Server Response:", data);
                    
//                                 // If there's no ID, update the cell with the returned ID from the server
//                                 if (!id && data.ids) {
//                                     cell.setAttribute('data-id', data.ids);
//                                 }
//                             })
//                             .catch(error => {
//                                 console.error("Error:", error);
//                             });
//                         });
//                     }
//                 });

//             });
//         });
//     }
// }
// //RECALCULATION OF INPUTS ENDS HERE!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!







































































// const columnMatch = newFormula.match(/[A-Z]{1,2}/g);
//         let result;
//         let involvedResult;
        
        
//         const operationA = [...table.querySelectorAll('.cursor-cell[data-column="A"][data-operation="operation"]')];
//         // const columnsWithFormula = [...table.querySelectorAll('[data-formula][data-column]')];
        

//         if (columnMatch) {
//             columnMatch.forEach(cols => {
//                 // console.log(cols);
//                 operationA.forEach(operationCell => {
//                     const operationRow = operationCell.dataset.row;
//                     const involvedCell = table.querySelector(`[data-column='${cols}'][data-row='${operationRow}']`);
//                     if (!involvedCell) return;

//                     if (involvedCell) {
//                         involvedCell.addEventListener('input', (event) => {
//                             const row = event.target.getAttribute('data-row');
//                             const targetCell = event.target.getAttribute('data-column');
                    
//                             const formulaCells = [...table.querySelectorAll(`[data-formula='${formula}'][data-row='${row}']`)];
                    
//                             formulaCells.forEach(formulaCell => {
//                                 let updatedFormula = newFormula;
//                                 const colsWithFormula = formulaCell.dataset.column;
                                
                                
//                                 columnMatch.forEach(colToReplace => {
//                                     let cellContent = table.querySelector(`[data-row='${row}'][data-column='${colToReplace}']`);
    
//                                     // If the cell is empty or doesn't exist, replace it with 0
//                                     if (!cellContent || cellContent.textContent === "") {
//                                         updatedFormula = updatedFormula.replace(colToReplace, "0");
//                                     } else {
//                                         cellContent = cellContent.textContent.trim();
//                                         updatedFormula = updatedFormula.replace(colToReplace, cellContent);
//                                     }
//                                 });

//                                 try {
//                                     result = evaluate(updatedFormula);
//                                     result = Math.round(result * 100) / 100; // Round to 2 decimal places
//                                     formulaCell.textContent = result;
//                                 } catch (error) {
//                                     formulaCell.textContent = 'Invalid!';
//                                 }

//                                 // CALCULATION OF INVOLVED CELLS STARTS HERE!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
//                                 const involvedCols = [...table.querySelectorAll(`[data-formula][data-row='${row}']`)];
                                
//                                 involvedCols.forEach(includesCol => {
//                                     const targetFormula = includesCol.dataset.formula;

                                    
//                                     if (!targetFormula.includes(targetCell)) {

                                        
//                                         let newInvolvedFormula = null;
//                                         const subFormula = targetFormula.substring(1);
//                                         console.log(subFormula);
//                                         if(subFormula.startsWith('SUM')) {
//                                             newInvolvedFormula = targetFormula.substring(4);
//                                             newInvolvedFormula = expandRange(newInvolvedFormula).join('+');
//                                         } else {
//                                             newInvolvedFormula = targetFormula.substring(1);
//                                         }
                                        
//                                         let updatednewInvolvedFormula = newInvolvedFormula;
//                                         const matchTargetFormula = newInvolvedFormula.match(/[A-Z]{1,2}/g);

//                                         matchTargetFormula.forEach(matchCol => {
//                                             let getTargetCols = table.querySelector(`[data-row='${row}'][data-column='${matchCol}']`);

                                            
//                                             if (!getTargetCols || getTargetCols.textContent === "") {
//                                                 updatednewInvolvedFormula = updatednewInvolvedFormula.replace(matchCol, "0");
//                                                 // console.log(updatednewInvolvedFormula);
//                                             } else {
//                                                 getTargetCols = getTargetCols.textContent.trim();
//                                                 updatednewInvolvedFormula = updatednewInvolvedFormula.replace(matchCol, getTargetCols);
//                                                 // console.log(updatednewInvolvedFormula);
//                                             }
//                                         });

                                        
//                                         try {
//                                             involvedResult = evaluate(updatednewInvolvedFormula);
//                                             involvedResult = Math.round(involvedResult * 100) / 100; // Round to 2 decimal places
//                                             includesCol.textContent = involvedResult;
//                                         } catch (error) {
//                                             includesCol.textContent = 'Invalid!';
//                                         }

//                                     }
//                                 });
//                                 // CALCULATION OF INVOLVED CELLS STARTS HERE!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

                    
//                             });
//                         });
//                     }
                    
//                 });
//             });
//         }



















































// function recalculateFormula(formula) {
//     let newFormula = null;
//     const subFormula = formula.substring(1);
//     if(subFormula.startsWith('SUM')) {
//         newFormula = formula.substring(4);
//         newFormula = expandRange(newFormula).join('+');
//     } else {
//         newFormula = formula.substring(1);
//     }

//     // Debounce utility function to delay recalculations during input changes
//     function debounce(func, delay) {
//         let timer;
//         return function (...args) {
//             clearTimeout(timer);
//             timer = setTimeout(() => func.apply(this, args), delay);
//         };
//     }

//     const columnMatch = newFormula.match(/[A-Z]{1,2}/g);
//     let result;
//     let involvedResult;

//     // Cache common query selections to avoid redundant DOM lookups
//     const operationACache = [...table.querySelectorAll('.cursor-cell[data-column="A"][data-operation="operation"]')];
//     const cellCache = new Map();  // Caching cells for faster access

//     if (columnMatch) {
//         columnMatch.forEach(cols => {
//             operationACache.forEach(operationCell => {
//                 const operationRow = operationCell.dataset.row;
                
//                 const cacheKey = `${cols}-${operationRow}`;
//                 if (!cellCache.has(cacheKey)) {
//                     // Cache the involved cell for reuse in future lookups
//                     const involvedCell = table.querySelector(`[data-column='${cols}'][data-row='${operationRow}']`);
//                     cellCache.set(cacheKey, involvedCell);
//                 }

//                 const involvedCell = cellCache.get(cacheKey);
//                 if (!involvedCell) return;

//                 involvedCell.addEventListener('blur', debounce((event) => {
//                     const row = event.target.getAttribute('data-row');
//                     const targetCell = event.target.getAttribute('data-column');
                    
//                     const formulaCells = [...table.querySelectorAll(`[data-formula='${formula}'][data-row='${row}']`)];

//                     // Process all formula cells in a batch
//                     const updates = [];
                    
//                     formulaCells.forEach(formulaCell => {
//                         let updatedFormula = newFormula;
//                         const colsWithFormula = formulaCell.dataset.column;

//                         columnMatch.forEach(colToReplace => {
//                             const cacheKey = `${row}-${colToReplace}`;
//                             if (!cellCache.has(cacheKey)) {
//                                 const cellContent = table.querySelector(`[data-row='${row}'][data-column='${colToReplace}']`);
//                                 cellCache.set(cacheKey, cellContent);
//                             }
                            
//                             let cellContent = cellCache.get(cacheKey);
                            
//                             if (!cellContent || cellContent.textContent === "") {
//                                 updatedFormula = updatedFormula.replace(colToReplace, "0");
//                             } else {
//                                 cellContent = cellContent.textContent.trim();
//                                 updatedFormula = updatedFormula.replace(colToReplace, cellContent);
//                             }
//                         });

//                         try {
//                             result = evaluate(updatedFormula);
//                             result = Math.round(result * 100) / 100;  // Round to 2 decimal places
//                             updates.push({ formulaCell, result });
//                         } catch (error) {
//                             updates.push({ formulaCell, result: 'Invalid!' });
//                         }
//                     });

//                     // Apply all updates to the DOM in one go (batch processing)
//                     updates.forEach(({ formulaCell, result }) => {
//                         formulaCell.textContent = result;
//                     });

//                     // Involved cell calculation batch starts here
//                     const involvedCols = [...table.querySelectorAll(`[data-formula][data-row='${row}']`)];

//                     involvedCols.forEach(includesCol => {
//                         const targetFormula = includesCol.dataset.formula;

//                         if (!targetFormula.includes(targetCell)) {
//                             let newInvolvedFormula = null;
//                             const subFormula = targetFormula.substring(1);
//                             if (subFormula.startsWith('SUM')) {
//                                 newInvolvedFormula = targetFormula.substring(4);
//                                 newInvolvedFormula = expandRange(newInvolvedFormula).join('+');
//                             } else {
//                                 newInvolvedFormula = targetFormula.substring(1);
//                             }

//                             let updatednewInvolvedFormula = newInvolvedFormula;
//                             const matchTargetFormula = newInvolvedFormula.match(/[A-Z]{1,2}/g);

//                             matchTargetFormula.forEach(matchCol => {
//                                 const cacheKey = `${row}-${matchCol}`;
//                                 if (!cellCache.has(cacheKey)) {
//                                     const getTargetCols = table.querySelector(`[data-row='${row}'][data-column='${matchCol}']`);
//                                     cellCache.set(cacheKey, getTargetCols);
//                                 }

//                                 let getTargetCols = cellCache.get(cacheKey);
//                                 if (!getTargetCols || getTargetCols.textContent === "") {
//                                     updatednewInvolvedFormula = updatednewInvolvedFormula.replace(matchCol, "0");
//                                 } else {
//                                     getTargetCols = getTargetCols.textContent.trim();
//                                     updatednewInvolvedFormula = updatednewInvolvedFormula.replace(matchCol, getTargetCols);
//                                 }
//                             });

//                             try {
//                                 involvedResult = evaluate(updatednewInvolvedFormula);
//                                 involvedResult = Math.round(involvedResult * 100) / 100; // Round to 2 decimal places
//                                 includesCol.textContent = involvedResult;
//                             } catch (error) {
//                                 includesCol.textContent = 'Invalid!';
//                             }
//                         }
//                     });
//                     // Involved cell calculation batch ends here

//                 }, 200));  // Debounce time set to 200ms
//             });
//         });
//     }

    
// }














































































// function recalculateFormula(formula) {
//     let newFormula = null;
//     const subFormula = formula.substring(1);
//     if(subFormula.startsWith('SUM')) {
//         newFormula = formula.substring(4);
//         newFormula = expandRange(newFormula).join('+');
//     } else {
//         newFormula = formula.substring(1);
//     }

//     const columnMatch = newFormula.match(/[A-Z]{1,2}/g);
//     let result;
//     let involvedResult;
//     let originalContent = '';
//     let originalContentLength = 0;

//     // Cache common query selections to avoid redundant DOM lookups
//     const operationACache = [...table.querySelectorAll('.cursor-cell[data-column="A"][data-operation="operation"]')];
//     const cellCache = new Map();  // Caching cells for faster access

//     if (columnMatch) {
//         columnMatch.forEach(cols => {
//             operationACache.forEach(operationCell => {
//                 const operationRow = operationCell.dataset.row;
                
//                 const cacheKey = `${cols}-${operationRow}`;
//                 if (!cellCache.has(cacheKey)) {
//                     // Cache the involved cell for reuse in future lookups
//                     const involvedCell = table.querySelector(`[data-column='${cols}'][data-row='${operationRow}']`);
//                     cellCache.set(cacheKey, involvedCell);
//                 }

//                 // console.log(cellCache);

//                 const involvedCell = cellCache.get(cacheKey);
//                 // console.log(involvedCell);
//                 if (!involvedCell) return;

//                 involvedCell.addEventListener('focus', function(event) {
//                     let currentCell = event.target;
//                     // Capture the initial content and its length before user makes any changes
//                     originalContent = currentCell.textContent.trim();
//                     originalContentLength = originalContent.length;
//                 });

//                 involvedCell.addEventListener('blur', (event) => {
//                     let currentCell = event.target;
                    
//                     const updatedContent = currentCell.textContent.trim();
//                     const updatedContentLength = updatedContent.length;

//                     if(originalContent !== updatedContent || originalContentLength !== updatedContentLength) {
//                         const row = event.target.getAttribute('data-row');
//                         const targetCell = event.target.getAttribute('data-column');

                        
//                         const formulaCells = [...table.querySelectorAll(`[data-formula='${formula}'][data-row='${row}']`)];
//                         console.log(formulaCells);
//                         // Process all formula cells in a batch
//                         const updates = [];
//                         // const updatesInvolved = [];
                        
//                         formulaCells.forEach(formulaCell => {
//                             let updatedFormula = newFormula;
//                             // const colsWithFormula = formulaCell.dataset.column;
//                             // console.log(formulaCell);

//                             columnMatch.forEach(colToReplace => {
//                                 const cacheKey = `${row}-${colToReplace}`;
//                                 if (!cellCache.has(cacheKey)) {
//                                     const cellContent = table.querySelector(`[data-row='${row}'][data-column='${colToReplace}']`);
//                                     cellCache.set(cacheKey, cellContent);
//                                 }
                                
//                                 let cellContent = cellCache.get(cacheKey);
                                
//                                 if (!cellContent || cellContent.textContent === "") {
//                                     updatedFormula = updatedFormula.replace(colToReplace, "0");
//                                 } else {
//                                     cellContent = cellContent.textContent.trim();
//                                     updatedFormula = updatedFormula.replace(colToReplace, cellContent);
//                                 }
//                             });

//                             try {
//                                 result = evaluate(updatedFormula);
//                                 result = Math.round(result * 100) / 100;  // Round to 2 decimal places
//                                 updates.push({ formulaCell, result });
//                             } catch (error) {
//                                 updates.push({ formulaCell, result: 'Invalid!' });
//                             }
//                         });

//                         // Apply all updates to the DOM in one go (batch processing)
//                         updates.forEach(({ formulaCell, result }) => {
//                             formulaCell.textContent = result;

//                             let id = formulaCell.getAttribute('data-id');

//                             let results = {
//                                 tableId: table.id,
//                                 content: result,
//                                 colspan: 1,
//                                 rowspan: 1,
//                                 merged: false
//                             }
                            
                            
//                             fetch(`/update-content-cell/${id}`, {            
//                             method: "POST",
//                             headers: {
//                                 'Content-Type': 'application/json',
//                                 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
//                             },
//                             body: JSON.stringify(results)    
//                             })
//                             .then(response => response.json())
//                             .then(data => {
//                                 console.log("Server Response:", data);
                    
//                                 // If there's no ID, update the cell with the returned ID from the server
//                                 if (!id && data.ids) {
//                                     cell.setAttribute('data-id', data.ids);
//                                 }
//                             })
//                             .catch(error => {
//                                 console.error("Error:", error);
//                             });
//                         });

//                         // Involved cell calculation batch starts here
//                         const involvedCols = [...table.querySelectorAll(`[data-formula][data-row='${row}']`)];

//                         console.log(involvedCols);
//                         involvedCols.forEach(includesCol => {
//                             const targetFormula = includesCol.dataset.formula;

//                             if (!targetFormula.includes(targetCell)) {
//                                 let newInvolvedFormula = null;
//                                 const subFormula = targetFormula.substring(1);
//                                 if (subFormula.startsWith('SUM')) {
//                                     newInvolvedFormula = targetFormula.substring(4);
//                                     newInvolvedFormula = expandRange(newInvolvedFormula).join('+');
//                                 } else {
//                                     newInvolvedFormula = targetFormula.substring(1);
//                                 }

//                                 let updatednewInvolvedFormula = newInvolvedFormula;
//                                 const matchTargetFormula = newInvolvedFormula.match(/[A-Z]{1,2}/g);

//                                 matchTargetFormula.forEach(matchCol => {
//                                     const cacheKey = `${row}-${matchCol}`;
//                                     if (!cellCache.has(cacheKey)) {
//                                         const getTargetCols = table.querySelector(`[data-row='${row}'][data-column='${matchCol}']`);
//                                         cellCache.set(cacheKey, getTargetCols);
//                                     }

//                                     let getTargetCols = cellCache.get(cacheKey);
//                                     if (!getTargetCols || getTargetCols.textContent === "") {
//                                         updatednewInvolvedFormula = updatednewInvolvedFormula.replace(matchCol, "0");
//                                     } else {
//                                         getTargetCols = getTargetCols.textContent.trim();
//                                         updatednewInvolvedFormula = updatednewInvolvedFormula.replace(matchCol, getTargetCols);
//                                     }
//                                 });

//                                 try {
//                                     involvedResult = evaluate(updatednewInvolvedFormula);
//                                     involvedResult = Math.round(involvedResult * 100) / 100; // Round to 2 decimal places
//                                     includesCol.textContent = involvedResult;
//                                     // updatesInvolved.push({includesCol, involvedResult});
//                                 } catch (error) {
//                                     // updatesInvolved.push({includesCol, involvedResult: 'result'});
//                                     includesCol.textContent = 'Invalid!';
//                                 }

//                                 // updatesInvolved.forEach(({includesCol, involvedResult}) => {
//                                 //     includesCol.textContent = involvedResult;

//                                     let id = includesCol.getAttribute('data-id');

//                                     let involvedResults = {
//                                         tableId: table.id,
//                                         content: involvedResult,
//                                         colspan: 1,
//                                         rowspan: 1,
//                                         merged: false
//                                     }
//                                     // console.log(id);
//                                 // });


//                             }
//                         });
//                     }
//                 });
//             });
//         });
//     }


    
// }










































// function getFormulas(formula) {
//     let newFormula = null;
//     const subFormula = formula.substring(1);

//     if (subFormula.startsWith('SUM')) {
//         newFormula = formula.substring(4);
//         newFormula = expandRange(newFormula).join('+');
//     } else {
//         newFormula = formula.substring(1);
//     }
//     return newFormula;
// }

// function getAllFormulaColumns() {
//     const formulaColumns = new Set();
    

//     const rowStart = table.getAttribute('data-row-start');
//     const cellsWithFormula = table.querySelectorAll(`[data-formula][data-row='${rowStart}']`); // adjust this if your attribute selector is different

//     cellsWithFormula.forEach(cell => {
//         const formula = cell.getAttribute('data-formula');
//         const columnsInFormula = getFormulas(formula);
//         const columnsArray = columnsInFormula.split('+'); // Assuming they are joined by '+'
        
//         columnsArray.forEach(col => {
//             const trimmedCol = col.trim();
//             // Regex to match valid column identifiers (A-Z, AA-AZ, BA-BZ, etc.)
//             const regex = /^[A-Z]{1,2}$/; // Matches 1 or 2 uppercase letters
            
//             if (regex.test(trimmedCol)) {
//                 formulaColumns.add(trimmedCol); // Add to Set to remove duplicates
//             }
//         });
//     });

//     // Convert the Set back to an array, if needed
//     return Array.from(formulaColumns);
// }

// // Example usage
// const uniqueColumns = getAllFormulaColumns();
// const operationACache = [...table.querySelectorAll('.cursor-cell[data-column="A"][data-operation="operation"]')];

// uniqueColumns.forEach(formulaCols => {
//     operationACache.forEach(operationCell => {
//         const rows = operationCell.dataset.row;
        
//         const involvedCells = [...table.querySelectorAll(`[data-column='${formulaCols}'][data-row='${rows}']`)];

//         if(!involvedCells) return;
//         involvedCells.forEach(cell => {
//             cell.addEventListener('blur', function(event) {
//                 const selectedCell = event.target;
//                 const currentRow = selectedCell.dataset.row;
//                 const cellsWithFormula = table.querySelectorAll(`[data-formula][data-row='${currentRow}']`);
                
//                 cellsWithFormula.forEach(cellFormula => {
//                     const formulas = cellFormula.dataset.formula;
//                     const formulaCols = cellFormula.dataset.column;

//                     let newFormula = null;
//                     const subFormula = formulas.substring(1);
                
//                     if (subFormula.startsWith('SUM')) {
//                         newFormula = formulas.substring(4);
//                         newFormula = expandRange(newFormula).join('+');
//                         // console.log(newFormula);
//                     } else {
//                         newFormula = formulas.substring(1);
//                         // console.log(newFormula);
//                     }

//                     console.log(newFormula);
                    
//                     let updatedFormula = newFormula;
//                     let cellContent = table.querySelector(`[data-row='${currentRow}'][data-column='${formulaCols}']`);;
                    
//                     if (!cellContent || cellContent.textContent === "") {
//                         updatedFormula = updatedFormula.replace(cellFormula, "0");
//                     } else {
//                         cellContent = cellFormula.textContent.trim();
//                         console.log(cellContent);
//                         updatedFormula = updatedFormula.replace(cellFormula, cellContent);
//                     }
//                 });
//             });
//         });
//     });
// });