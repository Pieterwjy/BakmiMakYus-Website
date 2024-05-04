@extends('owner.main')
@section('container')
<h1 class="mb-0">Edit Akun</h1>
@if($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
    <hr />
    <form action="{{ route('owner.akun.update',$account->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1">Nama</span>
            <input type="text" name="name"  class="form-control" placeholder="Nama" aria-label="Name" aria-describedby="basic-addon1" value="{{ $account->name}}" required>
        </div>
        <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1">Email</span>
            <input type="email" name="email" class="form-control" placeholder="Email" aria-label="Email" aria-describedby="basic-addon1" value="{{ $account->email}}" required>
        </div>
        <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1">No. Telp</span>
            <input type="tel" name="phone" class="form-control" placeholder="Phone" aria-label="Phone" aria-describedby="basic-addon1"value="{{ $account->phone}}" required>
        </div>
            <div class="input-group mb-3">
                @if($account->role == 'admin')
                <label class="input-group-text" for="inputGroupRole">Hak Akses</label>
                <select class="form-select" name="role" id="inputGroupRole">
                    <option value="{{ $account->role}}">{{ $account->role}}</option>
                    {{-- <option value="admin">Admin</option> --}}
                    <option value="owner">Owner</option>
                    <option value="cook">Cook</option>
                </select>
                
                @elseif($account->role == 'owner')
                <label class="input-group-text" for="inputGroupRole">Hak Akses</label>
                <select class="form-select" name="role" id="inputGroupRole" >
                    <option value="{{ $account->role}}">{{ $account->role}}</option>
                    {{-- <option value="admin">Admin</option> --}}
                    {{-- <option value="admin">Admin</option>
                    <option value="cook">Cook</option> --}}
                </select>
                @else
                <label class="input-group-text" for="inputGroupRole">Hak Akses</label>
                <select class="form-select" name="role" id="inputGroupRole">
                    <option value="{{ $account->role}}">{{ $account->role}}</option>
                    {{-- <option value="admin">Admin</option> --}}
                    <option value="admin">Admin</option>
                    <option value="cook">Cook</option>
                </select>
                @endif
            </div>
            <div class="row">
                <div class="d-grid">
                    <button class="btn btn-primary">Ubah</button>
                </div>
            </div>
        </div>
    </form>
    @if(auth()->user()->id == $account->id)
    <br>
    <div class="container">
    <h1 class="mb-0">Ubah Kata Sandi</h1>
    <hr />
    <form method="post" action="{{ route('password.update') }}">
        @csrf
        @method('put')
        <div class="row">
        <div class="input-group mb-3">
            <label class="input-group-text" for="inputGroupRole">Kata Sandi Saat Ini</label>
            {{-- <label for="current_password" class="form-label">{{ __('Current Password') }}</label> --}}
            <input id="current_password" name="current_password" type="password" class="form-control" autocomplete="current-password"/>
        </div>
        </div>
        <div class="row">
            <x-input-error :messages="$errors->updatePassword->get('current_password')"/>
            </div>
        <div class="row">
        <div class="input-group mb-3">
            <label class="input-group-text" for="inputGroupRole">Kata Sandi Baru</label>
            {{-- <label for="password" class="form-label">{{ __('New Password') }}</label> --}}
            <input id="password" name="password" type="password" class="form-control" autocomplete="new-password" />
            
               
        </div>
        </div>
        <div class="row">
            <x-input-error :messages="$errors->updatePassword->get('password')" />
        </div>
        <div class="row">
        <div class="input-group mb-3">
            <label class="input-group-text" for="inputGroupRole">Konfirmasi Kata Sandi Baru</label>
            {{-- <label for="password_confirmation" class="form-label">{{ __('Confirm Password') }}</label> --}}
            <input id="password_confirmation" name="password_confirmation" type="password" class="form-control" autocomplete="new-password" />            
        </div>
        </div>
            <div class="row">
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')"/>
        </div>
        <div class="row">
            <div class="d-grid">
            <button class="btn btn-primary">Ganti Kata Sandi</button>
            
            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Kata Sandi Berhasil Dirubah.') }}</p>
            @endif
            </div>
        </div>
    </form>
    <br>
    <br>
    <br>
    <br>
    </div>
    @endif
@endsection