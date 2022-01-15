function setupListenersProduct(selectTarget) {
    selectTarget.replaceWith(selectTarget, createSelect({
        id: "variantColor",
        name: "variantColor",
        class: "form-control",
        label: "Choose your Variant Color",
        ajax: true,
        delay: 1000,
        url: '/api/products/variants',
        data: (value) => {
            const query = {
                code: value,
            }
            return query;
        },
        processResults: (data) => {
            console.log(data);
            return {
                results: data
            };
        },
        callback: (item) => {
            document.getElementById('variant-img').src = `https://cdn.shopify.com/s/files/1/0014/1865/7881/files/${item.colorCode}_50x50_crop_center.png`;
        }
    }));

    prodForm.addEventListener("reset", () => {
        document.getElementById("variantColor").dispatchEvent(new Event("reset"));
    });


}
const variantCheck = document.getElementById("variantCheck");
const selectTarget = document.getElementById("select-target-variant");
const prodForm = document.getElementById("add-product-form");

if(selectTarget){ 
    setupListenersProduct(selectTarget);


    variantCheck.addEventListener('change', function() {
        const color = document.getElementById("variantColor");
        color.required = this.checked;

    });

}