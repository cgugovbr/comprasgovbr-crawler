<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Schemas\Blueprints\CustomBlueprint;
use App\Schemas\Grammars\CustomGrammar;
use Illuminate\Support\Facades\DB;

class CreateResponsavelTable extends Migration
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

        $schema->create('Responsavel', function (Blueprint $table) {

            // Chave Primária
            $table->increments('IdResponsavel');
            $table->bigInteger('IdResponsavelOriginal');
            $table->integer('IdContrato')->unsigned()->nullable();
            $table->varChar('NomUsuario')->nullable();
            $table->varChar('TxtFuncaoId')->nullable();
            $table->varChar('TxtInstalacaoId')->nullable();
            $table->varChar('TxtPortaria')->nullable();
            $table->varChar('SitResponsavel')->nullable();
            $table->date('DatInicio')->nullable();
            $table->date('DatFim')->nullable();

            // Indices
            $table->index('IdResponsavelOriginal', 'Idx_Responsavel_IdResponsavelOriginal');
        });

        Schema::table('Responsavel', function (Blueprint $table) {
            $table->foreign('IdContrato', 'FK_Responsavel_Contrato')->references('IdContrato')->on('Contrato')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Responsavel');
    }
}
