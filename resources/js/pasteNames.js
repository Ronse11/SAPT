document.addEventListener('DOMContentLoaded', function() {
    const studentIdHandler = new TableHandler('studentIdNo');
    const studentName = new TableHandler('studentName');

    studentIdHandler.init();
    studentName.init();  
});

class TableHandler {
    constructor(tdID) {
        this.tdID = tdID;
        this.initialContent = '';
    }

    init() {
        document.querySelectorAll(`#${this.tdID} td[contenteditable=true]`).forEach(cell => {
            cell.addEventListener('focus', this.handleCellFocus.bind(this));
            cell.addEventListener('blur', this.handleCellBlur.bind(this));
        });
    }

    handleCellFocus(event) {
        let cell = event.target;
        this.initialContent = cell.textContent.trim();
    }

    handleCellBlur(event) {
        let cell = event.target;
        let id = cell.getAttribute('data-id');
        let content = cell.textContent.trim();
        let room_id = cell.getAttribute('data-room-id');

        if (content !== this.initialContent) {
            let url = `/update-studentNames/${id}`;
            let payload = {
                _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                content: content,
                room_id: room_id,
                tdID: this.tdID 
            };

            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(payload)
            })
            .then(response => {
                if (!response.ok) {
                    console.error('Response error:', response.statusText);
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => console.log('Success:', data))
            .catch(error => console.error('Fetch Error:', error));
        }
    }
}



// ADDING STUDENTS
document.addEventListener('DOMContentLoaded', function() {
    const addStudent = document.querySelector('.addStudent');
    const addButton = document.querySelector('.addStudentButton');
    const hide = document.querySelector('.hideHidden');
    const delStudentIcon = document.querySelector('.delStudentIcon');
    const delStudentButton = document.querySelectorAll('.delStudentButton');
    const pasteButtons = document.querySelector('.pasteButtons');
    const showPasteButtons = document.querySelector('.showPasteButtons');
    const checkAllCheckbox = document.querySelector('.checkAllStudent[type="checkbox"]');
    const checkDeleteStudentCheckboxes = document.querySelectorAll('.checkDeleteStudent');
    const isUserCheck = document.querySelectorAll('.isUserCheck');

    // Function to update the visibility of buttons based on checkbox states
    // function updateButtonVisibility() {
    //     const anyChecked = [...checkDeleteStudentCheckboxes].some(cb => cb.checked);

    //     if (anyChecked) {
    //         showPasteButtons.classList.remove('hidden');
    //         pasteButtons.classList.add('hidden');
    //     }
    // }

    // Show "Add Student" form
    addButton.addEventListener('click', (event) => {
        addStudent.classList.remove('hidden');
        event.stopPropagation();
    });

    // Hide "Add Student" form when clicking outside
    document.addEventListener('click', (event) => {
        if (!hide.contains(event.target)) {
            addStudent.classList.add('hidden');
        }
    });

    // Toggle visibility of delete buttons when "Delete Student Icon" is clicked
    delStudentIcon.addEventListener('click', () => {
        delStudentButton.forEach(button => button.classList.toggle('hidden'));
        pasteButtons.classList.toggle('hidden');
        showPasteButtons.classList.toggle('hidden');
        isUserCheck.forEach(button => button.classList.toggle('hidden'));
    });

    // Event listener for individual checkboxes
    // checkDeleteStudentCheckboxes.forEach(function(checkbox) {
    //     checkbox.addEventListener('change', updateButtonVisibility);
    // });

    // Event listener for the "Cancel" button
    document.querySelector('.cancelStudentButton').addEventListener('click', () => {
        checkDeleteStudentCheckboxes.forEach(checkbox => checkbox.checked = false);
        checkAllCheckbox.checked = false;
        showPasteButtons.classList.add('hidden');
        pasteButtons.classList.remove('hidden');
        delStudentButton.forEach(button => {
            button.classList.add('hidden');
        });
        isUserCheck.forEach(button => button.classList.remove('hidden'));
    });

    // Event listener for the "Check All" checkbox
    checkAllCheckbox.addEventListener('change', function() {
        const isChecked = this.checked;
        checkDeleteStudentCheckboxes.forEach(function(checkbox) {
            checkbox.checked = isChecked;
        });
        // updateButtonVisibility();
    });
});
