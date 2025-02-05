#
# Dockerfile para os ambientes de Desenvolvimento, Homologação, Treinamento e PRODUÇÃO do e-Agendas na AWS
#

#
# Compsoer - container temporario para instalar os pacotes do php (utiliza cache)
#
FROM composer:2 as composer
WORKDIR /app
COPY ./composer.json composer.json
COPY ./composer.lock composer.lock
RUN composer --no-ansi install --no-ansi --prefer-dist --no-dev --no-scripts --no-interaction --ignore-platform-reqs --no-autoloader

#
# App - container da aplicacao
#
FROM ubuntu:22.04

LABEL maintainer="DISOL"

ARG WWWGROUP=1001
ARG NODE_VERSION=18

WORKDIR /var/www/html

ENV TZ America/Sao_Paulo
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

RUN apt-get update \
    && apt-get install -y gnupg gosu curl ca-certificates zip unzip git supervisor libcap2-bin libpng-dev python2 dnsutils librsvg2-bin \
    && curl -sS 'https://keyserver.ubuntu.com/pks/lookup?op=get&search=0x14aa40ec0831756756d7f66c4f4ea0aae5267a6c' | gpg --dearmor | tee /usr/share/keyrings/ppa_ondrej_php.gpg > /dev/null \
    && echo "deb [signed-by=/usr/share/keyrings/ppa_ondrej_php.gpg] https://ppa.launchpadcontent.net/ondrej/php/ubuntu jammy main" > /etc/apt/sources.list.d/ppa_ondrej_php.list \
    && apt-get update \
    && apt-get install -y php8.1-cli php8.1-dev \
       php8.1-gd php8.1-imagick \
       php8.1-curl \
       php8.1-imap php8.1-mbstring \
       php8.1-xml php8.1-zip php8.1-bcmath php8.1-soap \
       php8.1-intl php8.1-readline \
       php8.1-ldap \
       php8.1-msgpack php8.1-igbinary php8.1-redis php8.1-swoole \
       php8.1-memcached php8.1-pcov php8.1-xdebug \
    && curl -sLS https://getcomposer.org/installer | php -- --install-dir=/usr/bin/ --filename=composer \
    && apt-get -y autoremove \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

RUN setcap "cap_net_bind_service=+ep" /usr/bin/php8.1

RUN groupadd --force -g $WWWGROUP sail
RUN useradd -ms /bin/bash --no-user-group -g $WWWGROUP -u 1337 sail

# Configuracao para SqlServer
RUN curl https://packages.microsoft.com/keys/microsoft.asc | apt-key add - \
    && curl https://packages.microsoft.com/config/ubuntu/22.04/prod.list > /etc/apt/sources.list.d/mssql-release.list \
    && apt-get update \
    && ACCEPT_EULA=Y apt-get install -y msodbcsql17 \
    && ACCEPT_EULA=Y apt-get install -y mssql-tools \
    && apt-get install -y gcc unixodbc unixodbc-dev musl-dev make \
    && apt-get -y autoremove \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*
RUN pecl install sqlsrv
RUN pecl install pdo_sqlsrv
RUN printf "; priority=20\nextension=sqlsrv.so\n" > /etc/php/8.1/mods-available/sqlsrv.ini
RUN printf "; priority=30\nextension=pdo_sqlsrv.so\n" > /etc/php/8.1/mods-available/pdo_sqlsrv.ini
RUN phpenmod sqlsrv pdo_sqlsrv
RUN echo 'export PATH="$PATH:/opt/mssql-tools/bin"' >> ~/.bashrc

# Configuracao para dev
RUN apt-get update \
    && apt-get install -y vim iproute2 cron telnet \
    && apt-get -y autoremove \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Copia dados do container temporário composer
COPY --from=composer /app /var/www/html

# Copia código da aplicação
COPY . /var/www/html

COPY ./docker/production/start-container /usr/local/bin/start-container
COPY ./docker/production/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY ./docker/production/php.ini /etc/php/8.1/cli/conf.d/99-sail.ini
RUN chmod +x /usr/local/bin/start-container

# Cria composer autoloader
RUN composer --no-ansi dump-autoload --no-scripts --no-dev --no-interaction --optimize

# Configura alias
RUN echo "alias l='ls -la'" >> ~/.bash_profile && \
    echo "alias l='ls -la'" >> ~/.bashrc && \
    echo "alias a='php artisan'" >> ~/.bash_profile && \
    echo "alias a='php artisan'" >> ~/.bashrc

EXPOSE 8000

ENTRYPOINT ["start-container"]
