<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Combos_model extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'combos';

    protected $primaryKey = 'id';

    protected $fillable = [
        'nome',
        'valor',
        'desc',
    ];
}
