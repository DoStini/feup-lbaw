@extends('layouts.app')

@section('title', 'API TESTING')

@section('content')

@include('partials.errormodal')

<script type="text/javascript" defer>
    function sendOrderStatus(event) {
        const formData = new FormData(document.getElementById('edit-order-status-form'));
        let requestURL = "/api/orders/";
        requestURL = requestURL.concat(formData.get("order-id"), "/status");

        window.axios.post
        (
            requestURL,
            formData.get("status"),
            {
                headers: {
                    'Content-Type': 'json'
                }
            }
        )
        .then((response) => {
            reportData("Order Updated Successfully!");
            console.log(response);
        })
        .catch((error) => {
            reportData("There was an error editing the status", error.response.data["errors"]);
        });

        event.preventDefault();
    }

</script>

<form class="container d-flex flex-column" id="edit-order-status-form" autocomplete="off" onsubmit="return sendOrderStatus(event);">
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
