<?php

namespace Comprasnet\App\Models;

use Illuminate\Database\Eloquent\Model;

class ContratoItem extends Model
{
    public $timestamps = false;

    protected $primaryKey = 'IdContratoItem';

    protected $table = 'ContratoItem';

    protected $fillable = [
        'IdContratoItemOriginal',
        'IdContrato',
        'TipId',
        'TipMaterial',
        'GrpId',
        'CatMatSerItemId',
        'DescComplementar',
        'QtdItem',
        'ValUnitario',
        'ValTotal',
        'NumItemCompra',
        'datInicioItem',
    ];
}
