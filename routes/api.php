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
Route::post('/orders/{id}/cancel', 'OrderController@cancel')->middleware(['auth.api'])->name("cancel_order");

Route::get('/products/variants', 'ProductController@variants');
Route::get('/products', [
    'middleware' => 'searchProducts',
    'uses' => 'ProductController@list'
]);

Route::get('/products/list', 'ProductController@datatableList');
Route::get('/orders', 'OrderController@list');
Route::get('/users', [
    'uses' => 'UserController@list'
]);
Route::get('/address/zipcode', 'ZipCodeController@zipCode');
Route::get('/coupon/search', 'CouponController@search');
Route::get('/coupon', 'CouponController@list');
Route::group(
    [
        'prefix' => 'coupon/{id}',
        'middleware' => ['auth.api'],
    ],
    function () {
        Route::post('/disable', 'CouponController@disable');
        Route::post('/enable', 'CouponController@enable');
    }
);

Route::post('/users/{id}/block', 'ShopperController@blockShopper');
Route::post('/users/{id}/unblock', 'ShopperController@unblockShopper');


Route::post('/account/recover', 'Auth\RecoverAccountController@submitRecoverRequest');

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
        'middleware' => ['auth.api']
    ],
    function () {
        Route::get('/', 'CartController@get');
        Route::post('/add', 'CartController@add');
        Route::post('/update', 'CartController@update');
        Route::delete('/{id}/remove', 'CartController@delete');
    }
);

Route::group(
    [
        'prefix' => 'users/{id}/notifications',
        'middleware' => ['auth.api', 'is.shopper']
    ],
    function () {
        Route::get('/', 'NotificationController@get');
        Route::post('/', 'NotificationController@clear');
    }
);

Route::group(
    [
        'prefix' => 'users/wishlist',
        'middleware' => ['auth.api']
    ],
    function () {
        Route::get('/', 'WishlistController@get');
        Route::post('/add', 'WishlistController@add');
        Route::delete('/{product_id}/remove', 'WishlistController@delete');
    }
);

Route::middleware('auth.api')->post('/reviews/{id}/update', 'ReviewController@updateReview');
Route::middleware('auth.api')->delete('/reviews/{id}/delete', 'ReviewController@deleteReview');
Route::middleware('auth.api')->post('/reviews/{id}/vote', 'ReviewController@voteOnReview');
Route::middleware('auth.api')->delete('/reviews/{id}/vote/', 'ReviewController@removeVoteOnReview');
