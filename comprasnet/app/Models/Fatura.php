<?php

namespace Comprasnet\App\Models;

use Illuminate\Database\Eloquent\Model;

class Fatura extends Model
{
    public $timestamps = false;

    protected $primaryKey = 'IdFatura';

    protected $table = 'Fatura';

    protected $fillable = [
        'IdFatura',
        'IdFaturaOriginal',
        'IdContrato',
        'TipoListaFaturaId',
        'TxtJustificativaFaturaId',
        'TxtSfPadraoId',
        'NumFatura',
        'DatEmissao',
        'DatPrazo',
        'DatVencimento',
        'ValValor',
        'ValJuros',
        'ValMulta',
        'ValGlosa',
        'ValValorLiquido',
        'NumProcesso',
        'DatProtocolo',
        'DatAteste',
        'SitRepactuacao',
        'TxtInfComplementar',
        'TxtMesRef',
        'TxtAnoRef',
        'SitFatura',
        'TxtChaveNfe',
        'IdFaturaOriginal',
        'NumFatura',
    ];
}
