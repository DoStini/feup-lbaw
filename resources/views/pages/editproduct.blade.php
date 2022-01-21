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
            <label for="photos">Add More Photos</label>
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
                    <img class="col-4 col-md-2" src="{{$photo->url}}"></img>
                @endforeach
            </div>
        </div>
    @endif

    <button type="submit" class="btn btn-primary my-2">Submit</button>
</form>
</div>
</div>

<script defer>
    let editor;

    window.addEventListener('load', () => {
        ckeditor
        .create( document.querySelector( '#description' ), {
            toolbar: [ 'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList'],
            heading: {
                options: [
                    { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                    { model: 'heading1', view: 'h4', title: 'Heading 1', class: 'ck-heading_heading4' },
                    { model: 'heading2', view: 'h6', title: 'Heading 2', class: 'ck-heading_heading6' }
                ]
            }
        } )
        .then( newEditor => {
            editor = newEditor;
        } )
        .catch( error => {
            console.log( error );
        } );
    
        document.getElementById('add-product-form').addEventListener('submit', (e) => {
            console.log("daknsda");
            let description = document.getElementById('description');
            description.value = editor.getData();
        });
    })

</script>


@endsection
