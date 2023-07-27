<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Schemas\Blueprints\CustomBlueprint;
use App\Schemas\Grammars\CustomGrammar;
use Illuminate\Support\Facades\DB;

class CreateFaturaItensTable extends Migration
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

        $schema->create('Fatura_Itens', function (Blueprint $table) {

            // Chave Primária
            $table->bigIncrements('IdFaturaItens');

            $table->bigInteger('IdFatura');
            $table->bigInteger('IdItemContratoOriginal')->nullable();
            $table->decimal('QtdFaturada', 15, 5)->nullable();
            $table->decimal('ValUnitarioFaturado', 19, 4)->nullable();
            $table->decimal('ValTotalFaturado', 17, 2)->nullable();

            $table->index('IdItemContratoOriginal', 'Idx_Fatura_Itens_IdItemContratoOriginal');
        });

        Schema::table('Fatura_Itens', function (Blueprint $table) {
            $table->foreign('IdFatura', 'FK_Fatura_Itens_Fatura')->references('IdFatura')->on('Fatura')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Fatura_Itens');
    }
}
