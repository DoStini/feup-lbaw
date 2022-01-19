@extends('layouts.app')

@section('title', 'Orders Dashboard')

@section('content')

@include('partials.errormodal')

<div class="container">
@include('partials.links.dashboardLinks', ['page' => 'productDashboard'])

@if($errors->any())
<script async>
    (async() => {
        while(!window.hasOwnProperty('reportData'))
            await new Promise(resolve => setTimeout(resolve, 100));

        let errors = JSON.parse(`<?php echo($errors->toJson()) ?>`.replace(/\s+/g," "));

        reportData("Couldn't create the product", errors, {
            'name' : 'Product Name',
            'variantCheck' : 'Variations',
            'originVariantID' : 'Variant ID',
            'colorVariant' : 'Variant Color',
            'stock' : 'Stock',
            'description' : 'Description',
            'price' : 'Price',
            'photos' : 'Photos',
        });
    })();
</script>
@endif

<div class="container">


<form class="container d-flex flex-column" id="add-product-form" autocomplete="off"
        class="container form"  enctype="multipart/form-data" 
        method="POST" action="{{route('editProduct', ['id' => $product->id])}}">
    @csrf
    <div class="row">
        <div class="form-group col-md-12">
            <label for="name">Product Name</label>
            <input id="name" class="form-control" type="text" name="name" value="{{old('name') ?? $product->name}}">
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
            <input id="stock" type="number" value="{{old('stock') ?? $product->stock}}" min="0" class="form-control" name="stock" autocomplete="stock">
            @error('stock')
            <span class="error form-text text-danger">
                {{$message}}
            </span>
            @enderror
        </div>
        <div class="form-group col-md-6">
            <label for="price">Price</label>
            <input required id="price" class="form-control" type="number" step="0.01" min="0" value="{{old('price') ?? $product->price}}" name="price">
            @error('price')
            <span class="error form-text text-danger">
                {{$message}}
            </span>
            @enderror
        </div>
    </div>

    <div class="row">
        <div class="form-group col-12">
            <label for="description">Description</label>
            <textarea id="description" class="form-control" name="description">{{old('description') ?? $product->description}}</textarea>
            @error('description')
            <span class="error form-text text-danger">
                {{$message}}
            </span>
            @enderror
        </div>
    </div>

    @if ($product->photos->count() > 0)
        <div class="row mt-3 px-3">
            <label for="photos">Photos</label>
            <div id="photos" class="form-control col-12">
                @foreach ($product->photos as $photo)
                    <div id="photo-{{$photo->id}}" class="col-4 col-md-2">
                        <img class="edit-product-photo col-12" src="{{$photo->url}}">
                        <i class="bi bi-x-lg remove-photo-icon" 
                            onclick="deleteProductPhoto({{$photo->id}})"></i>
                        </img>
                    </div>
                @endforeach

                <form id="image-form" method="POST" action="{{route("addProductPhoto", ["id" => $product->id])}}">
                    <label for="add-photos">
                        <i class="bi bi-plus-circle add-product-photo"></i>
                    </label>
                </form>

            </div>
        </div>
    @endif

    <button type="submit" class="btn btn-primary my-2">Submit</button>
</form>
</div>
</div>

<form id="image-form" method="POST" action="{{route("addProductPhoto", ["id" => $product->id])}}" enctype="multipart/form-data">
    @csrf
    <input multiple="true" type="file" name="photos[]" id="add-photos" value="{{old('photos')}}"
        onchange="applyProductPhoto()"/>
</form>

<script defer>

    const deleteProductPhoto = (id) => {
        deleteRequest(`/api/products/{{$product->id}}/photo/${id}`)
            .then(() => {
                document.getElementById(`photo-${id}`).remove();
            }).catch((e) => {
                console.log()
                reportData("Error removing photo", e.response.data)
            });
    }

    const applyProductPhoto = () => {
        const form = document.getElementById('image-form');
        console.log(form)
        form.dispatchEvent(new Event("submit"));
    }

</script>

@endsection