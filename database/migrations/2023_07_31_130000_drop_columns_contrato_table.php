<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Schemas\Grammars\CustomGrammar;
use Illuminate\Database\Schema\Blueprint;
use App\Schemas\Blueprints\CustomBlueprint;
use Illuminate\Database\Migrations\Migration;

class DropColumnsContratoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumns('Contrato', ['EndLinkHistorico', 'EndLinkEmpenhos', 'EndLinkCronograma'])) {

            Schema::table('Contrato', function (Blueprint $table) {
                $table->dropcolumn('EndLinkHistorico');
                $table->dropcolumn('EndLinkEmpenhos');
                $table->dropcolumn('EndLinkCronograma');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::connection()->setSchemaGrammar(new CustomGrammar());
        $schema = DB::connection()->getSchemaBuilder();

        $schema->blueprintResolver(function($table, $callback) {
            return new CustomBlueprint($table, $callback);
        });

        $schema->table('Contrato', function (Blueprint $table) {
            if (!Schema::hasColumns('Contrato', ['EndLinkHistorico', 'EndLinkEmpenhos', 'EndLinkCronograma'])) {
                $table->varChar('EndLinkHistorico')->nullable();
                $table->varChar('EndLinkEmpenhos')->nullable();
                $table->varChar('EndLinkCronograma')->nullable();
            }
        });
    }
}
