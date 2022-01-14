@extends('layouts.app')

@section('title', 'Orders Dashboard')

@section('content')

@include('partials.errormodal')

<div class="container">
@include('partials.links.dashboardLinks', ['page' => 'orderDashboard'])

<script type="text/javascript" defer>
    function send(event) {
        const formData = new FormData(document.getElementById('add-product-form'));
        clearErrors();

        formDataPost("/admin/products/create",formData)
        .then((response) => {
            console.log(response);
            reportData("Product Added Successfully!");
        })
        .catch((error) => {
            if(error.response) {
                if(error.response.data) {
                    reportData("There was an error creating a product", error.response.data["errors"], {
                        'name' : 'Product Name',
                        'attributes' : 'Variations',
                        'stock' : 'Stock',
                        'description' : 'Description',
                        'price' : 'Price',
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

<div class="container">


<form class="container d-flex flex-column" id="add-product-form" autocomplete="off" onsubmit="return send(event);">
    <div class="row">
        <div class="form-group col-md-12">
            <label for="name">Product Name</label>
            <input required id="name" class="form-control" type="text" name="name" value="">
            <span class="error form-text text-danger" id="name-error"></span>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-6">
            <label for="stock">Stock</label>
            <input id="stock" class="form-control" name="stock" autocomplete="stock">
            <span class="error form-text text-danger" id="stock-error"></span>
        </div>
        <div class="form-group col-md-6">
            <label for="price">Price</label>
            <input id="price" class="form-control" name="price">
            <span class="error form-text text-danger" id="price-error"></span>
        </div>
    </div>

    <div class="mb-3">
        <label for="profile-picture">Product Photos</label>
        <input multiple="true" id="profile-picture" class="form-control" type="file" name="profile-picture">
        <span class="error form-text text-danger" id="profile-picture-error"></span>
    </div>

    <div class="mb-3">
        <label for="about-me">Description</label>
        <textarea id="about-me" class="form-control" name="about-me" value=""></textarea>
        <span class="error form-text text-danger" id="about_me-error"></span>
    </div>

    <button type="submit" class="btn btn-primary">Submit</button>
</form>
</div>
</div>
@endsection
