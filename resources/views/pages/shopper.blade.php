@extends('layouts.app')

@section('title', $shopper->user->name)

@section('content')

@include('partials.shopper', ['shopper' => $shopper])
@include('partials.errormodal')

<script type="text/javascript" defer>
    function send(event) {
        const formData = new FormData(document.getElementById('edit-form'));
        clearErrors();

        window.axios.post
        (
            "/api/users/{{$shopper->id}}/private/edit",
            formData,
            {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }
        )
        .then((response) => {
            reportData("Profile Updated Successfully!");
        })
        .catch((error) => {
            if(error.response) {
                if(error.response.data) {
                    reportData("There was an error editing the profile", error.response.data["errors"], {
                        'cur-password' : 'Current Password',
                        'password' : 'New Password',
                        'name' : 'Name',
                        'email' : 'Email',
                        'phone_number' : 'Phone Number',
                        'nif' : 'NIF',
                        'about_me' : 'About Me',
                        'profile-picture' : 'Profile Picture',
                        'id' : 'ID',
                    });

                    setErrors( error.response.data["errors"]);
                }
            }
        });

        event.preventDefault();
    }

    function clearErrors() {
        document.querySelectorAll(".error").forEach((el) => {
            el.innerText = "";
        })
    }

    function setErrors(errors) {
        for(let key in errors) {
            let element = document.getElementById(`${key}-error`);
            if(element == null) continue;

            let text = "";
            let obj = errors[key];
            if(typeof obj === 'object' && obj !== null) {
                for(let dataKey in obj) {
                    text = text.concat(obj[dataKey],'<br>');
                }
            } else {
                text = obj;
            }

            element.innerHTML = text;
        }
    }

</script>
<img src="{{asset($shopper->user->photo->url)}}">

<form class="container d-flex flex-column" id="edit-form" autocomplete="off" onsubmit="return send(event);">
    <label for="name"> Name</label>
    <input required id="name" type="text" name="name" value="{{$shopper->user->name}}">
    <span class="error form-text" id="name-error">

    </span>

    <label for="email"> Email</label>
    <input required id="email" type="email" name="email" value="{{$shopper->user->email}}">
    <span class="error form-text" id="email-error">

    </span>

    <label for="password"> Change Password</label>
    <input id="password" type="password" name="password" autocomplete="new-password">
    <span class="error form-text" id="password-error">

    </span>

    <label for="password-confirm">Confirm Password</label>
    <input id="password-confirm" type="password" name="password-confirmation">

    <label for="profile-picture"> Upload New Photo</label>
    <input id="profile-picture" type="file" name="profile-picture">
    <span class="error form-text" id="profile-picture-error">

    </span>

    <label for="about-me"> About Me</label>
    <textarea id="about-me" name="about-me" value="">
        {{$shopper->about_me}}
    </textarea>
    <span class="error form-text" id="about_me-error">

    </span>

    <label for="nif"> NIF</label>
    <input id="nif" type="text" name="nif" value="{{$shopper->nif}}">
    <span class="error form-text" id="nif-error">

    </span>

    <label for="phone-number"> Phone</label>
    <input id="phone-number" type="text" name="phone-number" value="{{$shopper->phone_number}}">
    <span class="error form-text" id="phone_number-error">

    </span>

    <label for="cur-password">Current Password</label>
    <input autocomplete="on" required id="cur-password" type="password" name="cur-password">
    <span class="error form-text" id="cur-password-error">

    </span>

    <button type="submit" class="btn btn-primary">Submit</button>
</form>

@endsection
