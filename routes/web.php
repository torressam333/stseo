<?php

use Illuminate\Support\Facades\Route;

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

/*Tag Routes*/
Route::post('app/create_tag', 'AdminController@addTag');
Route::post('app/edit_tag', 'AdminController@editTag');
Route::get('app/get_tags', 'AdminController@getTag');
Route::post('app/delete_tag', 'AdminController@deleteTag');

/*Images and files routes*/
Route::post('app/upload', 'AdminController@upload');
Route::post('app/delete_image', 'AdminController@deleteImage');

/*Categories Routes*/
Route::post('app/create_category', 'AdminController@addCategory');
Route::get('app/get_categories', 'AdminController@getCategories');
Route::post('app/delete_category', 'AdminController@deleteCategory');
Route::post('app/edit_category', 'AdminController@editCategory');




Route::get('/', function () {
    return view('welcome');
});

Route::any('/{slug}', function () {
    return view('welcome');
});
