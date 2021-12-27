<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', 'Auth\LoginController@getUser');

Route::post('/users/{id}/private/edit', 'ShopperController@edit')->middleware(['auth:sanctum', 'userOwnerAdmin'])->name("edit_user");
Route::post('/orders/{id}/status', 'OrderController@update')->middleware(['auth:sanctum', 'admin'])->name("edit_order");

Route::get('/products', [
    'middleware' => 'searchProducts',
    'uses' => 'ProductController@list'
]);
