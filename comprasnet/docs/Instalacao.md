# Instalação

> **[IMPORTANTE]** Esta versão utiliza SQL Server como banco de dados. Todos os arquivos de criação e versionamento do banco
de dados (_migrations_) foram adaptados ao SQL Server. Caso deseja utilizar MySQL ou PostreSQL poderá
simplificar os _migrations_.

## Requisitos

1. Para utilizar essa aplicação é necessário possuir um usuário e senha
com autorização para utilização da API.

2. Requisitos do framework verificar [site do Laravel](https://laravel.com/docs/deployment#server-requirements).

## Primeiros passos

### 1. Faça o "clone" deste repositório

```bash
git clone git@github.com:cgugovbr/comprasgovbr-crawler.git
```

## 2. Instale as dependências

Acesse a pasta recém criada 'comprasgovbr-crawler' antes de rodar o comando:

```bash
composer install
```

## 3. Configure as informações de sistema

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

## 4. [PRODUÇÃO] Configurar o serviço cron do Linux

Quando o sistema estiver em produção será necessário configurar o crontab do servidor
para chamar as rotins do sistema. Esta linha abaixo apenas configura para que o sistema
fique constantemente sendo requisitado no ambiente em produção, porém a periodicidade das
requisições ao ComprasGovBr é determinado dentro do sistema.
Veja mais detalhes no item 5.

```bash
echo "* * * * * root cd /var/www/ && php artisan schedule:run >> /dev/null 2>&1" >> /etc/crontab
```

> Atualize a raiz da aplicação na linha de comando acima ('/var/www')

## 5. [PRODUÇÃO] Configurando a periodicidade

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