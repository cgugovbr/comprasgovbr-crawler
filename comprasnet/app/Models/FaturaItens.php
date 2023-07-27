<?php

namespace Comprasnet\App\Models;

use Illuminate\Database\Eloquent\Model;

class FaturaItens extends Model
{
    public $timestamps = false;

    protected $primaryKey = 'IdFaturaItens';

    protected $table = 'Fatura_Itens';

    protected $fillable = [
        'IdFatura',
        'IdItemContratoOriginal',
        'QtdFaturada',
        'ValUnitarioFaturado',
        'ValTotalFaturado',
    ];
}
