@extends('adminlte::page')


@section('content_header')

@endsection
@section('content')

<livewire:orders :orders="$orders"/>

@endsection
