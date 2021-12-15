@extends('layouts.app')

@section('title', $shopper->name)

@section('content')
@include('partials.shopper', ['shopper' => $shopper])

<script type="text/javascript">
function send() {
    window.axios.post("/api/users/private/{{Auth::id()}}/edit", {name: document.getElementById("name").value}).then((response) => {console.log(response)}).catch((response) => {console.log(response)})
}
</script>


    <label for="name"> Name</label>
    <input id="name" type="text" name="name" required>

    <button type="submit" onclick="send();"></button>



@endsection
