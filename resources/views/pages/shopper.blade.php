@extends('layouts.app')

@section('title', $shopper->name)

@section('content')
@include('partials.shopper', ['shopper' => $shopper])

<script type="text/javascript">
function send(event) {
    const formData = new FormData(document.getElementById('edit-form'));

    window.axios.post
    (
        "/api/users/private/{{$shopper->id}}/edit",
        formData,
        {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        }
    )
    .then((response) => {console.log(response)})
    .catch((response) => {console.log(response)});

    event.preventDefault();
}
</script>
<img src="{{asset($shopper->user->photo->url)}}">

<form id="edit-form" autocomplete="off">
    <label for="name"> Name</label>
    <input id="name" type="text" name="name" value="{{$shopper->user->name}}">

    <label for="email"> Email</label>
    <input id="email" type="email" name="email" value="{{$shopper->user->email}}">

    <label for="password"> Change Password</label>
    <input id="password" type="password" name="password" autocomplete="new-password">

    <label for="profile-picture"> Upload New Photo</label>
    <input id="profile-picture" type="file" name="profile-picture">

    <button type="button" onclick="send(event);"></button>
</form>



@endsection
