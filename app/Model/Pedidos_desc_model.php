<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Pedidos_desc_model extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pedidos_desc';

    protected $primaryKey = 'id';

    protected $fillable = [
        'numero_pedido',
        'valor_pedido',
        'tipo_pag',
        'pedidos_id'
    ];
}
