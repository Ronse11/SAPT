// const { column } = require("mathjs");

document.addEventListener('DOMContentLoaded', () => {
    const exportButton = document.getElementById('export');
    const loadingScreen = document.getElementById('loadingScreen');
    
    exportButton.addEventListener('click', () => {
        showLoading();
        exportData();
    });
    
    function showLoading() {
        loadingScreen.classList.remove('hidden');
    }
    
    function hideLoading() {
        loadingScreen.classList.add('hidden');
    }

    function exportData() {
        // Collect data from the table
        const currentTable = document.querySelector('.main-table');
        const getId = currentTable.id;

        const roomID = currentTable.getAttribute('data-room-id');

        const csRows = Array.from(document.querySelectorAll(`#${getId} tr`)).slice(1); // Skip the header row
        const ratingRows = Array.from(document.querySelectorAll(`#rating-table tr`)).slice(2); // Skip the header row
        const tableData = [];
        const tableRating = [];
        
        // Initialize an array to track occupied cells
        const cellMap = [];

        
        // Filter rows with meaningful content
        const rowsWithContent = csRows.filter(row => {
            const cells = Array.from(row.querySelectorAll('td, th'));
            return cells.some(cell => cell.textContent.trim() !== "");
        });

        const rowsWithContentRating = ratingRows.filter(row => {
            const cells = Array.from(row.querySelectorAll('td, th'));
            return cells.some(cell => cell.textContent.trim() !== "");
        });
        
        rowsWithContent.forEach((row, rowIndex) => {
            const rowData = [];
            const cells = Array.from(row.querySelectorAll('td, th')).slice(1); // Adjust if needed to skip specific columns
            let colIndex = 0;
        
            
            cells.forEach((cell) => {
                // Skip to the next available column index if the current one is occupied
                while (cellMap[rowIndex] && cellMap[rowIndex][colIndex]) {
                    colIndex++;
                }
                
                const cellText = cell.innerText.trim() || '';
                if (!cellText) return; // Skip empty cells
        
                const rowspan = parseInt(cell.getAttribute('rowspan') || '1', 10);
                const colspan = parseInt(cell.getAttribute('colspan') || '1', 10);
                const cellColumn = cell.getAttribute('data-column');
                const cellRow = cell.getAttribute('data-row');
                const formula = cell.getAttribute('data-formula') || ''; // Capture the formula
                const updatedFormula = formula
                    .replace(/([A-Z]+)(?=\D|$)(?![^"]*"[^"]*")/g, (match) => {
                        return match === "SUM" ? match : `${match}${cellRow}`;
                    })
                    .replace(/^if\(([^,]+),([^,]+),([^,]+)\)$/, (_, condition, trueValue, falseValue) => {
                        // Remove numbers from inside quotes (if present) and leave the letter alone
                        const formatValue = (value) => value.replace(/\d+/g, ""); // Remove numbers from inside quotes
                        
                        return `=if(${condition},"${formatValue(trueValue.trim())}","${formatValue(falseValue.trim())}")`;
                    });
        
                // Detect text formatting (bold, italic, underline)
                const isBold = cell.style.fontWeight === 'bold' || cell.classList.contains('font-bold');
                const isItalic = cell.style.fontStyle === 'italic' || cell.classList.contains('italic');
                const isUnderline = cell.style.textDecoration === 'underline' || cell.classList.contains('underline');
        
                // Detect text alignment (start, center, end)
                const isLeftAligned = cell.classList.contains('text-left');
                const isCenterAligned = cell.classList.contains('text-center');
                const isRightAligned = cell.classList.contains('text-right');
        
                // Store the cell data with all relevant attributes
                rowData[colIndex] = {
                    value: cellText,
                    formula: updatedFormula, // Include the formula in the data
                    cellColumn: cellColumn,
                    cellRow: cellRow,
                    rowspan: rowspan,
                    colspan: colspan,
                    bold: isBold,
                    italic: isItalic,
                    underline: isUnderline,
                    align: isLeftAligned ? 'left' : (isCenterAligned ? 'center' : (isRightAligned ? 'right' : '')),
                };

                // Mark the cells as occupied in the cellMap based on rowspan and colspan
                for (let r = 0; r < rowspan; r++) {
                    for (let c = 0; c < colspan; c++) {
                        if (!cellMap[rowIndex + r]) {
                            cellMap[rowIndex + r] = [];
                        }
                        cellMap[rowIndex + r][colIndex + c] = true;
                    }
                }
        
                colIndex += colspan;

            });
        
            if (rowData.length > 0) { // Only add rows with meaningful data
                tableData.push(rowData);
            }
        });


        rowsWithContentRating.forEach((row, rowIndex) => {
            const rowData = [];
            const cells = Array.from(row.querySelectorAll('td, th'));
            let colIndex = 0;
            
            cells.forEach((cell) => {
                // Skip to the next available column index if the current one is occupied
                while (cellMap[rowIndex] && cellMap[rowIndex][colIndex]) {
                    colIndex++;
                }
                
                const cellText = cell.innerText.trim() || '';
                if (!cellText) return; // Skip empty cells
        
                const rowspan = parseInt(cell.getAttribute('rowspan') || '1', 10);
                const colspan = parseInt(cell.getAttribute('colspan') || '1', 10);
                const cellColumn = cell.getAttribute('data-column');
                const cellRow = cell.getAttribute('data-row');
                const formula = cell.getAttribute('data-formula') || ''; // Capture the formula
                const updatedFormula = formula
                    .replace(/([A-Z]+)(?=\D|$)(?![^"]*"[^"]*")/g, (match) => {
                        return match === "SUM" ? match : `${match}${cellRow}`;
                    })
                    .replace(/^if\(([^,]+),([^,]+),([^,]+)\)$/, (_, condition, trueValue, falseValue) => {
                        // Remove numbers from inside quotes (if present) and leave the letter alone
                        const formatValue = (value) => value.replace(/\d+/g, ""); // Remove numbers from inside quotes
                        
                        return `=if(${condition},"${formatValue(trueValue.trim())}","${formatValue(falseValue.trim())}")`;
                    });
        
                // Detect text formatting (bold, italic, underline)
                const isBold = cell.style.fontWeight === 'bold' || cell.classList.contains('font-bold');
                const isItalic = cell.style.fontStyle === 'italic' || cell.classList.contains('italic');
                const isUnderline = cell.style.textDecoration === 'underline' || cell.classList.contains('underline');
        
                // Detect text alignment (start, center, end)
                const isLeftAligned = cell.classList.contains('text-left');
                const isCenterAligned = cell.classList.contains('text-center');
                const isRightAligned = cell.classList.contains('text-right');
        
                // Store the cell data with all relevant attributes
                rowData[colIndex] = {
                    value: cellText,
                    formula: updatedFormula, // Include the formula in the data
                    cellColumn: cellColumn,
                    cellRow: cellRow,
                    rowspan: rowspan,
                    colspan: colspan,
                    bold: isBold,
                    italic: isItalic,
                    underline: isUnderline,
                    align: isLeftAligned ? 'left' : (isCenterAligned ? 'center' : (isRightAligned ? 'right' : '')),
                };

                // Mark the cells as occupied in the cellMap based on rowspan and colspan
                for (let r = 0; r < rowspan; r++) {
                    for (let c = 0; c < colspan; c++) {
                        if (!cellMap[rowIndex + r]) {
                            cellMap[rowIndex + r] = [];
                        }
                        cellMap[rowIndex + r][colIndex + c] = true;
                    }
                }
        
                colIndex += colspan;

            });


            if (rowData.length > 0) { // Only add rows with meaningful data
                tableRating.push(rowData);
            }
        });


        // Send the data to the backend
        fetch(`/export-to-excel/${roomID}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ tableData, tableRating }),
        })
        .then(response => response.json())
        .then(async data => {
            if (data.error) {
                throw new Error(data.error);
            }
            
            const response = await fetch(data.download_url);
            return ({
                blob: response.blob(),
                filename: data.filename 
            });
        })
        .then(async ({ blob, filename }) => {
            const blobData = await blob;
            const url = window.URL.createObjectURL(blobData);
            const a = document.createElement('a');
            a.style.display = 'none';
            a.href = url;
            a.download = filename;
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(url);
        })
        .catch((error) => {
            console.error('Error:', error);
            alert('An unexpected error occurred.');
        })
        .finally(() => {
            hideLoading();
        });
    }

});



































// function exportData() {
//     // Collect data from the table
//     const currentTable = document.querySelector('.main-table');
//     const getId = currentTable.id;

//     const roomID = currentTable.getAttribute('data-room-id');

//     const rows = Array.from(document.querySelectorAll(`#${getId} tr`)).slice(1);
//     const tableData = [];

//     // Initialize an array to track occupied cells
//     const cellMap = [];

    
//         rows.forEach((row, rowIndex) => {
//             const rowData = [];
//             const cells = Array.from(row.querySelectorAll('td, th')).slice(1);
//             let colIndex = 0;
    
//             cells.forEach((cell) => {
//                 // Skip to the next available column index if current is occupied
//                 while (cellMap[rowIndex] && cellMap[rowIndex][colIndex]) {
//                     colIndex++;
//                 }
    
//                 const cellText = cell.innerText || '';
//                 const rowspan = parseInt(cell.getAttribute('rowspan') || '1', 10);
//                 const colspan = parseInt(cell.getAttribute('colspan') || '1', 10);
//                 const cellRow = cell.getAttribute('data-row');
//                 const formula = cell.getAttribute('data-formula') || ''; // Capture the formula
//                 const updatedFormula = formula
//                 .replace(/([A-Z]+)(?=\D|$)(?![^"]*"[^"]*")/g, (match) => {
//                     return match === "SUM" ? match : `${match}${cellRow}`;
//                 })
//                 // Step 2: Handle "if" statements where values inside quotes should not change
//                 .replace(/^if\(([^,]+),([^,]+),([^,]+)\)$/, (_, condition, trueValue, falseValue) => {
//                     // Remove any row numbers inside quotes (if present) and leave the letter alone
//                     const formatValue = (value) => {
//                         return value.replace(/\d+/g, ""); // Remove numbers from inside quotes
//                     };
                    
//                     return `=if(${condition},"${formatValue(trueValue.trim())}","${formatValue(falseValue.trim())}")`;
//                 });
    
//                 console.log(colspan);
    
//                 // Detect text formatting (bold, italic, underline)
//                 const isBold = cell.style.fontWeight === 'bold' || cell.classList.contains('font-bold');
//                 const isItalic = cell.style.fontStyle === 'italic' || cell.classList.contains('italic');
//                 const isUnderline = cell.style.textDecoration === 'underline' || cell.classList.contains('underline');
    
//                 // Detect text alignment (start, center, end)
//                 const isLeftAligned = cell.classList.contains('text-left');
//                 const isCenterAligned = cell.classList.contains('text-center');
//                 const isRightAligned = cell.classList.contains('text-right');
    
//                 // Store the cell data with all relevant attributes
//                 rowData[colIndex] = {
//                     value: cellText,
//                     formula: updatedFormula, // Include the formula in the data
//                     rowspan: rowspan,
//                     colspan: colspan,
//                     bold: isBold,
//                     italic: isItalic,
//                     underline: isUnderline,
//                     align: isLeftAligned ? 'left' : (isCenterAligned ? 'center' : (isRightAligned ? 'right' : '')),
//                 };
    
//                 // Mark the cells as occupied in the cellMap based on rowspan and colspan
//                 for (let r = 0; r < rowspan; r++) {
//                     for (let c = 0; c < colspan; c++) {
//                         if (!cellMap[rowIndex + r]) {
//                             cellMap[rowIndex + r] = [];
//                         }
//                         cellMap[rowIndex + r][colIndex + c] = true;
//                     }
//                 }
    
//                 colIndex += colspan;
//             });
    
//             tableData.push(rowData);
//         });
//     function formatIfStatement(input) {
//         return input.replace(/^if\(([^,]+),([^,]+),([^,]+)\)$/, (_, condition, trueValue, falseValue) => {
//             return `=if(${condition},"${trueValue.trim()}","${falseValue.trim()}")`;
//         });
//     }


//     // Send the data to the backend
//     fetch(`/export-to-excel/${roomID}`, {
//         method: 'POST',
//         headers: {
//             'Content-Type': 'application/json',
//             'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
//         },
//         body: JSON.stringify({ tableData }),
//     })
//     .then((response) => {
//         if (!response.ok) {
//             throw new Error(`HTTP error! status: ${response.status}`);
//         }
//         return response.blob(); // Get the response as a Blob
//     })
//     .then((blob) => {
//         // Create a download link for the binary file
//         const url = window.URL.createObjectURL(blob);
//         const a = document.createElement('a');
//         a.style.display = 'none';
//         a.href = url;
//         a.download = `export_${new Date().toISOString().slice(0, 19).replace(/:/g, '-')}.xlsx`; // Generate a filename
//         document.body.appendChild(a);
//         a.click();
//         window.URL.revokeObjectURL(url); // Clean up the URL object
//     })
//     .catch((error) => {
//         console.error('Error:', error);
//         alert('An unexpected error occurred.');
//     });
// }