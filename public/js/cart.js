let loading = false;

function addToCartRequest(id, amount) {
    jsonBodyPost("/api/users/cart/add", {
        "product_id": id ,
        "amount": amount,
    })
    .then((response) => {
        launchSuccessAlert("Added sucessfully to cart");
    })
    .catch((error) => {
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
}

function noProducts() {
    const elem = document.createElement("div");
    elem.className = "container";

    elem.innerHTML = `
        <div class="row justify-content-center">
            No products in the cart
        </div>
    `;

    menuContent.appendChild(elem);
}

function handleDelete(data) {
    clearElements();
    fillMenu(data.items, data.total);
}

function handleUpdate(product, idx, amount, total) {
    product.amount = amount;

    document.getElementById(`item-amount-${idx}`).innerText = formatAmount(product);
    document.getElementById(`item-subtotal-${idx}`).innerText = formatProductSubtotal(product);
    document.getElementById("price-total").innerText = `${total}€`;
}

function formatAmount(product) {
    return `${product.amount}x${product.price}€`;
}

function formatProductSubtotal(product) {
    return `${(product.amount * product.price).toFixed(2)}€`;
}

function insertProduct(product, idx) {
    const productImg = product.photos[0];
    const fallBack = "/img/default.jpg";
    const elem = document.createElement("div");
    elem.id = `cart-product-${idx}`;
    elem.innerHTML =
        `<div class="container" href="#">
            <div class="row align-items-center mb-3">
                <img class="col-3" src="${productImg}" onerror="this.src='${fallBack}'">
                <div class="col-9">
                    <div class="row align-items-center justify-content-between">
                        <div class="col-10 dropdown-cart-name">${product.name}</div>
                        <i id="cart-remove-${idx}" class="cart-remove col-2 bi bi-trash-fill"></i>
                    </div>
                    <div class="row dropdown-cart-amount justify-content-between">
                        <div id="item-amount-${idx}" class="col px-0">
                            ${formatAmount(product)}
                        </div>
                        <div id="item-subtotal-${idx}" class="col item-subtotal">
                            ${formatProductSubtotal(product)}
                        </div>
                    </div>
                    <div class="row dropdown-cart-amount justify-content-center">
                        <div id="cart-number-selector-${idx}" class="col-6"></div>
                    </div>
                </div>
            </div>
        </div>`;

    elem.querySelector('img').addEventListener('click', () => route(`products/${product.id}`));

    menuContent.appendChild(elem);

    elem.querySelector(".cart-remove").addEventListener('click', () => {
        deleteRequest(`/api/users/cart/${product.id}/remove`)
            .then(response => {
                if (response.status === 200) {
                    handleDelete(response.data);
                }
            });
    });

    const selector = createNumberSelector({
        id: `number-selector-${idx}`,
        min: 1,
        value: product.amount,
        max: product.stock,
        onBlur: (target, value, prevValue) => {
            if (value === prevValue) {
                target.value = value;
                return;
            }
            jsonBodyPost("/api/users/cart/update", { product_id: product.id, amount: value})
                .then(response => {
                    if (response.status === 200) {
                        target.value = value;
                        handleUpdate(product, idx, value, response.data.total);
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
        }
    });
    document.getElementById(`cart-number-selector-${idx}`).appendChild(selector);
}

function clearElements() {
    menuContent.innerHTML = "";
    let elem = document.querySelector("#cart-dropdown-menu .dropdown-divider");
    elem?.remove();

    elem = document.getElementById("cart-resume");
    elem?.remove();
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

    menu.appendChild(fixed);
}

function renderCart() {
    if (loading) return;

    clearElements();

    loading = true;

    const spinner = document.createElement("div");
    spinner.className = "container";
    spinner.innerHTML = `
        <div class="row justify-content-center">
            <i class="spinner-border" role="status"></i>
        </div>`;

    menuContent.appendChild(spinner);

    get("/api/users/cart")
        .then((response) => {
            loading = false;
            menuContent.innerHTML = "";
            if (response.status === 200) {
                fillMenu(response.data.items, response.data.total);
            }
        });
}

function setupCart() {
    menu.addEventListener("click", (e) => {
        e.stopPropagation();
    });

    buttonElem?.addEventListener("click", (e) => {
        if (menu.classList.contains("show"))
            renderCart();
    });
}


const menu = document.getElementById("cart-dropdown-menu");
const menuContent = document.getElementById("cart-dropdown-menu-content");
const buttonElem = document.getElementById("cart-dropdown");
let button;

if (menu) {
    setupCart();
    button = new bootstrap.Dropdown(buttonElem);
}
