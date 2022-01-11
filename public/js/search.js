const baseDelay = 100;
const searchInterval = 500;

let current = {};

function ensureBounds(target) {
    if (target.getAttribute("type") !== "number") return;
    let min = parseInt(target.getAttribute("min"));
    if (min === NaN) min = -Infinity;
    const max = parseInt(target.getAttribute("max"));
    if (max === NaN) max = +Infinity;

    const val = parseInt(target.value);

    if (val !== NaN) {
        if (val < min)
            target.value = min;
        if (val > max)
            target.value = max;
    }
}

function inputModified(target) {
    if (target.getAttribute("old-value") !== target.value) {
        target.setAttribute("old-value", target.value);
        return true;
    }

    return false;
}

function setupSearchListeners() {
    const searchForm = document.getElementById("search-form");

    const formTargets = [...document.querySelectorAll("#search-form input:not([type=checkbox])")];
    formTargets.push(document.getElementById("search-products-input"));

    formTargets.forEach((target) => {
        let timeout;
        let isTyping;

        target.setAttribute("old-value", target.value)

        target.addEventListener('keydown', (e) => {
            if (e.key === "Enter") {
                target.blur();
            }
        });

        target.addEventListener('keydown', (e) => {
            isTyping = true;
        });

        target.addEventListener('focus', () => {
            isTyping = true;
            timeout = setInterval(() => {
                if (!isTyping) {
                    ensureBounds(target);
                    if (inputModified(target)) {
                        sendSearchProductsRequest(handleSearchProducts);
                    }
                }

                isTyping = false;
            }, searchInterval);
        });

        target.addEventListener('blur', () => {
            clearInterval(timeout);

            ensureBounds(target);
            if (inputModified(target))
                sendSearchProductsRequest(handleSearchProducts);
        });
    });

    setupUniqueCheckboxes("search-form", (_e) => {
        sendSearchProductsRequest(handleSearchProducts);
    });

    searchForm.addEventListener("reset", (e) => {
        formTargets.forEach(elem => elem.value = "");

        document.querySelectorAll("#search-form input[type=checkbox]")
            .forEach(elem => elem.checked = false);

        sendSearchProductsRequest(handleSearchProducts);
        e.preventDefault();
    })
}

function capitalize(s){
    return s.toUpperCase();
    //return s && s.charAt(0).toUpperCase() + s.slice(1).toLowerCase();
}

function setupAnimation(element, delay) {
    element.style.top =  "-50px";
    element.style.opacity = "0"

    setTimeout(() => {
        element.style.top =  "";
        element.style.opacity = "";
    }, delay);
}

function createProduct(product, delay) {
    const productImg = product.photos[0];
    const fallBack = "/img/default.jpg";

    const wishlisted = !product.shopper_id; // If the product is wihslisted, shopper will not be null

    const html = `
        <div id="product-${product.id}" class="card mb-5 search-products-item">
            <img class="card-img-top search-card-top" src="${productImg}" onerror="this.src='${fallBack}'">
            <div class="card-body">
                <h4 class="card-title" style="height: 2.5em; display: -webkit-box;-webkit-line-clamp: 2;-webkit-box-orient: vertical; overflow: hidden;">${capitalize(product.name)}</h4>
                <div class="container ps-0 pe-0">
                    <div class="row justify-content-between align-items-center">
                        <h4 class="col mb-0">${product.price} &euro;</h4>
                        ${isShopper ?
                            `<button type="button" class="add-cart col-2 me-2 btn btn-outline-secondary px-0">
                                <i class="bi bi-cart-plus mx-auto"></i>
                            </button>
                            <button type="button" class="remove-wishlist col-2 me-2 btn btn-outline-secondary px-0" 
                                ${wishlisted ? `style="display:none"` : ""}>
                                <i class="bi bi-heart-fill mx-auto"></i>
                            </button>
                            <button type="button" class="add-wishlist col-2 me-2 btn btn-outline-secondary px-0"
                                ${!wishlisted ? `style="display:none"` : ""}>
                                <i class="bi bi-heart mx-auto"></i>
                            </button>`
                            : ""}
                    </div>
                </div>
            </div>
        </div>`;

    console.log(html)


    const element = document.createElement("div");
    element.id = `root-product-${product.id}`;
    element.className = "col-lg-4 col-md-6 col-xs-12";
    element.style = "visibility: visible";
    element.innerHTML = html;

    element.querySelector("img").addEventListener('click', () => route(`products/${product.id}`, current));

    if (isShopper) {
        const cartButton = element.querySelector(".add-cart");
        const addToWishlist = element.querySelector(".add-wishlist");
        const removeFromWishlist = element.querySelector(".remove-wishlist");
        addToWishlist.addEventListener("click", (e) => {
            addToWishlistRequest(product.id);
            addToWishlist.dispatchEvent(new Event("blur"));
            removeFromWishlist.style.display = "";
            addToWishlist.style.display = "none";

        });
        removeFromWishlist.addEventListener("click", (e) => {
            removeFromWishlistRequest(product.id);
            removeFromWishlist.dispatchEvent(new Event("blur"));
            removeFromWishlist.style.display = "none";
            addToWishlist.style.display = "";
        });


        cartButton.addEventListener("click", (e) => {
            addToCartRequest(product.id);
            cartButton.dispatchEvent(new Event("blur"));
        });
    }

    if (delay !== 0)
        setupAnimation(element.querySelector(".card"), delay);

    return element;
}

