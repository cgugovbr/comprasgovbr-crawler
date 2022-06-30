<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contrato extends Model
{
    // Timestamp não funciona no sql server
    // const CREATED_AT = 'DthCriadoEm';
    // const UPDATED_AT = 'DthAtualizadoEm';
    public $timestamps = false;

    protected $primaryKey = 'IdContrato';

    protected $table = 'Contrato';

    protected $fillable = [
        'IdContrato',
        'TxtReceitaDespesa',
        'NumContrato',
        'TpContrato',
        'CatContrato',
        'TxtSubcategoria',
        'NomUnidadesRequisitantes',
        'NumProcesso',
        'DescObjeto',
        'TxtInformacaoComplementar',
        'DescModalidade',
        'NumLicitacao',
        'DatAssinatura',
        'DatPublicacao',
        'DatVigenciaInicio',
        'DatVigenciaFim',
        'ValInicial',
        'ValGlobal',
        'NumParcelas',
        'ValParcela',
        'ValAcumulado',
        'EndLinkHistorico',
        'EndLinkEmpenhos',
        'EndLinkCronograma',
        'DthCriadoEm',
        'DthAtualizadoEm'
    ];
}
