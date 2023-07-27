<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Schemas\Blueprints\CustomBlueprint;
use App\Schemas\Grammars\CustomGrammar;
use Illuminate\Support\Facades\DB;

class CreateCronogramaTable extends Migration
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

        $schema->create('Cronograma', function (Blueprint $table) {

            // Chave Primária
            $table->bigIncrements('IdCronograma')->generatedAs('1,1');

            $table->bigInteger('IdContrato')->unsigned()->nullable();

            $table->varChar('TpCronograma')->nullable();
            $table->varChar('NumCronograma', 20)->nullable();
            $table->varChar('TxtReceitaDespesa')->nullable();
            $table->varChar('ObsCronograma', 8000)->nullable();
            $table->tinyInteger('MesReferencia')->nullable();
            $table->smallInteger('AnoReferencia')->nullable();
            $table->date('DatVencimento')->nullable();
            $table->varChar('FlgRetroativo', 5)->nullable();
            $table->decimal('ValCronograma', 15, 2)->nullable();

            // Indices
            $table->index('TpCronograma', 'Idx_Cronograma_TpCronograma');
            $table->index('NumCronograma', 'Idx_Cronograma_NumCronograma');
            $table->index('MesReferencia', 'Idx_Cronograma_MesReferencia');
            $table->index('AnoReferencia', 'Idx_Cronograma_AnoReferencia');
            $table->index('DatVencimento', 'Idx_Cronograma_DatVencimento');
            $table->index('ValCronograma', 'Idx_Cronograma_ValCronograma');
        });

        Schema::table('Cronograma', function (Blueprint $table) {
            $table->foreign('IdContrato', 'FK_Cronograma_Contrato')->references('IdContrato')->on('Contrato')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Cronograma');
    }
}
