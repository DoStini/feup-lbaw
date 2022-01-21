function buildEditedNotifcation(notification) {
    const message = "Your profile was edited by an admin";

    const html = document.createElement('li');
    html.style.cursor = "pointer";
    html.innerHTML = `<div class="dropdown-item">
                       <p>${message}</p>
                      </div>`;
    html.addEventListener('click', () => {
        route(`users/${notification.shopper}/private`);
    });
    return html;
}

function buildOrderNotification(notification) {
    const message = "There was an update to your order:";
    const html = document.createElement('li');
    html.style.cursor = "pointer";
    html.innerHTML = `<div class="dropdown-item">
                        <p class="text-center">${message}</p>
                        <div d-flex>
                            <h6 class="text-center">Product ${notification.order_id} is now <a class="badge rounded-pill badge-decoration-none badge-${notification.order_notif_type} ">
                            ${notification.order_notif_type.toUpperCase()}
                        </a></h6>
                        </div>
                      </div>`;
    html.addEventListener('click', () => {
        route(`orders/${notification.order_id}`);
    });

    return html;
}

function buildCartNotification(notification) {
    const message = "A product's price in <br> your cart has changed";
    const html = document.createElement('li');
    html.style.cursor = "pointer";
    html.innerHTML = `<div class="dropdown-item">
                        <p class="text-center">${message}</p>
                      </div>`;
    html.addEventListener('click', () => {
        route(`products/${notification.product_id}`);
    });

    return html;
}

function buildWishlistNotification(notification) {
    const message = "A product in your <br> wishlist is now available";
    const html = document.createElement('li');
    html.style.cursor = "pointer";
    html.innerHTML = `<div class="dropdown-item">
                        <p class="text-center">${message}</p>
                      </div>`;
    html.addEventListener('click', () => {
        route(`products/${notification.product_id}`);
    });

    return html;
}

function parseNotification(notification) {
    switch (notification.type) {
        case "account":
            if (notification.account_mng_notif_type === "edited") {
                return buildEditedNotifcation(notification);
            }
            break;
        case "order":
            return buildOrderNotification(notification);
        case "cart":
            return buildCartNotification(notification);
        case "wishlist":
            return buildWishlistNotification(notification);
        default:
            break;
    }
}

function getDivider() {
    const html = document.createElement('div');
    html.className = "dropdown-divider";
    return html;
}

function buildNextNotificationButton() {
    const button = document.createElement("div");
    button.id = "next-page-btn";
    button.className = "d-flex justify-content-center align-items-center";
    button.innerHTML = `<i class="bi bi-arrow-down-circle-fill btn next-page-search" style="font-size: 1.5em;"></i>`;

    return button;
}
