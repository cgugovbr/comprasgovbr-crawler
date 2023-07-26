<?php

namespace Comprasnet\App\Models;

use Illuminate\Database\Eloquent\Model;

class Arquivo extends Model
{
    public $timestamps = false;

    protected $primaryKey = 'IdArquivo';

    protected $table = 'Arquivo';

    protected $fillable = [
        'IdArquivoOriginal',
        'IdContrato',
        'TipArquivo',
        'NumProcesso',
        'NumSequencialDocumento',
        'TxtDescricao',
        'TxtPathArquivo',
        'OriArquivo',
        'UrlLinkSei',
    ];
}
