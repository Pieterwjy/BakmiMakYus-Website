@extends('owner.head')

@section('body')
    @include('owner.partials.navbar')

    <div class="container mt-4">
        @yield('container')
    </div>
@endsection
