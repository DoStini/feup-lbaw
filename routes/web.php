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
Route::get('/', 'Auth\LoginController@home');

// Users
Route::get('users/', 'ShopperController@getAuth');
Route::get('users/cart', 'ShopperController@getCart');
Route::get('users/orders', 'ShopperController@getOrders');
Route::get('users/{id}', 'ShopperController@showProfile');
Route::get('users/{id}/private', 'ShopperController@getEditPage');


// Products
Route::get('products/{id}', 'ProductController@show');

// Authentication
Route::get('join', 'Auth\JoinController@show')->name('join')->middleware('guest');
Route::post('login', 'Auth\LoginController@login')->name('login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');
Route::post('register', 'Auth\RegisterController@register')->name('register');
