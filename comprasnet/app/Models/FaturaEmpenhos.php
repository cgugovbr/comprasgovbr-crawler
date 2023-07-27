<?php

namespace Comprasnet\App\Models;

use Illuminate\Database\Eloquent\Model;

class FaturaEmpenhos extends Model
{
    public $timestamps = false;
    public $incrementing = false;

    protected $primaryKey = null;

    protected $table = 'Fatura_Empenhos';

    protected $fillable = [
        'IdFatura',
        'IdEmpenhoOriginal',
    ];
}
