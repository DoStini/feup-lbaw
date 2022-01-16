const currentPriceContainer = document.getElementById('current-price');

let quantityContainer = document.getElementById(`quantity-container`)
let selector;
if(quantityContainer!=null) {
    selector = createNumberSelector({
        id: `product-amount-${productInfo.id}`,
        value: 1,
        min: 1,
        onChange: (target, value) => {
            target.value = value;
            if(value > productInfo.stock) {
                selector.invalidInput(`Product's stock is ${productInfo.stock}`);
            } else {
                selector.validInput();
            }
            currentPriceContainer.innerText = `Subtotal: ${(parseFloat(productInfo.price) * value).toFixed(2)} €`;
        }
    });

    quantityContainer.append(selector);
}

const addToCartButton = document.getElementById('add-to-cart-btn');
if(addToCartButton) {
    addToCartButton.addEventListener('click', async () =>
        addToCartRequest(productInfo.id, parseInt(document.getElementById(`product-amount-${productInfo.id}`).value))
    );
}

const addToWishlist = document.getElementById("add-wishlist");
const removeFromWishlist = document.getElementById("remove-wishlist");

if(addToWishlist && removeFromWishlist) {
    addToWishlist.addEventListener("click", (e) => {
        addToWishlistRequest(productInfo.id, () => {
            removeFromWishlist.style.display = "";
            addToWishlist.style.display = "none";
        });
        addToWishlist.dispatchEvent(new Event("blur"));
    });
    removeFromWishlist.addEventListener("click", (e) => {
        removeFromWishlistRequest(productInfo.id, () => {
            removeFromWishlist.style.display = "none";
            addToWishlist.style.display = "";
        });
        removeFromWishlist.dispatchEvent(new Event("blur"));
    });
}

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

const teaserTextContainer = document.getElementById('description-text-teaser');

let isOverflowing = teaserTextContainer.clientHeight < teaserTextContainer.scrollHeight;
if(!isOverflowing) {
    showMoreButton.style.display = "none";
}
