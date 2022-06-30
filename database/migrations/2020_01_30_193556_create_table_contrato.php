<?php

use App\Schemas\Grammars\CustomGrammar;
use App\Schemas\Blueprints\CustomBlueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateTableContrato extends Migration
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

        $schema->create('Contrato', function (Blueprint $table) {
            $table->integer('IdContrato');
            $table->varChar('TxtReceitaDespesa')->nullable();
            $table->varChar('NumContrato')->nullable();

            // Contratante
            $table->integer('CodOrgaoContratante')->nullable();
            $table->varChar('NomOrgaoContratante')->nullable();
            $table->integer('CodUnidadeGestoraContratante')->nullable();
            $table->varChar('ResNomeUnidadeGestoraContratante', 50)->nullable();
            $table->varChar('NomUnidadeGestoraContratante')->nullable();

            // Fornecedor
            $table->varChar('TpFornecedor')->nullable();
            $table->varChar('NumCnpjCpf')->nullable();
            $table->varChar('NomFornecedor')->nullable();

            $table->varChar('TpContrato')->nullable();
            $table->varChar('CatContrato')->nullable();
            $table->varChar('NumProcesso')->nullable();
            $table->varChar('DescObjeto', 8000)->nullable();
            $table->varChar('TxtInformacaoComplementar', 8000)->nullable();
            $table->varChar('DescModalidade')->nullable();
            $table->varChar('NumLicitacao')->nullable();
            $table->date('DatAssinatura')->nullable();
            $table->date('DatPublicacao')->nullable();
            $table->date('DatVigenciaInicio')->nullable();
            $table->date('DatVigenciaFim')->nullable();
            $table->decimal('ValInicial', 15, 2)->nullable();
            $table->decimal('ValGlobal', 15, 2)->nullable();
            $table->integer('NumParcelas')->nullable();
            $table->decimal('ValParcela', 15, 2)->nullable();
            $table->decimal('ValAcumulado', 15, 2)->nullable();
            $table->varChar('EndLinkHistorico')->nullable();
            $table->varChar('EndLinkEmpenhos')->nullable();
            $table->varChar('EndLinkCronograma')->nullable();

            // Chave Primária
            $table->primary('IdContrato', 'PK_Contrato');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Contrato');
    }
}
