function buildEditedNotifcation(message) {
    const html = document.createElement('li');
    html.innerHTML = `<a class="dropdown-item">${message}</a>`;

    document.getElementById("notification-content")
        .appendChild(html);
}

function parseNotification(notification) {
    switch (notification.type) {
        case "account":
            if (notification.account_mng_notif_type === "edited") {
                buildEditedNotifcation("Your profile was edited by an admin")
            }
            break;
    
        default:
            break;
    }
}
