@extends('layouts.app')

@section('title', 'API TESTING')

@section('content')

@if($errors->any())

@foreach($errors->getMessages() as $key => $message)
    <p>{{$key}} = @foreach ($message as $mess) {{$mess}}</p><br> @endforeach
@endforeach
@endif


<form class="container form"  enctype="multipart/form-data" method="POST" action="{{route('addProduct')}}">
    @csrf
    <h2>ADD PRODUCT</h2>
    <label for="name"> NAME</label>
    <input  class="form-control" type="text" name="name" value="{{old('name')}}">
    <label for="attributes"> ATTRIBUTES</label>
    <textarea  class="form-control" type="text" name="attributes">{{old('attributes')}}</textarea>
    <label for="stock"> STOCK</label>
    <input  class="form-control" type="number" name="stock" value="{{old('stock')}}">
    <label for="description"> DESCRIPTION</label>
    <textarea  class="form-control" type="text" name="description">{{old('description')}}</textarea>
    <label for="photos[]"> PHOTOS</label>
    <input  class="form-control" id="photos" class="form-control" type="file" name="photos[]" value="{{old('photos')}}"  multiple>
    <label for="price"> PRICE</label>
    <input  class="form-control" type="number" step="0.01" value="{{old('price')}}" name="price">

    <button type="submit"> SUBMIT</button>
</form>

@endsection
