@extends('layouts.app')

@section('title', 'Users Dashboard')

@section('content')

<div class="container pb-4">
    <div class="row d-flex align-items-center">
        @include('partials.links.dashboardLinks', ['page' => 'userDashboard'])
        <div class="col-md-12 d-flex justify-content-end">
            <a class="btn btn-primary mx-1" href={{route('getNewAdminPage')}}>
                Create New Admin
            </a>
            {{-- <a class="btn btn-primary mx-1" data-bs-toggle="offcanvas" href="#usersOffCanvas" role="button" aria-controls="usersOffCanvas">
                Filters
            </a> --}}
        </div>
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
                    <th class="text-center">Subscribed to Newsletter?</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody id="user-area">
            </tbody>
        </table>
    </div>
</div>

<script type="application/javascript" defer>
    $('#user-dashboard-table').DataTable({
        'responsive': true,
        'ajax': {
            'url': '/api/users/',
            'data': function(e) {
                // console.log(e);
            }
        },
        "drawCallback": function (settings) {
            // // FOR DEBUGGING
            // var response = settings.json;
            // console.log("hello");
            // console.log(response);
        },
        serverSide: true,
        'order': [[5, 'asc']],
        'columnDefs':[
            { 'name': 'id', 'targets': 0},
            // { 'name': 'created_at', 'targets': 1},
            { 'name': 'name', 'targets': 2},
            { 'name': 'email', 'targets': 3},
            { 'name': 'phone_number', 'targets': 4, 'orderable': false},
            { 'name': 'nif', 'targets': 5, 'orderable': false},
            {
                'name': 'newsletter_subcribed', 'targets': 6,
                'render': function(data, type, row) {
                    let text = "";
                    if(type === "display") {
                        if(data === false) {
                            text = "NO";
                        } else {
                            text = "YES";
                        }
                        data = text;
                    }

                    return data;
                }
            },
            {
                'targets':7, 'orderable': false, 'searchable': false,
                'render': function(data, type, row) {
                    let text = "";
                    if(type === 'display') {
                        text = `
                        <div class="d-flex justify-content-around" style="font-size: 1.2em;">
                            <a class="bi bi-info-circle-fill icon-click" href="/users/${row[0]}" data-bs-toggle="tooltip" title="Go to User Page"></a>
                            <a class="bi bi-pencil-square icon-click px-1" href="/users/${row[0]}/private/" data-bs-toggle="tooltip" title="Edit User Info"></a>
                        </div>`;
                        data = text;
                    }

                    return data;
                }
            }
        ]
    });
</script>

  @endsection
