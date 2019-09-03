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

use App\Http\Middleware;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/anderson/{nombre?}', function($nombre = null) {
    $texto_a_mostrar = "<h2>La primer ruta en Laravel hecha por Anderson</h2>";
    $texto_a_mostrar.= 'Nombre: ' . $nombre;

    return view('prueba', array(
        'texto' => $texto_a_mostrar
    ));
});

Route::get('/animales', 'PruebaController@index');
Route::get('/test', 'PruebaController@testOrm');

Route::get('/user/test', 'UserController@test');
Route::get('/category/test', 'CategoryController@test');
Route::get('/post/test', 'PostController@test');

//UserController
Route::post('/api/register', 'UserController@register');
Route::post('/api/login', 'UserController@login');
Route::put('/api/user/update', 'UserController@update')->middleware(Middleware\ApiAuthMiddleware::class);
Route::post('/api/user/upload', 'UserController@uploadAvatar')->middleware(Middleware\ApiAuthMiddleware::class);
