<?php

namespace App\Http\Controllers\HandAPI;

use App\Model\Pedidos_model;
use App\Model\Pedidos_desc_model;
use App\Servicos\ConsultaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Model\Sucos_model as suco;
use App\Model\Lanches_model as lanche;
use App\Model\Usuario_model;
use App\Model\Numeros_model;
use App\Model\Endereco_model;
use App\Http\Controllers\Controller;



class HandFullController extends Controller
{
    public function index(){


        return "API Handful";
    }
    public function login(Request $request){
        if ($request->all() == null){
            $arr = [
                "code" => 405,
                "messagem" => "Insira as Credenciais para Logar"
            ];
            return response()->json($arr);
        }elseif ($request->cpf == null || $request->senha == null){
            $arr = [
                "code" => 405,
                "messagem" => "O Campo Cpf ou Senha Não foi preechido Corretamente"
            ];
            return response()->json($arr);
        }else{
            if(HandFullController::check($request->cpf,$request->senha) == false){
                $arr = [
                    "code" => 403,
                    "messagem" => "CPF ou Senha Incorretos",
                ];
                return response()->json($arr);
            }elseif(HandFullController::check($request->cpf,$request->senha) == true){
                $dados = HandFullController::dados($request->cpf);

                $arr = [
                    "code" => 200,
                    "messagem" => "Logado Com Sucesso",
                    "dados" => $dados,
                ];
                return response()->json($arr);
            }
        }

    }
    public function lista_sucos(){

        $arr = [];

        $itens = suco::all();

        //dd($itens);
        foreach ($itens as $item){
          $item_arr = array([
              'nome' => $item->nome,
              'valor' => $item->valor,
              'desc' => $item->desc,
          ]);
          //dd($item_arr);
          array_push($arr, $item_arr[0]);
        }

       //dd($arr);

        return json_encode($arr);
    }
    public function lista_lanches(){

        $arr = [];

        $itens = lanche::all();


        foreach ($itens as $item){
            $item_arr = array([
                'nome' => $item->nome,
                'valor' => $item->valor,
                'desc' => $item->desc,
            ]);
            array_push($arr, $item_arr[0]);
        }

        //dd($arr);

        return response()->json($arr);
    }
    public function cadastro(Request $request){

        if($request->all() == null){
            return "Insira algum dado para efetuar o cadastro";
        }
        $dado = Usuario_model::where('cpf', $request->cpf)->get();
        //dd(count($dado));

        if(count($dado) == 0){
            $fulano = new Usuario_model();
            $fulano->nome = $request->nome;
            $fulano->sobrenome = $request->sobrenome;
            $fulano->cpf = $request->cpf;
            $fulano->datanasc = $request->datanasc;
            $fulano->senha = $request->senha;

            $fulano->save();

            $id = $fulano->id;

            $numero_fulano = new Numeros_model();


            $numero_fulano->numero_principal = $request->num_principal;
            $numero_fulano->numero_secundario = $request->num_secundario;
            $numero_fulano->usuario_id = $id;

            $numero_fulano->save();

            $end_fulano = new Endereco_model();

            $end = $request->end;

            $end_fulano->cep = $end['cep'];
            $end_fulano->rua = $end['rua'];
            $end_fulano->complemento = $end['compl'];
            $end_fulano->numero = $end['num'];
            $end_fulano->usuario_id = $id;

            $end_fulano->save();

             $arr = [
                 'code' => 200,
                 'mensagem'=> "Cadastrado com Sucesso"
             ];

            return response()->json($arr);
        }else{
            $arr = [
                'code' => 404,
                'mensagem'=> "Usuario já cadastrado na Plataforma"
            ];

            return response()->json($arr);
        }

    }
    public function pedido(Request $request){

        $ped_desc = new Pedidos_desc_model();
        $ped = new Pedidos_model();

        $ped->usuario_id = $request->id;

        $ped->save();

        $id = $ped->push();

        $ped_desc->pedidos_id = $id;
        $ped_desc->numero_pedido = ConsultaService::randon();
        $ped_desc->valor_pedido = $request->valor;
        $ped_desc->tipo_pag = $request->pag;

        $ped_desc->save();

        $arr = [
            'cod' => 200,
            'mensagem' => 'Pedido Efetuado com Sucesso',
            'numero_pedido' => $ped_desc->numero_pedido
        ];

        return response()->json($arr);
    }


    //Rotas de Get and Check information
    public function dados($cpf){
        //dd($cpf);
        $usuario = DB::table('usuario')
            ->select('id','nome','sobrenome','cpf','datanasc')
            ->where('cpf','=', $cpf)
            ->get();
        //dd($usuario[0]);
        $numeros = DB::table('numero')
            ->select('numero.numero_principal','numero.numero_secundario')
            ->join('usuario','usuario.id','=','numero.usuario_id')
            ->where('usuario.cpf','=', $cpf)
            ->get();

        $endereco = DB::table('endereco')
            ->select('endereco.rua','endereco.complemento','endereco.numero','endereco.cep')
            ->join('usuario','usuario.id','=','endereco.usuario_id')
            ->where('usuario.cpf','=', $cpf)
            ->get();



        $pedidos = DB::table('pedidos')
            ->Join('usuario','pedidos.id','=','usuario.id')
            ->Join('pedidos_desc','pedidos_desc.pedidos_id','=','pedidos.id')
            ->where('usuario.id','=',$cpf)
            ->select('pedidos_desc.numero_pedido',
                    'pedidos_desc.valor_pedido',
                    'pedidos_desc.tipo_pag')
            ->get();

        //dd($pedidos[1]);
        $arr =[];
        $i = 0;
        foreach ($pedidos as $ped){
            array_push($arr, $pedidos[$i]);
            $i++;
        }


        $arr = [
            "usuario" => [
                'dado' => $usuario[0],
                'numero' => $numeros[0],
                'endereco' => $endereco[0]
            ],
            "pedidos" => $arr
        ];

        return response()->json($arr);
    }
    public function check($cpf, $senha){

       //dd($cpf,$senha);
       $check = Usuario_model::where('cpf','=',$cpf)->where('senha','=',$senha)->get();
       //$var = $check->get();
       //dd(count($check));
       if (count($check) == 1){
           return true;
       }
        return false;
    }

}

