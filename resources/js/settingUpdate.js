document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('input[data-id]').forEach(input => {
        input.addEventListener('blur', handleUpdate);
        input.addEventListener('focus', handleCellFocus);
    });
});
let initialContentLength = 0;

function handleCellFocus(event) {
    let input = event.target;
    initialContentLength = input.value.trim().length;
}

function handleUpdate(event) {
    let input = event.target;
    let id = input.getAttribute('data-id');
    let attribute = input.getAttribute('data-name');
    let content = input.value.trim();
    let newContentLength = content.length;
    console.log(id)

    if (newContentLength !== initialContentLength) {
        let url = `/update-class/${id}`;
        let payload = {
            _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            [attribute]: content
        };
        console.log(payload)

        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(payload)
        })
        .then(response => response.json())
        .catch(error => console.error('Error:', error));
    }
}
