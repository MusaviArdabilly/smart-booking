<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Synapsis Smart Booking</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sb-admin-2.css') }}">
    <link rel="stylesheet" href="{{ asset('css/all.min.css') }}">
    <link href="{{ asset('datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css">
</head>

<body class="{{ request()->is('login*') ? 'login' : '' }}">
    <div id="app">
        <div class="navbar-fixed-top d-flex flex-column flex-md-row align-items-center py-3 px-5 fixed-top">
            <nav class="navbar navbar-default my-0 mr-lg-auto mr-md-auto mr-0 h2">
                <a class="navbar-brand mr-0 text-white" href="{{ url('/') }}"><strong>Smart Booking</strong></a>
            </nav>

            <nav class="navbar my-md-0">
                <div class="nav-link-wrapper">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}">Login</a>
                        @endauth
                    @endif
            </nav>
        </div>
        <main>
            @yield('content')
        </main>
    </div>
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    <!-- Bootstrap core JavaScript-->
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{ asset('js/jquery.easing.min.js') }}"></script>
    <!-- Chart JS -->
    <script src="{{ asset('js/Chart.min.js') }}"></script>
    {{-- Datatable --}}
    <script src="{{ asset('datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('datatables/dataTables.bootstrap4.min.js') }}"></script>
    <!-- Custom scripts for all pages-->
    <script src="{{ asset('js/sb-admin-2.min.js') }}"></script>
    @yield('script')
</body>

</html>
