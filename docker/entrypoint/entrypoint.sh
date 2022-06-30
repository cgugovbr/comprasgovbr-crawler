#!/usr/bin/env bash

#
# Script de inicialização para aplicações Laravel
#

# Criar arquivo .env
cd /app
echo "Criando aquivo .env"
cp .env-secret .env

# Prepara as variáveis de ambiente (kubernetes secrets)
# Retira espaços, barras e new lines das variáveis
SCAPED_APP_NAME=${app_name//[$'\t\r\n ']}
SCAPED_APP_ENV=${app_env//[$'\t\r\n ']}
SCAPED_APP_DEBUG=${app_debug//[$'\t\r\n ']}
SCAPED_APP_URL=${app_url//[$'\t\r\n ']}
SCAPED_APP_URL=${SCAPED_APP_URL//\//\\/}

SCAPED_DB_CONNECTION=${db_connection//[$'\t\r\n ']}
SCAPED_DB_HOST=${db_host//[$'\t\r\n ']}
SCAPED_DB_HOST=${SCAPED_DB_HOST//\//\\/}
SCAPED_DB_PORT=${db_port//[$'\t\r\n ']}
SCAPED_DB_DATABASE=${db_database//[$'\t\r\n ']}
SCAPED_DB_USERNAME=${db_username//[$'\t\r\n ']}
SCAPED_DB_PASSWORD=${db_password//[$'\t\r\n ']}

SCAPED_OAUTH_CLIENT_ID=${oauth_client_id//[$'\t\r\n ']}
SCAPED_OAUTH_CLIENT_ID=${SCAPED_OAUTH_CLIENT_ID//\//\\/}
SCAPED_OAUTH_CLIENT_SECRET=${oauth_client_secret//[$'\t\r\n ']}

SCAPED_MAIL_DRIVER=${mail_driver//[$'\t\r\n ']}
SCAPED_MAIL_HOST=${mail_host//[$'\t\r\n ']}
SCAPED_MAIL_HOST=${SCAPED_MAIL_HOST//\//\\/}
SCAPED_MAIL_PORT=${mail_port//[$'\t\r\n ']}
SCAPED_MAIL_USERNAME=${mail_username//[$'\t\r\n ']}
SCAPED_MAIL_PASSWORD=${mail_password//[$'\t\r\n ']}

SCAPED_COMPRASNET_BASE_URL=${COMPRASNET_BASE_URL//[$'\t\r\n ']}
SCAPED_COMPRASNET_BASE_URL=${SCAPED_COMPRASNET_BASE_URL//\//\\/}
SCAPED_COMPRASNET_CODIGO_ORGAO=${COMPRASNET_CODIGO_ORGAO//[$'\t\r\n ']}
SCAPED_COMPRASNET_CODIGO_UG=${COMPRASNET_CODIGO_UG//[$'\t\r\n ']}
SCAPED_COMPRASNET_ADM_EMAILS=${COMPRASNET_ADM_EMAILS//[$'\t\r\n ']}
SCAPED_COMPRASNET_SUPPORT_EMAILS=${COMPRASNET_SUPPORT_EMAILS//[$'\t\r\n ']}

# Popula variáveis locais para substituição
LOCAL_APP_NAME=${SCAPED_APP_NAME:-Laravel}
LOCAL_APP_ENV=${SCAPED_APP_ENV:-production}
LOCAL_APP_DEBUG=${SCAPED_APP_DEBUG:-false}
LOCAL_APP_URL=${SCAPED_APP_URL:-http:\/\/localhost}
LOCAL_APP_URL=${SCAPED_APP_URL:-http:\/\/localhost}

LOCAL_DB_CONNECTION=${SCAPED_DB_CONNECTION:-mysql}
LOCAL_DB_HOST=${SCAPED_DB_HOST:-127.0.0.1}
LOCAL_DB_PORT=${SCAPED_DB_PORT:-3306}
LOCAL_DB_DATABASE=${SCAPED_DB_DATABASE:-laravel}
LOCAL_DB_USERNAME=${SCAPED_DB_USERNAME:-root}
LOCAL_DB_PASSWORD=${SCAPED_DB_PASSWORD:-root}

LOCAL_OAUTH_CLIENT_ID=${SCAPED_OAUTH_CLIENT_ID:-false}
LOCAL_OAUTH_CLIENT_SECRET=${SCAPED_OAUTH_CLIENT_SECRET:-false}

LOCAL_MAIL_DRIVER=${SCAPED_MAIL_DRIVER:-smtp}
LOCAL_MAIL_HOST=${SCAPED_MAIL_HOST:-smtp.mailtrap.io}
LOCAL_MAIL_PORT=${SCAPED_MAIL_PORT:-2525}
LOCAL_MAIL_USERNAME=${SCAPED_MAIL_USERNAME:-null}
LOCAL_MAIL_PASSWORD=${SCAPED_MAIL_PASSWORD:-null}

LOCAL_COMPRASNET_BASE_URL=${SCAPED_COMPRASNET_BASE_URL:-http:\/\/contratos.comprasnet.gov.br\/api}
LOCAL_COMPRASNET_CODIGO_ORGAO=${SCAPED_COMPRASNET_CODIGO_ORGAO:-25000}
LOCAL_COMPRASNET_CODIGO_UG=${SCAPED_COMPRASNET_CODIGO_UG:-null}
LOCAL_COMPRASNET_ADM_EMAILS=${SCAPED_COMPRASNET_ADM_EMAILS:-null}
LOCAL_COMPRASNET_SUPPORT_EMAILS=${SCAPED_COMPRASNET_SUPPORT_EMAILS:-null}

# Substituir variáveis no arquivo .env.php pelas variáveis do ambiente que vieram do secret
sed -i -e "s/SECRET_APP_NAME/$LOCAL_APP_NAME/g" .env
sed -i -e "s/SECRET_APP_ENV/$LOCAL_APP_ENV/g" .env
sed -i -e "s/SECRET_APP_DEBUG/$LOCAL_APP_DEBUG/g" .env
sed -i -e "s/SECRET_APP_URL/$LOCAL_APP_URL/g" .env

sed -i -e "s/SECRET_DB_CONNECTION/$LOCAL_DB_CONNECTION/g" .env
sed -i -e "s/SECRET_DB_HOST/$LOCAL_DB_HOST/g" .env
sed -i -e "s/SECRET_DB_PORT/$LOCAL_DB_PORT/g" .env
sed -i -e "s/SECRET_DB_DATABASE/$LOCAL_DB_DATABASE/g" .env
sed -i -e "s/SECRET_DB_USERNAME/$LOCAL_DB_USERNAME/g" .env
sed -i -e "s/SECRET_DB_PASSWORD/$LOCAL_DB_PASSWORD/g" .env

sed -i -e "s/SECRET_OAUTH_CLIENT_ID/$LOCAL_OAUTH_CLIENT_ID/g" .env
sed -i -e "s/SECRET_OAUTH_CLIENT_SECRET/$LOCAL_OAUTH_CLIENT_SECRET/g" .env

sed -i -e "s/SECRET_MAIL_DRIVER/$LOCAL_MAIL_DRIVER/g" .env
sed -i -e "s/SECRET_MAIL_HOST/$LOCAL_MAIL_HOST/g" .env
sed -i -e "s/SECRET_MAIL_PORT/$LOCAL_MAIL_PORT/g" .env
sed -i -e "s/SECRET_MAIL_USERNAME/$LOCAL_MAIL_USERNAME/g" .env
sed -i -e "s/SECRET_MAIL_PASSWORD/$LOCAL_MAIL_PASSWORD/g" .env

sed -i -e "s/SECRET_COMPRASNET_BASE_URL/$LOCAL_COMPRASNET_BASE_URL/g" .env
sed -i -e "s/SECRET_COMPRASNET_CODIGO_ORGAO/$LOCAL_COMPRASNET_CODIGO_ORGAO/g" .env
sed -i -e "s/SECRET_COMPRASNET_CODIGO_UG/$LOCAL_COMPRASNET_CODIGO_UG/g" .env
sed -i -e "s/SECRET_COMPRASNET_ADM_EMAILS/$LOCAL_COMPRASNET_ADM_EMAILS/g" .env
sed -i -e "s/SECRET_COMPRASNET_SUPPORT_EMAILS/$LOCAL_COMPRASNET_SUPPORT_EMAILS/g" .env

# Limpa dados anteriores da aplicação caso existente
rm -rf /var/www/* 2>/dev/null
rm -rf /var/www/.* 2>/dev/null

# Move a aplicação para da pasta /app para /var/www
mv /app/* /var/www/ && mv /app/.* /var/www/ && echo "Aplicação movida com sucesso!" 2>/dev/null
cd /var/www

# Cria APP_KEY
echo "Criar chave de segurança"
php artisan key:generate && source .env

# Função especial para verificar se existe sinal de mais (+) no token
while [[ $APP_KEY == *[+]* ]]; do
  echo "Token com caracter inválido, refazendo token"
  php artisan key:generate --force
  source .env
done

# Cria Sotrage link
echo "Criar storage link"
php artisan storage:link

# Cria/atualiza tabelas no banco de dados
# echo "DB Migrate"
php artisan migrate --force

# Popula tabelas
# echo "DB Seed"
# php artisan db:seed --force

# Limpa arquivos compilados
php artisan cache:clear
php artisan config:clear
# php artisan event:clear
# php artisan route:clear
php artisan view:clear
php artisan clear-compiled

# Otimiza aplicação Laravel para produção
php artisan config:cache
# php artisan event:cache
# php artisan route:cache
php artisan view:cache
php artisan optimize

# Configura php-fpm para rodar com usuário www-data
# echo "user = www-data" >> /usr/local/etc/php-fpm.d/docker.conf
# echo "group = www-data" >> /usr/local/etc/php-fpm.d/docker.conf
# echo "user = www-data" >> /usr/local/etc/php-fpm.d/zz-docker.conf
# echo "group = www-data" >> /usr/local/etc/php-fpm.d/zz-docker.conf

# Instancia o php-fpm (Dockerfile)
# php-fpm &
# runuser -l www-data -c php-fpm
# su - www-data -c php-fpm

# Remove pasta caso exista
# rm -rf /var/www/public/files/dados

# Montar volume na pasta correta do sistema
# ln -s /dados /var/www/public/files/

# Corrige usuário e grupo do primeiro log
chown -R www-data:www-data ./storage/
# chown -R www-data:www-data ./public/files/dados
# chown -R www-data:www-data ./public/files/dados/*

# Configura o Contab para rodar os jobs do Sistema
# @TODO

# Configurar o Crontab com o Task SCheduler (não funciona com o usuário ROOT ??)
cat ./docker/laravel_crontab >> /etc/crontab

# Inicia serviço Cron
service cron start

# Segue a execução do script
exec "$@"