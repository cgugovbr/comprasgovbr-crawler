<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Schemas\Blueprints\CustomBlueprint;
use App\Schemas\Grammars\CustomGrammar;
use Illuminate\Support\Facades\DB;

class CreatePublicacaoTable extends Migration
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

        $schema->create('Publicacao', function (Blueprint $table) {

            // Chave Primária
            $table->bigIncrements('IdPublicacao');

            $table->bigInteger('IdContrato');
            $table->bigInteger('IdPublicacaoOriginal');
            $table->bigInteger('IdHistoricoOriginal')->unsigned()->nullable();
            $table->date('DatPublicacao')->nullable();
            $table->bigInteger('IdStatusPublicacaoOriginal')->unsigned()->nullable();
            $table->varChar('SitStatus')->nullable();
            $table->string('TxtTextoDOU', 'max')->nullable(); // nVarChar(max)
            $table->varChar('UrlLinkPublicacao')->nullable();

            // Indices
            $table->index('IdPublicacaoOriginal', 'Idx_Publicacao_IdPublicacaoOriginal');
            $table->index('IdHistoricoOriginal', 'Idx_Publicacao_IdHistoricoOriginal');
            $table->index('IdStatusPublicacaoOriginal', 'Idx_Publicacao_IdStatusPublicacaoOriginal');
        });

        Schema::table('Publicacao', function (Blueprint $table) {
            $table->foreign('IdContrato', 'FK_Publicacao_Contrato')->references('IdContrato')->on('Contrato')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Publicacao');
    }
}
