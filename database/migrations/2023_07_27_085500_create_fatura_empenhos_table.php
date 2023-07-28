<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Schemas\Blueprints\CustomBlueprint;
use App\Schemas\Grammars\CustomGrammar;
use Illuminate\Support\Facades\DB;

class CreateFaturaEmpenhosTable extends Migration
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

        $schema->create('Fatura_Empenho', function (Blueprint $table) {

            /**
             * O retorno da API para o vínculo traz o id do empenho, por isso utilizamos o
             * vínculo do id local da tabela fatura com o id original da tablea empenho,
             * redizindo uma chamada sql no baco para o vinculo na tabela de empenhos.
             */

            // Chave Primária
            $table->bigIncrements('IdFaturaEmpenho');

            $table->bigInteger('IdFatura');
            $table->bigInteger('IdEmpenhoOriginal')->nullable();

            $table->index('IdFatura', 'Idx_Fatura_Empenho_IdFatura');
            $table->index('IdEmpenhoOriginal', 'Idx_Fatura_Empenho_IdEmpenhoOriginal');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Fatura_Empenho');
    }
}
