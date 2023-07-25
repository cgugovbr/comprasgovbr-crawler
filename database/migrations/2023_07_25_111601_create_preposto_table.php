<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Schemas\Blueprints\CustomBlueprint;
use App\Schemas\Grammars\CustomGrammar;
use Illuminate\Support\Facades\DB;

class CreatePrepostoTable extends Migration
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

        $schema->create('Preposto', function (Blueprint $table) {

            // Chave Primária
            $table->increments('IdPreposto');
            $table->bigInteger('IdPrepostoOriginal');
            $table->integer('IdContrato')->unsigned()->nullable();
            $table->varChar('NomUsuario')->nullable();
            $table->varChar('EmlUsuario')->nullable();
            $table->varChar('TelFixo')->nullable();
            $table->varChar('TelCelular')->nullable();
            $table->varChar('TxtDocFormalizacao')->nullable();
            $table->varChar('TxtInformacaoComplementar')->nullable();
            $table->date('DatInicio')->nullable();
            $table->date('DatFim')->nullable();
            $table->varChar('SitPreposto')->nullable();

            // Indices
            $table->index('IdPrepostoOriginal', 'Idx_Preposto_NumPreposto');
        });

        Schema::table('Preposto', function (Blueprint $table) {
            $table->foreign('IdContrato', 'FK_Preposto_Contrato')->references('IdContrato')->on('Contrato')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Preposto');
    }
}
