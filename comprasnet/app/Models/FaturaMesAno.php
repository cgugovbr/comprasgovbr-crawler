<?php

namespace Comprasnet\App\Models;

use Illuminate\Database\Eloquent\Model;

class FaturaMesAno extends Model
{
    public $timestamps = false;

    protected $primaryKey = 'IdFaturaMesAno';

    protected $table = 'FaturaMesAno';

    protected $fillable = [
        'IdFatura',
        'TxtMesRef',
        'TxtAnoRef',
        'ValValorRef',
    ];
}
