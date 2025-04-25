<ul class="menu bg-neutral w-56 h-full text-base-100 text-2xl">
    <li>
        <x-nav-link href="/dashboard">Dashboard</x-nav-link>
    </li>
    <li>
        <x-nav-link href="/trade">Trade</x-nav-link>
    </li>
    <li>
        <x-nav-link href="/bots">Bots</x-nav-link>
    </li>
    <li>
        <x-nav-link href="/liquidity">Liquidity</x-nav-link>
    </li>

    <li class="flex absolute bottom-2 text-center">
        <a href="/logout">FP: {{\App\ChiaWallet::getFingerprint()}}</a>
    </li>
</ul>
