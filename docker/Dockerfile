FROM php:8.0-cli

RUN apt update && apt install -y libpq-dev

RUN docker-php-ext-install pdo_mysql pdo_pgsql
