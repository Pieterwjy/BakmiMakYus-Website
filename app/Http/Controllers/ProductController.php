<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::all();
        return view('owner.product.owner_product')->with('products',$products)->with('title','Menu Owner');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    // Fetch categories from the Product table
    $categories = Product::select('product_category')->distinct()->get();

    // Pass the categories to the view
    return view('owner.product.buat_product', ['categories' => $categories, 'title' => 'Buat Menu']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'product_name' => 'required|max:255',
            'product_price' => 'required',
            'product_description' => 'required',
            'product_category' => 'required',
            'images' => 'mimes:jpeg,png'
        ]);
        if($request->file('images')){
            $validatedData['images'] = $request->file('images')->store('menu-images');
        }
        Product::create($validatedData);
        return redirect()->route('owner.product.index')->with('success', 'Menu berhasil dibuat');
    }

    /**
     * Display the specified resource.
     */
    // public function show(Product $product)
    // {
    //     //
    // }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(String $id)
    {
        return view('owner.product.edit_product', ['product' => Product::where('id',$id)->first()])->with('title','Edit Menu');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'product_name' => 'required|max:255',
            'product_price' => 'required',
            'product_description' => 'required',
            'product_category' => 'required',
            'product_status',
            'images' => 'mimes:jpeg,png',
            'product_stock' => 'required'
        ]);

        if($request->file('images')){
            if($request->oldImage){
                Storage::delete($request->oldImage);
            }
            $validatedData['images'] = $request->file('images')->store('menu-images');
            Log::info('New image stored:', ['path' => $validatedData['images']]);
        }

        Product::where('id',$id)->update($validatedData);
        return redirect()->route('owner.product.index')->with('success', 'Menu berhasil diperbaharui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product = Product::findOrFail($product->id);
        if ($product->images) {
            Storage::delete($product->images);
        }
        $product->delete();
        return redirect()->route('owner.product.index')->with('success', 'Menu berhasil dihapus');
    }

    public function checkStock($id)
    {
        // Fetch the item details from the database
        $item = Product::findOrFail($id); // Assuming your item model is named Item

        return response()->json([
            'status' => 'success',
            'item' => $item
        ]);
    }
}
