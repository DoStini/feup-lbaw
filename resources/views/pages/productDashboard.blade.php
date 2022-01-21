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

<div class="modal fade" id="confirm" tabindex="-1" aria-labelledby="confirmTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" id="confirmContent">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmTitle">Confirm delete account</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body" id="confirmBody">
                This is an irreversible action, please take caution
                <button class="my-2 btn btn-danger w-100 delete-product-btn">
                    Delete Product
                </button>
            </div>
        </div>
    </div>
</div>

<script type="application/javascript" defer>
    const confirmElem =  document.getElementById("confirm");

    let confirmDelete = new bootstrap.Modal(confirmElem);

    let table = $('#product-dashboard-table').DataTable({
        'responsive': true,
        'ajax': {
            'url': '/api/products/list',
        },
        serverSide: true,
        "initComplete": function(settings, json) {
            document.querySelectorAll("#product-dashboard-table th").forEach((elem) => {
                elem.classList.remove("font-monospace");
            })
        },
        'order': [[0, 'desc']],
        'columnDefs':[
            { 'name': 'id', 'targets': 0, 'width':'6em', 'className': 'font-monospace'},
            { 'name': 'name', 'targets': 1},
            { 'name': 'stock', 'targets': 2, 'className': 'font-monospace', 'width':'6em'},
            {
                'name': 'price', 'targets': 3, 'searchable':false, 'width':'6em',
                'render': function(data, type, row) {
                    if(type === "display") {
                        data = parseFloat(data).toFixed(2) + " €";
                    }

                    return data;
                }, 'className': 'font-monospace'
            },
            {
                'name': 'avg_stars', 'targets': 4, 'searchable':false, 'width': '6em',
                'render': function(data, type, row) {
                    if(type === "display") {
                        data = parseFloat(data).toFixed(2);
                    }

                    return data;
                }, 'className': 'font-monospace'
            },
            {
                'targets':5, 'orderable': false, 'searchable': false, 'width':'7em',
                'render': function(data, type, row) {
                    let text = "";
                    if(type === 'display') {
                        text = `
                        <div class="d-flex justify-content-evenly" style="font-size: 1.2em;">
                            <a class="bi bi-pencil-square icon-click pe-1" href="/admin/products/${row[0]}/edit/" data-bs-toggle="tooltip" title="Edit Product"></a>
                            <a class="bi bi-trash-fill icon-click pe-1" onclick="deleteProduct(${row[0]})" data-bs-toggle="tooltip" title="Delete Product"></a>
                            <a class="bi bi-info-circle-fill icon-click" href='/products/${row[0]}' data-bs-toggle="tooltip" title="Go to Product Page"></a>
                        </div>`;
                        data = text;
                    }

                    return data;
                }, 'className': 'td-text-center'
            },
        ]
    });


    function deleteProduct(prodId) {
        confirmElem.querySelector('.delete-product-btn').addEventListener("click", () => {
            deleteRequest(`/api/products/${prodId}`)
            .then(() => {
                    confirmDelete.hide();
                    table.ajax.reload();
                })
                .catch();
        });
        confirmDelete.show();
    }

</script>

  @endsection
