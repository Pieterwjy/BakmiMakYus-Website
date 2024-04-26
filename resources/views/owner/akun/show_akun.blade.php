@extends('owner.main')
@section('container')

<h1 class="mb-0">Lihat Akun</h1>
    <hr />
        <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1">Name</span>
            <input type="text" name="name"  class="form-control" placeholder="Nama" aria-label="Name" aria-describedby="basic-addon1" value="{{ $account->name}}"disabled readonly>
        </div>
        <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1">Email</span>
            <input type="email" name="email" class="form-control" placeholder="Email" aria-label="Email" aria-describedby="basic-addon1" value="{{ $account->email}}"disabled readonly>
        </div>
        <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1">Phone</span>
            <input type="tel" name="phone" class="form-control" placeholder="Phone" aria-label="Phone" aria-describedby="basic-addon1"value="{{ $account->phone}}"disabled readonly>
        </div>
            <div class="input-group mb-3">
                <label class="input-group-text" for="inputGroupRole">Hak Akses</label>
                <select class="form-select" name="role" id="inputGroupRole" disabled readonly>
                <option value="{{ $account->role}}">{{ $account->role}}</option>  
                  <option value="admin">Admin</option>
                  <option value="cook">Cook</option>
                </select>
            </div>
            {{-- <div class="input-group mb-3">
                <span class="input-group-text" id="basic-addon1">Password</span>
                <input type="password" name="password" class="form-control" placeholder="password" aria-label="Password" aria-describedby="basic-addon1"value="{{ $account->password}}disabled readonly">
            </div> --}}
@endsection