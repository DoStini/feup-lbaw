

<h4 class="text-center my-2">Unfinished Orders</h4>
<table class="table my-4" style="font-size: 0.9em;">
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
    </thead>
    <tbody>
    @foreach ($info->updatableOrders as $updatableOrder)
        <tr>    
            <th class="text-center">{{$updatableOrder->id}}</th>
            <th class="text-center">{{$updatableOrder->name}} ({{$updatableOrder->shopper_id}})</th>
            <th class="text-center">{{date("d M Y, H:i", strtotime($updatableOrder->timestamp))}}</th>
            <th class="text-center">{{date("d M Y, H:i", strtotime($updatableOrder->timestamp))}}</th>
            <th class="text-center">{{$updatableOrder->total}} €</th>
            <th class="text-center"><a class="badge rounded-pill badge-decoration-none badge-{{$updatableOrder->status}} ">{{strToUpper($updatableOrder->status)}}</a></th>
            <th>
                <div class="d-flex justify-content-around" style="font-size: 1.2em;">
                    <a class="bi bi-forward-fill icon-click"></a>
                    <a class="bi bi-info-circle-fill icon-click"></a>
                </div>
            </th>
        </tr>
    @endforeach
    </tbody>
</table>



<h4 class="text-center my-4">Finished Orders</h4>


<div class="accordion accordion-flush my-4" id="accordionFlushExample">
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
                        <th class="text-center">Order ID</th>
                        <th class="text-center">Shopper Name (ID)</th>
                        <th class="text-center">Created At</th>
                        <th class="text-center">Last Update</th>
                        <th class="text-center">Total</th>
                        <th class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($info->finishedOrders as $finishedOrder)
                    <tr>
                        <th class="text-center">{{$finishedOrder->id}}</th>
                        <th class="text-center">{{$finishedOrder->name}} ({{$finishedOrder->shopper_id}})</th>
                        <th class="text-center">{{date("d M Y, H:i", strtotime($finishedOrder->timestamp))}}</th>
                        <th class="text-center">{{date("d M Y, H:i", strtotime($finishedOrder->timestamp))}}</th>
                        <th class="text-center">{{$finishedOrder->total}} €</th>
                        <th class="text-center"><a class="badge rounded-pill badge-decoration-none badge-{{$finishedOrder->status}} ">{{strToUpper($finishedOrder->status)}}</a></th>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
      </div>
    </div>
  </div>