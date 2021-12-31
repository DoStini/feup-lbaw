@include('partials.errormodal')


<script type="text/javascript" defer>
    function updatePhoto(photo) {
        const fallBack = "/img/user.png";

        const userImg = $("#user-img");
        const headerImg = $("#header-user-img");

        if (userImg.attr("src") === photo) {
            return;
        }

        userImg.on("error", () => userImg.attr("src", fallBack));
        userImg.on("error", () => headerImg.attr("src", fallBack));
        userImg.attr("src", photo);
        headerImg.attr("src", photo);
    }

    function renderElements(user) {
        $("#header-user-name").text(user.name);
        updatePhoto(user.photo);
    }

    function send(event) {
        const formData = new FormData(document.getElementById('edit-form'));
        clearErrors();

        formDataPost("/api/users/{{$shopper ? $shopper->user->id : $admin->id}}/private/edit",formData)
        .then((response) => {
            reportData("Profile Updated Successfully!");
            renderElements(response.data);
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

<form class="container d-flex flex-column" id="edit-form" autocomplete="off" onsubmit="return send(event);">
    <div class="row">
        <div class="form-group col-md-6">
            <label for="name">Name</label>
            <input required id="name" class="form-control" type="text" name="name" value="{{$shopper ? $shopper->user->name : $admin->name}}">
            <span class="error form-text text-danger" id="name-error"></span>
        </div>
        <div class="form-group col-md-6">
            <label for="email">Email</label>
            <input required id="email" class="form-control" type="email" name="email" value="{{$shopper ? $shopper->user->email : $admin->email}}">
            <span class="error form-text text-danger" id="email-error"></span>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-6">
            <label for="password"> New Password</label>
            <input id="password" class="form-control" type="password" name="password" autocomplete="new-password">
        </div>
        <div class="form-group col-md-6">
            <label for="password-confirm">Confirm New Password</label>
            <input id="password-confirm" class="form-control" type="password" name="password-confirmation">
        </div>
        <span class="error form-text text-danger" id="password-error"></span>
    </div>

    <div class="mb-3">
        <label for="profile-picture"> Upload New Photo</label>
        <input id="profile-picture" class="form-control" type="file" name="profile-picture">
        <span class="error form-text text-danger" id="profile-picture-error"></span>
    </div>
    
    @if($shopper)
        <div class="mb-3">
            <label for="about-me"> About Me</label>
            <textarea id="about-me" class="form-control" name="about-me" value="">{{$shopper->about_me}}</textarea>
            <span class="error form-text text-danger" id="about_me-error"></span>
        </div>

        <div class="row">
            <div class="form-group col-md-6">
                <label for="nif"> NIF</label>
                <input id="nif" type="text" class="form-control" name="nif" value="{{$shopper->nif}}">
                <span class="error form-text text-danger" id="nif-error"></span>
            </div>
            <div class="form-group col-md-6">
                <label for="phone-number"> Phone</label>
                <input id="phone-number" type="text" class="form-control" name="phone-number" value="{{$shopper->phone_number}}">
                <span class="error form-text text-danger" id="phone_number-error"></span>        
            </div>
        </div>
    @endif

    <div class="form-group my-4">
        <label for="cur-password"><b>Confirm your password before applying changes:</b></label>
        <input autocomplete="on" required id="cur-password" class="form-control" type="password" name="cur-password">
        <span class="error form-text text-danger" id="cur-password-error"></span>
    </div>
    
    <button type="submit" class="btn btn-primary">Submit</button>
</form>