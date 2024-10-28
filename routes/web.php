<?php

use App\Http\Controllers\backend\{VariationValueController,VariationTypeController,SizeController,CustomerController,StaffController,RoleController,PermissionController,SellerController,DriverController,SettingController,AuthController,ChargeController,CategoryController,OrderController,ProductController,SubCategoryController};
use Illuminate\Support\Facades\Route;


Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
Route::get('/privacy-policy', [AuthController::class, 'privacy'])->name('privacy');
Route::get('/term-condition', [AuthController::class, 'term'])->name('term');
Route::post('/login', [AuthController::class, 'login'])->name('postlogin');
Route::middleware('auth')->group(function () {
    // Logout route
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    // Dashboard route
    Route::get('dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
    Route::post('profile/update', [AuthController::class, 'updateProfile'])->name('updateProfile');
    Route::get('profile', [AuthController::class, 'userProfile'])->name('userProfile');
    Route::resource('driver', DriverController::class);
    Route::resource('customer', CustomerController::class);
    Route::resource('permission', PermissionController::class);
    Route::resource('role', RoleController::class);
    Route::resource('staff', StaffController::class);
    Route::post('toggle', [StaffController::class, 'toggle'])->name('toggle');
    Route::resource('seller', SellerController::class);
    Route::resource('category', CategoryController::class);
    Route::resource('subcategory', SubCategoryController::class);
    Route::resource('charge', ChargeController::class);
    Route::resource('variation_type', VariationTypeController::class);
    Route::resource('variation_value', VariationValueController::class);
    Route::resource('product', ProductController::class);
    Route::get('destroy/image/{id}/{multi_image}', [ProductController::class, 'destroyImage'])->name('product.destroy_image');
    Route::get('variation/destroy/image/{id}/{multi_image}', [ProductController::class, 'variationdestroyImage'])->name('variation.destroy_image');
    Route::get('get_subcat', [ProductController::class,'getSubcategoriesByCategory'])->name('get_subcat');
    Route::get('get_value', [ProductController::class,'getValuesByType'])->name('get_value');
    Route::resource('setting', SettingController::class);
    Route::resource('orders', OrderController::class);
    Route::post('/update-order-status', [OrderController::class, 'updateOrderStatus'])->name('orders.updateStatus');
    Route::post('/update-order', [OrderController::class, 'updateOrder'])->name('orders.update.date');
    Route::post('/assign-order-driver', [OrderController::class, 'assignDriver'])->name('orders.assignDriver');
    Route::get('/payment-detail', [OrderController::class, 'paymentDetail'])->name('order.payment_detail');
    Route::get('/transaction', [OrderController::class, 'transaction'])->name('transaction');
   });


