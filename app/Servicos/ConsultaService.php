<?php

namespace App\Servicos;

use Illuminate\Support\Facades\DB;
use function GuzzleHttp\Promise\all;

class ConsultaService {

    public static function getID($cpf){
        $usuario = DB::table('usuario')
            ->select('id')
            ->where('cpf','=', $cpf)
            ->get();
        return $usuario;
    }
    public static function randon(){
        $num_ped = rand(10000000, 99999999);

        return $num_ped;
    }

}
