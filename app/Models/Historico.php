<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Historico extends Model
{
    public $timestamps = false;

    protected $primaryKey = 'IdHistorico';

    protected $table = 'Historico';

    protected $fillable = [
        'IdHistorico',
        'IdContrato',
        'TxtReceitaDespesa',
        'NumContrato',
        'ObsHistorico',
        'CodUG',
        'TpFornecedor',
        'NumCnpjCpf',
        'NomFornecedor',
        'TpContrato',
        'CatContrato',
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
        'ValGlobalNovo',
        'NumParcelasNovo',
        'ValParcelaNovo',
        'DatInicioNovoValor',
        'FlgRetroativo',
        'MesReferenciaRetroativoDE',
        'AnoReferenciaRetroativoDE',
        'MesReferenciaRetroativoATE',
        'AnoReferenciaRetroativoATE',
        'DatVencimentorRetroativo',
        'ValRetroativo'
    ];
}
