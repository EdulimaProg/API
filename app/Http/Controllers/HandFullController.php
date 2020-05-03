<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HandFullController extends Controller
{
    public function index(){
        return 'heelo';
    }
    public function listaItens(){

        $itens = DB::table('itens')->get();

        return $itens;
    }

    public function listaCategorias(){

        $categorias = DB::table('categoria')->get();
        
        return $categorias;
    }

    public function cadastro(Request $request){

        //dd($request->all());

        DB::table('usuario')->insert(
            ['nome' => $request->nome,
             'sobrenome' => $request->sobrenome,
              'aniversario' => $request->datanasc,
              'cpf' => $request->cpf
              ]
        );
        
        return 'ok';
    }
}

