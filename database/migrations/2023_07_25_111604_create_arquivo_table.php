<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Schemas\Blueprints\CustomBlueprint;
use App\Schemas\Grammars\CustomGrammar;
use Illuminate\Support\Facades\DB;

class CreateArquivoTable extends Migration
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

        $schema->create('Arquivo', function (Blueprint $table) {

            // Chave Primária
            $table->increments('IdArquivo');
            $table->bigInteger('IdArquivoOriginal');
            $table->integer('IdContrato')->unsigned()->nullable();
            $table->varChar('TipArquivo')->nullable();
            $table->varChar('NumProcesso')->nullable();
            $table->varChar('NumSequencialDocumento')->nullable();
            $table->varChar('TxtDescricao')->nullable();
            $table->varChar('TxtPathArquivo')->nullable();
            $table->varChar('OriArquivo')->nullable();
            $table->varChar('UrlLinkSei')->nullable();

            // Indices
            $table->index('IdArquivoOriginal', 'Idx_Arquivo_IdArquivoOriginal');
        });

        Schema::table('Arquivo', function (Blueprint $table) {
            $table->foreign('IdContrato', 'FK_Arquivo_Contrato')->references('IdContrato')->on('Contrato')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Arquivo');
    }
}
