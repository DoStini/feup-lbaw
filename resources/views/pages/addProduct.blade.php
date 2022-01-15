@extends('layouts.app')

@section('title', 'Orders Dashboard')

@section('content')

@include('partials.errormodal')

<div class="container">
@include('partials.links.dashboardLinks', ['page' => 'addProduct'])

@if($errors->any())
<script async>
    (async() => {
        while(!window.hasOwnProperty('reportData'))
            await new Promise(resolve => setTimeout(resolve, 100));

        let errors = JSON.parse(`<?php echo($errors->toJson()) ?>`.replace(/\s+/g," "));

        reportData("Couldn't create the product", errors, {
            'name' : 'Product Name',
            'attributes' : 'Variations',
            'stock' : 'Stock',
            'description' : 'Description',
            'price' : 'Price',
            'photos' : 'Photos',
        });
    })();
</script>
@endif

<div class="container">


<form class="container d-flex flex-column" id="add-product-form" autocomplete="off"  class="container form"  enctype="multipart/form-data" method="POST" action="{{route('addProduct')}}">
    @csrf
    <div class="row">
        <div class="form-group col-md-12">
            <label for="name">Product Name</label>
            <input required id="name" class="form-control" type="text" name="name" value="{{old('name')}}">
            @error('name')
            <span class="error form-text text-danger">
                {{$message}}
            </span>
            @enderror
            <span class="error form-text text-danger" id="name-error"></span>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-6">
            <label for="stock">Stock</label>
            <input id="stock" type="number" value="{{old('stock')}}" class="form-control" name="stock" autocomplete="stock">
            @error('stock')
            <span class="error form-text text-danger">
                {{$message}}
            </span>
            @enderror
        </div>
        <div class="form-group col-md-6">
            <label for="price">Price</label>
            <input id="price" class="form-control" type="number" step="0.01" value="{{old('price')}}" name="price">
            @error('price')
            <span class="error form-text text-danger">
                {{$message}}
            </span>
            @enderror
        </div>
    </div>

    <div class="row">
        <div class="form-group col-12">
            <label for="photos">Product Photos</label>
            <input multiple="true" id="photos" class="form-control" type="file" name="photos[]" value="{{old('photos')}}">
            @if($errors->has('photos'))
            <span class="error form-text text-danger">
                @foreach ($errors->get('photos') as $message)
                    {{$message}}<br/>
                @endforeach
            </span>
            @enderror
        </div>
    </div>

    <div class="row">
        <div class="form-group col-12">
            <label for="description">Description</label>
            <textarea id="description" class="form-control" name="description">{{old('description')}}</textarea>
            @error('description')
            <span class="error form-text text-danger">
                {{$message}}
            </span>
            @enderror
        </div>
    </div>
    <div class="d-flex align-items-center mb-3">
        <label for="variantCheck">Is variant?</label>
        <input value="" id="variantCheck" name="variantCheck" class="form-check-input mx-2" type="checkbox" data-bs-toggle="collapse" data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
    </div>
    <div class="row collapse mb-3" id="collapseExample">
        <div class="col-md-6">
            <label for="originVariantID" class="mb-3">Variant of (Product ID): </label>
            <input id="originVariantID" type="number" value="{{old('originVariantID')}}" class="form-control" name="originVariantID" autocomplete="originVariantID">
            @error('originVariantID')
            <span class="error form-text text-danger">
                {{$message}}
            </span>
            @enderror
        </div>
        <div class="col-md-6">
            <label for="variant" class="form-label">
                Variant Color
            </label>
            <div class="d-flex align-items-center">
                <div id="select-target-variant"></div>
                {{-- <select class="address-select form-select" id="variantColor"></select>
                {{sprintf("https://cdn.shopify.com/s/files/1/0014/1865/7881/files/%s_50x50_crop_center.png", $color)}}
                --}}
                <img id="variant-img" class="variant-color mx-4" src={{asset('img/notfound.jpg')}} onerror="this.src='{{asset('img/notfound.jpg')}}'">
            </div>
        </div>
    </div>

    <button type="submit" class="btn btn-primary">Submit</button>
</form>
</div>
</div>

@endsection
