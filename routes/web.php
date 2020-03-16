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

Route::get('/api/article/detail/{id}', 'SpaController@getArticleById');
Route::get('/api/article/{page}/{limit?}', 'SpaController@getArticleListByPage');
Route::get('/api/selection/{type}/{limit}', 'SpaController@getSelection');
Route::get('/api/tags', 'SpaController@getTags');
Route::get('/api/timeline/{page}', 'SpaController@getTimelinesByPage');
Route::post('/api/article/star', 'SpaController@setStarState');
Route::post('/api/operate', 'SpaController@setOperate');

Route::post('/api/login', 'SpaController@login');
Route::get('/api/logout', 'SpaController@logout');
Route::get('/api/authenticate', 'SpaController@authenticate');
Route::post('/api/article/create', 'SpaController@createArticle')->middleware('auth');
Route::get('/{any}', 'SpaController@index')->where('any', '.*');
