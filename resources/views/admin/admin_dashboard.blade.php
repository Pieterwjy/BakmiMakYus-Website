@extends('admin.main')
@section('container')
<div class ='container' style="position: absolute;
top: 40%;">
<div class="jumbotron-fluid">
    <h1 class="display-4">Selamat datang,</h1>
    <p class="lead">{{auth()->user()->name}}</p>
    <hr class="my-4">
    <p>Hak akses : {{auth()->user()->role}}</p>
  </div>
</div>
@endsection