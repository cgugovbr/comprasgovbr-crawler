<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Schemas\Blueprints\CustomBlueprint;
use App\Schemas\Grammars\CustomGrammar;
use Illuminate\Support\Facades\DB;

class AlterTableEmpenhoIdEmpenhoOriginal extends Migration
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

        $schema->table('Empenho', function (Blueprint $table) {
            $table->bigInteger('IdEmpenhoOriginal')->nullable();

            // Indices
            $table->index('IdEmpenhoOriginal', 'Idx_Empenho_IdEmpenhoOriginal');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('Empenho', function (Blueprint $table) {
            $table->dropIndex('Idx_Empenho_IdEmpenhoOriginal');
            $table->dropColumn('IdEmpenhoOriginal');
        });
    }
}
