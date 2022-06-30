<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Schemas\Blueprints\CustomBlueprint;
use App\Schemas\Grammars\CustomGrammar;
use Illuminate\Support\Facades\DB;

class CreateEmpenhoTable extends Migration
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

        $schema->create('Empenho', function (Blueprint $table) {

            // Chave Primária
            $table->increments('IdEmpenho');

            $table->integer('IdContrato')->unsigned()->nullable();
            $table->varChar('NumEmpenho', 12);
            $table->varChar('NomCredor');
            $table->varChar('TxtPlanoInterno')->nullable();
            $table->varChar('DescNaturezaDepesa');
            $table->decimal('ValEmpenhado', 15, 2)->nullable();
            $table->decimal('ValALiquidar', 15, 2)->nullable();
            $table->decimal('ValLiquidado', 15, 2)->nullable();
            $table->decimal('ValPago', 15, 2)->nullable();
            $table->decimal('ValRPInscrito', 15, 2)->nullable();
            $table->decimal('ValRPALiquidar', 15, 2)->nullable();
            $table->decimal('ValRPLiquidado', 15, 2)->nullable();
            $table->decimal('ValRPPago', 15, 2)->nullable();

            // Indices
            $table->index('NumEmpenho', 'Idx_Empenho_NumEmpenho');
        });

        Schema::table('Empenho', function (Blueprint $table) {
            $table->foreign('IdContrato', 'FK_Empenho_Contrato')->references('IdContrato')->on('Contrato')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Empenho');
    }
}
