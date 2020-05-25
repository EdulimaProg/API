<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Numeros_model extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'numero';

    protected $primaryKey = 'id';

    protected $fillable = [
        'numero_principal',
        'numero_secundario',
        'usuario_id'
    ];

    public function usuario(){
        return $this->belongsTo('App\http\Model\Usuario_model', 'usuario_id');
    }
}
