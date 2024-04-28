<?php

namespace App\Http\Controllers;

use App\Models\Table;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Requests\StoreTableRequest;
use App\Http\Requests\UpdateTableRequest;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
class TableController extends Controller
{

    public function scan($tableNumber)
    {
        $table = Table::where('table_number', $tableNumber)->first();
        // $table = Table::find($tableNumber);
    
        if (! $table) {
            // Redirect back with an error message if the table is not found
            return redirect()->back()->with('error', 'Table not found');
        }
    
        $menu = Product::all();
    
        return view('table.scan', compact('table', 'menu'));
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tables = Table::all();
        return view('owner.table.owner_meja')->with('tables',$tables)->with('title','Dashboard Meja');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Retrieve the maximum table number
    $lastTableNumber = Table::max('table_number');

    // Increment the last table number by 1 to get the next available table number
    $nextTableNumber = $lastTableNumber + 1;

        return view('owner.table.buat_meja',compact('nextTableNumber'))->with('title','Buat Meja');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $table_number = $request->table_number;
        $qrCodePath = 'qr_codes/table_' . $table_number . '.png';

        $request->merge(['table_qr' => $qrCodePath]);
        $validatedData = $request->validate([
            'table_number' => 'required|unique:tables',
            'table_capacity' => 'required',
            'table_qr' => 'required'
        ]);
        Table::create($validatedData);
        QrCode::format('png')->size(200)->generate(route('table.scan', $request->table_number), public_path($qrCodePath));
        
        return redirect()->route('owner.table.index')->with('success', 'Meja berhasil dibuat');

    }

    /**
     * Display the specified resource.
     */
    public function show(Table $table)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(String $id)
    {
        
        return view('owner.table.edit_meja', ['table' => Table::where('id',$id)->first()])->with('title','Edit Meja');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $table = Table::where('id',$id)->first();

        $table_number = $request->table_number;
        $qrCodePath = 'qr_codes/table_' . $table_number . '.png';

        $request->merge(['table_qr' => $qrCodePath]);
        if($table->table_number == $request->table_number) {
            $validatedData = $request->validate([
                'table_number' => 'required|max:255',
                'table_capacity' => 'required',
                'table_qr' => 'required'
            ]);
        } else {
            $validatedData = $request->validate([
                'table_number' => 'required|max:255|unique:tables,table_number',
                'table_capacity' => 'required',
                'table_qr' => 'required'
            ]);
        }
        // $validatedData = $request->validate([
        //     'table_number' => 'required|max:255|unique:tables,table_number'.$table->table_number,
        //     'table_capacity' => 'required',
        //     'table_qr' => 'required'
        // ]);

        // $validatedData = $request->validate([
        //     'table_number' => 'required|max:255',
        //     'table_capacity' => 'required',
        //     'table_qr' => 'required'
        // ]);

        // if($request->file('image')){
        //     if($request->oldImage){
        //         Storage::delete($request->oldImage);
        //     }
        //     $validatedData['image'] = $request->file('image')->store('post-images');
        // }

        Table::where('id',$id)->update($validatedData);

         // Generate the QR code for the updated table
    
    QrCode::format('png')->size(200)->generate(route('table.scan', $request->table_number), public_path($qrCodePath));
 
        return redirect()->route('owner.table.index')->with('success', 'Meja berhasil diperbaharui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Table $table)
    {
        $table = Table::findOrFail($table->id);
        if ($table->table_qr) {
            $filePath = public_path($table->table_qr);
            if (File::exists($filePath)) {
                File::delete($filePath);
            }
        }
        $table->delete();
 
        // return redirect()->route('product.index')->with('success', 'product deleted successfully');
        return redirect()->route('owner.table.index')->with('success', 'Meja berhasil dihapus');
    }
}
