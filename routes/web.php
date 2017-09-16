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



Route::group(['middleware' => ['web']],function()
{
    //Authentication
    Route::get('auth/login',['uses' => 'Auth\LoginController@showLoginForm', 'as' => 'login']);
    Route::post('auth/login',  'Auth\LoginController@login');
    Route::get('auth/logout', ['uses' => 'Auth\LoginController@logout', 'as'=>'logout' ]);


    //Registration
    Route::get('auth/register', 'Auth\RegisterController@showRegistrationForm');
    Route::post('auth/register', 'Auth\RegisterController@register');

    //Pasword reset
    Route::get('password/reset/{token}','Auth\ResetPasswordController@showResetForm')->name('password.reset');
    Route::post('password/email','Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
    Route::post('password/reset','Auth\ResetPasswordController@reset');
    Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
    // True one
//    Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
//    Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
//    Route::post('password/reset', 'Auth\ResetPasswordController@reset');
//    Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');

    //Categories
    Route::resource('categories','CategoryController',['except'=>['create']]);

    //Tags
    Route::resource('tags','TagController',['except'=>['create']]);

    //Comments
    Route::post('comments/{post_id}',['uses' => 'CommentsController@store', 'as' => 'comments.store']);
    Route::get('comments/{id}/edit',['uses' => 'CommentsController@edit', 'as' => 'comments.edit']);
    Route::put('comments/{id}',['uses' => 'CommentsController@update', 'as' => 'comments.update']);
    Route::get('comments/{id}/delete',['uses' => 'CommentsController@delete', 'as' => 'comments.delete']);
    Route::delete('comments/{id}',['uses' => 'CommentsController@destroy', 'as' => 'comments.destroy']);



    Route::get('blog/{slug}',['as'=>'blog.single','uses'=>'BlogController@getSingle'])
    ->where('slug','[\w\d\-\_]+');
    Route::get('blog',['uses' => 'BlogController@getIndex', 'as' => 'blog.index']);
    Route::get('contact','PagesController@getContact');
    Route::post('contact','PagesController@postContact');
    Route::get('about','PagesController@getAbout');
    Route::get('/','PagesController@getIndex')->name('home');
    Route::resource('posts','PostController');
}
);
