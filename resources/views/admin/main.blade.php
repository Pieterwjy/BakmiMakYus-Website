@extends('admin.head')

@section('body')
    @include('admin.partials.navbar')

    <div class="container mt-4">
        @yield('container')
    </div>
@endsection
