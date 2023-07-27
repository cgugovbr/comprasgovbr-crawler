<?php

namespace Comprasnet\App\Models;

use Illuminate\Database\Eloquent\Model;

class FaturaMesAno extends Model
{
    public $timestamps = false;

    protected $primaryKey = 'IdFaturaMesAno';

    protected $table = 'Fatura_Mes_Ano';

    protected $fillable = [
        'IdFatura',
        'TxtMesRef',
        'TxtAnoRef',
        'ValValorRef',
    ];
}
