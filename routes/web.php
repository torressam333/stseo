<?php

use App\Http\Middleware\AdminCheck;
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

Route::prefix('app')->middleware([AdminCheck::class])->group(function () {
    /*Tag Routes*/
    Route::post('/create_tag', 'TagsController@addTag');
    Route::post('/edit_tag', 'TagsController@editTag');
    Route::get('/get_tags', 'TagsController@getTag');
    Route::post('/delete_tag', 'TagsController@deleteTag');

    /*Images and files routes*/
    Route::post('/upload', 'CategoriesController@upload');
    Route::post('/delete_image', 'CategoriesController@deleteImage');

    /*Categories Routes*/
    Route::post('/create_category', 'CategoriesController@addCategory');
    Route::get('/get_categories', 'CategoriesController@getCategories');
    Route::post('/delete_category', 'CategoriesController@deleteCategory');
    Route::post('/edit_category', 'CategoriesController@editCategory');

    /*Admin user creation routes*/
    Route::post('/create_user', 'AdminUserController@createUser');
    Route::get('/get_users', 'AdminUserController@getUser');
    Route::post('/edit_user', 'AdminUserController@editUser');
    Route::post('/delete_user', 'AdminUserController@deleteUser');
    Route::post('/admin_login', 'AdminUserController@adminLogin');

    /*Role Routes*/
    Route::post('/create_role', 'RolesController@addRole');
    Route::get('/get_roles', 'RolesController@getRole');
    Route::post('/edit_role', 'RolesController@editRole');
    Route::post('/delete_role', 'RolesController@deleteRole');

    /*Role Permission Assignment Routes*/
    Route::post('assign_roles','RolePermissionsController@assignRolePermission');

    /*Blog Routes*/
    Route::post('createBlog', 'BlogPostsController@createBlog');
    Route::post('updateBlog/{id}', 'BlogPostsController@updateBlog');
    Route::get('blog_data', 'BlogPostsController@getBlogData'); //get blogs items
    Route::post('delete_blog', 'BlogPostsController@deleteBlog'); //delete blog
    Route::get('blog/{id}', 'BlogPostsController@getSingleBlog');

});

//Non admin Blog Routes
Route::post('createBlog', 'BlogPostsController@uploadEditorImage');
Route::get('slug', 'BlogPostsController@slug');


//Login/Auth routes
Route::get('/', 'AdminUserController@index');
Route::get('/logout', 'AdminUserController@logout');
Route::any('{slug}', 'AdminUserController@index')->where('slug', '([A-z\d\-\/_.]+)?');


