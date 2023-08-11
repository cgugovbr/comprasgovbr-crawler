<?php

namespace Comprasnet\App\Models;

use Illuminate\Database\Eloquent\Model;

class LogAtividade extends Model
{
    public $timestamps = false;

    protected $primaryKey = 'IdLogAtividade';

    protected $table = 'LogAtividade';

    protected $fillable = [
        'IdLogAtividade',
        'TipAtividade',
        'SitAtividade',
        'DatLogAtividade',
        'OriExecucao',
        'DetExecucao',
    ];
}
