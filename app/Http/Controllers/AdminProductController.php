<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class AdminProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::all();
        return view('admin.product.admin_product')->with('products',$products)->with('title','Menu Admin');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return view('admin.product.edit_product', ['product' => Product::where('id',$id)->first()])->with('title','Edit Menu');
    }
    

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // $validatedData = $request->validate([
        //     'product_name' => 'required|max:255',
        //     'product_price' => 'required',
        //     'product_description' => 'required',
        //     'product_category' => 'required',
        //     'product_status' => 'required',
        //     'images' => 'mimes:jpeg,png'
        // ]);

        $validatedData = $request->validate([
            'product_status' => 'required'
        ]);

        if($request->file('images')){
            if($request->oldImage){
                Storage::delete($request->oldImage);
            }
            $validatedData['images'] = $request->file('images')->store('menu-images');
        }

        // Post::where('id',$id)->update($request->only(['title','body']));
        Product::where('id',$id)->update($validatedData);
 
        return redirect()->route('admin.product.index')->with('success', 'Menu berhasil diperbaharui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
