<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Schemas\Grammars\CustomGrammar;
use Illuminate\Database\Schema\Blueprint;
use App\Schemas\Blueprints\CustomBlueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnFundamentoLegalAditivoHistoricoTable extends Migration
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
            if (!Schema::hasColumns('Historico',
                ['TxtFundamentoLegalAditivo'])) {
                $table->varChar('TxtFundamentoLegalAditivo')->nullable();
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
        if (Schema::hasColumns('Historico',
            ['TxtFundamentoLegalAditivo'])) {

            Schema::table('Historico', function (Blueprint $table) {
                $table->dropcolumn('TxtFundamentoLegalAditivo');
            });
        }
    }
}
