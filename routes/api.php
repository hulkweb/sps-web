<?php

use App\Http\Controllers\Api\{UserController,AuthController,CartController,CategoryController,FavoritesController,OrderController,SubCategoryController,ProductController};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)->group(function () {
        Route::post('/login', 'login');
        Route::get('/home_api', 'home_api');
        Route::post('/register', 'register');
        Route::post('/verify-otp', 'verifyOtp');
        Route::post('/change-password', 'changePassword');
        Route::post('/reset-password', 'resetPassword');
});


Route::controller(UserController::class)->group(function () {
    Route::get('get_state', 'getState');
    Route::get('get_city/{id}', 'getCity');

});

Route::middleware(['auth:sanctum'])->group(function () {

    Route::controller(ProductController::class)->group(function () {
        Route::get('product_detail','productDetail');
        Route::get('get_category','getCategory');
        Route::get('get_subcategory','getSubCategory');
        Route::get('get_product','getProduct');
    });


    Route::controller(UserController::class)->group(function () {
        Route::get('/users', 'users');
        Route::post('/user/add', 'userAdd');
        Route::post('/update/status/assignorder', 'updateStatusOrder');
        Route::get('/get/assignorder', 'assignOrder');
        Route::get('transaction', 'transaction');
    });

    Route::controller(AuthController::class)->group(function () {
        Route::get('get-user-notification',  'notification');
        Route::post('user-notification-read',  'notificationRead');

        Route::get('/user_detail', 'userDetail');
        Route::post('/update_profile', 'updateProfile');
        Route::get('/logout', 'logout');
        Route::get('/user/address', 'userAddress');
        Route::post('/add/address', 'createShippingAddress');
        Route::post('/delete/address', 'deleteShippingAddress');
        Route::post('/default/address', 'makeShippingAddressDefault');
    });

    Route::controller(CartController::class)->group(function () {
        Route::get('/cart', 'cartList');
        Route::get('/cart/summary', 'cartSummary');
        Route::get('/charge', 'chargeList');
        Route::post('/addcart', 'addCart');
        Route::post('/removecart', 'removeCart');
        Route::post('/change/quantity', 'changeQuantity');
    });

    Route::controller(FavoritesController::class)->group(function () {
        Route::get('/favorite', 'favoriteList');
        Route::post('/addfavorite', 'addFavorite');
    });

    Route::controller(OrderController::class)->group(function () {
        Route::get('/order', 'orderList');
        Route::get('/seller_order', 'orderSellerList');
        Route::get('/driver_order', 'orderDriverList');
        Route::get('/order_detail', 'orderItemList');
        Route::get('/order_by_status', 'orderByStatus');
        Route::get('/order_by_image', 'orderByImage');
        Route::get('/order_by_payment', 'orderByPayment');
        Route::post('/payment/update', 'paymentUpdate');
        Route::post('/order/place', 'orderPlace');
        Route::post('/delivery/status', 'deliveryStatus');
        Route::post('/delivery/image', 'deliveryImage');
    });
});





