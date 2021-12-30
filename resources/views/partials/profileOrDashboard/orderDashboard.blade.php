

<h4 class="text-center">Unfinished Orders</h4>
<table class="table" style="font-size: 0.9em;">
    <thead class="table-dark">
        <tr>
            <th>Order ID</th>
            <th>Shopper Name (ID)</th>
            <th>Created At</th>
            <th>Last Update</th>
            <th>Total</th>
            <th>Status</th>
            <th>Advance Status</th>
        </tr>
    </thead>
    <tbody>
    @foreach ($info->updatableOrders as $updatableOrder)
        <tr>
            <th>{{$updatableOrder->id}}</th>
            <th>{{$updatableOrder->name}} ({{$updatableOrder->shopper_id}})</th>
            <th>{{date("d M Y, H:i", strtotime($updatableOrder->timestamp))}}</th>
            <th>{{date("d M Y, H:i", strtotime($updatableOrder->timestamp))}}</th>
            <th>{{$updatableOrder->total}} €</th>
            <th><a class="badge rounded-pill badge-decoration-none badge-{{$updatableOrder->status}} ">{{strToUpper($updatableOrder->status)}}</a></th>
            <th><a class="badge rounded-pill badge-decoration-none badge-clickable ">Advance Status</a></th>
        </tr>
    @endforeach
    </tbody>
</table>



<h4 class="text-center">Finished Orders</h4>


<div class="accordion accordion-flush" id="accordionFlushExample">
    <div class="accordion-item">
      <h2 class="accordion-header" id="flush-headingOne">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
          {{$info->finishedOrders->count()}} Finished Orders
        </button>
      </h2>
      <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
        <div class="accordion-body">
            <table class="table" style="font-size: 0.9em;">
                <thead class="table-dark">
                    <tr>
                        <th>Order ID</th>
                        <th>Shopper Name (ID)</th>
                        <th>Created At</th>
                        <th>Last Update</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Advance Status</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($info->finishedOrders as $finishedOrder)
                    <tr>
                        <th>{{$finishedOrder->id}}</th>
                        <th>{{$finishedOrder->name}} ({{$finishedOrder->shopper_id}})</th>
                        <th>{{date("d M Y, H:i", strtotime($finishedOrder->timestamp))}}</th>
                        <th>{{date("d M Y, H:i", strtotime($finishedOrder->timestamp))}}</th>
                        <th>{{$finishedOrder->total}} €</th>
                        <th><a class="badge rounded-pill badge-decoration-none badge-{{$finishedOrder->status}} ">{{strToUpper($finishedOrder->status)}}</a></th>
                        <th><a class="badge rounded-pill badge-decoration-none badge-clickable ">Advance Status</a></th>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
      </div>
    </div>
  </div>