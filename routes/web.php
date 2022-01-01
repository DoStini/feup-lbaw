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
Route::middleware('auth')->get('users', 'UserController@getAuth')->name('getUsersPage');
Route::middleware('auth')->get('users/cart', 'CartController@show')->name('getCart');
Route::middleware('auth')->get('users/orders', 'ShopperController@getOrders')->name('getOrders');
Route::middleware('auth')->get('users/{id}', 'UserController@showProfile')->name('getUser');
Route::middleware('auth')->get('users/{id}/private', 'UserController@getEditPage')->name('editPage');
Route::middleware('auth')->get('users/{id}/private/addresses', 'UserController@getAddresses')->name('addresses');

//Administration
Route::middleware('auth')->get('admin', 'AdminController@getDashboard')->name('getDashboard');

// Products
Route::get('products', 'ProductController@search')->name('getProductSearch');
Route::get('products/{id}', 'ProductController@show')->name('getProduct');

// Authentication
Route::get('join', 'Auth\JoinController@show')->name('join')->middleware('guest');
Route::post('login', 'Auth\LoginController@login')->name('login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');
Route::post('register', 'Auth\RegisterController@register')->name('register');
