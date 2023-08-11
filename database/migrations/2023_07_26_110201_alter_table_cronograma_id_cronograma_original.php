<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Schemas\Blueprints\CustomBlueprint;
use App\Schemas\Grammars\CustomGrammar;
use Illuminate\Support\Facades\DB;

class AlterTableCronogramaIdCronogramaOriginal extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->setSchemaGrammar(new CustomGrammar());
        $schema = DB::connection()->getSchemaBuilder();

        $schema->blueprintResolver(function($table, $callback) {
            return new CustomBlueprint($table, $callback);
        });

        $schema->table('Cronograma', function (Blueprint $table) {
            $table->bigInteger('IdCronogramaOriginal')->nullable();

            // Indices
            $table->index('IdCronogramaOriginal', 'Idx_Cronograma_IdCronogramaOriginal');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('Cronograma', function (Blueprint $table) {
            $table->dropIndex('Idx_Cronograma_IdCronogramaOriginal');
            $table->dropColumn('IdCronogramaOriginal');
        });
    }
}
