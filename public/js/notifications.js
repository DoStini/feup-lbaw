function buildEditedNotifcation(message) {
    const html = document.createElement('li');
    html.innerHTML = `<a class="dropdown-item">${message}</a>`;
    return html;
}

function buildOrderNotification(notification) {
    const message = `Your order ${notification.order_id} was updated to ${notification.order_notif_type}`;

    const html = document.createElement('li');
    html.style.cursor = "pointer";
    html.innerHTML = `<a class="dropdown-item">${message}</a>`;
    html.addEventListener('click', () => {
        route(`orders/${notification.order_id}`);
    });

    return html;
}

function parseNotification(notification) {
    switch (notification.type) {
        case "account":
            if (notification.account_mng_notif_type === "edited") {
                return buildEditedNotifcation("Your profile was edited by an admin ");
            }
            break;
        case "order":
            return buildOrderNotification(notification);
        default:
            break;
    }
}

function buildNextNotificationButton() {
    const button = document.createElement("div");
    button.id = "next-page-btn";
    button.className = "d-flex justify-content-center align-items-center";
    button.innerHTML = `<i class="bi bi-arrow-down-circle-fill btn next-page-search"></i>`;

    return button;
}
