<?php

namespace Comprasnet\App\Models;

use Illuminate\Database\Eloquent\Model;

class Publicacao extends Model
{
    public $timestamps = false;

    protected $primaryKey = 'IdPublicacao';

    protected $table = 'Publicacao';

    protected $fillable = [
        'IdPublicacaoOriginal',
        'IdContrato',
        'IdHistoricoOriginal',
        'DatPublicacao',
        'IdStatusPublicacaoOriginal',
        'SitStatus',
        'TxtTextoDOU',
        'UrlLinkPublicacao',
    ];
}
