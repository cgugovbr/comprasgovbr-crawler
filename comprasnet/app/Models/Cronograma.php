<?php

namespace Comprasnet\App\Models;

use Illuminate\Database\Eloquent\Model;

class Cronograma extends Model
{
    public $timestamps = false;

    protected $primaryKey = 'IdCronograma';

    protected $table = 'Cronograma';

    protected $fillable = [
        'IdCronograma',
        'IdCronogramaOriginal',
        'IdContrato',
        'TpCronograma',
        'NumEmpenho',
        'TxtReceitaDespesa',
        'ObsCronograma',
        'MesReferencia',
        'AnoReferencia',
        'DatVencimento',
        'FlgRetroativo',
        'ValCronograma'
    ];
}
