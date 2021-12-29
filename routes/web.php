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
Route::get('/', 'StaticPagesController@home');

// Users
Route::get('users/cart', 'ShopperController@cart');
Route::get('users/{id}', 'ShopperController@show');
Route::get('users/{id}/private', 'ShopperController@getEditPage');
Route::get('users/', 'ShopperController@getAuth');


// Products
Route::get('products/{id}', 'ProductController@show');

// Authentication
Route::get('join', 'Auth\JoinController@show')->name('join')->middleware('guest');
Route::post('login', 'Auth\LoginController@login')->name('login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');
Route::post('register', 'Auth\RegisterController@register')->name('register');
