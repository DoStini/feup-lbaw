const currentPriceContainer = document.getElementById('current-price');

document.getElementById(`quantity-container`).append(createNumberSelector({
    id: `product-amount-${productInfo.id}`,
    value: 1,
    min: 1,
    max: productInfo.stock,
    onChange: (target, value) => {
        target.value = value;
        currentPriceContainer.innerText = `Subtotal: ${(parseFloat(productInfo.price) * value).toFixed(2)} €`;
    }
}));

const addToCartButton = document.getElementById('add-to-cart-btn');
addToCartButton.addEventListener('click', async () => {
    jsonBodyPost("/api/users/cart/add", {
        "product_id": productInfo.id ,
        "amount": parseInt(document.getElementsByTagName(`product-amount-${productInfo.id}`).value),
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