<div class="container">
    <div class="row d-flex align-items-center">
        <div class="col-md-6">
            <h4 class="my-2">Active Users</h4>
        </div>
        <div class="col-md-6 d-flex justify-content-end">
            <a class="btn btn-primary" data-bs-toggle="offcanvas" href="#usersOffCanvas" role="button" aria-controls="usersOffCanvas">
                Filters
            </a>
        </div>
    </div>
    <div class="row">
        <table class="table my-4" style="font-size: 0.9em;">
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
            @foreach ($info->activeUsers as $activeUser)
                <tr>    
                    <th class="text-center">{{$activeUser->id}}</th>
                    <th class="text-center">TBD</th>
                    <th class="text-center">{{$activeUser->name}}</th>
                    <th class="text-center">{{$activeUser->email}}</th>
                    <th class="text-center">{{$activeUser->phone_number ?? '-'}}</th>
                    <th class="text-center">{{$activeUser->nif ?? '-'}}</th>
                    <th class="text-center">{{$activeUser->newsletter_subcribed ? 'Yes' : 'No'}}</th>
                    <th>
                        <div class="d-flex justify-content-around" style="font-size: 1.2em;">
                            <a class="bi bi-info-circle-fill icon-click" href={{route('getUser', ['id' => $activeUser->id])}} data-bs-toggle="tooltip" title="Go to User Page"></a>
                            <a class="bi bi-pencil-square icon-click px-1" href={{route('editPage', ['id' => $activeUser->id])}} data-bs-toggle="tooltip" title="Edit User Info"></a>
                            <a class="bi bi-dash-circle-fill icon-click" data-bs-toggle="tooltip" title="Ban User"></a>
                        </div>
                    </th>
                </tr>
            @endforeach
            </tbody>
        </table>  
    </div>
</div>

<div class="offcanvas offcanvas-end" tabindex="-1" id="usersOffCanvas" aria-labelledby="usersOffCanvasLabel">
    <div class="offcanvas-header">
      <h5 class="offcanvas-title" id="usersOffCanvasLabel">Filters</h5>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
      <form id="user-dashboard-form" name="form1">
          <div class="container">
            <div class="row my-3">
                <label for="name">Name</label>
                <input required id="name" class="form-control" type="text" name="name" value="">
                <span class="error form-text text-danger" id="name-error"></span>
            </div>
            <div class="row">
                <button type="submit" class="btn btn-primary">Search</button>
            </div>
          </div>
          
      </form>
    </div>
  </div>