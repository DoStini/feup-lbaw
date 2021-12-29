

let quantityInputBox = document.getElementById('quantity-container');

$(`#quantity-container`).append(createNumberSelector({
    id: `p-${productInfo.id}`,
    value: 1,
    min: 1,
    max: productInfo.stock
}));

let currentPriceContainer = document.getElementById('current-price');
let inputPriceBox = document.getElementById('p-' + productInfo.id);
inputPriceBox.addEventListener('change', function() {
    currentPriceContainer.innerText = `Subtotal: ${(parseFloat(productInfo.price) * inputPriceBox.value).toFixed(2)} €`;
});

const addToCartButton = document.getElementById('add-to-cart-btn');
addToCartButton.addEventListener('click', async () => {
    jsonBodyPost("/api/users/cart/add", {
        "product_id": productInfo.id ,
        "amount": quantityInputBox.value,
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
});