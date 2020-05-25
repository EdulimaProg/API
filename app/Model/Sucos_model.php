<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Sucos_model extends Model
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
