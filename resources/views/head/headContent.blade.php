<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'reFurniture') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Serif+Text:ital@0;1&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Alata&display=swap" rel="stylesheet">
    <link href="{{ asset('css/product.css') }}" rel="stylesheet">



    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/utility.css') }}" rel="stylesheet">
    <link href="{{ asset('css/notifications.css') }}" rel="stylesheet">
    <link href="{{ asset('css/tabs.css') }}" rel="stylesheet">
    <link href="{{ asset('css/login.css') }}" rel="stylesheet">
    <link href="{{ asset('css/checkout.css') }}" rel="stylesheet">
    <link href="{{ asset('css/number.selector.css') }}" rel="stylesheet">
    <link href="{{ asset('css/select.css') }}" rel="stylesheet">
    <link href="{{ asset('css/cart.css') }}" rel="stylesheet">
    <link href="{{ asset('css/address.css') }}" rel="stylesheet">
    <link href="{{ asset('css/product.css') }}" rel="stylesheet">
    <link href="{{ asset('css/auxiliar.css') }}" rel="stylesheet">
    <script src="https://kit.fontawesome.com/1c937c97ed.js" crossorigin="anonymous"></script>
    <script type="text/javascript">
        // Fix for Firefox autofocus CSS bug
        // See: http://stackoverflow.com/questions/18943276/html-5-autofocus-messes-up-css-loading/18945951#18945951
    </script>
    <script>
        window.__theme = 'bs5';
    </script>
    <script type="text/javascript" src={{ asset('js/app.js') }} defer></script>
    <script type="text/javascript" src="https://js.pusher.com/7.0/pusher.min.js" defer></script>
    <script type="text/javascript" src={{ asset('js/lib/uniqueCheckbox.js') }} defer></script>
    <script type="text/javascript" src={{ asset('js/lib/select.js') }} defer></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js" defer></script>
    <link rel="stylesheet" href="{{ asset('css/select2-bootstrap-5-theme.min.css') }}">

    <script type="text/javascript" src={{ asset('js/ajax.js') }} defer></script>
    <script type="text/javascript" src={{ asset('js/notifications.js') }} defer></script>
    <script type="text/javascript" src={{ asset('js/axios.js') }} defer></script>
    <script type="text/javascript" src={{ asset('js/numberSelector.js') }} defer></script>
    <script type="text/javascript" src={{ asset('js/cart.js') }} defer></script>
    <script type="text/javascript" src={{ asset('js/search.js') }} defer></script>
    <script type="text/javascript" src={{ asset('js/userSearch.js') }} defer></script>
    <script type="text/javascript" src={{ asset('js/address.js') }} defer></script>
    <script type="text/javascript" src={{ asset('js/addProduct.js') }} defer></script>
</head>
