@extends('adminlte::page')


@section('content_header')
    <h2>Chia Fingerprints</h2>
@endsection
@section('content')
    <div class="card">


        <table class="table">
            <thead>
            <tr>
                <th scope="col">Fingerprint</th>
                <th scope="col">Name</th>
                <th scope="col"></th>
            </tr>
            </thead>
            <tbody>
            @foreach($keys as $key)
                <tr>
                    <td>{{$key->fingerprint}}</td>
                    <td>{{$key->name}}</td>
                    <td>
                        <a href="/login/{{$key->fingerprint}}" class="btn btn-success">Login</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>


</div>


@endsection
