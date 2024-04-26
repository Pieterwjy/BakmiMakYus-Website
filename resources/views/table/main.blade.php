@extends('table.head')

@section('body')
    @include('table.navbar')

    <div class="container mt-4">
        @yield('container')
    </div>
@endsection
