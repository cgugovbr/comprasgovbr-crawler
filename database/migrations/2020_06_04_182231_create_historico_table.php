<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Schemas\Blueprints\CustomBlueprint;
use App\Schemas\Grammars\CustomGrammar;
use Illuminate\Support\Facades\DB;

class CreateHistoricoTable extends Migration
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

        $schema->create('Historico', function (Blueprint $table) {

            // Chave Primária
            $table->increments('IdHistorico')->generatedAs('1,1');

            $table->integer('IdContrato')->unsigned()->nullable();

            $table->varChar('TxtReceitaDespesa')->nullable();
            $table->varChar('NumContrato')->nullable();
            $table->varChar('ObsHistorico', 8000)->nullable();
            $table->integer('CodUG')->nullable();

            // Fornecedor
            $table->varChar('TpFornecedor')->nullable();
            $table->varChar('NumCnpjCpf', 14)->nullable();
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

            $table->decimal('ValGlobalNovo', 15, 2)->nullable();
            $table->integer('NumParcelasNovo')->nullable();
            $table->decimal('ValParcelaNovo', 15, 2)->nullable();
            $table->date('DatInicioNovoValor')->nullable();

            $table->varChar('FlgRetroativo', 5)->nullable();

            $table->tinyInteger('MesReferenciaRetroativoDE')->nullable();
            $table->smallInteger('AnoReferenciaRetroativoDE')->nullable();
            $table->tinyInteger('MesReferenciaRetroativoATE')->nullable();
            $table->smallInteger('AnoReferenciaRetroativoATE')->nullable();
            $table->date('DatVencimentorRetroativo')->nullable();
            $table->decimal('ValRetroativo', 15, 2)->nullable();
        });

        // ALTER TABLE Historico ADD identity_IdHistorico INT IDENTITY(1,1)

        // ALTER TABLE Historico DROP COLUMN OldColumnName

        // EXEC sp_rename 'yourTable.NewColumn', 'OldColumnName', 'COLUMN'
        // DB::statement('EXEC sp_rename Historico.;');
        // DB::statement('ALTER TABLE Historico ON;');
        // DB::statement('SET IDENTITY_INSERT Historico ON;');

        Schema::table('Historico', function (Blueprint $table) {
            $table->foreign('IdContrato', 'FK_Historico_Contrato')->references('IdContrato')->on('Contrato')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Historico');
    }
}
