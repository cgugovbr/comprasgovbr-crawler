<?php

namespace Comprasnet\App\Models;

use Illuminate\Database\Eloquent\Model;

class Responsavel extends Model
{
    public $timestamps = false;

    protected $primaryKey = 'IdResponsavel';

    protected $table = 'Responsavel';

    protected $fillable = [
        'IdResponsavelOriginal',
        'IdContrato',
        'NomUsuario',
        'TxtFuncaoId',
        'TxtInstalacaoId',
        'TxtPortaria',
        'SitResponsavel',
        'DatInicio',
        'DatFim',
    ];
}
