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

function serializeJQueryForm(query) {
    return query.reduce((obj, curr) => {
        if (curr.value) {
            obj[curr.name] = curr.value;
        }

        return obj;
    }, {})
}

function setupSearchListeners() {
    const formTargets = $("#search-form input[type!='checkbox']").toArray();
    if (window.location.pathname === "/products") {
        formTargets.push(document.getElementById("search-products-input"));
    }

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
}

function capitalize(s){
    return s && s.charAt(0).toUpperCase() + s.slice(1).toLowerCase();
}

function setupAnimation(element, delay) {
    element.css("top", "-50px");
    element.css("opacity", "0");

    setTimeout(() => {
        element.css("top", "");
        element.css("opacity", "");
    }, delay);
}

function createProduct(product, delay) {
    const productImg = undefined// product.photos?[0];
    const fallBack = "/img/default.jpg";

    const html = `
        <div class="col-lg-4 col-md-6 col-xs-12" style="visibility: visible">
            <div id="product-${product.id}" class="card mb-5 search-products-item">
                <img class="card-img-top" src="${productImg}" onerror="this.src='${fallBack}'">
                <div class="card-body">
                    <h4 class="card-title">${capitalize(product.name)}</h4>
                    <div class="container ps-0 pe-0">
                        <div class="row justify-content-between align-items-center">
                            <h4 class="col mb-0">${product.price} &euro;</h4>
                            <button type="button" class="col-2 me-2 btn btn-outline-secondary px-0">
                                <i class="bi bi-cart-plus mx-auto"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>`;

    const element = $(html);

    element.on('click', () => route(`products/${product.id}`, current));
    
    const cartButton = element.find("button");
    cartButton.on("click", (e) => {
        e.stopPropagation();
        addToCartRequest(product.id);
        cartButton.trigger("blur");
    })
    
    if (delay !== 0)
        setupAnimation(element.find(".card"), delay);

    return element;
}

function insertNextPageButton(delay) {
    $("#next-page-btn").remove();

    if (current.currentPage >= current.lastPage) return;
    
    const button = $(`
        <div id="next-page-btn" class="d-flex justify-content-center align-items-center">
            <i class="bi bi-arrow-down-circle-fill btn next-page-search"></i>
        </div>
    `);

    button.on('click', () => {
        sendSearchProductsRequest(handleSearchNewPageProducts, current.currentPage + 1);
    });

    $("#search-area").append(button);

    if (delay !== 0)
        setupAnimation(button.find("i"), delay);
}

function clearProducts() {
    const container = $("#products-area");

    container.empty();
}

function insertProducts(data, shouldAnimate) {
    const factor = shouldAnimate ? 1 : 0;

    const container = $("#products-area");

    $("#results-text").text(data.docCount ? `${data.docCount} Results` : "No results");

    data.query.forEach((target, idx) => 
        container.append(createProduct(target, factor * (idx + 1) * baseDelay)));

    insertNextPageButton(factor * (data.query.length + 1) * baseDelay);
}

function setNewProducts(data) {
    clearProducts();
    insertProducts(data, true);
}

function handleSearchNewPageProducts() {
    const response = JSON.parse(this.response);

    if (this.status !== 200) return;

    const lastQuery = current.query;

    current = {...response};
    current.query = [
        ...lastQuery,
        ...current.query,
    ];

    insertProducts(response, true);
}

function handleSearchProducts() {
    const response = JSON.parse(this.response);

    if (this.status !== 200) return;

    current = response;

    setNewProducts(response);
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
    return {
        ...serializeJQueryForm($("#search-form input[type!='checkbox']").serializeArray()),
        text: $("#search-products-input").val(),
    }
}

function removeUriParams() {
    const url = document.location.href;
    window.history.pushState(undefined, "", url.split("?")[0]);
}

function setupInputForm() {
    if (window.location.pathname === "/products") {
        const params = (new URL(document.location)).searchParams;
        const text = params.get("text") ?? "";
        $("#search-products-input").val(text);

        removeUriParams();

        document.getElementById("search-products-form").onsubmit = (e) => {
            e.preventDefault();
            sendSearchProductsRequest(handleSearchProducts);
        };
    } else {    
        document.getElementById("search-products-form").onsubmit = (e) => {
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

    const checkbox = Object.keys(serializeJQueryForm($("#search-form input[type='checkbox'][group='sort-input']").serializeArray()));

    if (checkbox.length) {
        query = {
            ...query, 
            "order": checkbox.at(0),
        }
    }

    sendAjaxQueryRequest('get', `/api/products`, query, callback);
}

setupInputForm();
setupSearchListeners();
restoreCache();