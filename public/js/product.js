
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
