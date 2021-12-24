@extends('layouts.app')

@section('title', 'Products')

@section('content')
<form id="search-form" class="h-100">
    <div class="container h-100 w-100 mw-100 ps-5 pe-5 mt-4 mb-4">
        <div class="col">
            <div class="row align-items-start mb-5">
                <div class="input-group round">
                    <span class="input-group-text" id="search-icon">
                        <i class="bi bi-search"></i>
                    </span>
                    <input name="text" id="search-bar" type="text" class="form-control"
                        placeholder="What are you looking for?" aria-label="Search" aria-describedby="search-icon">
                </div>
            </div>

            <div class="row container mw-100 h-100">
                <div class="col-md-3 col-xs-12">
                    @include('partials.search.productfilters')
                </div>
                <div id="search-area" class="col-md-9 col-xs-12">
                    <div class="container w-100 pe-0">
                        <div id="products-area" class="row justify-content-start">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

@endsection

@section('customJsFile')
<script type="text/javascript" src={{ asset('js/search.js') }} defer></script>
@endsection