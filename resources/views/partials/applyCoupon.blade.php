<div class="my-4 w-75">
    <h4>Apply Coupon </h4>
    <input id="coupon-id" name="coupon-id" style="display:none"></input>
    <div class="my-3 container px-0">
        <div class="row justify-content-between align-items-center">
            <div class="col-10">
                <div id="select-target" ></div>
            </div>
            <div class="col-2">
                <i id="clear-coupon" class="bi bi-x-lg clear-coupon" data-bs-toggle="tooltip" data-bs-placement="top" title="Remove coupon"></i>
            </div>
        </div>
    </div>
</div>

<script>
    window.addEventListener('load', () => {
        const selectTarget = document.getElementById("select-target");
        selectTarget.replaceWith(selectTarget, createSelect({
            id: "coupon",
            name: "coupon",
            label: "Choose your Coupon",
            ajax: true,
            delay: 1000,
            url: '/api/coupon/search',
            data: (value) => {
                const query = {
                    code: value,
                    min: {{$cartTotal}}
                }
                return query;
            },
            processResults: (data) => {
                data.forEach((el) => el.text = `${el.code} - ${el.percentage * 100}% Off`)
                return {
                    results: data
                };
            },
            callback: (item) => {
                document.getElementById("coupon-id").value = item.id;
                document.querySelector("#order-total td").innerText = `${({{$cartTotal}} * (1 - item.percentage)).toFixed(2)} €`;
                document.querySelector("#coupon-used td").innerText = `${(item.percentage * 100).toFixed(2)}%`;
                document.querySelector("#coupon-used").style.display = "";
            }
        }));

        document.getElementById("clear-coupon").addEventListener("click", () => {
            document.getElementById("coupon").dispatchEvent(new Event("reset"));
            document.getElementById("coupon-id").value = "";
            document.querySelector("#order-total td").innerText = `{{$cartTotal}} €`;
            document.querySelector("#coupon-used").style.display = "none";
        });
    })

</script>