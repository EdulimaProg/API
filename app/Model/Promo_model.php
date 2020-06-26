<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Promo_model extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'promos';

    protected $primaryKey = 'id';

    protected $fillable = [
        'nome',
        'valor',
        'desc',
    ];
}
