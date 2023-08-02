<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Schemas\Grammars\CustomGrammar;
use Illuminate\Database\Schema\Blueprint;
use App\Schemas\Blueprints\CustomBlueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogAtividadeTable extends Migration
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

        $schema->create('LogAtividade', function (Blueprint $table) {

            // Chave Primária
            $table->bigIncrements('IdLogAtividade');
            $table->varChar('OriExecucao');
            $table->varChar('TipAtividade'); // Importação / Conexão / e-Mail
            $table->varChar('SitAtividade'); // 'success' / 'error' / 'warning'
            $table->dateTime('DatLogAtividade');
            $table->string('DetExecucao', 'max')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('LogarAtividade');
    }
}
