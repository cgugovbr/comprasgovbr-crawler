<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Schemas\Blueprints\CustomBlueprint;
use App\Schemas\Grammars\CustomGrammar;
use Illuminate\Support\Facades\DB;

class CreateFaturaTable extends Migration
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

        $schema->create('Fatura', function (Blueprint $table) {

            // Chave Primária
            $table->bigIncrements('IdFatura');
            $table->bigInteger('IdFaturaOriginal');
            $table->bigInteger('IdContrato')->unsigned()->nullable();
            $table->varChar('TipoListaFaturaId')->nullable();
            $table->varChar('TxtJustificativaFaturaId')->nullable();
            $table->varChar('TxtSfPadraoId')->nullable();
            $table->varChar('NumFatura')->nullable();
            $table->date('DatEmissao')->nullable();
            $table->date('DatPrazo')->nullable();
            $table->date('DatVencimento')->nullable();
            $table->decimal('ValValor', 17, 2)->nullable();
            $table->decimal('ValJuros', 17, 2)->nullable();
            $table->decimal('ValMulta', 17, 2)->nullable();
            $table->decimal('ValGlosa', 17, 2)->nullable();
            $table->decimal('ValValorLiquido', 17, 2)->nullable();
            $table->varChar('NumProcesso')->nullable();
            $table->date('DatProtocolo')->nullable();
            $table->date('DatAteste')->nullable();
            $table->varChar('SitRepactuacao')->nullable();
            $table->varChar('TxtInfComplementar')->nullable();
            $table->varChar('TxtMesRef')->nullable();
            $table->varChar('TxtAnoRef')->nullable();
            $table->varChar('SitFatura')->nullable();
            $table->varChar('TxtChaveNfe')->nullable();

            // Indices
            $table->index('IdFaturaOriginal', 'Idx_Fatura_IdFaturaOriginal');
            $table->index('NumFatura', 'Idx_Fatura_NumFatura');
        });

        Schema::table('Fatura', function (Blueprint $table) {
            $table->foreign('IdContrato', 'FK_Fatura_Contrato')->references('IdContrato')->on('Contrato')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Fatura');
    }
}
