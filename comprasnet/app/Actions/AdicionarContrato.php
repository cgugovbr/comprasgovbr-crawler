<?php

namespace Comprasnet\App\Actions;

use Comprasnet\App\Models\Contrato;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Comprasnet\App\Mail\ErroImportacao;

class AdicionarContrato extends ActionsCommon {

    /**
     * Adiciona/Atualiza um Contrato
     *
     * @param $data
     * @param $command
     *
     * @return void
     */
    public static function addContrato($data, $command = null) : void
    {
        try {

            $contrato = Contrato::firstOrNew(['IdContrato' => $data['id']]);

            $contrato->IdContrato = $data['id'];
            $contrato->TxtReceitaDespesa = (isset($data['receita_despesa']) && $data['receita_despesa'] <> '') ? $data['receita_despesa'] : null;
            $contrato->NumContrato = (isset($data['numero' ]) && $data['numero'] <> '') ? $data['numero'] : null;

            // Contratante
            $contrato->CodOrgaoContratante = ActionsCommon::validaDataArray($data, 'contratante.orgao.codigo', 'orgao_codigo');
            $contrato->NomOrgaoContratante = ActionsCommon::validaDataArray($data, 'contratante.orgao.nome', 'orgao_nome');
            $contrato->CodUnidadeGestoraContratante = ActionsCommon::validaDataArray($data, 'contratante.orgao.unidade_gestora.codigo', 'unidade_codigo');
            $contrato->ResNomeUnidadeGestoraContratante = ActionsCommon::validaDataArray($data, 'contratante.orgao.unidade_gestora.nome_resumido', 'unidade_nome_resumido');
            $contrato->NomUnidadeGestoraContratante = ActionsCommon::validaDataArray($data, 'contratante.orgao.unidade_gestora.nome', 'unidade_nome');

            // Fornecedor
            $contrato->TpFornecedor = ActionsCommon::validaDataArray($data, 'fornecedor.tipo', 'fornecedor_tipo');
            $contrato->NumCnpjCpf = str_replace(['.', '/', '-'], ['', '', ''], ActionsCommon::validaDataArray($data, 'fornecedor.cnpj_cpf_idgener', 'fonecedor_cnpj_cpf_idgener'));
            $contrato->NomFornecedor = ActionsCommon::validaDataArray($data, 'fornecedor.nome', 'fornecedor_nome');

            $contrato->TpContrato = ActionsCommon::validaDataArray($data,'tipo');
            $contrato->SitContrato = ActionsCommon::validaDataArray($data,'situacao');
            $contrato->CatContrato = ActionsCommon::validaDataArray($data,'categoria');
            $contrato->TxtSubcategoria = ActionsCommon::validaDataArray($data,'subcategoria');
            $contrato->NomUnidadesRequisitantes = ActionsCommon::validaDataArray($data,'unidades_requisitantes');
            $contrato->NumProcesso = ActionsCommon::validaDataArray($data,'processo');
            $contrato->DescObjeto = ActionsCommon::validaDataArray($data,'objeto');
            $contrato->TxtInformacaoComplementar = ActionsCommon::validaDataArray($data,'informacao_complementar');
            $contrato->DescModalidade = ActionsCommon::validaDataArray($data,'modalidade');
            $contrato->NumLicitacao = ActionsCommon::validaDataArray($data,'licitacao_numero');
            $contrato->DatAssinatura = ActionsCommon::validaDataArray($data,'data_assinatura');
            $contrato->DatPublicacao = ActionsCommon::validaDataArray($data,'data_publicacao');
            $contrato->DatVigenciaInicio = ActionsCommon::validaDataArray($data,'vigencia_inicio');
            $contrato->DatVigenciaFim = ActionsCommon::validaDataArray($data,'vigencia_fim');
            $contrato->ValInicial = str_replace(['.', ','], ['', '.'], ActionsCommon::validaDataArray($data,'valor_inicial'));
            $contrato->ValGlobal = str_replace(['.', ','], ['', '.'], ActionsCommon::validaDataArray($data,'valor_global'));
            $contrato->NumParcelas = ActionsCommon::validaDataArray($data,'num_parcelas');
            $contrato->ValParcela = str_replace(['.', ','], ['', '.'], ActionsCommon::validaDataArray($data,'valor_parcela'));
            $contrato->ValAcumulado = str_replace(['.', ','], ['', '.'], ActionsCommon::validaDataArray($data,'valor_acumulado'));
            $contrato->TpSubtipo = ActionsCommon::validaDataArray($data,'subtipo');
            $contrato->SitProrrogavel = ActionsCommon::validaDataArray($data,'prorrogavel');
            $contrato->TxtJustificativaInativo = ActionsCommon::validaDataArray($data,'justificativa_inativo');
            $contrato->TxtAmparoLegal = ActionsCommon::validaDataArray($data,'amparo_legal');
            $contrato->TxtFundamentoLegal = ActionsCommon::validaDataArray($data,'fundamento_legal');
            $contrato->TxtSisOriLicitacao = ActionsCommon::validaDataArray($data,'sistema_origem_licitacao');
            $contrato->CodUnidadeCompra = ActionsCommon::validaDataArray($data,'unidade_compra');

            $contrato->save();

            if ($command) {
                $command->info('Contrato com id ' . $data['id'] . ' inserido/atualizado com sucesso!');
            }

        } catch (\Exception $e) {
            $message = '[ERRO] Erro ao criar/atualizar contrasto - IdContrato' . $data['id'];
            Log::error($message);
            Log::error($e);
            Mail::send(new ErroImportacao($message));
        }
    }
}