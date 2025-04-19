import { CellManager, selectedCells } from "@js/merging.js";
// import { apply } from "mathjs";


const newCellManager = new CellManager();

const applied = document.getElementById('applied');
const showMessage = document.querySelector('.message');


document.addEventListener('DOMContentLoaded', function() {

    document.getElementById('delete-colors').addEventListener('click', deleteColors);
    document.getElementById('delete-font-colors').addEventListener('click', deleteFontColors);

    
    // COLOR PICKER START
    const colorPicker = document.getElementById('colorPicker');
    
    colorPicker.addEventListener('change', function() {
        const selectedColor = colorPicker.value.toLowerCase(); // Get the selected color in lowercase
        applyColorPicker(selectedColor);
    });
    // COLOR PICKER END

    // COLOR PICKER FONT START
    const colorFontPicker = document.getElementById('colorFontPicker');
    
    colorFontPicker.addEventListener('change', function() {
        const selectedColorFont = colorFontPicker.value.toLowerCase(); // Get the selected color in lowercase
        applyColorFontPicker(selectedColorFont);
    });
    // COLOR PICKER FONT END

    //COLOR DROPDOWN FOR BG START
    const caretButtonColor = document.getElementById('caretButtonColor');
    const dropdownColorMenu = document.getElementById('dropdownColorMenu');
    const dropdownOptionsColors = document.querySelectorAll('.dropdownOptionColors');
    const showingColor = document.getElementById('showingColor');
    let isClicked = false;  
    caretButtonColor.addEventListener('click', () => {
        dropdownColorMenu.classList.toggle('hidden');
    });
    document.addEventListener('click', (e) => {
        if (!caretButtonColor.contains(e.target)) {
            dropdownColorMenu.classList.add('hidden');
        }
    });
    dropdownOptionsColors.forEach(option => {
        option.addEventListener('click', (e) => {
            isClicked = true;
            const selectedOption = e.target.getAttribute('data-shade');

            applyColors(selectedOption);
            dropdownColorMenu.classList.add('hidden'); 

            const bgClass = Array.from(showingColor.classList).find(className => className.startsWith('bg-'));
            if (bgClass) {
                showingColor.classList.remove(bgClass);
                showingColor.classList.add(`bg-${selectedOption}`);
                showingColor.setAttribute('data-shade', selectedOption);
            }

        });

        const classToRemoveAndAdd = [
            'border-t', 'border-b', 'border-l', 'border-r', 'border-t-cursor', 'border-b-cursor', 'border-l-cursor', 'border-r-cursor'
        ];
        
        option.addEventListener('mouseover', (e) => {
            isClicked = false;
            const selectedOption = e.target.getAttribute('data-shade');


            selectedCells.forEach(cell => {

                cell.classList.remove( ...classToRemoveAndAdd );
            
                cell.classList.forEach(cls => {
                    if (cls.startsWith('bg-')) {
                        cell.classList.remove(cls);
                    }
                });
        
                const hasInline = cell.style.backgroundColor;
        
                if(hasInline) {
                    cell.style.backgroundColor = '';
                }
        
                cell.classList.add(`bg-${selectedOption}`);
            });
        });

        option.addEventListener('mouseout', (e) => {
            if (isClicked) return;
            selectedCells.forEach(cell => {
                cell.classList.add( ...classToRemoveAndAdd );
            
                cell.classList.forEach(cls => {
                    if (cls.startsWith('bg-')) {
                        cell.classList.remove(cls);
                    }
                });

            });
        });
    });
    //COLOR DROPDOWN FOR BG END

    //COLOR DROPDOWN FOR FONT START
    const caretButtonColorFont = document.getElementById('caretButtonColorFont');
    const dropdownColorFontMenu = document.getElementById('dropdownColorFontMenu');
    const dropdownOptionsColorsFont = document.querySelectorAll('.dropdownOptionColorFont');
    const showingFontColor = document.getElementById('showingFontColor');
    caretButtonColorFont.addEventListener('click', () => {
        dropdownColorFontMenu.classList.toggle('hidden');
    });
    document.addEventListener('click', (e) => {
        if (!caretButtonColorFont.contains(e.target)) {
            dropdownColorFontMenu.classList.add('hidden');
        }
    });
    dropdownOptionsColorsFont.forEach(option => {
        option.addEventListener('click', (e) => {
            const selectedOptionFont = e.target.getAttribute('data-shade');
            isClicked = true;
            
            applyFontColors(selectedOptionFont);
            dropdownColorMenu.classList.add('hidden'); 

            const hasInline = showingFontColor.style.color;
        
            if (hasInline) {
                showingFontColor.style.color = '';
            }

            showingFontColor.style.color = (`${selectedOptionFont}`);
            showingFontColor.setAttribute('data-shade', selectedOptionFont);

        });

        option.addEventListener('mouseover', (e) => {
            isClicked = false;
            const selectedOption = e.target.getAttribute('data-shade');

            selectedCells.forEach(cell => {

                const hasInline = cell.style.color;
        
                if (hasInline) {
                    cell.style.color = '';
                }
        
                cell.style.color = (`${selectedOption}`);
            });
        });

        option.addEventListener('mouseout', () => {
            if (isClicked) return;
            selectedCells.forEach(cell => {

                const hasInline = cell.style.color;
        
                if (hasInline) {
                    cell.style.color = '';
                }
        
                cell.style.color = '';
            });
        });
    });
    //COLOR DROPDOWN FOR FONT END

});

