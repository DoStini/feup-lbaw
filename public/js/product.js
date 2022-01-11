
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

const addToWishlist = document.getElementById("add-wishlist");
const removeFromWishlist = document.getElementById("remove-wishlist");
addToWishlist.addEventListener("click", (e) => {
    addToWishlistRequest(productInfo.id);
    addToWishlist.dispatchEvent(new Event("blur"));
    removeFromWishlist.style.display = "";
    addToWishlist.style.display = "none";
});
removeFromWishlist.addEventListener("click", (e) => {
    removeFromWishlistRequest(productInfo.id);
    removeFromWishlist.dispatchEvent(new Event("blur"));
    removeFromWishlist.style.display = "none";
    addToWishlist.style.display = "";
});
const showMoreButton = document.getElementById('show-more-button');
const teaserDescContainer = document.getElementById('description-box-teaser');
const showLessButton = document.getElementById('show-less-button');
const fullDescContainer = document.getElementById('description-box-full');

showMoreButton.addEventListener('click', () => {
    teaserDescContainer.style.display = "none";
    fullDescContainer.style.display = "block";
})

showLessButton.addEventListener('click', () => {
    teaserDescContainer.style.display = "block";
    fullDescContainer.style.display = "none";
})
