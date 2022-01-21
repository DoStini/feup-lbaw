@extends('layouts.app')

@section('title', 'Orders Dashboard')

@section('content')

<div class="container">
@include('partials.links.dashboardLinks', ['page' => 'productDashboard'])

@if($errors->any())
<script>
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
        <div class="form-group mb-3 col-md-6">
            <label for="name" class="form-label">Product Name</label>
            <input id="name" class="form-control" type="text" name="name" value="{{old('name') ?? $product->name}}">
            @error('name')
            <span class="error form-text text-danger">
                {{$message}}
            </span>
            @enderror
            <span class="error form-text text-danger" id="name-error"></span>
        </div>
        <div class="mb-3 col-md-6">
            <label for="category" class="form-label">
                Category
            </label>
            <div id="category-replace"></div>
        </div>
        <input id="category-id" name="category-id" hidden>
    </div>
    <div class="row">
        <div class="form-group col-md-6">
            <label for="stock" class="form-label">Stock</label>
            <input id="stock" type="number" value="{{old('stock') ?? $product->stock}}" min="0" class="form-control" name="stock" autocomplete="stock">
            @error('stock')
            <span class="error form-text text-danger">
                {{$message}}
            </span>
            @enderror
        </div>
        <div class="form-group col-md-6">
            <label for="price" class="form-label">Price</label>
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
            <label for="description" class="form-label">Description</label>
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
            <label for="photos" class="form-label">Photos</label>
            <div id="photos" class="form-control container">
                <div class="row">
                    @foreach ($product->photos as $photo)
                    <div id="photo-{{$photo->id}}" class="col-md-2 col-sm-4 col-6 my-2" style="position: relative;">
                        <img class="edit-product-photo col-12" src="{{$photo->url}}" style="height: 10em;">
                        <i class="bi bi-x-lg remove-photo-icon"
                            onclick="deleteProductPhoto({{$photo->id}})">
                        </i>
                    </div>
                    @endforeach
                    <div class="col-md-2 col-sm-4 col-6 d-flex justify-content-center align-items-center">
                        <label for="add-photos">
                            <i class="bi bi-plus-circle add-product-photo d-block icon-click" style="width: 100%;"></i>
                        </label>
                    </div>

                </div>
            </div>
        </div>
    @endif
    <div class="row justify-content-center">
        <button type="submit" class="btn btn-primary my-2">Submit</button>
    </div>
</form>
</div>
</div>

<form id="image-form" method="POST" action="{{route("addProductPhoto", ["id" => $product->id])}}" enctype="multipart/form-data">
    @csrf
    <input multiple="true" type="file" name="photos[]" id="add-photos" value="{{old('photos')}}"
        onchange="applyProductPhoto()"/>
</form>

<script defer>
    window.addEventListener("load", () => {
        const selectTarget = document.getElementById("category-replace");
        selectTarget.replaceWith(selectTarget, createSelect({
                id: "category",
                name: "category",
                label: "Select a Category",
                ajax: true,
                delay: 1000,
                url: '/api/category',
                data: (value) => {
                    const query = {
                        name: value,
                    }
                    return query;
                },
                processResults: (data) => {
                    data.forEach((el) => el.text = el.name)
                    return {
                        results: data
                    };
                },
                callback: (item) => {
                    document.getElementById("category-id").value = item.id;
                }
        }));

        @php
            $category = $product->categories->first();
        @endphp

        const category = document.getElementById("category");
        category.value = "{{$category != null ? $category->name : ''}}";
        document.getElementById("category-id").value = "{{$category != null ? $category->id : ''}}";
        category.dispatchEvent(new Event("update"));
    })


    const deleteProductPhoto = (id) => {
            deleteRequest(`/api/products/{{$product->id}}/photo/${id}`)
                .then(() => {
                    document.getElementById(`photo-${id}`).remove();
                }).catch((e) => {
                    reportData("Error removing photo", e.response.data)
                });
        }

        const applyProductPhoto = () => {
            const form = document.getElementById('image-form');
            form.dispatchEvent(new Event("submit"));
        }
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
            let description = document.getElementById('description');
            description.value = editor.getData();
        });
    })

</script>

@endsection
