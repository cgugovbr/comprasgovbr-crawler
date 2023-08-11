<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Schemas\Blueprints\CustomBlueprint;
use App\Schemas\Grammars\CustomGrammar;
use Illuminate\Support\Facades\DB;

class AlterTableHistoricoIdHistoricoOriginal extends Migration
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

        $schema->table('Historico', function (Blueprint $table) {
            $table->bigInteger('IdHistoricoOriginal')->nullable()->after('IdHistorico'); // After não funciona para o sql server

            // Indices
            $table->index('IdHistoricoOriginal', 'Idx_Historico_IdHistoricoOriginal');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('Historico', function (Blueprint $table) {
            $table->dropIndex('Idx_Historico_IdHistoricoOriginal');
            $table->dropColumn('IdHistoricoOriginal');
        });
    }
}
