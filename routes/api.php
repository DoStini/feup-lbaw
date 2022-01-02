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

Route::post('/users/{id}/private/edit', 'UserController@edit')->middleware(['auth.api', 'user.owner.admin'])->name("edit_user");
Route::post('/orders/{id}/status', 'OrderController@update')->middleware(['auth.api', 'admin'])->name("edit_order");

Route::get('/products', [
    'middleware' => 'searchProducts',
    'uses' => 'ProductController@list'
]);

Route::get('/users', [
    'middleware' => 'searchUsers',
    'uses' => 'UserController@list'
]);
Route::get('/address/zipcode', 'ZipCodeController@zipCode');

Route::group(
    [
        'prefix' => 'users/{id}/private/address',
        'middleware' => ['auth.api', 'user.owner.admin'],
    ],
    function () {
        Route::get('/', 'AddressController@getUserAddresses');
        Route::post('/add', 'AddressController@create');
        Route::post('/{address_id}/edit', 'AddressController@edit');
        Route::delete('/{address_id}/remove', 'AddressController@remove');
    }
);

Route::group(
    [
        'prefix' => 'users/cart',
        'middleware' => ['auth.api', 'is.shopper']
    ],
    function () {
        Route::get('/', 'CartController@get');
        Route::post('/add', 'CartController@add');
        Route::post('/update', 'CartController@update');
        Route::delete('/{id}/remove', 'CartController@delete');
    }
);
