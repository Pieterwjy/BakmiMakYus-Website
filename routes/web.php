<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\OwnerAccountController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/scan/{table}', [TableController::class, 'scan'])->name('table.scan');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth','role:owner'])->group(function(){
    Route::get('/owner', function() {
        return view('owner.owner_dashboard',['title' => 'Owner Dashboard'],[OwnerController::class, 'OwnerDashboard']);
    })->name('owner.dashboard');;
    Route::resource('/owner/akun', OwnerAccountController::class)->names('owner.akun');
    
    Route::get('/owner/logout', [OwnerController::class, 'OwnerLogout'])->name('owner.logout');;

    Route::resource('/owner/table', TableController::class)->names('owner.table');
    Route::resource('/owner/product', ProductController::class)->names('owner.product');
});

// Admin Owner Cook

require __DIR__.'/auth.php';
