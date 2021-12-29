let inMenu = false;
let loading = false;

const menu = $("#cart-dropdown-menu");
const menuContent = $("#cart-dropdown-menu-content");

function noProducts() {
    const elem = document.createElement("div");
    elem.className = "container";

    elem.innerHTML = `
        <div class="row justify-content-center">
            No products in the cart
        </div>
    `;

    menuContent.append(elem);
}

function handleDelete(data) {
    clearElements();
    fillMenu(data.items, data.total);
}

function handleUpdate(productId, target, value) {

}

function insertProduct(product, idx) {
    const productImg = product.photos[0];
    const fallBack = "/img/default.jpg";
    const elem = document.createElement("div");
    elem.id = `cart-product-${idx}`;
    elem.innerHTML = `
    <div class="container" href="#">
        <div class="row align-items-center mb-3">
            <img class="col-3" src="${productImg}" onerror="this.src='${fallBack}'">
            <div class="col-9">
                <div class="row align-items-center justify-content-between">
                    <div class="col-10 dropdown-cart-name">${product.name}</div>
                    <i id="cart-remove-${idx}" class="cart-remove col-2 bi bi-x-lg"></i>
                </div>
                <div class="row dropdown-cart-amount justify-content-between">
                    <div class="col px-0">
                        ${product.amount}x${product.price}€
                    </div>
                    <div class="col item-subtotal">
                        ${(product.amount * product.price).toFixed(2)}€
                    </div>
                </div>
                <div class="row dropdown-cart-amount justify-content-center">
                    <div id="cart-number-selector-${idx}" class="col-6"></div>
                </div>
            </div>
        </div>
    </div>
    `;

    menuContent.append(elem);

    $(`#cart-remove-${idx}`).on('click', () => {
        jsonBodyDelete("/api/users/cart/remove", { product_id: product.id })
            .then(response => {
                if (response.status === 200) {
                    handleDelete(response.data);
                }
            });
    });

    const selectorId = `cart-update-${idx}`;

    const selector = createNumberSelector(idx, product.amount, (target, value) => {
        jsonBodyPost("/api/users/cart/update", { product_id: product.id, amount: value})
            .then(response => {
                if (response.status === 200) {
                    target.value = value;
                }
            })
            .catch(error => {
                if(error.response) {
                    if(error.response.data) {
                        let errors = "";
                        for(var key in error.response.data.errors) {
                            errors = errors.concat(error.response.data.errors[key]);
                        }
                        launchErrorAlert("There was an error adding to the cart: " + error.response.data.message + "<br>" + errors);
                    }
                }
            });
    }, 1, product.stock);
    $(`#cart-number-selector-${idx}`).append(selector);
}

function clearElements() {
    menuContent.html("");
    $("#cart-dropdown-menu .dropdown-divider").remove();
    $("#cart-resume").remove();
}

function fillMenu(products, total) {
    if (products.length === 0) {
        noProducts();
        return;
    }

    products.forEach(insertProduct);
    const fixed = document.createElement("div");
    fixed.innerHTML = `
    <li><hr class="dropdown-divider"></li>
    <li id="cart-resume">
        <div class="container" href="#">
            <div class="row align-items-center">
                <div class="col mx-3 my-2">
                    <div class="row align-items-center justify-content-between mb-3">
                        <div id="items-total" class="col">${products.length} item${products.length > 1 ? "s" : ""}</div>
                        <div id="price-total" class="col">${total}€</div>
                    </div>
                    <div class="row mb-2">
                        <a class="btn btn-primary" href="/users/cart">Access your cart</a>
                    </div>
                </div>
            </div>
        </div>
    </li>
    `;

    menu.append(fixed);
}

function renderCart() {
    if (loading || inMenu) return;

    clearElements();

    loading = true;

    const spinner = document.createElement("div");
    spinner.className = "container";
    spinner.innerHTML = `
        <div class="row justify-content-center">
            <i class="spinner-border" role="status"></i>
        </div>`;
    menuContent.html(spinner);

    get("/api/users/cart")
        .then((response) => {
            loading = false;
            menuContent.html("");
            if (response.status === 200) {
                fillMenu(response.data.items, response.data.total);
            }
        });
}

function setupCart() {
    const button = $("#cart-dropdown");

    menu.on("click", (e) => {
        e.stopPropagation();
    });

    button?.on("mouseover", () => {
        renderCart();
        inMenu = true;
        button.dropdown("show");
    });
    // button?.on("mouseleave", () => {
    //     inMenu = false;
        
    //     // Wait for user to enter menu or exits.
    //     setTimeout(() => {
    //         if (!inMenu) {
    //             button.dropdown("hide");
    //         }
    //     }, 50);
    // });

    // menu?.on("mouseover", () => {
    //     inMenu = true;
    // });
    // menu?.on("mouseleave", () => {
    //     inMenu = false;
    //     button.dropdown("hide");
    // });
}

setupCart();
