<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class sucos_model extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sucos';

    protected $primaryKey = 'id';

    protected $fillable = [
        'nome',
        'valor',
        'desc',
    ];
}
