<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title.' - '.config('app.name') : config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen font-sans antialiased bg-base-200">

    {{-- NAVBAR mobile only --}}
    <x-nav sticky class="lg:hidden">
        <x-slot:brand>
            <x-app-brand />
        </x-slot:brand>
        <x-slot:actions>
            <label for="main-drawer" class="lg:hidden me-3">
                <x-icon name="o-bars-3" class="cursor-pointer" />
            </label>
        </x-slot:actions>
    </x-nav>

    {{-- MAIN --}}
    <x-main>
        {{-- SIDEBAR --}}
        <x-slot:sidebar drawer="main-drawer" collapsible class="bg-base-100 lg:bg-inherit">

            {{-- BRAND --}}
            <x-app-brand class="px-5 pt-4" />

            {{-- MENU --}}
            <x-menu activate-by-route>

                {{-- User --}}


                <x-menu-item title="Dashboard" icon="o-squares-2x2" link="/" />

                <x-menu-sub title="Bots" icon="o-cpu-chip">

                    <x-menu-item title="Archives" icon="o-archive-box" link="####" />
                </x-menu-sub>


                <x-menu-sub title="Settings" icon="o-cog-6-tooth" link="/bob">
                    <x-menu-item title="Connect Sage" icon="o-cog-6-tooth" link="/setup" />

                    <x-menu-sub title="Fingerprints" icon="o-finger-print" >
                        <x-menu-item title="All" link="/fingerprint" />
                        @foreach(\App\Models\Fingerprint::all() as $fp)

                        @if($fp->is_authorized)
                            <x-menu-item title="{{ $fp->name }}" icon-classes="text-green-500" icon="o-finger-print" link="/fingerprint/{{ $fp->id }}" />
                        @endif
                    @endforeach
                    </x-menu-sub>
                </x-menu-sub>
            </x-menu>
        </x-slot:sidebar>

        {{-- The `$slot` goes here --}}
        <x-slot:content>
            {{ $slot }}
        </x-slot:content>
    </x-main>

    {{--  TOAST area --}}
    <x-toast />
</body>
</html>
