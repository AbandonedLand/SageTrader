<?php

use Livewire\Volt\Volt;

Volt::route('/', 'home');
Volt::route('/setup','setup');
Volt::route('/setup/synctibetswap','setup.synctibetswap');
Volt::route('/fingerprints','wallet.fingerprints');
Volt::route('/bots/dca','bots.dca');
Volt::route('/bots/dca/create','bots.dca.create');
Volt::route('/bots/dca/{id}','bots.dca.show');
Volt::route('/bots/grid','bots.grid');
Volt::route('/wallet/offers','wallet.offers');
Volt::route('/wallet/offer/{id}','wallet.offer');
