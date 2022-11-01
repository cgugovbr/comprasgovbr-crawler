<div align="center"><img src="resources/images/comprasgovbr.png" width="240" height="240"></div>

# ComprasGovBr-Crawler

Este sistema foi desenvolvido com o intuito de importar dados do ComprasGovBr de um determinao órgão, 
incluíndo os respectivos contratos, empenhos, cronograma e histórico, para um banco de dados específico. 

> O sistema **NÃO** possui interface gráfica, somente scripts em PHP.

## Tecnologia

Este sistema foi desenvolvido utilizando Laravel versão 9.

Essa ferramenta é gratuita e cada instituição pública poderá utilizá-la sem limites.

Caso o órgão queira implementar nova funcionalidade, pedimos que após implentação e 
testes disponibilize neste repositório para que demais instituições possam utilizar.

## Licença

A licença dessa ferramenta é GPLv2. Pedimos que toda implementação seja disponibilizada para a comunidade.

## Instalação

**[IMPORTANTE]**
Esta versão utiliza SQL Server como banco de dados. Todos os arquivos de criação e versionamento do banco 
de dados (_migrations_) foram adaptados ao SQL Server. Caso deseja utilizar MySQL ou PostreSQL poderá 
simplificar os _migrations_.

### Requisitos Mímimos

- Servidor Linux
- ssh
- git
- composer
- Cron
- Acesso à internet
- PHP >= 8.0
- BCMath PHP Extension
- Ctype PHP Extension
- Fileinfo PHP extension
- JSON PHP Extension
- Mbstring PHP Extension
- OpenSSL PHP Extension
- PDO PHP Extension
- Tokenizer PHP Extension
- XML PHP Extension

### Primeiros passos

#### 1. Faça o "clone" deste repositório

```bash
git clone git@github.com:cgugovbr/comprasgovbr-crawler.git
```

### 2. Instale as dependências

Acesse a pasta recém criada 'comprasnet-crawler' antes de rodar o comando:

```bash
composer install
```

### 3. Configure as informações de sistema

Crie o arquivo '.env' a partir do arquivo de exemplo '.env.example' e gere uma chave para a aplicação:
```bash
cp .env.example .env
php artisan key:generate
```

Edite o arquivo de configuração '.env' com os seguintes dados:

3.1 Banco de dados

```env
DB_CONNECTION=sqlsrv
DB_HOST=127.0.0.1
DB_PORT=1433
DB_DATABASE=nome_database
DB_USERNAME=usuario_banco_dados
DB_PASSWORD=senha_usuario_banco_dados
```

3.2 Dados do seu órgão

- **COMPRASNET_CODIGO_ORGAO** - Código do seu órgão
- **COMPRASNET_ADM_EMAILS** - Emails separados por vírgulas que deverão receber notificações do sistema quando em produção
- **COMPRASNET_SUPPORT_EMAILS** - Emails separados por vírgulas que deverão receber notificações do sistema quando em _outros_ ambientes que não proudção
- **COMPRASNET_USUARIO_SISTEMA** - Usuário de sistema definido para seu órgão
- **COMPRASNET_SENHA_SISTEMA** - Senha de sistema definida para seu órgão

Além das configurações citadas acima deve-se configura os serviço de smtp do seu provedor de email.

```env
COMPRASNET_BASE_URL=https://contratos.comprasnet.gov.br/api
COMPRASNET_CODIGO_ORGAO=XXXXX
COMPRASNET_CODIGO_UG=
COMPRASNET_ADM_EMAILS="email01@domain.com,email02@domain.com,email03@domain.com,email04@domain.com,email05@domain.com,email06@domain.com"
COMPRASNET_SUPPORT_EMAILS="email01@domain.com,email02@domain.com"
COMPRASNET_USUARIO_SISTEMA=""
COMPRASNET_SENHA_SISTEMA=""

[...]

MAIL_DRIVER=smtp
MAIL_HOST=smtp.your_domain.gov.br
MAIL_PORT=587
MAIL_USERNAME=mail_service_username
MAIL_PASSWORD=mail_service_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=
MAIL_FROM_NAME=
```

