<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
// Static Pages
Route::get('/', 'StaticPagesController@home')->name('home');
Route::get('blocked', 'StaticPagesController@blocked')->name('blocked');
Route::get('contact-us', 'StaticPagesController@contactUs')->name('contact-us');

// Users
Route::middleware(['auth', 'is.shopper', 'is.blocked'])->post('users/checkout', 'CartController@checkout')->name('checkout');
Route::middleware(['auth', 'is.shopper', 'is.blocked'])->get('users/checkout', 'CartController@checkoutPage')->name('checkout-page');
Route::middleware(['auth', 'is.blocked'])->get('users', 'UserController@getAuthProfile')->name('getUsersPage');
Route::middleware(['auth', 'is.blocked'])->get('users/cart', 'CartController@show')->name('getCart');
Route::middleware(['auth', 'is.blocked'])->get('users/wishlist', 'WishlistController@redirect')->name('getWishlistPage');
Route::middleware(['auth', 'is.blocked'])->get('users/orders', 'ShopperController@getOrders')->name('getOrders');
Route::middleware(['auth', 'is.blocked'])->get('users/{id}', 'UserController@showProfile')->name('getUser');
Route::middleware(['auth', 'is.blocked'])->get('users/{id}/wishlist', 'WishlistController@show')->name('getWishlist');
Route::middleware(['auth', 'is.blocked'])->get('users/{id}/private', 'UserController@getEditPage')->name('editPage');
Route::middleware(['auth', 'is.blocked'])->get('users/{id}/private/addresses', 'ShopperController@getAddresses')->name('addresses');
Route::middleware(['auth', 'is.blocked'])->post('users/{id}/delete', 'UserController@delete')->name('deleteAccount');

//Administration
Route::get('admin/products/create', 'ProductController@getAddProductPage')->name('addProductPage');
Route::post('admin/products/create', 'ProductController@addProduct')->name('addProduct');
Route::get('admin/products/{id}/edit', 'ProductController@getEditProductPage')->name('editProductPage');
Route::post('admin/products/{id}/edit', 'ProductController@editProduct')->name('editProduct');
Route::get('admin', 'AdminController@getDashboard')->name('getDashboard');
Route::get('admin/orders', 'AdminController@getOrderDashboard')->name('getOrderDashboard');
Route::get('admin/products', 'AdminController@getProductDashboard')->name('getProductDashboard');
Route::get('admin/users', 'AdminController@getUserDashboard')->name('getUserDashboard');
Route::get('admin/coupon', 'AdminController@getCouponDashboard')->name('getCouponDashboard');
Route::get('admin/create', 'AdminController@getNewAdminPage')->name('getNewAdminPage');
Route::post('admin/create', 'AdminController@registerAdmin')->name('registerAdmin');
Route::get('admin/coupon/create', 'AdminController@getNewCouponPage')->name('getCreateCouponPage');
Route::post('admin/coupon/create', 'CouponController@create')->name('createCoupon');

// Products
Route::get('products', 'ProductController@search')->name('getProductSearch');
Route::get('products/{id}', 'ProductController@show')->name('getProduct');

Route::post('products/{product_id}/addReview', 'ReviewController@addReview')->name('addReview');

// Orders
Route::get('orders/{id}', 'OrderController@show')->name('orders');
Route::get('orders/{id}/paypal/create', 'PaypalController@createTransaction')->name('createTransaction');
Route::get('orders/{id}/paypal/finish', 'PaypalController@finishTransaction')->name('finishTransaction');

// Authentication
Route::get('join', 'Auth\JoinController@show')->name('join')->middleware('guest');
Route::post('login', 'Auth\LoginController@login')->name('login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');
Route::post('register', 'Auth\RegisterController@register')->name('register');
Route::get('recover/', 'Auth\RecoverAccountController@showEmailForm')->name('recoverPage');
Route::get('recover/new', 'Auth\RecoverAccountController@getFinishRecoverPage')->name('newPasswordPage');
Route::post('recover/new', 'Auth\RecoverAccountController@finishRecoverRequest')->name('newPassword');
Route::get('test', 'Auth\RecoverAccountController@testFunction')->name('asd');
