
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

function setupListenersProduct(selectTarget) {
    selectTarget.replaceWith(selectTarget, createSelect({
        id: "zip",
        name: "zip",
        label: "Choose your Zip Code",
        ajax: true,
        delay: 1000,
        url: '/api/address/zipcode',
        data: (value) => {
            const query = {
                code: value,
            }
            return query;
        },
        processResults: (data) => {
            data.forEach((el) => el.text = el.zip_code)
            return {
                results: data
            };
        },
        callback: (item) => {
            document.getElementById('county').value = item.county;
            document.getElementById('district').value = item.district;
            document.getElementById('zip_code_id').value = item.id || item.zip_code_id;
        }
    }));

    document.querySelectorAll(".edit-address-btn")
        .forEach((el) => el.addEventListener("click", handleEditClick));

    document.querySelectorAll(".remove-address-btn")
        .forEach((el) => el.addEventListener("click", handleRemoveClick));

    form.addEventListener("submit", (e) => {
        e.preventDefault();
        const data = Object.fromEntries((new FormData(e.target)).entries());

        if (!action) {
            return;
        }

        action.action(data);
    });

    form.addEventListener("reset", () => {
        document.getElementById("zip").dispatchEvent(new Event("reset"));
    });

    document.getElementById("close-window").addEventListener("click", () => {
        closeCollapse();
        resetAction();
    });

    document.getElementById("new-address").addEventListener("click", (e) => {
        e.preventDefault();

        if (collapseOpen()) {
            return;
        }

        form.querySelector("h4").innerText = "New address";
        form.dispatchEvent(new Event("reset"));

        openCollapse();
        newAction();
    });
}


const selectTarget = document.getElementById("select-target-product");
if(selectTarget) setupListenersProduct(selectTarget);