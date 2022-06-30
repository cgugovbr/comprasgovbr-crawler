<?php

namespace App\Schemas\Blueprints;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\Builder;

class CustomBlueprint extends Blueprint
{
    // VarChar ao invés de nvarchar para 'string'
    public function varChar($column, $length = null)
    {
        $length = $length ? : Builder::$defaultStringLength;

        return $this->addColumn('varChar', $column, compact('length'));
    }
}