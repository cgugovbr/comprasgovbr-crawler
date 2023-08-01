<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Schemas\Grammars\CustomGrammar;
use Illuminate\Database\Schema\Blueprint;
use App\Schemas\Blueprints\CustomBlueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsUnidadeGestoraFonteRecursoEmpenhoTable extends Migration
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
            if (!Schema::hasColumns('Empenho',
                ['CodUnidadeGestora', 'NumGestao', 'DatEmissao', 'TxtInformacaoComplementar',
                    'TxtSisOrigem', 'TxtFonteRecurso'])) {
                $table->varChar('CodUnidadeGestora')->nullable();
                $table->varChar('NumGestao')->nullable();
                $table->date('DatEmissao')->nullable();
                $table->string('TxtInformacaoComplementar', 'max')->nullable();
                $table->varChar('TxtSisOrigem')->nullable();
                $table->varChar('TxtFonteRecurso')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumns('Empenho',
            ['CodUnidadeGestora', 'NumGestao', 'DatEmissao', 'TxtInformacaoComplementar',
                'TxtSisOrigem', 'TxtFonteRecurso'])) {

            Schema::table('Empenho', function (Blueprint $table) {
                $table->dropcolumn('CodUnidadeGestora');
                $table->dropcolumn('NumGestao');
                $table->dropcolumn('DatEmissao');
                $table->dropcolumn('TxtInformacaoComplementar');
                $table->dropcolumn('TxtSisOrigem');
                $table->dropcolumn('TxtFonteRecurso');
            });
        }
    }
}
