@extends('layouts.logoOnlyApp')

@section('content')

@include('partials.errormodal')

<section id="auth" class="auth container">
    <div class="row justify-content-center">
        <div id="register" class="col-lg-6">
            <h2>Recover account</h2>
            <form id="recover-form"">
                @csrf

                <div class="form-group">
                    <label for="email">Email</label>
                    <input class="form-control" id="email" name="email" type="email" required>
                </div>

                <button type="submit" value="register" class="w-100 mt-3 btn btn-primary">Recover<span class="m-2" ><img src="{{asset('img/arrow_right.svg')}}" alt=""></span></button>
            </form>
        </div>
    </div>
</section>
@endsection


<script defer>
    window.addEventListener("load", () => {
        document.getElementById('recover-form').addEventListener('submit', (e) => {
            e.preventDefault();
            jsonBodyPost("/api/account/recover", {
                email: document.getElementById("email").value,
            });

            document.getElementById("email").value = "";

            reportData("If there is an account registered with that email, you will receive an email.")
        });
    })
</script>
