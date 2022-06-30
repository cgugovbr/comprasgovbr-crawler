<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Schemas\Blueprints\CustomBlueprint;
use App\Schemas\Grammars\CustomGrammar;
use Illuminate\Support\Facades\DB;

class AlterTableAddColumnsSubcatagoriaUnidadesRequisitantes extends Migration
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
            $table->varChar('TxtSubcategoria')->nullable()->after('CatContrato');
            $table->varChar('NomUnidadesRequisitantes')->nullable()->after('CatContrato');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('Contrato', function (Blueprint $table) {
            $table->dropColumn('TxtSubcategoria');
            $table->dropColumn('NomUnidadesRequisitantes');
        });
    }
}
