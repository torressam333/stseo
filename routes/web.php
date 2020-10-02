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
Route::post('app/create_tag', 'TagsController@addTag');
Route::post('app/edit_tag', 'TagsController@editTag');
Route::get('app/get_tags', 'TagsController@getTag');
Route::post('app/delete_tag', 'TagsController@deleteTag');

/*Images and files routes*/
Route::post('app/upload', 'CategoriesController@upload');
Route::post('app/delete_image', 'CategoriesController@deleteImage');

/*Categories Routes*/
Route::post('app/create_category', 'CategoriesController@addCategory');
Route::get('app/get_categories', 'CategoriesController@getCategories');
Route::post('app/delete_category', 'CategoriesController@deleteCategory');
Route::post('app/edit_category', 'CategoriesController@editCategory');

/*Admin user creation routes*/
Route::get('/', 'AdminUserController@index');
Route::get('/logout', 'AdminUserController@logout');
Route::post('app/create_user', 'AdminUserController@createUser');
Route::get('app/get_users', 'AdminUserController@getUser');
Route::post('app/edit_user', 'AdminUserController@editUser');
Route::post('app/admin_login', 'AdminUserController@adminLogin');
Route::any('{slug}', 'AdminUserController@index');


