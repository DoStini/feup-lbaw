
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
if(addToCartButton) {
    addToCartButton.addEventListener('click', async () =>
        addToCartRequest(productInfo.id, parseInt(document.getElementById(`product-amount-${productInfo.id}`).value))
    );
}

const wishlistButton = document.getElementById('add-to-wishlist-btn');
if(wishlistButton) {
    wishlistButton.addEventListener('click', async () =>
        addToWishlistRequest(productInfo.id)
    );
}
