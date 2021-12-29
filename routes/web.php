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

Route::get('users/cart', 'CartController@show')->name('getCart');
Route::get('users/{id}', 'ShopperController@show')->name('getUser');
Route::get('users/{id}/private', 'ShopperController@getEditPage')->name('editPage');
Route::get('users/', 'ShopperController@getAuth')->name('getUsersPage');


// Products
Route::get('products/{id}', 'ProductController@show')->name('getProduct');

// Authentication
Route::get('join', 'Auth\JoinController@show')->name('join')->middleware('guest');
Route::post('login', 'Auth\LoginController@login')->name('login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');
Route::post('register', 'Auth\RegisterController@register')->name('register');
