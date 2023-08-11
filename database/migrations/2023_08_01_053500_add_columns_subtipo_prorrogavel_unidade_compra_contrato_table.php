<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Schemas\Grammars\CustomGrammar;
use Illuminate\Database\Schema\Blueprint;
use App\Schemas\Blueprints\CustomBlueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsSubtipoProrrogavelUnidadeCompraContratoTable extends Migration
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

        $schema->table('Contrato', function (Blueprint $table) {
            if (!Schema::hasColumns('Contrato',
                ['TpSubtipo', 'SitProrrogavel', 'TxtJustificativaInativo',
                    'TxtAmparoLegal', 'TxtFundamentoLegal', 'TxtSisOriLicitacao'
                    , 'CodUnidadeCompra'])) {
                $table->varChar('TpSubtipo')->nullable();
                $table->varChar('SitProrrogavel')->nullable();
                $table->varChar('TxtJustificativaInativo')->nullable();
                $table->varChar('TxtAmparoLegal')->nullable();
                $table->varChar('TxtFundamentoLegal')->nullable();
                $table->varChar('TxtSisOriLicitacao')->nullable();
                $table->varChar('CodUnidadeCompra')->nullable();
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
        if (Schema::hasColumns('Contrato',
            ['TpSubtipo', 'SitProrrogavel', 'TxtJustificativaInativo',
            'TxtAmparoLegal', 'TxtFundamentoLegal', 'TxtSisOriLicitacao'
            , 'CodUnidadeCompra'])) {

            Schema::table('Contrato', function (Blueprint $table) {
                $table->dropcolumn('TpSubtipo');
                $table->dropcolumn('SitProrrogavel');
                $table->dropcolumn('TxtJustificativaInativo');
                $table->dropcolumn('TxtAmparoLegal');
                $table->dropcolumn('TxtFundamentoLegal');
                $table->dropcolumn('TxtSisOriLicitacao');
                $table->dropcolumn('CodUnidadeCompra');
            });
        }
    }
}
