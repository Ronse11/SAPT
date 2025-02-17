document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('td[contenteditable=true]').forEach(cell => {
        cell.addEventListener('blur', handleCellBlur);
        cell.addEventListener('keydown', handleCellNavigation);
    });
});

function handleCellBlur(event) {
    let cell = event.target;
    let id = cell.getAttribute('data-id');
    let row = cell.getAttribute('data-row');
    let column = cell.getAttribute('data-column');
    let content = cell.textContent.trim();
    let room_id = cell.getAttribute('data-room-id');
    let student_name = cell.getAttribute('data-room-student');
    let room_studentID = cell.getAttribute('data-room-studentID');

    if (content.length > 0) {
        let url = id ? `/update/${id}` : '/create';
        let payload = {
            _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            content: content,
            room_id: room_id,
            room_studentID: room_studentID,
            student_name: student_name
        };
        if (!id) {
            payload.row = row;
            payload.column = column;
        }

        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(payload)
        })
        .then(response => response.json())
        .then(data => {
            if (!id) {
                cell.setAttribute('data-id', data.id);
            }
        })
        .catch(error => console.error('Error:', error));
    } else if (id) {
        let url = `/delete/${id}`;

        fetch(url, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                cell.removeAttribute('data-id');
                cell.textContent = '';
            }
        })
        .catch(error => console.error('Error:', error));
    }
}

function handleCellNavigation(event) {
    navigateWithArrowKeys(event);
}

function navigateWithArrowKeys(event) {
    let cell = event.target;
    let row = parseInt(cell.getAttribute('data-row'));
    let column = cell.getAttribute('data-column').charCodeAt(0);
    let nextCell;

    switch (event.key) {
        case 'ArrowUp':
            nextCell = document.querySelector(`td[data-row="${row - 1}"][data-column="${String.fromCharCode(column)}"]`);
            break;
        case 'ArrowDown':
            nextCell = document.querySelector(`td[data-row="${row + 1}"][data-column="${String.fromCharCode(column)}"]`);
            break;
        case 'ArrowLeft':
            nextCell = document.querySelector(`td[data-row="${row}"][data-column="${String.fromCharCode(column - 1)}"]`);
            if(nextCell == null) {
                nextCell = document.querySelector(`td[data-row="${row}"][data-column="${String.fromCharCode(column - 2)}"]`) || document.querySelector(`td[data-row="${row}"][data-column="${String.fromCharCode(column - 3)}"]`)|| document.querySelector(`td[data-row="${row}"][data-column="${String.fromCharCode(column - 4)}"]`);
            }
            break;
        case 'ArrowRight':
            nextCell = document.querySelector(`td[data-row="${row}"][data-column="${String.fromCharCode(column + 1)}"]`);
            if(nextCell == null) {
                nextCell = document.querySelector(`td[data-row="${row}"][data-column="${String.fromCharCode(column + 2)}"]`) || document.querySelector(`td[data-row="${row}"][data-column="${String.fromCharCode(column + 3)}"]`)|| document.querySelector(`td[data-row="${row}"][data-column="${String.fromCharCode(column + 4)}"]`);
            }

            break;
    }

    if (nextCell) {
        event.preventDefault();
        nextCell.focus();
    }
}
