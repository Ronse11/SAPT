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
        let url = id ? `/update-attitude/${id}` : '/create-attitude';
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
        let url = `/delete-attitude/${id}`;

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
            break;
        case 'ArrowRight':
            nextCell = document.querySelector(`td[data-row="${row}"][data-column="${String.fromCharCode(column + 1)}"]`);
            break;
    }

    if (nextCell) {
        event.preventDefault();
        nextCell.focus();
    }
}



// GETTING THE PS
function getPsAttitude() {
    $('tr').each(function() {
        let attitude = 0;
        let behaviour;
        let participation;
        let sBehaviour;
        let sParticipation;
        let sumOfBehaviour;
        let sumOfParticipation;
        let psAttitude = $(this).find('.ps-attitude');

        $('tr').find('td[chosen-behaviour="column"]').each(function() {    
            behaviour = parseFloat($(this).text());
        });
        $('tr').find('td[chosen-participation="column"]').each(function() {    
            participation = parseFloat($(this).text());
        });


        $(this).find('td[behaviour-score="column"]').each(function() {    
            sBehaviour = parseFloat($(this).text());
        });
        $(this).find('td[participation-score="column"]').each(function() {    
            sParticipation = parseFloat($(this).text());
        });


        $(this).find('.ps-attitude').each(function() {    
            if(!isNaN(sBehaviour)) {
                sumOfBehaviour = (parseFloat(sBehaviour) / behaviour) * 100;
                sumOfParticipation = (parseFloat(sParticipation) / participation) * 100;

                let weightOfBehaviour = behaviour / 100;
                let weightOfParticipation = participation / 100;

                if(!isNaN(sParticipation)) {
                    attitude = parseFloat((sumOfBehaviour * weightOfBehaviour) + (sumOfParticipation * weightOfParticipation));
                }

            }
        });
        
        // Update the total cell with the calculated sum
        if (psAttitude.length) {
            const tAttitude = attitude.toString().split('.');
            if(tAttitude.length === 1 || tAttitude[1].length === 1) {
                psAttitude.text(attitude);
            } else {
                psAttitude.text(attitude.toFixed(2));
            }
        }
        
    });
}

function getWeightAttitude() {
    $('tr').each(function() {
        let weight = 0;
        let wAttitude = $(this).find('.weight-attitude');
        let getPs = 0;
        let getWs = 0;

        $('tr').find('td[chosen-percent-attitude="column"]').each(function() {
            getWs = parseFloat($(this).text());
        });

        $(this).find('td[chosen-ps-attitude="column"]').each(function() {
            getPs = parseFloat($(this).text());
        });

        $(this).find('.weight-attitude').each(function() {

            if(!isNaN(getPs)) {

                let percentAttitude = getWs / 100;

                weight = getPs * percentAttitude;
            }
            
        });

        if (wAttitude.length) {
            const tWAttitude = weight.toString().split('.');
            if(tWAttitude.length === 1 || tWAttitude[1].length === 1) {
                wAttitude.text(weight);
            } else {
                wAttitude.text(weight.toFixed(2));
            }
        }
    });
}


$(document).ready(function() {
    // PS ATTITUDE
    getPsAttitude();

    // WS ATTITUDE
    getWeightAttitude()
            
    // RECALCULATE THE DATA WHENEVER THE ROWS OF QUIZZES IS CHANGED
    $('td[chosen-behaviour="column"]').on('input', function() {
        // PS ATTITUDE
        getPsAttitude();
        // WS ATTITUDE
        getWeightAttitude()
    });
    $('td[chosen-participation="column"]').on('input', function() {
        // PS ATTITUDE
        getPsAttitude();
        // WS ATTITUDE
        getWeightAttitude()
    });
    $('td[behaviour-score="column"]').on('input', function() {
        // PS ATTITUDE
        getPsAttitude();
        // WS ATTITUDE
        getWeightAttitude()
    });
    $('td[participation-score="column"]').on('input', function() {
        // PS ATTITUDE
        getPsAttitude();
        // WS ATTITUDE
        getWeightAttitude()
    });

    $('td[chosen-percent-attitude="column"]').on('input', function() {
        // WS ATTITUDE
        getWeightAttitude()
    });
});