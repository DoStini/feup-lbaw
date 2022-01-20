<div class="my-4 container">
    <div class="row mx-3">
        <h4>Order Summary</h4>
    </div>
    <br>
    <div class="row">
        <table id="vertical-1" style="border-spacing: 2em .5em; border-collapse: inherit;">
            <tr id="coupon-used" style="display:none">
                <th>Coupon Used</th>
                <td>25%</td>
            </tr>
            <tr>
                <th>Subtotal:</th>
                <td id="order-subtotal">{{$cartTotal}} €</td>
            </tr>
            @if ($showTotal)
            <tr style="font-size: 1.2em;" id="order-total" >
                <th>Total:</th>
                <td>{{$cartTotal}} €</td>
            </tr>

            @endif
        </table>
    </div>
</div>
