<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

/* Route::prefix('/handfull/lanche')->group(function(){

}); */

Route::get('info/php', function(){
    return phpinfo();
});

Route::post('login', 'HandFullController@login');

Route::get('index', 'HandFullController@index');
Route::get('listar-sucos', 'HandFullController@lista_sucos');
Route::get('listar-lanches', 'HandFullController@lista_lanches');
Route::get('listar/categorias', 'HandFullController@listaCategorias');
Route::post('cadastro/usuario', 'HandFullController@cadastro');
Route::post('pedido', 'HandFullController@pedido');
