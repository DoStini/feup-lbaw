<div class="container h-100">
    <div class="row-sm-5 mb-4 d-flex justify-content-around align-items-center">
      <div class="col-sm-5 h-75">
        <div class="card h-100">
          <div class="card-body d-flex justify-content-center flex-column">
            <h3 class="card-title text-center m-4">Users</h3>
            <h5 class="card-text text-center">{{$info->userNum}} Active Users</h5>
            <a href="{{route('getUserDashboard')}}" class="btn btn-primary">Manage</a>
          </div>
        </div>
      </div>
      <div class="col-sm-5 h-75">
        <div class="card h-100">
          <div class="card-body d-flex justify-content-center flex-column">
            <h3 class="card-title text-center m-4">Orders</h3>
            <h5 class="card-text text-center">{{$info->orderNum}} Ongoing Orders</h5>
            <a href={{route('getOrderDashboard')}} class="btn btn-primary">Manage</a>
          </div>
        </div>
      </div>
    </div>
</div>
