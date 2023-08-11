# Comandos

## Buscando os dados da API do ComprasGovBr Contratos

O sistema não possui interface gráfica, como citado anteriormente,
portanto acesse o terminal de sua aplicação e rode o seguinte comando:

```bash
php artisan comprasnet:contratos --empenho --cronograma --historico --email --inativos
```

O comando acima irá buscar todos os contratos, seus respectivos empenhos (_--empenho_),
o cronograma (_--cronograma_), o histórico (_--historico_),
busca também os contratos **inativos** (opção _--inativos_) e envia uma mensagem no email
quando finaliza a execução do script (_--email_).

Existem outros comando, buscar somente os empenhos de um contrato, historico ou cronograma.
Todos os comandos possuem um _helper_ com as devidas descrições.
Para acessar digite no console:

```bash
php artisan

[...]
comprasnet
  comprasnet:arquivos         Importar os Arquivos de um Contrato
  comprasnet:contrato         Importar um contrato e seus dados relacionados
  comprasnet:contratos:orgao  Importar TODOS os contratos de um órgão
  comprasnet:cronograma       Importar o Cronograma de um Contrato
  comprasnet:empenhos         Importar os Empenhos de um Contrato
  comprasnet:faturas          Importar os Faturas de um Contrato
  comprasnet:historico        Importar o Historico de um Contrato
  comprasnet:prepostos        Importar os Prepostos de um Contrato
  comprasnet:publicacoes      Importar Publicações de um Contrato
  comprasnet:responsaveis     Importar os Responsaveis de um Contrato
```

Ou para verificar a funcionalidade e opções de um comando digite:

```bash
php artisan comprasnet:empenhos -h

Description:
  Importar os Empenhos de um Contrato

Usage:
  comprasnet:empenhos [options] [--] <contrato>

Arguments:
  contrato                   Número do Contrato (IdContrato - ex: 2660)

Options:
      --email                Enviar email com relatório da execução
      --email_to[=EMAIL_TO]  Email a ser enviado
  -h, --help                 Display this help message
  -q, --quiet                Do not output any message
  -V, --version              Display this application version
      --ansi                 Force ANSI output
      --no-ansi              Disable ANSI output
  -n, --no-interaction       Do not ask any interactive question
      --env[=ENV]            The environment the command should run under
  -v|vv|vvv, --verbose       Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug
```
