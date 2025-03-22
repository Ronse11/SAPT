

import { column } from "mathjs";





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

export function getGradeEquivalent(score) {
    if (isNaN(score)) return "Invalid Score"; // Prevent errors for non-numeric input
    const range = gradeRanges.find(r => score >= r.min && score <= r.max);
    return range ? formatGrade(range.grade) : "Invalid Score";
}

export function formatGrade(grade) {
    if (grade % 1 === 0) {
        return grade.toFixed(1); // Ensures 3.0, 2.0, 1.0
    } else if (grade * 100 % 10 === 0) {
        return grade.toFixed(2); // Ensures 2.50, 1.50, etc.
    }
    return grade; // Default case (if no formatting needed)
}

export function getMidTermGrade(grade) {
    if (isNaN(grade)) return { display: "Invalid Grade", value: null };

    const originalValue = parseFloat(grade * 0.4).toFixed(2); 
    const roundedValue = Math.round(originalValue); 

    return { display: roundedValue, value: originalValue };
}

export function getFinTermGrade(grade) {
    if (isNaN(grade)) return { display: "Invalid Grade", value: null };

    const originalValue = parseFloat(grade * 0.6).toFixed(2);
    const roundedValue = Math.round(originalValue);

    return { display: roundedValue, value: originalValue };
}

export function getFinalRateGrade(mid, fin) {

    const value = parseFloat(mid) + parseFloat(fin);
    const originalValue = parseFloat(value).toFixed(2);
    const roundedValue = Math.round(originalValue);

    return { display: roundedValue, value: originalValue };
}

export function getPassedOrFailed(grade) {
    if(grade >= 75) {
        return 'Passed';
    } else {
        return '';
    }
}