function applyColors(color) {

    if(selectedCells.length === 0) return;

    console.log(color)

    const sendColors = [];
    const cellAttr = document.getElementById('cell');
    // const teacherID = cellAttr.getAttribute('data-teacher-id');

    const parentTable = cellAttr.closest('table');
    const tableId = parentTable.id;
    const table = document.querySelector(`#${tableId}`);
    const roomId = table.getAttribute('data-room-id');
    const teacherID = table.getAttribute('data-teacher-id');

    const currentColor = color;

    const classToRemove = [
        'border-t', 'border-b', 'border-l', 'border-r', 'border-t-cursor', 'border-b-cursor', 'border-l-cursor', 'border-r-cursor'
    ];

    selectedCells.forEach(cell => {

        cell.classList.remove( ...classToRemove );
    
        cell.classList.forEach(cls => {
            if (cls.startsWith('bg-')) {
                cell.classList.remove(cls);
            }
        });

        const hasInline = cell.style.backgroundColor;

        if(hasInline) {
            cell.style.backgroundColor = '';
        }

        cell.classList.add(`bg-${currentColor}`);

        const row = parseInt(cell.dataset.row);
        const col = newCellManager.columnNameToIndex(cell.dataset.column);
        const column = newCellManager.indexToColumnName(col);

        sendColors.push({
            table_id: tableId,
            teacher_id: teacherID,
            room_id: roomId,
            row: row,
            column: column,
            color_name: currentColor,
            type: 'bg'
        });

    });

    fetch('/save-colors', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ colors: sendColors }),
    })
    .then(response => response.json())
    .then(data => {
        const msg = 'Background Color Successfully Saved!';
        floatMessage(msg);
    })
    .catch(error => {
        console.log('Error Saving Colors:', error);
    })
}

function applyColorPicker(selectedColor) {

    if(selectedCells.length === 0) return;


    const sendColors = [];
    const cellAttr = document.getElementById('cell');
    // const teacherID = cellAttr.getAttribute('data-teacher-id');

    const parentTable = cellAttr.closest('table');
    const tableId = parentTable.id;
    const table = document.querySelector(`#${tableId}`);
    const roomId = table.getAttribute('data-room-id');
    const teacherID = table.getAttribute('data-teacher-id');

    const currentColor = selectedColor;

    selectedCells.forEach(cell => {

        cell.classList.remove( 'border-t', 'border-b', 'border-l', 'border-r', 'border-t-cursor', 'border-b-cursor', 'border-l-cursor', 'border-r-cursor');
        cell.style.backgroundColor = currentColor;

        const row = parseInt(cell.dataset.row);
        const col = newCellManager.columnNameToIndex(cell.dataset.column);
        const column = newCellManager.indexToColumnName(col);

        sendColors.push({
            table_id: tableId,
            teacher_id: teacherID,
            room_id: roomId,
            row: row,
            column: column,
            color_name: currentColor,
            type: 'bg'
        });
    });

    console.log(sendColors)

    fetch('/save-colors', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ colors: sendColors }),
    })
    .then(response => response.json())
    .then(data => {
        const msg = 'Background Color Successfully Saved!';
        floatMessage(msg);
    })
    .catch(error => {
        console.log('Error Saving Colors:', error);
    })
}

