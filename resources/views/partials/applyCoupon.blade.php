<div class="my-4 w-75">
    <h4>Apply Coupon </h4>
    <input id="coupon-id" name="coupon-id" style="display:none"></input>
    <div class="my-3 container">
        <div class="row justify-content-between align-items-center">
            <div class="col-10">
                <input id="coupon" type="text" class="form-control">
            </div>
            <div class="col-2">
                <i id="clear-coupon" class="bi bi-x-lg clear-coupon" data-bs-toggle="tooltip" data-bs-placement="top" title="Remove coupon"></i>
            </div>
        </div>
        <div class="row mt-4 justify-content-center">
            <a id="coupon-check-btn" class="btn btn-primary col-10">Apply Coupon</a>
        </div>
    </div>
</div>

<script>
    window.addEventListener('load', () => {

        document.getElementById("coupon-check-btn").addEventListener("click", (e) => {
            e.preventDefault();
            jsonBodyPost("/api/coupon/validate", {
                code: document.getElementById("coupon").value,
                total: {{$cartTotal}},
            }).then((data) => {
                const item = data.data;
                document.getElementById("coupon-id").value = item.id;
                document.querySelector("#order-total td").innerText = `${({{$cartTotal}} * (1 - item.percentage)).toFixed(2)} €`;
                document.querySelector("#coupon-used td").innerText = `${(item.percentage * 100).toFixed(2)}%`;
                document.querySelector("#coupon-used").style.display = "";
            }).catch((e) => {
                console.log(e)
                reportData("The given coupon does not exist or cannot be applied to this order");
            });
        })

        document.getElementById("clear-coupon").addEventListener("click", () => {
            document.getElementById("coupon").value = "";
            document.getElementById("coupon-id").value = "";
            document.querySelector("#order-total td").innerText = `{{$cartTotal}} €`;
            document.querySelector("#coupon-used").style.display = "none";
        });
    })

</script>