# Iniciando o desenvolvimento

Para começar a desenvolvier neste projeto indicamos ter instalado docker em sua máquina. 
Se for windows utilizar a versão docker para o wsl.

## Primeiros passos

1. Clonar a aplicação

```bash
git clone git@github.com:cgugovbr/comprasnet-crawler.git
```

2. Instalar dependências iniciais

```bash
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php81-composer:latest \
    composer install --ignore-platform-reqs
```

3. Criar o arquivo .env à partir do .env-example

Substituir as variáveis de banco de dados para:

```dotenv
DB_CONNECTION=sqlsrv
DB_HOST=sqlsrv
DB_PORT=1433
DB_DATABASE=comprasnet_crawler
DB_USERNAME=root
DB_PASSWORD=owdka3D&xYyfWrAodQ9cGa5
```

4. Rodar no terminal

```bash
./vendor/bin/sail up
```

> Ler a documentação do 'sail' em caso de dúvida quanto ao ambiente local de desenvolvimento - https://laravel.com/docs/sail

5. Criar o banco de dados do ComprasNet Crawler

No terminal rode o seguinte comando:
```bash
docker exec -it comprasgovbr-crawler-sqlsrv-1 /opt/mssql-tools/bin/sqlcmd -S localhost -U sa -P "owdka3DxYyfWrAodQ9cGa5" -q "CREATE DATABASE comprasnet_crawler"
```

6. Acesse o shell do container da aplicação

```bash
sail shell 
```

E execute os seguintes comandos dentro o shell:

- Criar chave:
```bash
php artisan key:generate
```

- Crie as tabelas:
```bash
php artisan migrate
```

- Crie link para pasta storage
```bash
php artisan storage:link
```

6. Teste a aplicação

Acesse o browser e verifique se a aplicação está configurada corretamente acessado:

http://localhost

