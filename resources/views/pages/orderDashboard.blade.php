@extends('layouts.app')

@section('title', 'Orders Dashboard')

@section('content')
<script src={{asset('js/adminOrders.js')}} defer></script>
@include('partials.alert')


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
        {{-- <tr class="datatable-searchboxes">
            <th class="filter-datatable">Order ID</th>
            <th class="filter-datatable">Shopper Name (ID)</th>
            <th class="filter-datatable">Created At</th>
            <th class="filter-datatable" >Last Update</th>
            <th class="filter-datatable" >Total</th>
            <th class="filter-datatable">Status</th>
            <th ></th>
        </tr> --}}
    </thead>
    <tbody>

    </tbody>
</table>

</div>

<script type="application/javascript" defer>
    // $('#order-dashboard-table thead tr.datatable-searchboxes th.filter-datatable').each( function () {
    //     var title = $(this).text();
    //     $(this).html( '<input class="w-100" type="text" placeholder="Search '+title+'" />' );
    // } );

    let table = $('#order-dashboard-table').DataTable({
        'responsive': true,
        'drawCallback': function() {
            /** @type {Array<Element>} dropdownElements */
            dropdownElements = [].slice.call(document.querySelectorAll(".dropdown-toggle"));
            /** @type {Array<bootstrap.Dropdown>} dropdowns */
            dropdowns = [];

            dropdownElements.forEach(function (element) {
                dropdowns[element.id] = new bootstrap.Dropdown(element);
            });
        },
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
                        <div class="d-flex justify-content-around" id="dropdown-menu-order-status-${row[0]}" style="font-size: 1.2em;">
                            <div class="dropdown dropstart">
                                <button class="p-1 dropdown-toggle btn" type="button" id="dropdown-menu-order-status-btn-${row[0]}""
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-pencil-square icon-click" data-bs-toggle="tooltip"
                                    title="Advance Status"></i>
                                </button>

                                <div class="dropdown-menu"  aria-labelledby="dropdown-menu-order-status-btn-${row[0]}">
                                    <form class="px-4 py-3" id="edit-order-status-form-${row[0]}" onsubmit="return sendOrderStatus(event, ${row[0]});">
                                        <div class="mb-3">
                                            <label for="order-status-${row[0]}" class="form-label">Edit Order Status</label>
                                            <select id="order-status-${row[0]}" name="status" class="form-select">
                                                @foreach ($statuses as $status)
                                                <option ${ '{{$status}}' === row[5] ? "selected" : ""}
                                                    value="{{$status}}">{{strtoupper($status)}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Edit Order</button>
                                    </form>
                                </div>
                                <a class="btn p-1 bi bi-info-circle-fill icon-click" href='/orders/${row[0]}'
                                    data-bs-toggle="tooltip" title="Go to Order Page"></a>
                            </div>
                        </div>`;
                        // <div class="d-flex justify-content-around" style="font-size: 1.2em;">
                        //     {{--<a class="bi bi-forward-fill icon-click" href="" data-bs-toggle="tooltip" title="Advance Status"></a>--}}
                        //      <a class="bi bi-info-circle-fill icon-click" href='/orders/${row[0]}' data-bs-toggle="tooltip" title="Go to Order Page"></a>
                        // </div>`;
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
