<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Schemas\Blueprints\CustomBlueprint;
use App\Schemas\Grammars\CustomGrammar;
use Illuminate\Support\Facades\DB;

class AlterTableContratoAddColumnsPrepArqRespFat extends Migration
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

        $schema->table('Contrato', function (Blueprint $table) {
            $table->varChar('EndLinkPrepostos')->nullable()->after('EndLinkHistorico');
            $table->varChar('EndLinkFaturas')->nullable()->after('EndLinkPrepostos');
            $table->varChar('EndLinkResponsaveis')->nullable()->after('EndLinkFaturas');
            $table->varChar('EndLinkArquivos')->nullable()->after('EndLinkResponsaveis');
            $table->varChar('EndLinkPublicacoes')->nullable()->after('EndLinkArquivos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('Contrato', function (Blueprint $table) {
            $table->dropColumn('EndLinkPrepostos');
            $table->dropColumn('EndLinkFaturas');
            $table->dropColumn('EndLinkResponsaveis');
            $table->dropColumn('EndLinkArquivos');
            $table->dropColumn('EndLinkPublicacoes');
        });
    }
}
