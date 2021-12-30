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
// Home
Route::get('/', 'StaticPagesController@home')->name('home');

// Users
Route::get('users', 'UserController@getAuth')->name('getUsersPage');
Route::get('users/cart', 'CartController@show')->name('getCart');
Route::get('users/orders', 'ShopperController@getOrders')->name('getOrders');
Route::get('users/{id}', 'UserController@showProfile')->name('getUser');
Route::get('users/{id}/private', 'UserController@getEditPage')->name('editPage');

//Administration
Route::get('admin', 'AdminController@getDashboard')->name('getDashboard');

// Products
Route::get('products', 'ProductController@search')->name('getProductSearch');
Route::get('products/{id}', 'ProductController@show')->name('getProduct');

// Orders
Route::get('orders/{id}', 'OrderController@show')->name('orders');

// Authentication
Route::get('join', 'Auth\JoinController@show')->name('join')->middleware('guest');
Route::post('login', 'Auth\LoginController@login')->name('login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');
Route::post('register', 'Auth\RegisterController@register')->name('register');
