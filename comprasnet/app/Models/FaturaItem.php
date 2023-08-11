<?php

namespace Comprasnet\App\Models;

use Illuminate\Database\Eloquent\Model;

class FaturaItem extends Model
{
    public $timestamps = false;

    protected $primaryKey = 'IdFaturaItem';

    protected $table = 'FaturaItem';

    protected $fillable = [
        'IdFatura',
        'IdItemContratoOriginal',
        'QtdFaturada',
        'ValUnitarioFaturado',
        'ValTotalFaturado',
    ];
}
