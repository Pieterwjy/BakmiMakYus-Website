@extends('cook.head')

@section('body')
    @include('cook.partials.navbar')

    <div class="container mt-4">
        @yield('container')
    </div>
@endsection
