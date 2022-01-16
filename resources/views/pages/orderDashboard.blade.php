@extends('layouts.app')

@section('title', 'Orders Dashboard')

@section('content')

<div class="container pb-4">

@include('partials.links.dashboardLinks', ['page' => 'orderDashboard'])
<table class="table my-4 w-100" style="font-size: 0.9em;" id="order-dashboard-table">
    <thead class="table-dark">
        <tr>
            <th class="text-center">Order ID</th>
            <th class="text-center">Shopper Name (ID)</th>
            <th class="text-center">Created At</th>
            <th class="text-center">Last Update</th>
            <th class="text-center">Total</th>
            <th class="text-center">Status</th>
            <th class="text-center">Actions</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>

</div>

<script type="application/javascript" defer>
    $('#order-dashboard-table').DataTable({
        'responsive': true,
        'ajax': {
            'url': '/api/orders/',
        },
        serverSide: true,
        'order': [[5, 'asc'],[3, 'desc']],
        'columnDefs':[
            { 'name': 'id', 'targets': 0, 'className': 'text-center'},
            {
                'name': 'name', 'targets': 1, 'className': 'text-center',
                'render': function(data, type, row) {
                    if(type === "display") {
                        data = data + ` (${row[7]})`;
                    }

                    return data;
                }
            },
            { 'name': 'timestamp', 'targets': 2, 'className': 'text-center'},
            { 'name': 'last_update', 'targets': 3, 'className': 'text-center'},
            {
                'name': 'total', 'targets': 4,
                'render': function(data, type, row) {
                    if(type === "display") {
                        data = parseFloat(data).toFixed(2) + " €";
                    }

                    return data;
                }
            },
            {
                'name': 'status', 'targets': 5,
                'render': function(data, type, row) {
                    let text = "";
                    if(type === "display") {
                        text = `<a class="badge rounded-pill badge-decoration-none badge-${data} ">${data.toUpperCase()}</a>`
                        data = text;
                    }
                    return data;
                }, 'className': 'text-center'
            },
            {
                'targets':6, 'orderable': false, 'searchable': false,
                'render': function(data, type, row) {
                    let text = "";
                    if(type === 'display') {
                        text = `
                        <div class="d-flex justify-content-around" style="font-size: 1.2em;">
                            {{--<a class="bi bi-forward-fill icon-click" href="" data-bs-toggle="tooltip" title="Advance Status"></a>--}}
                             <a class="bi bi-info-circle-fill icon-click" href='/orders/${row[0]}' data-bs-toggle="tooltip" title="Go to Order Page"></a>
                        </div>`;
                        data = text;
                    }

                    return data;
                }, 'className': 'text-center'
            },
            {
                'targets':7, 'orderable': false, 'visible':false, 'name':'shopper_id'
            }
        ]
    });
</script>

  @endsection
