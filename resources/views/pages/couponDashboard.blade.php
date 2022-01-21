@extends('layouts.app')

@section('title', 'Coupons Dashboard')

@section('content')

<div class="container pb-4">
    <div class="row d-flex align-items-center">
        @include('partials.links.dashboardLinks', ['page' => 'couponDashboard'])
    </div>
    <div class="row">
        <table class="table w-100 table-responsive my-4" style="font-size: 0.9em;" id="coupon-dashboard-table">
            <thead class="table-dark">
                <tr>
                    <th class="text-center">Coupon ID</th>
                    <th class="text-center">Code</th>
                    <th class="text-center">Percentage</th>
                    <th class="text-center">Minimum Value</th>
                    <th class="text-center">Active</th>
                    <th class="text-center">Enable/Disable</th>
                </tr>
            </thead>
            <tbody id="coupon-area">
            </tbody>
        </table>
    </div>
</div>

<script type="application/javascript" defer>
    const table = $('#coupon-dashboard-table').DataTable({
        'responsive': true,
        'ajax': {
            'url': '/api/coupon/',
        },
        serverSide: true,
        'order': [[1, 'desc']],
        'columnDefs':[
            { 'name': 'id', 'targets': 0, 'className': 'text-center', 'width':'6em'},
            { 'name': 'code', 'targets': 1, 'className': 'text-center'},
            { 'name': 'percentage', 'targets': 2, 'className': 'percentage', 'width':'6em'},
            {
                'name': 'minimum_cart_value', 
                'targets': 3, 
                'width':'8em',
                'render': function(data, type, row) {
                    if(type === "display") {
                        data = parseFloat(data).toFixed(2) + " €";
                    }

                    return data;
                }
            },
            { 'name': 'is_active', 'targets': 4, 'className': 'text-center', 'width':'6em'},
            {
                'targets': 5, 'orderable': false, 'searchable': false,
                'render': function(data, type, row) {
                    let text = "";
                    if(type === 'display') {
                        text = `
                        <div class="d-flex justify-content-around" style="font-size: 1.2em;">
                            ${
                                row[4] === "YES" 
                                    ? `<a class="bi bi-slash-circle-fill icon-click" onclick="disableCoupon(${row[0]})" data-bs-toggle="tooltip" title="Disable Coupon"></a>`
                                    : `<a class="bi bi-check-circle icon-click" onclick="enableCoupon(${row[0]})" data-bs-toggle="tooltip" title="Enable Coupon"></a>`
                            }
                            
                            
                        </div>`;
                        data = text;
                    }

                    return data;
                }, 'className': 'text-center'
            }
        ]
    });


    function enableCoupon(id) {
        jsonBodyPost(`/api/coupon/${id}/enable`)
            .then(() => {
                table.ajax.reload();
            })
            .catch();
    }

    function disableCoupon(id) {
        jsonBodyPost(`/api/coupon/${id}/disable`)
            .then(() => {
                table.ajax.reload();
            })
            .catch();
    }

</script>

  @endsection