3.3 Criação do banco de dados

Execute o seguinte comando para criar a estrutura do banco de dados:

```bash
php artisan migrate
```

**TUDO PRONTO! Já pode chamar as rotinas para buscar seus dados na API do ComprasGovBr. 
Os passos 4 e 5 (abaixo) somente deverão ser configurados nos ambientes de teste e produção.**

### 4. [PRODUÇÃO] Configurar o serviço cron do Linux

Quando o sistema estiver em produção será necessário configurar o crontab do servidor 
para chamar as rotins do sistema. Esta linha abaixo apenas configura para que o sistema
fique constantemente sendo requisitado no ambiente em produção, porém a periodicidade das 
requisições ao ComprasGovBr é determinado dentro do sistema. 
Veja mais detalhes no item 5.

```bash
echo "* * * * * root cd /var/www/ && php artisan schedule:run >> /dev/null 2>&1" >> /etc/crontab
```

> Atualize a raiz da aplicação na linha de comando acima ('/var/www')

### 5. [PRODUÇÃO] Configurando a periodicidade

Neste item poderemos configuarar a periodicidade das requsições de seus dados na API 
do ComprasGovBr Contratos. Para fazer essa configuração é necesário ter um breve conhecimento
de crontab, que pode ser facilmente verificado no site: 
[https://crontab.guru/](https://crontab.guru/).

Acesse o arquivo 'app\Console\Kernel.php' e edite a seguinte linha:

```php
    protected function schedule(Schedule $schedule)
    {
        [...]

        $schedule->command('comprasnet:contratos -e -c -i --inativos')
            // Edite esta linha abaixo com as configurações desejadas
            ->cron('0 5 * * *')
            ->sendOutputTo($file_path)
            ->onSuccess(function () use ($data) {
                Mail::send(new DadosImportados($data));
            })
            ->onFailure(function () use ($data) {
                Mail::send(new ErroImportacao($data));
            });
    }
```

No exemplo acima o sistema ira importar os dados todos os dias às 5h da manhã.

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
  comprasnet:contratos   Importar TODOS os contratos de um órgão
  comprasnet:cronograma  Importar o Cronograma de um Contrato
  comprasnet:empenhos    Importar os Empenhos de um Contrato
  comprasnet:historico   Importar o Historico de um Contrato
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

## Docker

Caso você utilize containers (Docker ou Kubernetes) existe um arquivo na raiz chamado 
Dockerfile que poderá ser utilizado para criar o cotnainer com todas as bibliotecas 
necessárias para rodar o sistema. Lembrando que neste caso temos algumas bibliotecas 
para o SQL Server, que poderá ser removida caso utilize outro banco de dados. 
O script de inicialização (_entrypoint.sh_) possui diversas funções dentre elas 
utilizando _secrets_ do Kubernetes para montar o arquivo de configuração '.env', 
configura o crontab automaticamente, rodar a migração dos dados e 
fazer cache das configurações do sistema. Caso não esteja utilizando Docker, 
pode ser interessante verificar as configurações feitas pelo script para 
reutilizar em seu ambiente de produção.

## Desenvolvimento

Caso deseja contruibuir com o desenvolvimento, para subir o ambiente local será necesário ter o Docker instalado
e após clonar a aplicação rodar os seguintes comandos:

### Instalar dependências:
```bash
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v $(pwd):/var/www/html \
    -w /var/www/html \
    laravelsail/php80-composer:latest \
    composer install --ignore-platform-reqs
```

### Iniciar a aplicação localmente
```bash
sail up
```

Qualquer dúvida sobre o Sail acesse [https://laravel.com/docs/sail](https://laravel.com/docs/sail)

## Contribuições

Dúvidas ou sugestões favor iniciar uma discussão.

Caso encontre algum erro favor criar um novo _issue_.

> Caso encontre alguma falha de segurança pedimos a gentileza de nos enviar um email para [disol@cgu.gov.br](mailto:disol@cgu.gov.br)
