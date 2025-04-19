import { CellManager, selectedCells } from "@js/merging.js";


const newCellManager = new CellManager();

const applied = document.getElementById('applied');
const showMessage = document.querySelector('.message');


document.addEventListener('DOMContentLoaded', function() {


    document.getElementById('applyUnderline').addEventListener('click', function() {
        applyFontStyle('Underline');
    });
    document.getElementById('applyBold').addEventListener('click', function() {
        applyFontStyle('Bold');
    });
    document.getElementById('applyItalic').addEventListener('click', function() {
        applyFontStyle('Italic');
    });

    // TEXT ALIGNMENT
    document.getElementById('applyTextStart').addEventListener('click', function() {
        applyAlignment('Start');
    });
    document.getElementById('applyTextCenter').addEventListener('click', function() {
        applyAlignment('Center');
    });
    document.getElementById('applyTextEnd').addEventListener('click', function() {
        applyAlignment('End');
    });

    document.getElementById('delete-styles').addEventListener('click', deleteFontStyle);
});


function applyFontStyle(style) {

    if (selectedCells.length === 0) return;
    
    const underlineData = [];
    const cellAttr = document.getElementById('cell');
    // const teacherID = cellAttr.getAttribute('data-teacher-id');

    const parentTable = cellAttr.closest('table'); 
    const tableID = parentTable.id;
    const table = document.querySelector(`#${tableID}`);
    const roomID = table.getAttribute('data-room-id');
    const teacherID = table.getAttribute('data-teacher-id');

    const fontStyle = style;

    selectedCells.forEach(cell => {

        switch(style) {
            case 'Underline':
                cell.classList.add( 'underline');
            break;
            case 'Bold':
                cell.classList.add( 'font-bold');
            break;
            case 'Italic':
                cell.classList.add( 'italic');        
            break;
            default:
                console.log('Unknown Style!');
        }
        

        const row = parseInt(cell.dataset.row);
        const col = newCellManager.columnNameToIndex(cell.dataset.column);
        const column = newCellManager.indexToColumnName(col);

        // Store the border data for all sides of the cell
        underlineData.push({
            table_id: tableID,
            teacher_id: teacherID,
            room_id: roomID,
            row: row,
            column: column,
            style: fontStyle
        });
    });

    fetch('/save-underline-fonts', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ fontStyles: underlineData }),
    })
    .then(response => response.json())
    .then(data => {
        const msg = 'Font Style Successfully Saved!';
        floatMessage(msg);
    })
    .catch(error => {
        console.error('Error saving borders:', error);
    });
}



// Reapply borders on page load by fetching the data from the server
// window.addEventListener('load', function() {

//     const cellAttr = document.getElementById('cell');
//     const roomID = cellAttr.getAttribute('data-room-id');

//     const parentTable = cellAttr.closest('table'); 
//     const tableID = parentTable.id;

//     fetch(`/get-styles/${roomID}/${tableID}`)
//     .then(response => {
//         if (response.status === 204 || response.status === 404) {
//             console.log("No styles found, stopping script execution.");
//             return;
//         }

//         return response.json();
//     })
//     .then(data => {

//         if (!data || !data.stylesAllCell) return;

//         const stylesAllCellData = data.stylesAllCell || [];

//         const applyStyleToCells = (cellData, tableSelector) => {
//             const table = document.querySelector(tableSelector);
//             if (!table) {
//                 console.error(`Table not found: ${tableSelector}`);
//                 return;
//             }
        
//             const styleClassMap = {
//                 'Start': ['text-center', 'text-end', 'text-start'],
//                 'Center': ['text-center', 'text-start', 'text-end'],
//                 'End': ['text-center', 'text-start', 'text-end']
//             };
        
//             for (const item of cellData) {
//                 const columnName = item.column;
//                 const cell = table.querySelector(`[data-row="${item.row}"][data-column="${columnName}"]`);
        
