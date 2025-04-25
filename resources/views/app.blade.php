<!doctype html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    @livewireStyles

    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="h-screen">
<div class="grid grid-cols-auto w-screen h-screen bg-gray-300">
    <div class="col-span-2">
        @include('layout.nav')
    </div>
    <div class="col-span-9 col-start-3 px-8 py-4">
    @yield('content')
    </div>
</div>


@livewireScripts

</body>
</html>
