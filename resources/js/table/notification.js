document.addEventListener('DOMContentLoaded', ()=> {

    const markAsReadBtn = document.querySelector('.mark-as-read-btn');
    const deleteNotifBtn = document.querySelector('.delete-all-notif');

    document.getElementById("notificationButton").addEventListener("click", () => {
        document.getElementById("dropdownMenu").classList.toggle("hidden");
        document.getElementById("notificationCount").classList.add("hidden");
    });

    markAsReadBtn.addEventListener('click', (event) => {
        const notifId = event.target.getAttribute('data-notif');
        markAsRead(notifId);
    });

    deleteNotifBtn.addEventListener('click', (event) => {
        const notifId = event.target.getAttribute('data-notif');
        deleteAllNotifications(notifId);
    });
    
    function markAsRead(notificationId) {
        fetch(`/notifications/${notificationId}/read`, {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
                "Content-Type": "application/json"
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                let notificationElement = document.querySelectorAll('.notif');
                console.log(notificationElement);
                if (notificationElement) {
                    notificationElement.forEach((notif) => {
                        notif.classList.add('opacity-65');
                    });
                }
            }
        })
        .catch(error => console.error("Error:", error));
    }
    
    function deleteAllNotifications(id) {
        fetch(`/notifications/delete-all/${id}`, {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
                "Content-Type": "application/json"
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById("notificationList").innerHTML = "";
            }
        })
        .catch(error => console.error("Error:", error));
    }
    
});