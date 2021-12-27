@extends('layouts.app')

@section('title', 'API TESTING')

@section('content')

<form class="container d-flex flex-column" id="edit-form" autocomplete="off" onsubmit="return send(event);">
    <h2>ORDER STATUS EDIT</h2>

    <label for="order-id">ORDER ID</label>
    <input required id="order-id" type="number" name="order-id">

    <label for="status">ORDER STATUS</label>
    <input id="status" list="statuses" name="status">
    <datalist id="statuses">
    @each('dev.devoptions', $statuses, "status")
    </datalist>

    <button type="submit" class="btn btn-primary">Submit</button>
</form>

@endsection
