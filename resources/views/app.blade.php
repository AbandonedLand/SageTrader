<!doctype html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    @livewireStyles
    @yield('head')
    <title>SageTrader</title>
    <link rel="stylesheet" href="/css/adminlte.css">
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
</head>
<body>
    @yield('content')
    @include('demo')
    <script src="/plugins/jquery/jquery.min.js"></script>
    <script src="/js/bootstrap.bundle.js"></script>
    <!-- AdminLTE App -->
    <script src="/js/adminlte.js"></script>
    @livewireScripts

</body>
</html>
