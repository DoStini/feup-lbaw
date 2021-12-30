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
    element.style.top = "-50px";
    element.style.opacity = "0";

    setTimeout(() => {
        element.style.top = "";
        element.style.opacity = "";
    }, delay);
}

function createProduct(product, delay) {
    const html = `
        <div class="card mb-5 search-products-item">

            <img class="card-img-top"
                src="data:image/jpeg;base64,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">
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
        </div>`;

    const element = document.createElement('div');
    element.className = "col-lg-4 col-md-6 col-xs-12";
    element.style = "visibility: visible";
    element.id = `product-${product.id}`;
    element.innerHTML = html;

    element.addEventListener('click', () => route(`products/${product.id}`, current));

    if (delay !== 0)
        setupAnimation(element.firstElementChild, delay);

    return element;
}

function insertNextPageButton(delay) {
    $("#next-page-btn").remove();

    if (current.currentPage >= current.lastPage) return;
    
    const button = document.createElement('div');
    button.className = "d-flex justify-content-center align-items-center";
    button.id = "next-page-btn";
    
    button.innerHTML = '<i class="bi bi-arrow-down-circle-fill btn next-page-search"></i>';
    button.firstChild.addEventListener('click', () => {
        sendSearchProductsRequest(handleSearchNewPageProducts, current.currentPage + 1);
    });

    $("#search-area").append(button);

    if (delay !== 0)
        setupAnimation(button.firstChild, delay);
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