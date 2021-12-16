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

    <label for="password-confirm">Confirm Password</label>
    <input id="password-confirm" type="password" name="password_confirmation">

    <label for="profile-picture"> Upload New Photo</label>
    <input id="profile-picture" type="file" name="profile-picture">

    <label for="about-me"> About Me</label>
    <textarea id="about-me" name="about-me" value="">
        {{$shopper->about_me}}
    </textarea>

    <label for="nif"> NIF</label>
    <input id="nif" type="text" name="nif" value="{{$shopper->nif}}">

    <label for="phone-number"> Phone</label>
    <input id="phone-number" type="text" name="phone-number" value="{{$shopper->phone_number}}">

    <label for="cur-password">Current Password</label>
    <input autocomplete="on" id="cur-password" type="password" name="cur-password">

    <button type="button" onclick="send(event);"></button>
</form>



@endsection
