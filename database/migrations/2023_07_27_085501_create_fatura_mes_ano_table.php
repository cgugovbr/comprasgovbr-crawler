<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Schemas\Blueprints\CustomBlueprint;
use App\Schemas\Grammars\CustomGrammar;
use Illuminate\Support\Facades\DB;

class CreateFaturaMesAnoTable extends Migration
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

        $schema->create('Fatura_Mes_Ano', function (Blueprint $table) {

            // Chave Primária
            $table->bigIncrements('IdFaturaMesAno');

            $table->bigInteger('IdFatura');
            $table->varChar('TxtMesRef')->nullable();
            $table->varChar('TxtAnoRef')->nullable();
            $table->decimal('ValValorRef', 17, 2)->nullable();
        });

        Schema::table('Fatura_Mes_Ano', function (Blueprint $table) {
            $table->foreign('IdFatura', 'FK_Fatura_Mes_Ano_Fatura')->references('IdFatura')->on('Fatura')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Fatura_Mes_Ano');
    }
}
