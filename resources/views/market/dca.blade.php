@extends('adminlte::page')


@section('content_header')
    <div class="card">
        <div class="card-body">
            <h2 class="text-center">Dollar Cost Averaging <span class="text-muted text-sm">Powered by dexie.space</span></h2>
        </div>
    </div>
@endsection
@section('content')
    @livewire('dca')
@endsection
