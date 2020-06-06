<?php

use App\Http\Controllers\ProductController;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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

Route::post('/register', 'Auth\RegisterController@register')->name('register');
Route::post('/login', 'Auth\LoginController@login')->name('login');
Route::post('/logout', 'Auth\LoginController@logout')->name('logout');
Route::get('/user', fn () => Auth::user())->name('user');
Route::get('/users/list', 'UserController@list')->name('user.list');
Route::get('/users/products/{id}', 'UserController@products')->name('user.products');
Route::get('/users/{id}', 'UserController@currentuser')->name('user.current');
Route::post('/products', 'ProductController@create')->name('product.create');
Route::get('/products', 'ProductController@list')->name('product.list');
Route::post('/products/save', 'ProductController@dotsave')->name('product.save');
Route::post('/products/current', 'ProductController@current')->name('product.current');
Route::get('/products/index', 'ProductController@index')->name('product.index');
Route::get('/products/{id}', 'ProductController@show')->name('product.show');
Route::get('products/delete/{id}', 'ProductController@delete')->name('product.delete');
Route::post('/products/{product}/comments', 'ProductController@addComment')->name('product.comment');
Route::put('/products/{id}/like', 'ProductController@like')->name('product.like');
Route::delete('/products/{id}/like', 'ProductController@unlike');
Route::get('/refresh-token', function (Illuminate\Http\Request $request) {
	$request->session()->regenerateToken();

	return response()->json();
});
