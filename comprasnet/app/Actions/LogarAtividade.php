<?php

namespace Comprasnet\App\Actions;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Comprasnet\App\Mail\ErroImportacao;
use Comprasnet\App\Models\LogAtividade;

class LogarAtividade {

    public static function handle(
        string $origem_atividade,
        string $tipo_atividade,
        string $situacao_atividade = 'success',
        string $detalhamento_atividade = null
    ): void
    {
        try {
            LogAtividade::create([
                'OriExecucao' => $origem_atividade,
                'TipAtividade' => $tipo_atividade,
                'SitAtividade' => $situacao_atividade,
                'DetExecucao' => $detalhamento_atividade,
                'DatLogAtividade' => Carbon::now(),
            ]);

        } catch (\Exception $e) {
            $message = '[ERRO] Não foi possível registrar log de importação - ' .
                'Origem Atividade: ' . $origem_atividade . ' | ' .
                'Tipo Atividade: ' . $tipo_atividade . ' | ' .
                'Situação Atividade: ' . $situacao_atividade . ' | ' .
                'Detalhamento Atividade : ' . $detalhamento_atividade;
            Log::error($message);
            Log::error($e);
            Mail::send(new ErroImportacao($message));
        }
    }
}