FROM php:8.2-apache
RUN apt-get update && apt-get install -y \
    nodejs \
    npm \
    && docker-php-ext-install pdo_mysql

WORKDIR /var/www/html

RUN npm init -y && npm install monaco-editor@0.55.1
