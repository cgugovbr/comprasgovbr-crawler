#!/bin/bash

#
# Script de inicialização do Cromprasnet-Crawler
#

# Criar arquivo .env
date +'%Y-%m-%d %H:%M:%S' && echo "Criando aquivo .env"
cd /app
cp .env-secret .env

# Preenchendo variáveis do .env com valores do secrets do Kubernetes
date +'%Y-%m-%d %H:%M:%S' && echo "Configurando .env com valores constantes nos secrets do Kubernetes"

# Prepara as variáveis de ambiente (remove espaços, barras e new lines)
ESCAPED_APP_NAME=${app_name//[$'\t\r\n ']}
ESCAPED_APP_ENV=${app_env//[$'\t\r\n ']}
ESCAPED_APP_DEBUG=${app_debug//[$'\t\r\n ']}
ESCAPED_APP_URL=${app_url//[$'\t\r\n ']}
ESCAPED_APP_URL=${ESCAPED_APP_URL//\//\\/}

ESCAPED_DB_CONNECTION=${db_connection//[$'\t\r\n ']}
ESCAPED_DB_HOST=${db_host//[$'\t\r\n ']}
ESCAPED_DB_HOST=${ESCAPED_DB_HOST//\//\\/}
ESCAPED_DB_PORT=${db_port//[$'\t\r\n ']}
ESCAPED_DB_DATABASE=${db_database//[$'\t\r\n ']}
ESCAPED_DB_USERNAME=${db_username//[$'\t\r\n ']}
ESCAPED_DB_PASSWORD=${db_password//[$'\t\r\n ']}

ESCAPED_MAIL_MAILER=${mail_mailer//[$'\t\r\n ']}
ESCAPED_MAIL_HOST=${mail_host//[$'\t\r\n ']}
ESCAPED_MAIL_HOST=${ESCAPED_MAIL_HOST//\//\\/}
ESCAPED_MAIL_PORT=${mail_port//[$'\t\r\n ']}
ESCAPED_MAIL_USERNAME=${mail_username//[$'\t\r\n ']}
ESCAPED_MAIL_PASSWORD=${mail_password//[$'\t\r\n ']}
ESCAPED_MAIL_ENCRYPTION=${mail_encryption//[$'\t\r\n ']}
ESCAPED_MAIL_FROM_ADDRESS=${mail_from_address//[$'\t\r\n ']}

ESCAPED_COMPRASNET_BASE_URL=${comprasnet_base_url//[$'\t\r\n ']}
ESCAPED_COMPRASNET_BASE_URL=${ESCAPED_COMPRASNET_BASE_URL//\//\\/}
ESCAPED_COMPRASNET_CODIGO_ORGAO=${comprasnet_codigo_orgao//[$'\t\r\n ']}
ESCAPED_COMPRASNET_CODIGO_UG=${comprasnet_codigo_ug//[$'\t\r\n ']}
ESCAPED_COMPRASNET_ADM_EMAILS=${comprasnet_adm_emails//[$'\t\r\n ']}
ESCAPED_COMPRASNET_SUPPORT_EMAILS=${comprasnet_support_emails//[$'\t\r\n ']}
ESCAPED_COMPRASNET_USUARIO_SISTEMA=${comprasnet_usuario_sistema//[$'\t\r\n ']}
ESCAPED_COMPRASNET_SENHA_USUARIO=${comprasnet_senha_usuario//[$'\t\r\n ']}

# Popula variáveis locais para substituição
LOCAL_APP_NAME=${ESCAPED_APP_NAME:-Laravel}
LOCAL_APP_ENV=${ESCAPED_APP_ENV:-production}
LOCAL_APP_DEBUG=${ESCAPED_APP_DEBUG:-false}
LOCAL_APP_URL=${ESCAPED_APP_URL:-http:\/\/localhost}
LOCAL_APP_URL=${ESCAPED_APP_URL:-http:\/\/localhost}

LOCAL_DB_CONNECTION=${ESCAPED_DB_CONNECTION:-sqlsrv}
LOCAL_DB_HOST=${ESCAPED_DB_HOST:-127.0.0.1}
LOCAL_DB_PORT=${ESCAPED_DB_PORT:-1433}
LOCAL_DB_DATABASE=${ESCAPED_DB_DATABASE:-comprasnet}
LOCAL_DB_USERNAME=${ESCAPED_DB_USERNAME:-db_user}
LOCAL_DB_PASSWORD=${ESCAPED_DB_PASSWORD:-db_pass}

LOCAL_MAIL_MAILER=${ESCAPED_MAIL_MAILER:-smtp}
LOCAL_MAIL_HOST=${ESCAPED_MAIL_HOST:-smtp.mailtrap.io}
LOCAL_MAIL_PORT=${ESCAPED_MAIL_PORT:-2525}
LOCAL_MAIL_USERNAME=${ESCAPED_MAIL_USERNAME:-null}
LOCAL_MAIL_PASSWORD=${ESCAPED_MAIL_PASSWORD:-null}
LOCAL_MAIL_ENCRYPTION=${ESCAPED_MAIL_ENCRYPTION:-null}
LOCAL_MAIL_FROM_ADDRESS=${ESCAPED_MAIL_FROM_ADDRESS:-null}

LOCAL_COMPRASNET_BASE_URL=${ESCAPED_COMPRASNET_BASE_URL:-http:\/\/contratos.comprasnet.gov.br\/api}
LOCAL_COMPRASNET_CODIGO_ORGAO=${ESCAPED_COMPRASNET_CODIGO_ORGAO:-25000}
LOCAL_COMPRASNET_CODIGO_UG=${ESCAPED_COMPRASNET_CODIGO_UG:-null}
LOCAL_COMPRASNET_ADM_EMAILS=${ESCAPED_COMPRASNET_ADM_EMAILS:-null}
LOCAL_COMPRASNET_SUPPORT_EMAILS=${ESCAPED_COMPRASNET_SUPPORT_EMAILS:-null}
LOCAL_COMPRASNET_USUARIO_SISTEMA=${ESCAPED_COMPRASNET_USUARIO_SISTEMA:-null}
LOCAL_COMPRASNET_SENHA_USUARIO=${ESCAPED_COMPRASNET_SENHA_USUARIO:-null}

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

sed -i -e "s/SECRET_MAIL_MAILER/$LOCAL_MAIL_MAILER/g" .env
sed -i -e "s/SECRET_MAIL_HOST/$LOCAL_MAIL_HOST/g" .env
sed -i -e "s/SECRET_MAIL_PORT/$LOCAL_MAIL_PORT/g" .env
sed -i -e "s/SECRET_MAIL_USERNAME/$LOCAL_MAIL_USERNAME/g" .env
sed -i -e "s/SECRET_MAIL_PASSWORD/$LOCAL_MAIL_PASSWORD/g" .env
sed -i -e "s/SECRET_MAIL_ENCRYPTION/$LOCAL_MAIL_ENCRYPTION/g" .env
sed -i -e "s/SECRET_MAIL_FROM_ADDRESS/$LOCAL_MAIL_FROM_ADDRESS/g" .env

sed -i -e "s/SECRET_COMPRASNET_BASE_URL/$LOCAL_COMPRASNET_BASE_URL/g" .env
sed -i -e "s/SECRET_COMPRASNET_CODIGO_ORGAO/$LOCAL_COMPRASNET_CODIGO_ORGAO/g" .env
sed -i -e "s/SECRET_COMPRASNET_CODIGO_UG/$LOCAL_COMPRASNET_CODIGO_UG/g" .env
sed -i -e "s/SECRET_COMPRASNET_ADM_EMAILS/$LOCAL_COMPRASNET_ADM_EMAILS/g" .env
sed -i -e "s/SECRET_COMPRASNET_SUPPORT_EMAILS/$LOCAL_COMPRASNET_SUPPORT_EMAILS/g" .env
sed -i -e "s/SECRET_COMPRASNET_USUARIO_SISTEMA/$LOCAL_COMPRASNET_USUARIO_SISTEMA/g" .env
sed -i -e "s/SECRET_COMPRASNET_SENHA_USUARIO/$LOCAL_COMPRASNET_SENHA_USUARIO/g" .env

# Limpa dados anteriores da aplicação caso existente
date +'%Y-%m-%d %H:%M:%S' && echo "Limpando pasta '/var/www'"
rm -rf /var/www/* 2>/dev/null
rm -rf /var/www/.* 2>/dev/null

# Move a aplicação para da pasta /app para /var/www
date +'%Y-%m-%d %H:%M:%S' && echo "Movendo aplicação para pasta '/var/www'"
mv /app/* /var/www/ && mv /app/.* /var/www/ && echo "Aplicação movida com sucesso!" 2>/dev/null
cd /var/www

# Cria APP_KEY
date +'%Y-%m-%d %H:%M:%S' && echo "Criando chave de segurança"
php artisan key:generate && source .env

# Função especial para verificar se existe sinal de mais (+) no token
while [[ $APP_KEY == *[+]* ]]; do
  echo "Token com caracter inválido, refazendo token"
  php artisan key:generate --force
  source .env
done

# Cria Sotrage link
date +'%Y-%m-%d %H:%M:%S' && echo "Criando storage link"
rm public/storage > /dev/null 2>&1
php artisan storage:link
chown -R www-data:www-data /var/www/storage/
chown -R www-data:www-data /var/www/public/storage

# Cria/atualiza tabelas no banco de dados
date +'%Y-%m-%d %H:%M:%S' && echo "Migrando tabelas"
php artisan migrate --force

# Popula tabelas
# date +'%Y-%m-%d %H:%M:%S' && echo "Populando tabelas"
# php artisan db:seed --force

# Otimizando aplicação Laravel
date +'%Y-%m-%d %H:%M:%S' && echo "Otimizando aplicação"
php artisan optimize:clear
php artisan optimize
php artisan config:clear

# Configura php-fpm para rodar com usuário www-data
# echo "user = www-data" >> /usr/local/etc/php-fpm.d/docker.conf
# echo "group = www-data" >> /usr/local/etc/php-fpm.d/docker.conf
# echo "user = www-data" >> /usr/local/etc/php-fpm.d/zz-docker.conf
# echo "group = www-data" >> /usr/local/etc/php-fpm.d/zz-docker.conf

# Instancia o php-fpm (Dockerfile)
# php-fpm &
# runuser -l www-data -c php-fpm
# su - www-data -c php-fpm

# Configurar o cron com o Task Scheduler
date +'%Y-%m-%d %H:%M:%S' && echo "Configurando crontab"
cat ./docker/laravel_crontab >> /etc/crontab

# Inicia serviço Cron
date +'%Y-%m-%d %H:%M:%S' && echo "Inicia o cron"
service cron start

# Segue a execução do script
exec "$@"
