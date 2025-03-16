// export const formulaState = {
//     isActive: false
// };

// export function activateFormula() {
//     if (!formulaState.isActive) {
//         formulaState.isActive = true;
//     }
// }

// export function deactivateFormula() {
//     if (formulaState.isActive) {
//         formulaState.isActive = false;
//     }
// }

// export function isFormulaActiveStateRating() {
//     return formulaState.isActive;
// }

document.addEventListener("DOMContentLoaded", () => {
    const table = document.querySelector(".rating-table");
    const roomId = table.getAttribute('data-room-id');
    const teacherId = table.getAttribute('data-teacher-id');
    
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

        const originalValue = grade * 0.4; // Keep decimal
        const roundedValue = Math.round(originalValue); // Round for display

        return { display: roundedValue, value: originalValue };
    }

    function getFinTermGrade(grade) {
        if (isNaN(grade)) return { display: "Invalid Grade", value: null };

        const originalValue = grade * 0.6; // Keep decimal
        const roundedValue = Math.round(originalValue); // Round for display

        return { display: roundedValue, value: originalValue };
    }

    function getFinalRateGrade(mid, fin) {
        return Math.round(mid + fin);
    }

    function getPassedOrFailed(grade) {
        if(grade >= 75) {
            return 'Passed';
        } else {
            return '';
        }
    }

    table.addEventListener("keydown", async (event) => {
        if (event.key === "Enter") {  
            event.preventDefault();   
            const cell = event.target;
            cell.setAttribute('data-p', 'process');
            const formula = cell.innerText.trim();
    
            if (!formula.startsWith("=")) return; // Ensure it's a formula
    
            const column = formula.substring(1);
            const currentCol = cell.getAttribute('data-column');
    
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
                    
                            addGradeEntry(bulkGradeData, skill.student_name, "FR.Eqv", skill.row, finalRate);
                            addGradeEntry(bulkGradeData, skill.student_name, "FR.N.Eqv", skill.row, getGradeEquivalent(finalRate));
                            addGradeEntry(bulkGradeData, skill.student_name, "Remarks", skill.row, getPassedOrFailed(finalRate));
                    
                            getCell("FR.Eqv", skill.row).innerText = finalRate;
                            getCell("FR.N.Eqv", skill.row).innerText = getGradeEquivalent(finalRate);
                            getCell("Remarks", skill.row).innerText = getPassedOrFailed(finalRate);
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
    const inputs = ['MidGr.', 'T.F.Gr.'];

    inputs.forEach(id => {
        const inputElement = document.getElementById(id);
        if (inputElement) {
            inputElement.addEventListener('blur', handleGradeInputBlur);
        }
    });
    
    async function handleGradeInputBlur(event) {
        const cell = event.target;
        cell.setAttribute('data-p', 'process');
        const column = cell.value.trim();
        const currentCol = cell.getAttribute('name');
        
        console.log(column);
        console.log(currentCol);
    
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
    
                        addGradeEntry(bulkGradeData, skill.student_name, "FR.Eqv", skill.row, finalRate);
                        addGradeEntry(bulkGradeData, skill.student_name, "FR.N.Eqv", skill.row, getGradeEquivalent(finalRate));
                        addGradeEntry(bulkGradeData, skill.student_name, "Remarks", skill.row, getPassedOrFailed(finalRate));
    
                        getCell("FR.Eqv", skill.row).innerText = finalRate;
                        getCell("FR.N.Eqv", skill.row).innerText = getGradeEquivalent(finalRate);
                        getCell("Remarks", skill.row).innerText = getPassedOrFailed(finalRate);
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
                previousValue = unitValue; 
    
            } catch (error) {
                console.error("Error applying units:", error);
            }
        }
    });
    

    // semester.addEventListener('blur', async() => {
    //     const semValue = semester.value.trim(); 

    //     if (semValue === previousSemtValue) return; 
    
    //     if (semValue.length >= 1) {
    //         try {
    //             const response = await fetch("/apply-sem", {
    //                 method: "POST",
    //                 headers: {
    //                     "Content-Type": "application/json",
    //                     'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    //                 },
    //                 body: JSON.stringify({ semValue, room_id: roomId })
    //             });
    
    //             if (!response.ok) {
    //                 const errorData = await response.json();
    //                 alert(errorData.error);
    //                 return;
    //             }
    
    //         } catch (error) {
    //             console.error("Error applying units:", error);
    //         }
    //     }
    // });





    // SAVING THE COLUMN IN USING INPUT
    
    
    const userInput = ['semester', 'MidGr.', 'T.F.Gr.'];
    const previousValues = {};
    
    userInput.forEach(id => {
        const input = document.getElementById(id);
        if (input) {
            input.addEventListener('focus', (event) => {
                previousValues[id] = event.target.value.trim(); // Store old value
            });
    
            input.addEventListener('blur', handleInputColumn);
        }
    });
    
    async function handleInputColumn(event) {
        const input = event.target;
        const newValue = input.value.trim();
        const previousValue = previousValues[input.id];
    
        if (newValue === previousValue) return; // No change, exit
    
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
                        table_id: input.id, // Send dynamic table_id
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
    


});