@extends('layouts.app')

@section('title', 'Orders Dashboard')

@section('content')

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
            'variantCheck' : 'Variations',
            'originVariantID' : 'Variant ID',
            'colorVariant' : 'Variant Color',
            'stock' : 'Stock',
            'description' : 'Description',
            'price' : 'Price',
            'photos' : 'Photos',
            'category-id': 'Category'
        });
    })();
</script>
@endif

<div class="container">


<form class="container d-flex flex-column" id="add-product-form" autocomplete="off"  class="container form"  enctype="multipart/form-data" method="POST" action="{{route('addProduct')}}">
    @csrf
    <div class="row">
        <div class="form-group mb-3 col-md-6">
            <label for="name" class="form-label">Product Name</label>
            <input id="name" class="form-control" type="text" name="name" value="{{old('name')}}">
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
            <input id="stock" type="number" value="{{old('stock')}}" min="0" class="form-control" name="stock" autocomplete="stock">
            @error('stock')
            <span class="error form-text text-danger">
                {{$message}}
            </span>
            @enderror
        </div>
        <div class="form-group col-md-6">
            <label for="price" class="form-label">Price</label>
            <input required id="price" class="form-control" type="number" step="0.01" min="0" value="{{old('price')}}" name="price">
            @error('price')
            <span class="error form-text text-danger">
                {{$message}}
            </span>
            @enderror
        </div>
    </div>

    <div class="row mt-3">
        <div class="form-group col-12">
            <label for="photos" class="form-label">Product Photos</label>
            <input required multiple="true" id="photos" class="form-control" type="file" name="photos[]" value="{{old('photos')}}">
            @if($errors->has('photos'))
            <span class="error form-text text-danger">
                @foreach ($errors->get('photos') as $message)
                    {{$message}}<br/>
                @endforeach
            </span>
            @enderror
        </div>
    </div>

    <div class="row mt-3">
        <div class="form-group col-12">
            <label for="description" class="form-label">Description</label>
            <textarea id="description" class="form-control" name="description">{{old('description')}}</textarea>
            @error('description')
            <span class="error form-text text-danger">
                {{$message}}
            </span>
            @enderror
        </div>
    </div>
    <div class="row mt-2">
        <div class="d-flex align-items-center mb-3">
            <label for="variantCheck" class="form-label">Is variant?</label>
            <input value="" id="variantCheck" name="variantCheck" class="form-check-input mx-2" type="checkbox" data-bs-toggle="collapse" data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample" autocomplete="variantCheck">
        </div>
        <div class="row collapse my-3" id="collapseExample">
            <div class="col-12">
                <div class="alert alert-primary text-center" role="alert">
                    Select a color. If this product is the first of its variants, don't select any product ID.
                  </div>
            </div>
            <div class="col-md-6">
                <label for="originVariantID" class="mb-3 form-label">Variant of (Product ID): </label>
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
    </div>

    <button type="submit" class="btn btn-primary my-2" onclick="this.form.submit()">Submit</button>
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
            let description = document.getElementById('description');
            description.value = editor.getData();
        });

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
    })

</script>


@endsection

