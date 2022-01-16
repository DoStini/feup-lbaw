<script type="text/javascript" src={{ asset('js/login.js') }}></script>

@extends('layouts.app')

@section('title', 'Coupons Dashboard')

@section('content')

<div class="container">
@include('partials.links.dashboardLinks', ['page' => 'createCouponDashboard'])

<form id="coupon-form"  method="POST" action={{route('createCoupon')}}>
    @csrf
    <div class="form-group">
        <label for="code">Code</label>
        <input class="form-control" id="code" name="code" type="text" value="{{ old('code') }}" required>
        @error('code', 'new_coupon')
        <span class="error form-text">
            {{$message}}
        </span>
    @enderror
    </div>

    <div class="form-group">
        <label for="percentage">Discount</label>
        <input class="form-control" id="percentage" type="number" min="0" max="100" name="percentage" value="{{ old('percentage') }}" step="0.05" required>
        @error('percentage', 'new_coupon')
            <span class="error form-text">
                {{$message}}
            </span>
        @enderror
    </div>

    <div class="form-group">
        <label for="minimum_cart_value">Minimum Cart Value</label>
        <input class="form-control" id="minimum_cart_value" type="number" min="0" name="minimum_cart_value" value="{{ old('minimum_cart_value') }}" required>
        @error('minimum_cart_value', 'new_coupon')
            <span class="error form-text">
                {{$message}}
            </span>
        @enderror
    </div>

    <div class="form-group mt-1">
        <input name="is_active" id="is_active" class="form-check-input" type="checkbox"
        aria-label="Active?" checked="{{ old('is_active') || true }}">

        <label class="form-check-label" for="is_active">Active?</label>

    </div>

    <button type="submit" class="w-100 mt-3 btn btn-primary">Create Coupon</button>
</form>

</div>

@endsection
