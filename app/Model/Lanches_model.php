<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class lanches_model extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'lanches';

    protected $primaryKey = 'id';

    protected $fillable = [
      'nome',
      'valor',
      'desc',
    ];
}
