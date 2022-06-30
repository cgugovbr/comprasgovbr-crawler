<?php

namespace App\Schemas\Grammars;

use Illuminate\Database\Schema\Grammars\SqlServerGrammar;
use Illuminate\Support\Fluent;

class CustomGrammar extends SqlServerGrammar
{
    protected function typeVarChar(Fluent $column)
    {
        return "varchar({$column->length})";
    }
}