// window.addEventListener('load', function() {

//     const cellAttr = document.getElementById('cell');
//     const roomID = cellAttr.getAttribute('data-room-id');

//     const parentTable = cellAttr.closest('table'); 
//     const tableID = parentTable.id;


//     fetch(`/get-colors/${roomID}/${tableID}`)
//     .then(response => {
//         if (response.status === 204 || response.status === 404) {
//             console.log("No colors found, stopping script execution.");
//             return;
//         }

//         return response.json();
//     })
//     .then(data => {

//         if (!data || !data.getColors) return;

//         const getColors = data.getColors || [];
        
//         const applyColorsToCell = (cellData, tableSelector) => {
//             // Query table once and exit if not found
//             const table = document.querySelector(tableSelector);
//             if (!table) {
//                 console.error(`Table not found: ${tableSelector}`);
//                 return;
//             }
        
//             // Create a cache for class names to remove
//             const classesToRemove = [
//                 'border-t', 'border-b', 'border-l', 'border-r', 
//                 'border-t-cursor', 'border-b-cursor', 'border-l-cursor', 'border-r-cursor'
//             ];
        
//             // Iterate over cell data
//             for (const { row, column, color_name, type } of cellData) {
//                 // Cache cell query
//                 const cell = table.querySelector(`[data-row="${row}"][data-column="${column}"]`);
                
//                 if (cell) {
//                     // Remove classes in one operation
//                     cell.classList.remove(...classesToRemove);
        
//                     if (color_name.startsWith('#')) {
//                         // Apply color based on type
//                         if (type === 'font') {
//                             cell.style.color = color_name;
//                         } else {
//                             cell.style.backgroundColor = color_name;
//                         }
//                     } else {
//                         // Add Tailwind class
//                         cell.classList.add(`bg-${color_name}`);
//                     }
//                 } else {
//                     console.error(`Cell not found at row ${row}, column ${column} in table ${tableSelector}`);
//                 }
//             }
//         };
        
//         // Call the function with pre-queried table selector
//         applyColorsToCell(getColors, `#${data.tableID}`);
        
        
//     })
//     .catch(error => {
//         console.error('Error fetching colors:', error);
//     });
// });


function deleteColors() {


    if (selectedCells.length === 0) return;
 
    const deleteColors = [];
    const cellAttr = document.getElementById('cell');

    const parentTable = cellAttr.closest('table'); 
    const tableID = parentTable.id;
    const table = document.querySelector(`#${tableID}`);
    const roomID = table.getAttribute('data-room-id');

    selectedCells.forEach(cell => {

        const row = parseInt(cell.dataset.row);
        const col = newCellManager.columnNameToIndex(cell.dataset.column);
        const column = newCellManager.indexToColumnName(col);

        const currentColorClass = Array.from(cell.classList).find(cls => cls.startsWith('bg-'));

        console.log(currentColorClass)

        deleteColors.push({
            table_id: tableID,
            room_id: roomID,
            row: row,
            column: column,
            color_name: currentColorClass,
            type: 'bg'
        });
    });

    // Send the delete data to the server via AJAX
    fetch('/delete-colors-all-cells', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ colors: deleteColors }),
    })
    .then(response => response.json())
    .then(data => {
        const msg = 'Deleted Background Color Successfully!';
        floatMessage(msg);

        const classesToAdd = [
            'border-t', 'border-b', 'border-l', 'border-r', 'border-t-cursor', 'border-b-cursor', 'border-l-cursor', 'border-r-cursor'
        ];

        const classesContains = [
            'border-t-2', 'border-b-2', 'border-l-2', 'border-r-2', 'border-t-black', 'border-b-black', 'border-l-black', 'border-r-black'
        ];

        // Remove the borders visually
        for (const { row, column, color_name, type } of data.getColors) {
            const cell = selectedCells.find(c => 
                parseInt(c.dataset.row) === row &&
                newCellManager.columnNameToIndex(c.dataset.column) === newCellManager.columnNameToIndex(column)
            );
            
            if (cell) {
                const containsAnyBorderClass = classesContains.some(borderClass => cell.classList.contains(borderClass));

                // Only add the border classes if none of the classes in classesContains are present
                if (!containsAnyBorderClass) {
                    classesToAdd.forEach(borderClass => {
                        cell.classList.add(borderClass);
                    });
                }
                const colorClass = `${color_name}`;
                if (cell.classList.contains(colorClass)) {
                    if(type == 'bg') {
                        cell.classList.remove(colorClass); // Remove the background color class
                    }
                }
            }
        }

    })
    .catch(error => {
        console.error('Error deleting borders:', error);
    });
    
}

