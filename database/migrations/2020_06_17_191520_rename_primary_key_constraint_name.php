<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RenamePrimaryKeyConstraintName extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->renameConstraintPK('Contrato');
        $this->renameConstraintPK('Cronograma');
        $this->renameConstraintPK('Empenho');
        $this->renameConstraintPK('Historico');
    }

    private function renameConstraintPK($tableName)
    {
        DB::statement('
            DECLARE @OLD_PK_KEY VARCHAR(255) = (SELECT name FROM sys.objects WHERE parent_object_id = (OBJECT_ID(\'' . $tableName . '\')) AND type IN (\'PK\'));
            EXEC sp_rename @OLD_PK_KEY, \'PK_' . $tableName . '\'
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
