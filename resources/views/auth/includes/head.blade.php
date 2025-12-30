<head>

    <meta charset="utf-8" />
    {{--  <title>Sign In | Velzon - Admin & Dashboard Template</title>  --}}
    <title>{{ env('APP_NAME') }} | @yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{--  <meta content="WealthcubeTech.com has been specially set up for Wealthcube Team and its exclusive partners."
        name="description" />  --}}
    <meta content="Themesbrand" name="author" />
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('assets/images/logo.png') }}">

    <!-- Layout config Js -->
    <script src="{{ asset('assets/js/layout.js') }}"></script>
    <!-- Bootstrap Css -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- custom Css-->
    <link href="{{ asset('assets/css/custom.min.css') }}" rel="stylesheet" type="text/css" />


</head>
