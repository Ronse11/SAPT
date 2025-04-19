import { CellManager, selectedCells } from "@js/merging.js";


const newCellManager = new CellManager();



document.addEventListener('DOMContentLoaded', function() {

    document.getElementById('addFontSize').addEventListener('click', function() {
        addFontSize('1');
    });

    document.getElementById('minusFontSize').addEventListener('click', function() {
        addFontSize('-1');
    });


    const fontSizeButton = document.getElementById('fontSizeButton');
    const dropdownFontSize = document.getElementById('dropdownFontSize');
    const dropdownSize = document.querySelectorAll('.dropDownSize');
    const showFontSize = document.getElementById('showFontSize');

    fontSizeButton.addEventListener('click', function() {
        dropdownFontSize.classList.toggle('hidden');
    });
    document.addEventListener('click', (e) => {
        if (!fontSizeButton.contains(e.target)) {
            dropdownFontSize.classList.add('hidden');
        }
    });
    dropdownSize.forEach(size => {
        size.addEventListener('click', (e) => {
            const selectSize = e.target.getAttribute('data-value');

            addChosenFontSize(selectSize);
            dropdownFontSize.classList.add('hidden');
        });
    });
    showFontSize.addEventListener('blur', function(event) {
        const getSize = event.target.value;

        addChosenFontSize(getSize);
    });

    // MERGE AND UNMERGE CARET BUTTON STARTS HERE!
    const caretButtonMergeUnmerge = document.getElementById('caretButtonMergeUnmerge');
    const dropdownMergeUnmerge = document.getElementById('dropdownMergeUnmerge');
    // const dropdownLabelButton = document.getElementById('apply-border-btn');
    const dropdownOptionMergeUnmerge = document.querySelector('.dropdownOptionMergeUnmerge');
    caretButtonMergeUnmerge.addEventListener('click', () => {
        dropdownMergeUnmerge.classList.toggle('hidden');
    });
    document.addEventListener('click', (e) => {
        if (!caretButtonMergeUnmerge.contains(e.target)) {
            dropdownMergeUnmerge.classList.add('hidden');
        }
    });
    dropdownOptionMergeUnmerge.addEventListener('click', (e) => {
        // const selectedOptionFont = e.target.getAttribute('data-shade');

        dropdownColorMenu.classList.add('hidden'); 
    });
    // MERGE AND UNMERGE CARET BUTTON ENDS HERE!
    
});


function addFontSize(count) {

    if (selectedCells.length === 0) return;
    
    console.log(count);
    
    const fontSizeData = [];
    const cellAttr = document.getElementById('cell');

    const parentTable = cellAttr.closest('table'); 
    const tableID = parentTable.id;
    const table = document.querySelector(`#${tableID}`);
    const roomID = table.getAttribute('data-room-id');
    const teacherID = table.getAttribute('data-teacher-id');

    const showFontSize = document.getElementById('showFontSize');
    let newSize;

    selectedCells.forEach(cell => {

        console.log(cell);

        const regex = /^text-(\d{1,2}|100)px$/;
        const regex1 = /^(\d{1,2}|100)px$/;
        const matchingClass = [...cell.classList].find(cls => cls.match(regex));
        const matchingStyle = cell.style.fontSize.match(regex1);

        console.log(matchingStyle);
        
        if (matchingClass || matchingStyle) {
            const size = matchingClass ? matchingClass.match(regex) : matchingStyle;
            const fontSize = size[1];
            newSize = parseFloat(fontSize) + parseInt(count);

            cell.classList.remove(matchingClass);
            cell.style.fontSize = ``;

            if(newSize >= 1) {
                cell.style.fontSize = `${newSize}px`;
                showFontSize.value = newSize;
            } else {
                cell.style.fontSize = `1px`;
                showFontSize.value = 1;
            }
        }
        

        const row = parseInt(cell.dataset.row);
        const col = newCellManager.columnNameToIndex(cell.dataset.column);
        const column = newCellManager.indexToColumnName(col);

        fontSizeData.push({
            table_id: tableID,
            teacher_id: teacherID,
            room_id: roomID,
            row: row,
            column: column,
            font_size: newSize
        });
    });

    console.log(fontSizeData);

    // fetch('/save-underline-fonts', {
    //     method: 'POST',
    //     headers: {
    //         'Content-Type': 'application/json',
    //         'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    //     },
    //     body: JSON.stringify({ fontStyles: underlineData }),
    // })
    // .then(response => response.json())
    // .then(data => {
    //     console.log('Borders saved:', data);
    // })
    // .catch(error => {
    //     console.error('Error saving borders:', error);
    // });
}

function addChosenFontSize(count) {

    if (selectedCells.length === 0) return;
    
    console.log(count);
    
    const fontSizeData = [];
    const cellAttr = document.getElementById('cell');

    const parentTable = cellAttr.closest('table'); 
    const tableID = parentTable.id;
    const table = document.querySelector(`#${tableID}`);
    const roomID = table.getAttribute('data-room-id');
    const teacherID = table.getAttribute('data-teacher-id');

    const showFontSize = document.getElementById('showFontSize');

    selectedCells.forEach(cell => {

        const regex = /^text-(\d{1,2}|100)px$/;
        const regex1 = /^(\d{1,2}|100)px$/;
        const matchingClass = [...cell.classList].find(cls => cls.match(regex));
        const matchingStyle = cell.style.fontSize.match(regex1);

        if (matchingClass || matchingStyle) {
            cell.classList.remove(matchingClass);
            cell.style.fontSize = ``;

            console.log(count);

            if(count >= 1) {
                cell.style.fontSize = `${count}px`;
                showFontSize.value = count;
            } else {
                cell.style.fontSize = `1px`;
                showFontSize.value = 1;
            }
        }
        

        const row = parseInt(cell.dataset.row);
        const col = newCellManager.columnNameToIndex(cell.dataset.column);
        const column = newCellManager.indexToColumnName(col);

        fontSizeData.push({
            table_id: tableID,
            teacher_id: teacherID,
            room_id: roomID,
            row: row,
            column: column,
            font_size: count
        });
    });


    // fetch('/save-underline-fonts', {
    //     method: 'POST',
    //     headers: {
    //         'Content-Type': 'application/json',
    //         'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    //     },
    //     body: JSON.stringify({ fontStyles: underlineData }),
    // })
    // .then(response => response.json())
    // .then(data => {
    //     console.log('Borders saved:', data);
    // })
    // .catch(error => {
    //     console.error('Error saving borders:', error);
    // });
}