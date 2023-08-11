<?php

namespace Comprasnet\App\Models;

use Illuminate\Database\Eloquent\Model;

class Preposto extends Model
{
    public $timestamps = false;

    protected $primaryKey = 'IdPreposto';

    protected $table = 'Preposto';

    protected $fillable = [
        'IdPrepostoOriginal',
        'IdContrato',
        'NomUsuario',
        'EmlUsuario',
        'TelFixo',
        'TelCelular',
        'TxtDocFormalizacao',
        'TxtInformacaoComplementar',
        'DatInicio',
        'DatFim',
        'SitPreposto',
    ];
}