//                 if (!cell) {
//                     console.error(`Cell not found at row ${item.row}, column ${columnName} in table ${tableSelector}`);
//                     continue;
//                 }

//                 const { style } = item;
//                 switch (style) {
//                     case 'Underline':
//                         cell.classList.add('underline');
//                         break;
//                     case 'Bold':
//                         cell.classList.add('font-bold');
//                         break;
//                     case 'Italic':
//                         cell.classList.add('italic');
//                         break;
//                     case 'Start':
//                     case 'Center':
//                     case 'End':
//                         cell.classList.remove(...styleClassMap[style]);
//                         cell.classList.add(`text-${style.toLowerCase()}`);
//                         break;
//                     default:
//                         console.log('Unknown Style!');
//                 }
//             }
//         };
        
//         applyStyleToCells(stylesAllCellData, `#${data.tableID}`);
        
//     })
//     .catch(error => {
//         console.error('Error fetching borders:', error);
//     });
// });



function deleteFontStyle() {

    if (selectedCells.length === 0) return;
 
    const deleteStyle = [];
    const cellAttr = document.getElementById('cell');

    const parentTable = cellAttr.closest('table'); 
    const tableID = parentTable.id;
    const table = document.querySelector(`#${tableID}`);
    const roomID = table.getAttribute('data-room-id');

    selectedCells.forEach(cell => {

        const row = parseInt(cell.dataset.row);
        const col = newCellManager.columnNameToIndex(cell.dataset.column);
        const column = newCellManager.indexToColumnName(col);

        deleteStyle.push({
            table_id: tableID,
            room_id: roomID,
            row: row,
            column: column
        });
    });

    fetch('/delete-styles', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ fontStyles: deleteStyle }),
    })
    .then(response => response.json())
    .then(data => {
        const msg = 'Removed Font Style Successfully!';
        floatMessage(msg);

        for (const styleInfo of data.fontStyle) {
            const cell = selectedCells.find(c => 
                parseInt(c.dataset.row) === styleInfo.row &&
                newCellManager.columnNameToIndex(c.dataset.column) === newCellManager.columnNameToIndex(styleInfo.column)
            );
            if (cell) {
                cell.classList.remove( 'underline', 'font-bold', 'italic');
            }
        }

    })
    .catch(error => {
        console.error('Error deleting borders:', error);
    });
}



// ALIGNMENT STARTS HERE
function applyAlignment(style) {

    if (selectedCells.length === 0) return;
    
    const underlineData = [];
    const cellAttr = document.getElementById('cell');
    // const teacherID = cellAttr.getAttribute('data-teacher-id');

    const parentTable = cellAttr.closest('table'); 
    const tableID = parentTable.id;
    const table = document.querySelector(`#${tableID}`);
    const roomID = table.getAttribute('data-room-id');
    const teacherID = table.getAttribute('data-teacher-id');

    const fontStyle = style;

    selectedCells.forEach(cell => {

        switch(style) {
            case 'Start':
                cell.classList.remove('text-center', 'text-end');
                cell.classList.add('text-start');
            break;
            case 'Center':
                cell.classList.remove('text-center', 'text-start', 'text-end');
                cell.classList.add('text-center');
            break;
            case 'End':
                cell.classList.remove('text-center', 'text-start');        
                cell.classList.add('text-end');        
            break;
            default:
                console.log('Unknown Style!');
        }
        

        const row = parseInt(cell.dataset.row);
        const col = newCellManager.columnNameToIndex(cell.dataset.column);
        const column = newCellManager.indexToColumnName(col);

        // Store the border data for all sides of the cell
        underlineData.push({
            table_id: tableID,
            teacher_id: teacherID,
            room_id: roomID,
            row: row,
            column: column,
            style: fontStyle
        });
    });

    fetch('/save-underline-fonts', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ fontStyles: underlineData }),
    })
    .then(response => response.json())
    .then(data => {
        const msg = 'Text Alignment Successfully Saved!';
        floatMessage(msg);
    })
    .catch(error => {
        console.error('Error saving borders:', error);
    });
}
// ALIGNMENT END HERE


















