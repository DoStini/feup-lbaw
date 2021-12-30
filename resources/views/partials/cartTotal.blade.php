<div class="my-4 container">
    <div class="row mx-3">
        <h4>Order Summary</h4>
    </div>
    <br>
    <div class="row">
        <table id="vertical-1" style="border-spacing: 2em .5em; border-collapse: inherit;">
            <tr>
                <th>Subtotal (Tax Included):</th>
                <td>{{$cartTotal}} €</td>
            </tr>
            <tr>
                <th>Total Tax (IVA):</th>
                <td>{{round($cartTotal * 0.23, 2)}} €</td>
            </tr>
            <tr style="font-size: 1.2em;">
                <th>Total:</th>
                <td>{{$cartTotal}} €</td>
            </tr>
        </table>
    </div>
</div>
