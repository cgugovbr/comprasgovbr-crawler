<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Schemas\Blueprints\CustomBlueprint;
use App\Schemas\Grammars\CustomGrammar;
use Illuminate\Support\Facades\DB;

class CreateContratoItemTable extends Migration
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

        $schema->create('ContratoItem', function (Blueprint $table) {

            // Chave Primária
            $table->bigIncrements('IdContratoItem');

            $table->bigInteger('IdContratoItemOriginal');
            $table->bigInteger('IdContrato');
            $table->varChar('TipId')->nullable();
            $table->varChar('TipMaterial')->nullable();
            $table->varChar('GrpId')->nullable();
            $table->string('CatMatSerItemId', 'max')->nullable();
            $table->string('DescComplementar', 'max')->nullable();
            $table->integer('QtdItem')->nullable();
            $table->decimal('ValUnitario', 17, 2)->nullable();
            $table->decimal('ValTotal', 17, 2)->nullable();
            $table->varChar('NumItemCompra')->nullable();
            $table->dateTimeTz('datInicioItem')->nullable();
        });

        Schema::table('ContratoItem', function (Blueprint $table) {
            $table->foreign('IdContrato', 'FK_ContratoItem_Contrato')->references('IdContrato')->on('Contrato')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ContratoItem');
    }
}