window.addEventListener('load', function() {

    const loadingMessage = document.getElementById('overallLoadingScreen');


    const cellAttr = document.getElementById('cell');
    const parentTable = cellAttr.closest('table'); 
    const tableID = parentTable.id;
    const table = document.querySelector(`#${tableID}`);
    const roomID = table.getAttribute('data-room-id');

    const fetchAndApplyData = (url, applyFunction) => {
        fetch(url)
            .then(response => {
                if (response.status === 204 || response.status === 404) {
                    console.log("No data found, stopping script execution.");
                    return;
                }
                return response.json();
            })
            .then(data => {
                if (!data) return;
                applyFunction(data);
            })
            .catch(error => console.error('Error fetching data:', error));
    };

    const applyColorsToCell = (cellData, tableSelector) => {
        let columnOfFirstRow = null;
        const table = document.querySelector(tableSelector);
        if (!table) {
            console.error(`Table not found: ${tableSelector}`);
            return;
        }
    
        const backgroundClassesToRemove = [
            'border-t', 'border-b', 'border-l', 'border-r', 
            'border-t-cursor', 'border-b-cursor', 'border-l-cursor', 'border-r-cursor',
            'bg-*'
        ];

    
            cellData.forEach(({ row, column, color_name, type }) => {
                columnOfFirstRow = column;
                const firstCell = table.querySelector(`[data-row="${startRow}"][data-column="${columnOfFirstRow}"]`);
                const cell = table.querySelector(`[data-row="${row}"][data-column="${column}"]`);
                if (!cell) return;
        
                // Clear existing background styles
                if (type !== 'font') {
                    cell.classList.remove(...backgroundClassesToRemove);
                }
        
                // Apply font and background styles if both are defined
                if (type === 'font&bg') {
                    applyFontAndBackgroundStyles(cell, color_name, firstCell);
                } else if (type === 'font') {
                    separateFont(cell, color_name, firstCell);
                } else {
                    separateBG(cell, color_name, firstCell);
                }
            });

        
        function applyFontAndBackgroundStyles(cell, color_name, firstCell) {
            const [fontColor, bgColor] = parseColors(color_name);
            
            if (bgColor) {
                const colorResult = colorReCondition(bgColor, cell, firstCell);
                applyBackgroundStyle(cell, colorResult, bgColor);
            }
        
            if (fontColor) {
                const colorResult = colorReCondition(fontColor, cell,  firstCell);
                console.log(colorResult);
                applyFontStyle(cell, colorResult, fontColor);
            }
        }

        function separateFont(cell, color_name, firstCell) {
            const colorResult = colorReCondition(color_name, cell,  firstCell);
            applyFontStyle(cell, colorResult, color_name);
        }

        
        function separateBG(cell, color_name, firstCell) {
            const colorResult = colorReCondition(color_name, cell,  firstCell);
            applyBackgroundStyle(cell, colorResult, color_name);
        }

        function colorReCondition(currentColor, cell, firstCell) {
            
            const formula = firstCell.getAttribute('data-formula');

            if(firstCell && formula === null) {
                return currentColor;
            }

            const [firstColor, secColor] = separateColors(currentColor);
            
            const matchTrue = formula.match(/if\(.+?,(.+?),.+\)/);
            if(!matchTrue) {
                return currentColor;
            }
            const matchTrueContent = matchTrue ? matchTrue[1].trim() : null;
            const conditionCellContent = cell.textContent.trim();
            const result = matchTrueContent === conditionCellContent;

            return result ? firstColor : secColor;
        }
        
        function applyBackgroundStyle(cell, colorResult, bgColor) {
            if (colorResult.startsWith('#')) {
                cell.style.backgroundColor = colorResult;
            } else {
                cell.classList.add(`bg-${colorResult}`);
            }
            cell.setAttribute('bg-colors', bgColor);
        }
        
        function applyFontStyle(cell, colorResult, fontColor) {
            cell.setAttribute('font-colors', fontColor);
            console.log(colorResult);
            if (colorResult.startsWith('#')) {
                cell.style.color = colorResult;
            }
        }
        
        function parseColors(color_name) {
            return color_name.split('&');
        }

        function separateColors(color) {
            return color.split(',');
        }
        
    };    

    const applyBordersToCells = (cellData, tableSelector) => {
        const table = document.querySelector(tableSelector);
        if (!table) {
            console.error(`Table not found: ${tableSelector}`);
            return;
        }

        cellData.forEach(item => {
            const cell = table.querySelector(`[data-row="${item.row}"][data-column="${item.column}"]`);
            if (cell) {
                if (item.isTop) cell.classList.remove('border-t', 'border-t-cursor');
                if (item.isBottom) cell.classList.remove('border-b', 'border-b-cursor');
                if (item.isLeft) cell.classList.remove('border-l', 'border-l-cursor');
                if (item.isRight) cell.classList.remove('border-r', 'border-r-cursor');
                
                if (item.isTop) cell.classList.add('border-t-2', 'border-t-black');
                if (item.isBottom) cell.classList.add('border-b-2', 'border-b-black');
                if (item.isLeft) cell.classList.add('border-l-2', 'border-l-black');
                if (item.isRight) cell.classList.add('border-r-2', 'border-r-black');
            }
        });
    };

    const applyStyleToCells = (cellData, tableSelector) => {
        const table = document.querySelector(tableSelector);
        if (!table) {
            console.error(`Table not found: ${tableSelector}`);
            return;
        }

        const styleClassMap = {
            'Start': ['text-center', 'text-end', 'text-start'],
            'Center': ['text-center', 'text-start', 'text-end'],
            'End': ['text-center', 'text-start', 'text-end']
        };

        cellData.forEach(item => {
            const cell = table.querySelector(`[data-row="${item.row}"][data-column="${item.column}"]`);
            if (cell) {
                switch (item.style) {
                    case 'Underline':
                        cell.classList.add('underline');
                        break;
                    case 'Bold':
                        cell.classList.add('font-bold');
                        break;
                    case 'Italic':
                        cell.classList.add('italic');
                        break;
                    case 'Start':
                    case 'Center':
                    case 'End':
                        cell.classList.remove(...styleClassMap[item.style]);
                        cell.classList.add(`text-${item.style.toLowerCase()}`);
                        break;
                    default:
                        console.log('Unknown Style!');
                }
            }
        });
    };

    Promise.all([
        fetchAndApplyData(`/get-colors/${roomID}/${tableID}`, data => {
            applyColorsToCell(data.getColors || [], `#${data.tableID}`);
        }),
        fetchAndApplyData(`/get-borders/${roomID}/${tableID}`, data => {
            applyBordersToCells(data.bordersAllCell || [], `#${data.tableID}`);
        }),
        fetchAndApplyData(`/get-styles/${roomID}/${tableID}`, data => {
            applyStyleToCells(data.stylesAllCell || [], `#${data.tableID}`);
        })
    ]).finally(() => {
        requestAnimationFrame(() => {
            if (loadingMessage) {
                loadingMessage.remove();
            }
            newCellManager.clearSelection();
        });
    });
});


function floatMessage(msg) {
    showMessage.textContent = msg;
    applied.classList.remove('opacity-0', '-translate-x-[-15rem]');
    applied.classList.add('opacity-100', 'translate-y-0');
    applied.classList.remove('pointer-events-none');
    applied.classList.add('pointer-events-auto');
    setTimeout(() => {
        applied.classList.remove('opacity-100', 'translate-y-0');
        applied.classList.add('opacity-0', '-translate-x-[-15rem]');
        applied.classList.remove('pointer-events-auto');
        applied.classList.add('pointer-events-none');
    }, 2000);
}