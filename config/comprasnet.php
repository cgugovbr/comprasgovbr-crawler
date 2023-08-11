<?php

/**
 * Arquivo de configuração para leitura da API do Sistema Conta
 */

return [
    'base_url' => env('COMPRASNET_BASE_URL', 'https://contratos.comprasnet.gov.br/api'),

    'orgao' => env('COMPRASNET_CODIGO_ORGAO', '25000'),

    'ug' => env('COMPRASNET_CODIGO_UG'),

    // Necessário manter a barra ao final quando for uma busca por órgão ou ug '/contrato/orgao/'
    'contratos' => [
        'full' => '/contrato',
        'contrato' => '/contrato',
        'orgao' => '/contrato/orgao/',
        'ug' => '/contrato/ug/',
        'inativo_orgao' => '/contrato/inativo/orgao/',
        'inativo_ug' => '/contrato/inativo/ug/'
    ],

    'emails_to' => explode(',', env('COMPRASNET_ADM_EMAILS')),

    'emails_to_support' => explode(',', env('COMPRASNET_SUPPORT_EMAILS')),

    'usuario_sistema' => env('COMPRASNET_USUARIO_SISTEMA'),

    'senha_usuario' => env('COMPRASNET_SENHA_SISTEMA')
];
