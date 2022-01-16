@extends('layouts.app')

@section('title', 'Users Dashboard')

@section('content')

<div class="container">
    <div class="row d-flex align-items-center">
        @include('partials.links.dashboardLinks', ['page' => 'couponDashboard'])
        <div class="col-md-12 d-flex justify-content-end">
            <a class="btn btn-primary mx-1" href={{route('getCreateCouponPage')}}>
                Create New Coupon
            </a>
        </div>
    </div>
    <div class="row">
        <table class="table table-responsive my-4" style="font-size: 0.9em;">
            <thead class="table-dark">
                <tr>
                    <th class="text-center">Coupon ID</th>
                    <th class="text-center">Code</th>
                    <th class="text-center">Percentage</th>
                    <th class="text-center">Minimum cart value</th>
                    <th class="text-center">Active?</th>
                </tr>
            </thead>
            <tbody id="coupons-area">
            </tbody>
        </table>
    </div>
</div>

@endsection