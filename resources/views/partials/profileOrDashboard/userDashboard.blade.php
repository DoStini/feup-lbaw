<h4 class="text-center my-2">Active Users</h4>

<div class="accordion accordion-flush my-4" id="activeUsersAccordion">
    <div class="accordion-item">
      <h2 class="accordion-header" id="activeUsersFlush">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-activeUsers" aria-expanded="false" aria-controls="flush-activeUsers">
          {{$info->activeUsers->count()}} Active Users
        </button>
      </h2>
      <div id="flush-activeUsers" class="accordion-collapse collapse" aria-labelledby="activeUsersFlush" data-bs-parent="#activeUsersAccordion">
        <div class="accordion-body">
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
                <tbody>
                @foreach ($info->activeUsers as $activeUser)
                    <tr>    
                        <th class="text-center">{{$activeUser->id}}</th>
                        <th class="text-center">TBD</th>
                        <th class="text-center">{{$activeUser->name}}</th>
                        <th class="text-center">{{$activeUser->email}}</th>
                        <th class="text-center">{{$activeUser->phone_number}}</th>
                        <th class="text-center">{{$activeUser->nif}}</th>
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
    </div>
  </div>


<h4 class="text-center my-4">Blocked Users</h4>


<div class="accordion accordion-flush my-4" id="blockedUsersAccordion">
    <div class="accordion-item">
      <h2 class="accordion-header" id="blockedUsersFlush">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-blockedUsers" aria-expanded="false" aria-controls="flush-blockedUsers">
          {{$info->blockedUsers->count()}} Blocked Users
        </button>
      </h2>
      <div id="flush-blockedUsers" class="accordion-collapse collapse" aria-labelledby="blockedUsersFlush" data-bs-parent="#blockedUsersAccordion">
        <div class="accordion-body">
            <table class="table" style="font-size: 0.9em;">
                <thead class="table-dark">
                    <tr>
                        <th class="text-center">User ID</th>
                        <th class="text-center">Account Created At</th>
                        <th class="text-center">Name</th>
                        <th class="text-center">Email</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($info->activeUsers as $blockedUser)
                    <tr>    
                        <th class="text-center">{{$blockedUser->id}}</th>
                        <th class="text-center">TBD</th>
                        <th class="text-center">{{$blockedUser->name}}</th>
                        <th class="text-center">{{$blockedUser->email}}</th>
                        <th>
                            <div class="d-flex justify-content-around" style="font-size: 1.2em;">
                                <a class="bi bi-info-circle-fill icon-click" href={{route('getUser', ['id' => $blockedUser->id])}} data-bs-toggle="tooltip" title="Go to User Page"></a>
                                <a class="bi bi-pencil-square icon-click px-1" href={{route('editPage', ['id' => $activeUser->id])}} data-bs-toggle="tooltip" title="Edit User Info"></a>
                                <a class="bi bi-flag icon-click" data-bs-toggle="tooltip" title="Unblock User"></a>
                            </div>
                        </th>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
      </div>
    </div>
  </div>