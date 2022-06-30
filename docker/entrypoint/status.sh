#!/usr/bin/env sh

#
# Script para testar o php-fpm e a conexão com o banco de dados para o kubernetes
#

# Verifica se o php-fpm está rodando
TEST_PHP_FPM=$(ps -ef | grep -v grep | grep php-fpm | wc -l)
if [ $TEST_PHP_FPM -lt 2 ]; then
    echo 1
    exit 1
fi

# Verifica se possui conexão com o banco de dados
# @TODO

echo 0
exit 0