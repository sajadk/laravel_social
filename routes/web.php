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

Route::get('/', function () {
  return view('welcome');
})->name('home');

Route::post('/signup',[
   'uses' => 'UserController@postSignUp',
   'as' => 'signup'
   ]);

Route::post('/signin',[
   'uses' => 'UserController@postSignIn',
   'as' => 'signin'
   ]);

Route::get('/logout',[
   'uses' => 'UserController@getLogout',
   'as' => 'logout'
   ]);

Route::get('/dashboard',[
   'uses' => 'PostController@getDashboard',
   'as' => 'dashboard'
   ])->middleware('auth');

Route::get('/account',[
   'uses' => 'UserController@getAccount',
   'as' => 'account'
   ])->middleware('auth');

Route::post('/updateaccount',[
   'uses' => 'UserController@postSaveAccount',
   'as' => 'account.save'
   ])->middleware('auth');

Route::get('/userimage/{filename}',[
   'uses' => 'UserController@getUserImage',
   'as' => 'account.image'
   ])->middleware('auth');

Route::post('/createpost',[
   'uses' => 'PostController@postCreatePost',
   'as' => 'post.create'
   ])->middleware('auth');

Route::post('/editpost',[
   'uses' => 'PostController@postEditPost',
   'as' => 'post.edit'
   ])->middleware('auth');

Route::get('/delete-post/{post_id}',[
   'uses' => 'PostController@getDeletePost',
   'as' => 'post.delete'
   ])->middleware('auth');

Route::post('/like',[
   'uses' => 'PostController@postLikePost',
   'as' => 'like'
   ])->middleware('auth');

