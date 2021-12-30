@extends('layouts.app')

@section('title', 'API TESTING')

@section('content')

<form class="container form"  enctype="multipart/form-data" method="POST" action="{{route('addProduct')}}">
    @csrf
    <h2>ADD PRODUCT</h2>
    <label for="name"> NAME</label>
    <input  class="form-control" type="text" name="name">
    <label for="attributes"> ATTRIBUTES</label>
    <textarea  class="form-control" type="text" name="attributes"></textarea>
    <label for="stock"> STOCK</label>
    <input  class="form-control" type="number" name="stock">
    <label for="description"> DESCRIPTION</label>
    <textarea  class="form-control" type="text" name="description"></textarea>
    <label for="photos[]"> PHOTOS</label>
    <input  class="form-control" id="photos" class="form-control" type="file" name="photos[]" multiple>
    <label for="price"> PRICE</label>
    <input  class="form-control" type="number" step="0.01" name="price">

    <button type="submit"> SUBMIT</button>
</form>

@endsection
