@extends('layouts.app')

@section('title', 'Orders Dashboard')

@section('content')

<div class="container pb-4">

@include('partials.links.dashboardLinks', ['page' => 'orderDashboard'])
<table class="table my-4" style="font-size: 0.9em;" id="order-dashboard-table">
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
    @foreach ($info->orders as $order)
        <tr>
            <td class="text-center">{{$order->id}}</td>
            <td class="text-center">{{$order->name}} ({{$order->shopper_id}})</td>
            <td class="text-center">{{date("d M Y, H:i", strtotime($order->timestamp))}}</td>
            <td class="text-center">TBD</td>
            <td class="text-center">{{$order->total}} €</td>
            <td class="text-center"><a class="badge rounded-pill badge-decoration-none badge-{{$order->status}} ">{{strToUpper($order->status)}}</a></td>
            <td>
                <div class="d-flex justify-content-around" style="font-size: 1.2em;">
                    {{--<a class="bi bi-forward-fill icon-click" href="" data-bs-toggle="tooltip" title="Advance Status"></a>--}}
                    <a class="bi bi-info-circle-fill icon-click" href={{route('orders', ['id' => $order->id])}} data-bs-toggle="tooltip" title="Go to Order Page"></a>
                </div>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

</div>

<script type="application/javascript" defer>
    $('#order-dashboard-table').DataTable({
        'order': [[5, 'asc']],
    });
</script>

  @endsection
