<script src={{asset('js/adminOrders.js')}} defer></script>
@include('partials.alert')

<h4 class="text-center my-2">Unfinished Orders</h4>
<table id="updatable-orders-dashboard" class="table my-4" style="font-size: 0.9em;">
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
            <th class="text-center">TBD</th>
            <th class="text-center">{{$updatableOrder->total}} €</th>
            <th class="text-center"><a
                    class="badge rounded-pill badge-decoration-none badge-{{$updatableOrder->status}} ">{{strToUpper($updatableOrder->status)}}</a>
            </th>
            <th>
                <div class="d-flex justify-content-around" id="dropdown-menu-order-status-{{$updatableOrder->id}}" style="font-size: 1.2em;">
                    <div class="dropdown dropstart">
                        <button class="dropdown-toggle btn" type="button" id="dropdown-menu-order-status-btn-{{$updatableOrder->id}}"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-forward-fill icon-click" data-bs-toggle="tooltip"
                            title="Advance Status"></i>
                        </button>

                        <div class="dropdown-menu"  aria-labelledby="dropdown-menu-order-status-btn-{{$updatableOrder->id}}">
                            <form class="px-4 py-3" id="edit-order-status-form-{{$updatableOrder->id}}" onsubmit="return sendOrderStatus(event, {{$updatableOrder->id}});">
                                <div class="mb-3">
                                    <label for="order-status-{{$updatableOrder->id}}" class="form-label">Edit Order Status</label>
                                    <select id="order-status-{{$updatableOrder->id}}" name="status" class="form-select">
                                        @foreach ($statuses as $status)
                                        <option @if($status===$updatableOrder->status) selected @endif
                                            value="{{$status}}">{{strtoupper($status)}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">Edit Order</button>
                            </form>
                        </div>
                        <a class="bi bi-info-circle-fill icon-click" href={{route('orders', ['id'=>
                            $updatableOrder->id])}}
                            data-bs-toggle="tooltip" title="Go to Order Page"></a>
                    </div>
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
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                {{$info->finishedOrders->count()}} Finished Orders
            </button>
        </h2>
        <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne"
            data-bs-parent="#accordionFlushExample">
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
                            <th class="text-center">Actions</th>
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
                            <th class="text-center"><a
                                    class="badge rounded-pill badge-decoration-none badge-{{$finishedOrder->status}} ">{{strToUpper($finishedOrder->status)}}</a>
                            </th>
                            <th>
                                <div class="d-flex justify-content-around" style="font-size: 1.2em;">
                                    <a class="bi bi-info-circle-fill icon-click" href={{route('orders', ['id'=>
                                        $finishedOrder->id])}} data-bs-toggle="tooltip" title="Go to Order Page"></a>
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