function insertNextPageButton(delay) {
    const nextButton = document.getElementById("next-page-btn");
    if (nextButton)
        nextButton.remove();

    if (current.currentPage >= current.lastPage) return;

    const button = document.createElement("div");
    button.id = "next-page-btn";
    button.className = "d-flex justify-content-center align-items-center";
    button.innerHTML = `<i class="bi bi-arrow-down-circle-fill btn next-page-search"></i>`;

    button.addEventListener('click', () => {
        sendSearchProductsRequest(handleSearchNewPageProducts, current.currentPage + 1);
    });

    document.getElementById("search-area").appendChild(button);

    if (delay !== 0)
        setupAnimation(button.querySelector("i"), delay);
}

function clearProducts() {
    const container = document.getElementById("products-area");

    container.innerHTML = "";
}

function insertProducts(data, shouldAnimate) {
    const factor = shouldAnimate ? 1 : 0;

    const container = document.getElementById("products-area");

    document.getElementById("results-text").innerText = data.docCount ? `${data.docCount} Results` : "No results";

    data.query.forEach((target, idx) =>
        container.appendChild(createProduct(target, factor * (idx + 1) * baseDelay)));

    insertNextPageButton(factor * (data.query.length + 1) * baseDelay);
}

function setNewProducts(data) {
    clearProducts();
    insertProducts(data, true);
}

function handleSearchNewPageProducts(response) {
    if (response.status !== 200) return;

    const lastQuery = current.query;

    current = {...response.data};
    current.query = [
        ...lastQuery,
        ...current.query,
    ];

    insertProducts(response.data, true);
}

function handleSearchProducts(response) {
    if (response.status !== 200) return;

    current = response.data;

    setNewProducts(response.data);
}

function restoreCache() {
    if (history.state) {
        current = history.state;
        insertProducts(history.state, false);
    } else {
        sendSearchProductsRequest(handleSearchProducts);
    }
}

function getInputs() {
    const data = Object.fromEntries((new FormData(document.getElementById("search-form"))).entries());

    const keys = Object.keys(data);

    Object.values(data).forEach((val, idx) => {
        if (val === "on") {
            data.order = keys[idx];
            delete data[keys[idx]];
        }
    });

    return {
        text: document.getElementById("search-products-input").value,
        ...data,
    };
}

function removeUriParams() {
    const url = document.location.href;
    window.history.pushState(undefined, "", url.split("?")[0]);
}

function setupInputForm() {
    if (window.location.pathname === "/products") {
        const params = (new URL(document.location)).searchParams;
        const text = params.get("text") ?? "";
        document.getElementById("search-products-input").value = text;

        removeUriParams();

        document.getElementById("search-products-form").onsubmit = (e) => {
            e.preventDefault();
            sendSearchProductsRequest(handleSearchProducts);
        };
    } else {
        const elem = document.getElementById("search-products-form");
        if (!elem) return;
        elem.onsubmit = (e) => {
            e.preventDefault();
            const text = document.getElementById("search-products-input").value;
            window.location.assign(`/products${text ? `?text=${encodeURIComponent(text)}` : ""}`);
        };
    }
}

function sendSearchProductsRequest(callback, page) {
    let query = {
        "page": page || 0,
        ...getInputs(),
    }

    getQuery(`/api/products`, query).then(callback);
}


setupInputForm();

if (window.location.pathname === "/products") {
    setupSearchListeners();
    restoreCache();
}
