<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\OwnerAccountController;
use App\Http\Controllers\OwnerTransactionController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminProductController;
use App\Http\Controllers\AdminOrderController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\CookController;
use App\Http\Controllers\PDFController;
use Illuminate\Support\Facades\Route;
use App\Models\Table;

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

//Midtrans Notification Handler
Route::post('/notification-handler', [NotificationController::class, 'handleNotification']);



//Customer Route
Route::get('/', function () {
    // $tables = Table::all();
    $tables = Table::orderBy('table_number', 'asc')->get();
    return view('welcome', ['tables' => $tables]);
})->name('home');
Route::get('/scan/{table}', [TableController::class, 'scan'])->name('table.scan');
Route::post('/scan/{table}/order', [OrderController::class, 'store'])->name('order.store');
Route::get('/checkout/{orderId}', [OrderController::class, 'checkout'])->name('order.checkout');
Route::post('/checkout/cancel', [OrderController::class, 'checkoutCancel'])->name('checkout.cancel');
Route::get('/check-payment-status/{orderId}', [OrderController::class, 'checkPaymentStatus'])->name('check.payment.status');
Route::post('/update-order-status', [OrderController::class, 'updateOrderStatus'])->name('update.order.status');
Route::get('/payment/success', [PaymentController::class, 'success'])->name('payment.success');
Route::post('/check-stock', [OrderController::class, 'checkStock'])->name('check.stock');

//Default Laravel Breeze
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

//Owner Route
Route::middleware(['auth','role:owner'])->group(function(){
    Route::get('/owner', function() {
        return view('owner.owner_dashboard',['title' => 'Owner Dashboard'],[OwnerController::class, 'OwnerDashboard']);
    })->name('owner.dashboard');;
    Route::get('owner/generate-report', [PDFController::class, 'generatePDF'])->name('owner.generate.report');
    Route::get('/owner/logout', [OwnerController::class, 'OwnerLogout'])->name('owner.logout');
    Route::resource('/owner/akun', OwnerAccountController::class)->names('owner.akun');
    Route::resource('/owner/table', TableController::class)->names('owner.table');
    Route::resource('/owner/product', ProductController::class)->names('owner.product');
    Route::resource('/owner/transaction', OwnerTransactionController::class)->names('owner.transaction');
});

//Admin Route
Route::middleware(['auth','role:admin'])->group(function(){
    Route::get('/admin', function() {
        return view('admin.admin_dashboard',['title' => 'Admin Dashboard'],[AdminController::class, 'AdminDashboard']);
    })->name('admin.dashboard');;
    Route::get('/admin/logout', [AdminController::class, 'AdminLogout'])->name('admin.logout');
    Route::put('/admin/order-pay/{id}', [AdminOrderController::class, 'pay'])->name('admin.order.pay');
    Route::put('/admin/order-cancel/{id}', [AdminOrderController::class, 'cancel'])->name('admin.order.cancel');
    Route::put('/admin/order-payredirect/{id}', [AdminOrderController::class, 'payAndRedirect'])->name('admin.order.payandredirect');
    Route::put('/admin/order-complete/{id}', [AdminOrderController::class, 'complete'])->name('admin.order.complete');
    Route::get('/admin/order/fetch', [AdminOrderController::class, 'fetch'])->name('admin.order.fetch');
    Route::get('/admin/payment/success', [PaymentController::class, 'adminSuccess'])->name('admin.payment.success');
    Route::get('/admin/order/history', [AdminOrderController::class, 'history'])->name('admin.order.history');
    Route::get('/admin/order/checkout/{orderId}', [AdminOrderController::class, 'checkout'])->name('admin.order.checkout');
    Route::resource('/admin/order', AdminOrderController::class)->names('admin.order');
    Route::resource('/admin/product', AdminProductController::class)->names('admin.product');
});
//Cook Route
Route::middleware(['auth','role:cook'])->group(function(){
    Route::get('/cook', function() {
        return view('cook.cook_dashboard',['title' => 'Cook Dashboard'],[CookController::class, 'CookDashboard']);
    })->name('cook.dashboard');;
    Route::get('/cook/fetch', [CookController::class, 'fetch'])->name('cook.order.fetch');
    Route::get('/cook/logout', [CookController::class, 'CookLogout'])->name('cook.logout');;
});


require __DIR__.'/auth.php';
