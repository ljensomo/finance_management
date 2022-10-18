<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Finance Manager | @yield('title')</title>

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">

    {{-- datatable css --}}
    <link rel="stylesheet" href="{{ asset('css/jquery.dataTables.min.css') }}">
</head>
<body>
    <div id="app">
        @include('navigations.navbar')
        
        <div class="container">
            @yield('content')
        </div>
    </div>

    @include("modals.notification-modals")

    @yield('modals')

    @yield('scripts')

    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>