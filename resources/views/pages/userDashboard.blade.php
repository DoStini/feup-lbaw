@extends('layouts.app')

@section('title', 'Users Dashboard')

@section('content')

<div class="container pb-4">
    <div class="row d-flex align-items-center">
        @include('partials.links.dashboardLinks', ['page' => 'userDashboard'])
    </div>
    <div class="row">
        <table class="table w-100 table-responsive my-4" style="font-size: 0.9em;" id="user-dashboard-table">
            <thead class="table-dark">
                <tr>
                    <th class="text-center">User ID</th>
                    <th class="text-center">Account Created At</th>
                    <th class="text-center">Name</th>
                    <th class="text-center">Email</th>
                    <th class="text-center">Phone Number</th>
                    <th class="text-center">NIF</th>
                    <th class="text-center">Blocked?</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody id="user-area">
            </tbody>
        </table>
    </div>
</div>

<script type="application/javascript" defer>
    const table = $('#user-dashboard-table').DataTable({
        'responsive': true,
        'ajax': {
            'url': '/api/users/',
        },
        serverSide: true,
        'order': [[1, 'desc']],
        'columnDefs':[
            { 'name': 'id', 'targets': 0, 'className': 'text-center'},
            { 'name': 'timestamp', 'targets': 1, 'className': 'text-center'},
            { 'name': 'name', 'targets': 2, 'className': 'text-center'},
            { 'name': 'email', 'targets': 3, 'className': 'text-center'},
            { 'name': 'phone_number', 'targets': 4, 'orderable': false, 'className': 'text-center'},
            { 'name': 'nif', 'targets': 5, 'orderable': false, 'className': 'text-center'},
            {
                'name': 'is_blocked', 'targets': 6, 'className': 'text-center'
            },
            {
                'targets':7, 'orderable': false, 'searchable': false,
                'render': function(data, type, row) {
                    let text = "";
                    if(type === 'display') {
                        text = `
                        <div class="d-flex justify-content-around" style="font-size: 1.2em;">
                            <a class="bi bi-pencil-square icon-click px-1" href="/users/${row[0]}/private/" data-bs-toggle="tooltip" title="Edit User Info"></a>
                            ${
                                row[6] == 'YES'
                                    ? `<a class="bi bi-check-circle icon-click" onclick="unblock(${row[0]})" data-bs-toggle="tooltip" title="Unblock User"></a>`
                                    : `<a class="bi bi-slash-circle-fill icon-click" onclick="block(${row[0]})" data-bs-toggle="tooltip" title="Block User"></a>`
                            }
                            <a class="bi bi-info-circle-fill icon-click" href="/users/${row[0]}" data-bs-toggle="tooltip" title="Go to User Page"></a>
                        </div>`;
                        data = text;
                    }

                    return data;
                }, 'className': 'text-center'
            }
        ]
    });


    function block(id) {
        jsonBodyPost(`/api/users/${id}/block`)
            .then((message) => {
                console.log(message);
                table.ajax.reload();
            })
            .catch();
    }

    function unblock(id) {
        jsonBodyPost(`/api/users/${id}/unblock`)
            .then((message) => {
                console.log(message);
                table.ajax.reload();
            })
            .catch();
    }
</script>

  @endsection
