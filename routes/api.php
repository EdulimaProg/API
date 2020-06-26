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

// rota de login
Route::post('login', 'HandAPI\HandFullController@login');

Route::get('index', 'HandAPI\HandFullController@index');
//rota de consultas
Route::get('listar-sucos', 'HandAPI\HandFullController@lista_sucos');
Route::get('listar-lanches', 'HandAPI\HandFullController@lista_lanches');
Route::get('promo', 'HandAPI\HandFullController@promocoes');
//Desativado
//Route::get('listar/categorias', 'HandFullController@listaCategorias');
//
// rotas de cadastro de itens e pedidos
Route::post('cadastro/usuario', 'HandAPI\HandFullController@cadastro');
Route::post('pedido', 'HandAPI\HandFullController@pedido');

//Pagamentos
Route::post('pagamento','PagamentoAPI\PagamentoController@Pagamento');
