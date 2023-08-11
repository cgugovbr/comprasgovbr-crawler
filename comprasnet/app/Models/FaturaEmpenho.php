<?php

namespace Comprasnet\App\Models;

use Illuminate\Database\Eloquent\Model;

class FaturaEmpenho extends Model
{
    public $timestamps = false;

    protected $primaryKey = 'IdFaturaEmpenho';

    protected $table = 'Fatura_Empenho';

    protected $fillable = [
        'IdFatura',
        'IdEmpenhoOriginal',
    ];
}
