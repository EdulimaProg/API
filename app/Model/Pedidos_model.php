<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Pedidos_model extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pedidos';

    protected $primaryKey = 'id';

    protected $fillable = [
        'usuario_id',
    ];
}
