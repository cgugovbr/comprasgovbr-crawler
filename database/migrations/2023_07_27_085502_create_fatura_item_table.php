<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Schemas\Blueprints\CustomBlueprint;
use App\Schemas\Grammars\CustomGrammar;
use Illuminate\Support\Facades\DB;

class CreateFaturaItemTable extends Migration
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

        $schema->create('FaturaItem', function (Blueprint $table) {

            // Chave Primária
            $table->bigIncrements('IdFaturaItem');

            $table->bigInteger('IdFatura');
            $table->bigInteger('IdItemContratoOriginal')->nullable();
            $table->decimal('QtdFaturada', 15, 5)->nullable();
            $table->decimal('ValUnitarioFaturado', 19, 4)->nullable();
            $table->decimal('ValTotalFaturado', 17, 2)->nullable();

            $table->index('IdItemContratoOriginal', 'Idx_FaturaItem_IdItemContratoOriginal');
        });

        Schema::table('FaturaItem', function (Blueprint $table) {
            $table->foreign('IdFatura', 'FK_FaturaItem_Fatura')->references('IdFatura')->on('Fatura')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('FaturaItem');
    }
}
