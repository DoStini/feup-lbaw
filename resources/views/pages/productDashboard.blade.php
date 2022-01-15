@extends('layouts.app')

@section('title', 'Product Dashboard')

@section('content')

<div class="container pb-4">

@include('partials.links.dashboardLinks', ['page' => 'productDashboard'])
<table class="table my-4 w-100" style="font-size: 0.9em;" id="product-dashboard-table">
    <thead class="table-dark">
        <tr>
            <th class="text-center">Product ID</th>
            <th class="text-center">Title</th>
            <th class="text-center">Stock</th>
            <th class="text-center">Price</th>
            <th class="text-center">Avg. Stars</th>
            <th class="text-center">Actions</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>

</div>

<script type="application/javascript" defer>
    let table = $('#product-dashboard-table').DataTable({
        'responsive': true,
        'ajax': {
            'url': '/api/products/list',
        },
        serverSide: true,
        'order': [[0, 'desc']],
        "drawCallback": function (settings) {
            // // FOR DEBUGGING
            // var response = settings.json;
            // console.log("hello");
            // console.log(response);
        },
        'columnDefs':[
            { 'name': 'id', 'targets': 0, 'className': 'text-center', 'width':'6em'},
            { 'name': 'name', 'targets': 1, 'className': 'text-center'},
            { 'name': 'stock', 'targets': 2, 'className': 'text-center', 'width':'6em'},
            {
                'name': 'price', 'targets': 3, 'searchable':false, 'width':'6em',
                'render': function(data, type, row) {
                    if(type === "display") {
                        data = parseFloat(data).toFixed(2) + " €";
                    }

                    return data;
                }
            },
            {
                'name': 'avg_stars', 'targets': 4, 'searchable':false, 'width': '6em',
                'render': function(data, type, row) {
                    if(type === "display") {
                        data = parseFloat(data).toFixed(2);
                    }

                    return data;
                }
            },
            {
                'targets':5, 'orderable': false, 'searchable': false, 'width':'7em',
                'render': function(data, type, row) {
                    let text = "";
                    if(type === 'display') {
                        text = `
                        <div class="d-flex justify-content-evenly" style="font-size: 1.2em;">
                            <a class="bi bi-pencil-square icon-click px-1" href="/admin/products/${row[0]}/edit/" data-bs-toggle="tooltip" title="Edit Product"></a>
                            <a class="bi bi-trash icon-click px-1" href="/admin/products/${row[0]}/remove/" data-bs-toggle="tooltip" title="Remove Product"></a>
                            <a class="bi bi-info-circle-fill icon-click" href='/products/${row[0]}' data-bs-toggle="tooltip" title="Go to Product Page"></a>
                        </div>`;
                        data = text;
                    }

                    return data;
                }, 'className': 'text-center'
            },
        ]
    });
</script>

  @endsection