function applyFontColors(color) {

    if(selectedCells.length === 0) return;

    console.log(color)

    const sendColors = [];
    const cellAttr = document.getElementById('cell');
    // const teacherID = cellAttr.getAttribute('data-teacher-id');

    const parentTable = cellAttr.closest('table');
    const tableId = parentTable.id;
    const table = document.querySelector(`#${tableId}`);
    const roomId = table.getAttribute('data-room-id');
    const teacherID = table.getAttribute('data-teacher-id');

    const currentColor = color;

    selectedCells.forEach(cell => {

        const hasInline = cell.style.color;

        if (hasInline) {
            cell.style.color = '';
        }

        cell.style.color = (`${currentColor}`);

        const row = parseInt(cell.dataset.row);
        const col = newCellManager.columnNameToIndex(cell.dataset.column);
        const column = newCellManager.indexToColumnName(col);

        sendColors.push({
            table_id: tableId,
            teacher_id: teacherID,
            room_id: roomId,
            row: row,
            column: column,
            color_name: currentColor,
            type: 'font'
        });
    });

    fetch('/save-colors', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ colors: sendColors }),
    })
    .then(response => response.json())
    .then(data => {
        const msg = 'Font Color Successfully Saved!';
        floatMessage(msg);
    })
    .catch(error => {
        console.log('Error Saving Colors:', error);
    })

}

function applyColorFontPicker(selectedColor) {

    if(selectedCells.length === 0) return;


    const sendColors = [];
    const cellAttr = document.getElementById('cell');
    // const teacherID = cellAttr.getAttribute('data-teacher-id');

    const parentTable = cellAttr.closest('table');
    const tableId = parentTable.id;
    const table = document.querySelector(`#${tableId}`);
    const roomId = table.getAttribute('data-room-id');
    const teacherID = table.getAttribute('data-teacher-id');

    const currentColor = selectedColor;

    selectedCells.forEach(cell => {

        cell.style.color = currentColor;

        const row = parseInt(cell.dataset.row);
        const col = newCellManager.columnNameToIndex(cell.dataset.column);
        const column = newCellManager.indexToColumnName(col);

        sendColors.push({
            table_id: tableId,
            teacher_id: teacherID,
            room_id: roomId,
            row: row,
            column: column,
            color_name: currentColor,
            type: 'font'
        });
    });

    console.log(sendColors)

    fetch('/save-colors', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ colors: sendColors }),
    })
    .then(response => response.json())
    .then(data => {
        const msg = 'Font Color Successfully Saved!';
        floatMessage(msg);
    })
    .catch(error => {
        console.log('Error Saving Colors:', error);
    })
}


function deleteFontColors() {


    if (selectedCells.length === 0) return;
 
    const deleteFontColors = [];
    const cellAttr = document.getElementById('cell');

    const parentTable = cellAttr.closest('table'); 
    const tableID = parentTable.id;
    const table = document.querySelector(`#${tableID}`);
    const roomID = table.getAttribute('data-room-id');

    selectedCells.forEach(cell => {

        const row = parseInt(cell.dataset.row);
        const col = newCellManager.columnNameToIndex(cell.dataset.column);
        const column = newCellManager.indexToColumnName(col);

        deleteFontColors.push({
            table_id: tableID,
            room_id: roomID,
            row: row,
            column: column,
            type: 'font'
        });
    });

    // Send the delete data to the server via AJAX
    fetch('/delete-colors-all-cells', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ colors: deleteFontColors }),
    })
    .then(response => response.json())
    .then(data => {
        const msg = 'Deleted Font Color Successfully!';
        floatMessage(msg);

        // Remove the borders visually
        for (const { row, column, type } of data.getColors) {
            const cell = selectedCells.find(c => 
                parseInt(c.dataset.row) === row &&
                newCellManager.columnNameToIndex(c.dataset.column) === newCellManager.columnNameToIndex(column)
            );
            if (cell) {
                if(type == 'font'){
                    cell.style.color = '';
                }
            }
        }

    })
    .catch(error => {
        console.error('Error deleting borders:', error);
    });
    
}


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