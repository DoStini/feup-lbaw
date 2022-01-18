@extends('layouts.app')

@section('title', 'Products')

@section('content')

<form id="search-form" class="h-100">
    <div class="container h-100 w-100 mw-100 ps-5 pe-5 mt-4 mb-4">
        <div class="col">
            <div class="row container mw-100 h-100 mb-4">
                @include('partials.search.productfilters')
            </div>
            <div class="row container mw-100 h-100">
                <div id="search-area">
                    <div class="container w-100 pe-0">
                        <div id="products-area" class="row justify-content-start">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>


<script>
    const isShopper = "{{ (Auth::check() && !Auth::user()->is_admin) }}";
</script>

@endsection
