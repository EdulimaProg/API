<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Usuario_model extends Model
{
    protected $table = 'usuario';

    protected $primaryKey = 'id';

    protected $fillable = [
        'nome',
        'sobrenome',
        'cpf',
        'datanasc',
        'senha',
        'email',
        'created_at',
        'update_at'
    ];
    public function numero(){
        return $this->hasOne('App\Model\Numero_model', 'usuario_id', 'id');
    }
    public function endereco(){
        return $this->hasOne('App\Model\Endereco_model', 'usuario_id', 'id');
    }

}
