let inMenu = false;
const menu = $("#cart-dropdown-menu");

function buildProduct(product) {
    const elem = document.createElement("div");
    elem.innerHTML = `

    `;
}

function updateCart() {
    const spinner = document.createElement("li");
    spinner.innerHTML = `<div class="spinner-border" role="status"></div>`;
    // menu.html(spinner);

    console.log("spinner", spinner, menu)

    get("/api/users/cart")
        .then((response) => console.log(response.data));
}

function setupCart() {
    const button = $("#cart-dropdown");
    button?.on("mouseover", () => {
        inMenu = true;
        button.dropdown("show");
        updateCart();
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
