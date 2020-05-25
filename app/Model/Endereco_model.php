<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Endereco_model extends Model
{
    protected $table = 'endereco';

    protected $primaryKey = 'id';

    protected $fillable = [
        'cep',
        'rua',
        'complemento',
        'numero',
        'usuario_id'
    ];
}
