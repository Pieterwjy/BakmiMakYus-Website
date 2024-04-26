<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class OwnerAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $accounts = User::all();
        return view('owner.akun.owner_akun')->with('accounts',$accounts)->with('title','Akun Owner');
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('owner.akun.buat_akun')->with('title','Buat Akun');
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'phone' => ['regex:/^(0\d{9,13}|\+\d{1,3}\s?\d{9,13})$/'],
            'role' => 'required|in:owner,admin,cook', 
        ]);
        User::create($validatedData);
 
        return redirect()->route('owner.akun.index')->with('success', 'Akun berhasil dibuat');
    }


    /**
     * Display the specified resource.
     */

    public function show(String $id)
    {
        // $account = Account::findOrFail($account->id);
        
        return view('owner.akun.show_akun', ['account' => User::where('id',$id)->first()])->with('title','Lihat Akun');
    }
    
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(String $id)
    {
        // $account = Account::findOrFail($account->id);
 
        return view('owner.akun.edit_akun', ['account' => User::where('id',$id)->first()])->with('title','Edit Akun');
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,$id)
    {
        $user = Auth::user();
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($user->id, 'id')->ignore($id, 'id'),
            ],
            'phone' => ['regex:/^(0\d{9,13}|\+\d{1,3}\s?\d{9,13})$/'],
            'role' => 'required|in:owner,admin,cook', 
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        User::where('id',$id)->update($request->only(['name','email','phone','role']));
        return redirect()->route('owner.akun.index')->with('success', 'Akun Berhasil Terupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $akun)
    {
        $account = User::findOrFail($akun->id);
        
        $account->delete();
 
        // return redirect()->route('product.index')->with('success', 'product deleted successfully');
        return redirect()->route('owner.akun.index')->with('success', 'Akun berhasil dihapus');
    }

}