document.addEventListener("DOMContentLoaded", () => {
    const table = document.querySelector(".rating-table");
    const roomId = table.getAttribute('data-room-id');
    const teacherId = table.getAttribute('data-teacher-id');

    const getMidColumn = document.getElementById('MidGr.');
    const getFinColumn = document.getElementById('T.F.Gr.');
    


    table.addEventListener("keydown", async (event) => {
        if (event.key === "Enter") {  
            event.preventDefault();   
            const cell = event.target;
            cell.setAttribute('data-p', 'process');
            const formula = cell.innerText.trim();
    
            if (!formula.startsWith("=")) return; // Ensure it's a formula
    
            const column = formula.substring(1);
            const currentCol = cell.getAttribute('data-column');

            handleInputColumn(cell, formula);
            if (currentCol == 'MidGr.') {
                getMidColumn.value = column;
            } else if (currentCol == 'T.F.Gr.') {
                getFinColumn.value = column;
            }
    
            try {

                const response = await fetch("/apply-formula/grading-sheet", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ column, currentCol, room_id: roomId })
                });
    
                if (!response.ok) {
                    const errorText = await response.text();
                    try {
                        const errorData = JSON.parse(errorText);
                        alert(errorData.error);
                    } catch {
                        alert("An error occurred: " + errorText);
                    }
                    return;
                }
    
                const result = await response.json();
                const bulkGradeData = [];
                const gradeMapping = {
                    "MidGr.": { equivalent: "Mid.N.Eqv.", percent: "Mid" },
                    "T.F.Gr.": { equivalent: "F.N.Eqv.", percent: "Fin" }
                };
                
                const getCell = (column, row) => document.querySelector(`[data-column="${column}"][data-row="${row}"]`);
                
                const addGradeEntry = (list, student, column, row, content) => {
                    list.push({
                        teacher_id: teacherId,
                        room_id: roomId,
                        student_name: student,
                        column,
                        row,
                        content,
                        merged: 0,
                        rowspan: 1,
                        colspan: 1
                    });
                };
                
                for (const skill of result.data) {
                    const targetCell = getCell(skill.column, skill.row);
                    if (targetCell) {
                        targetCell.setAttribute("data-original", skill.content);
                        targetCell.innerText = Math.round(skill.content);
                    }
                
                    const mapping = gradeMapping[currentCol];
                    if (!mapping) continue; // Skip if no mapping exists
                
                    const { equivalent, percent } = mapping;
                    const numberGradeCell = getCell(equivalent, skill.row);
                    const percentGradeCell = getCell(percent, skill.row);
                
                    if (numberGradeCell) {
                        const numberGrade = getGradeEquivalent(Math.round(parseFloat(skill.content)));
                        numberGradeCell.innerText = numberGrade;
                        addGradeEntry(bulkGradeData, skill.student_name, equivalent, skill.row, numberGrade);
                    }
                
                    if (percentGradeCell) {
                        let gradeContent;
                        
                        if (percent === "Mid") {
                            gradeContent = getMidTermGrade(skill.content);
                            // const getGradeContent = gradeContent.value;
                            // percentGradeCell.setAttribute("data-original", parseFloat(getGradeContent).toFixed(2));
                    
                            // Store the original value in the "Mid" column
                            // percentGradeCell.dataset.original = getGradeContent;
                        } else if (percent === "Fin") {
                            gradeContent = getFinTermGrade(skill.content);
                    
                            // Get the "Mid" column cell and retrieve the original value
                            const midPercentCell = getCell("Mid", skill.row);
                            const midPercent = midPercentCell ? parseFloat(midPercentCell.dataset.original || midPercentCell.textContent.trim()) || 0 : 0;
                            const finalRate = getFinalRateGrade(midPercent, parseFloat(gradeContent.value));
                    
                            addGradeEntry(bulkGradeData, skill.student_name, "FR.Eqv", skill.row, finalRate.value);
                            addGradeEntry(bulkGradeData, skill.student_name, "FR.N.Eqv", skill.row, getGradeEquivalent(finalRate.display));
                            addGradeEntry(bulkGradeData, skill.student_name, "Remarks", skill.row, getPassedOrFailed(finalRate.display));
                    
                            getCell("FR.Eqv", skill.row).innerText = finalRate.display;
                            getCell("FR.N.Eqv", skill.row).innerText = getGradeEquivalent(finalRate.display);
                            getCell("Remarks", skill.row).innerText = getPassedOrFailed(finalRate.display);
                        }
                    
                        // Store the original value for Mid column
                        // if (percent === "Mid") {
                        //     percentGradeCell.dataset.original = gradeContent.value;
                        // }
                    
                        // Show rounded value in the cell
                        percentGradeCell.setAttribute("data-original", parseFloat(gradeContent.value).toFixed(2));
                        percentGradeCell.innerText = Math.round(gradeContent.value);
                    
                        addGradeEntry(bulkGradeData, skill.student_name, percent, skill.row, gradeContent.value);
                    }
                    
                }

                if (bulkGradeData.length > 0) {
                    await fetch("/save-number-grade", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
                        },
                        body: JSON.stringify({ grades: bulkGradeData })
                    });
                }
                
    
            } catch (error) {
                console.error("Error:", error);
                alert("Something went wrong. Please try again.");
            }

        }
    });


    // USING INPUT
    const inputs = ['semester', 'MidGr.', 'T.F.Gr.'];
    const previousValues = {};

    inputs.forEach(id => {
        const inputElement = document.getElementById(id);
        if (inputElement) {
            inputElement.addEventListener('focus', (event) => {
                previousValues[id] = event.target.value.trim();
            });
            inputElement.addEventListener('blur', handleGradeInputBlur);
        }
    });
    
    async function handleGradeInputBlur(event) {
        const cell = event.target;
        const cellValue = cell.value.trim();
        const previousValue = previousValues[cell.id] || '';

        if (cellValue === previousValue) return;

        handleInputColumn(cell, cellValue);

        if(cell.id === 'semester') return;

        cell.setAttribute('data-p', 'process');
        const column = cell.value.trim();
        const currentCol = cell.getAttribute('name');
        

        try {
            const response = await fetch("/apply-formula/grading-sheet", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ column, currentCol, room_id: roomId })
            });
    
            if (!response.ok) {
                const errorText = await response.text();
                try {
                    const errorData = JSON.parse(errorText);
                    alert(errorData.error);
                } catch {
                    alert("An error occurred: " + errorText);
                }
                return;
            }
    
            const result = await response.json();
            const bulkGradeData = [];
            const gradeMapping = {
                "MidGr.": { equivalent: "Mid.N.Eqv.", percent: "Mid" },
                "T.F.Gr.": { equivalent: "F.N.Eqv.", percent: "Fin" }
            };
    
            const getCell = (column, row) => document.querySelector(`[data-column="${column}"][data-row="${row}"]`);
            
            const addGradeEntry = (list, student, column, row, content) => {
                list.push({
                    teacher_id: teacherId,
                    room_id: roomId,
                    student_name: student,
                    column,
                    row,
                    content,
                    merged: 0,
                    rowspan: 1,
                    colspan: 1
                });
            };
    
            for (const skill of result.data) {
                const targetCell = getCell(skill.column, skill.row);
                if (targetCell) {
                    targetCell.setAttribute("data-original", skill.content);
                    targetCell.innerText = Math.round(skill.content);
                }
    
                const mapping = gradeMapping[currentCol];
                if (!mapping) continue;
    
                const { equivalent, percent } = mapping;
                const numberGradeCell = getCell(equivalent, skill.row);
                const percentGradeCell = getCell(percent, skill.row);
    
                if (numberGradeCell) {
                    const numberGrade = getGradeEquivalent(Math.round(parseFloat(skill.content)));
                    numberGradeCell.innerText = numberGrade;
                    addGradeEntry(bulkGradeData, skill.student_name, equivalent, skill.row, numberGrade);
                }
    
                if (percentGradeCell) {
                    let gradeContent;
    
                    if (percent === "Mid") {
                        gradeContent = getMidTermGrade(skill.content);
                    } else if (percent === "Fin") {
                        gradeContent = getFinTermGrade(skill.content);
    
                        const midPercentCell = getCell("Mid", skill.row);
                        const midPercent = midPercentCell ? parseFloat(midPercentCell.dataset.original || midPercentCell.textContent.trim()) || 0 : 0;
                        const finalRate = getFinalRateGrade(midPercent, parseFloat(gradeContent.value));
                        
                        addGradeEntry(bulkGradeData, skill.student_name, "FR.Eqv", skill.row, finalRate.value);
                        addGradeEntry(bulkGradeData, skill.student_name, "FR.N.Eqv", skill.row, getGradeEquivalent(finalRate.display));
                        addGradeEntry(bulkGradeData, skill.student_name, "Remarks", skill.row, getPassedOrFailed(finalRate.display));
                
                        const showFREqv =  getCell("FR.Eqv", skill.row);
                        showFREqv.innerText = finalRate.display;
                        showFREqv.setAttribute('data-original', finalRate.value);

                        const showFRNEqv =  getCell("FR.N.Eqv", skill.row);
                        showFRNEqv.innerText = getGradeEquivalent(finalRate.display);
                        showFRNEqv.setAttribute('data-original', finalRate.value);

                        getCell("Remarks", skill.row).innerText = getPassedOrFailed(finalRate.display);
                    }
    
                    percentGradeCell.setAttribute("data-original", parseFloat(gradeContent.value).toFixed(2));
                    percentGradeCell.innerText = Math.round(gradeContent.value);
    
                    addGradeEntry(bulkGradeData, skill.student_name, percent, skill.row, gradeContent.value);
                }
            }

            
            if (bulkGradeData.length > 0) {
                await fetch("/save-number-grade", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
                    },
                    body: JSON.stringify({ grades: bulkGradeData })
                });
            }
        } catch (error) {
            console.error("Error:", error);
            alert("Something went wrong. Please try again.");
        }
    }

    async function handleInputColumn(cell, newValue) {
        if (newValue.length >= 1) {
            try {
                const response = await fetch("/apply-sem", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        semValue: newValue,
                        table_id: cell.id,
                        room_id: roomId
                    })
                });
    
                if (!response.ok) {
                    const errorData = await response.json();
                    alert(errorData.error);
                }
            } catch (error) {
                console.error("Error applying units:", error);
            }
        }
    }
    


    // const tableMain = document.getElementById('main-table');
    // console.log(tableMain);


    


    const unit = document.getElementById('units');
    
    let previousUnitValue = unit.value.trim(); 
    
    unit.addEventListener('blur', async () => {
        const unitValue = unit.value.trim(); 
    
        if (unitValue === previousUnitValue) return; 
    
        if (unitValue.length >= 1) {
            try {
                const response = await fetch("/apply-units", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ unitValue, room_id: roomId })
                });
    
                if (!response.ok) {
                    const errorData = await response.json();
                    alert(errorData.error);
                    return;
                }
    
                const result = await response.json();
    
                const targetCells = document.querySelectorAll(`[data-column="Credits"]`); 
                targetCells.forEach(cell => {
                    cell.innerText = result.data;
                });
    
                // Update previousValue to the latest input value
                // previousValue = unitValue; 
    
            } catch (error) {
                console.error("Error applying units:", error);
            }
        }
    });
    










    // async function updateRow(row) {
    //     const columns = {
    //         midColumn: row.querySelector("td[data-column='MidGr.']"),
    //         midEqvColumn: row.querySelector("td[data-column='Mid.N.Eqv.']"),
    //         midFinalColumn: row.querySelector("td[data-column='Mid']"),
    //         tfColumn: row.querySelector("td[data-column='T.F.Gr.']"),
    //         tfEqvColumn: row.querySelector("td[data-column='F.N.Eqv.']"),
    //         tfFinalColumn: row.querySelector("td[data-column='Fin']"),
    //         finalEquivalent: row.querySelector("td[data-column='FR.Eqv']"),
    //         finalNumberEquivalent: row.querySelector("td[data-column='FR.N.Eqv']"),
    //         remarks: row.querySelector("td[data-column='Remarks']")
    //     };
    
    //     console.log('I worked');
    
    //     const midColOrigValue = columns.midColumn ? parseFloat(columns.midColumn.getAttribute("data-original")) || '' : '';
    //     const tfColOrigValue = columns.tfColumn ? parseFloat(columns.tfColumn.getAttribute("data-original")) || '' : '';
    
    //     let showMidTermGrade = null;
    //     let showFinalTermGrade = null;
    
    //     if (columns.midColumn && columns.midEqvColumn && columns.midFinalColumn && midColOrigValue !== '') {
    //         columns.midEqvColumn.textContent = getGradeEquivalent(Math.round(midColOrigValue));
    //         showMidTermGrade = getMidTermGrade(midColOrigValue);
    //         columns.midFinalColumn.textContent = showMidTermGrade.display;
    //     }
    
    //     if (columns.tfColumn && columns.tfEqvColumn && columns.tfFinalColumn && tfColOrigValue !== '') {
    //         columns.tfEqvColumn.textContent = getGradeEquivalent(Math.round(tfColOrigValue));
    //         showFinalTermGrade = getFinTermGrade(tfColOrigValue);
    //         columns.tfFinalColumn.textContent = showFinalTermGrade.display;
    //     }
    
    //     let finalRate = null;
    //     if (columns.finalEquivalent && showMidTermGrade && showFinalTermGrade) {
    //         finalRate = getFinalRateGrade(showMidTermGrade.value, showFinalTermGrade.value);
    //         columns.finalEquivalent.textContent = finalRate;
    //         if (columns.finalNumberEquivalent) {
    //             columns.finalNumberEquivalent.textContent = getGradeEquivalent(parseFloat(finalRate));
    //             columns.remarks.textContent = getPassedOrFailed(Math.round(finalRate));
    //         }
    //     }
    
    //     const rowId = columns.midColumn?.getAttribute("data-row") || columns.tfColumn?.getAttribute("data-row");
    //     const getStudent = row.querySelector("td[data-column='#1']");
    //     const student = getStudent.getAttribute("data-room-student");
    
    //     if (!rowId) {
    //         console.warn('Row ID is missing, skipping save.');
    //         return;
    //     }
    
    //     // Array to store available column data
    //     const bulkGradeData = [];
    
    //     Object.keys(columns).forEach(key => {
    //         const columnElement = columns[key];
    //         if (columnElement) {
    //             const content = columnElement.textContent.trim();
    //             if (content) {  // Only add if content is not empty
    //                 bulkGradeData.push({
    //                     teacher_id: teacherId,
    //                     room_id: roomId,
    //                     student_name: student,
    //                     column: columnElement.getAttribute("data-column"),
    //                     row: rowId,
    //                     content: content,
    //                     merged: 0,
    //                     rowspan: 1,
    //                     colspan: 1
    //                 });
    //             }
    //         }
    //     });
    
    //     if (bulkGradeData.length > 0) {
    //         console.log(bulkGradeData);
    //         // await fetch("/save-number-grade", {
    //         //     method: "POST",
    //         //     headers: {
    //         //         "Content-Type": "application/json",
    //         //         "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
    //         //     },
    //         //     body: JSON.stringify({ grades: bulkGradeData })
    //         // });
    //     } else {
    //         console.log("No data to save.");
    //     }
    // }
    
    
    

    // let observer = new MutationObserver(mutations => {
    //     mutations.forEach(mutation => {
    //         if (mutation.type === "childList") {
    //             let column = mutation.target;
    //             let row = column.closest("tr");
    //             if (row) {
    //                 updateRow(row);
    //             }
    //         }
    //     });
    // });

    // // Attach observer to both MidGr. and T.F.Gr.
    // ["MidGr.", "T.F.Gr."].forEach(columnName => {
    //     document.querySelectorAll(`td[data-column='${columnName}']`).forEach(column => {
    //         observer.observe(column, { childList: true, subtree: true });
    //     });
    // });

    
    




});




