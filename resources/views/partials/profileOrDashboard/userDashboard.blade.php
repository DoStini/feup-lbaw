<div class="container">
    <div class="row d-flex align-items-center">
        <div class="col-md-12 d-flex justify-content-end">
            <a class="btn btn-primary mx-1" href="#"}>
                Create New Admin
            </a>
            <a class="btn btn-primary mx-1" data-bs-toggle="offcanvas" href="#usersOffCanvas" role="button" aria-controls="usersOffCanvas">
                Filters
            </a>
        </div>
    </div>
    <div class="row">
        <table class="table table-responsive my-4" style="font-size: 0.9em;">
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

<div class="offcanvas offcanvas-end" tabindex="-1" id="usersOffCanvas" aria-labelledby="usersOffCanvasLabel">
    <div class="offcanvas-header">
      <h5 class="offcanvas-title" id="usersOffCanvasLabel">Filters</h5>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
      <form id="user-dashboard-form" name="form1">
          <div class="container">
            <div class="row my-3">
                <div class="col-md-12">
                    <label for="name">Name</label>
                    <input id="name" class="form-control" type="text" name="name" value="">
                    <span class="error form-text text-danger" id="name-error"></span>
                </div>
            </div>
            <div class="row">
                <p>Status</p>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="blocked" id="blockedCheck">
                    <label class="form-check-label" for="blockedCheck">
                        Blocked
                    </label>
                    </div>
                </div>
            </div>
            <div class="row">
                <button type="submit" class="btn btn-primary">Search</button>
            </div>
          </div>

      </form>
    </div>
  </div>
