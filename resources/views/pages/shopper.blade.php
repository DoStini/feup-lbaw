@extends('layouts.app')

@section('title', $shopper->name)

@section('content')
@include('partials.shopper', ['shopper' => $shopper])

<script type="text/javascript">
function send(event) {
    const formData = new FormData(document.getElementById('edit-form'));
    let params = {};
    for (var pair of formData.entries()) {
        params[pair[0]] = pair[1];
    }

    window.axios.post
    (
        "/api/users/private/{{$shopper->id}}/edit",
        params
    )
    .then((response) => {console.log(response)})
    .catch((response) => {console.log(response)});

    event.preventDefault();
}
</script>
<form id="edit-form">
    <label for="name"> Name</label>
    <input id="name" type="text" name="name" value="{{$shopper->name}}">

    <label for="email"> Email</label>
    <input id="email" type="email" name="email" value="{{$shopper->email}}">

    <label for="password"> Password</label>
    <input id="password" type="password" name="password">

    <button type="button" onclick="send(event);"></button>
</form>



@endsection
