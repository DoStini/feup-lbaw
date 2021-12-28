let inMenu = false;
let loading = false;

const menu = $("#cart-dropdown-menu");
const menuContent = $("#cart-dropdown-menu-content");
console.log("menus", menu, menuContent)

function insertProduct(product) {
    // const productImg = product.photos[0];
    const productImg = "https://scontent.flis3-1.fna.fbcdn.net/v/t1.6435-9/52574079_2443701065700247_3215647864260657152_n.jpg?_nc_cat=106&ccb=1-5&_nc_sid=09cbfe&_nc_ohc=BwmsmuJUYooAX8XqmBP&tn=Elj0521SoF3jNzT5&_nc_ht=scontent.flis3-1.fna&oh=00_AT_hPNZz3BgE0lwpVKLVUGJTfJKbX1f6awnIIfzIDO13lA&oe=61F1E5B5";
    const elem = document.createElement("div");
    elem.innerHTML = `
    <div class="container" href="#">
        <div class="row align-items-center mb-3">
            <img class="col-3" src="${productImg}">
            <div class="col-9">
                <div class="row align-items-center justify-content-between">
                    <div class="col-10 dropdown-cart-name">${product.name}</div>
                    <i class="cart-remove col-2 bi bi-x-lg"></i>
                </div>
                <div class="row dropdown-cart-amount justify-content-between">
                    <div class="col px-0">
                        ${product.amount}x${product.price}€
                    </div>
                    <div class="col item-subtotal">
                        ${(product.amount * product.price).toFixed(2)}€
                    </div>
                </div>
            </div>
        </div>
    </div>
    `;

    menuContent.append(elem);
}

function updateCart() {
    if (loading || inMenu) return;

    loading = true;

    const spinner = document.createElement("div");
    spinner.innerHTML = `<div class="spinner-border" role="status"></div>`;
    menuContent.html(spinner);
    console.log("spinner", spinner, menu)

    get("/api/users/cart")
        .then((response) => {
            console.log(response.data);
            loading = false;
            menuContent.html("");
            if (response.status === 200) {
                response.data.items.forEach((product) => insertProduct(product));
                const fixed = document.createElement("div");
                fixed.innerHTML = `
                <li><hr class="dropdown-divider"></li>
                <li>
                    <div class="container" href="#">
                        <div class="row align-items-center">
                            <div class="col mx-3 my-2">
                                <div class="row align-items-center justify-content-between mb-3">
                                    <div id="items-total" class="col">${response.data.items.length} item${response.data.items.length > 1 ? "s" : "item"}</div>
                                    <div id="price-total" class="col">${response.data.total}€</div>
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
        });
}

function setupCart() {
    const button = $("#cart-dropdown");

    menu.on("click", (e) => {
        e.stopPropagation();
    });

    button?.on("mouseover", () => {
        updateCart();
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
