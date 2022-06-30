<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empenho extends Model
{
    public $timestamps = false;

    protected $primaryKey = 'IdEmpenho';

    protected $table = 'Empenho';

    protected $fillable = [
        'IdEmpenho',
        'NumEmpenho',
        'NomCredor',
        'TxtPlanoInterno',
        'DescNaturezaDepesa',
        'ValEmpenhado',
        'ValALiquidar',
        'ValLiquidado',
        'ValPago',
        'ValRPInscrito',
        'ValRPALiquidar',
        'ValRPLiquidado',
        'ValRPPago'
    ];
}
