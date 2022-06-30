<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->updateVarcharSize('Contrato', 'NumCnpjCpf');
        $this->updateVarcharSize('Historico', 'NumCnpjCpf');
    }

    private function updateVarcharSize($tableName, $columnName, $size = 255)
    {
        DB::statement('ALTER TABLE ' . $tableName . ' ALTER COLUMN ' . $columnName . ' VARCHAR (' . $size . ')');
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
};
