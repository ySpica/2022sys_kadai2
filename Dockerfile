FROM php:8.1-fpm-alpine AS php

RUN docker-php-ext-install pdo_mysql

RUN apk add git
RUN git clone https://github.com/phpredis/phpredis.git /usr/src/php/ext/redis
RUN docker-php-ext-install redis

COPY ./php.ini ${PHP_INI_DIR}/php.ini

RUN install -o www-data -g www-data -d /var/www/upload/image/

RUN docker-php-ext-install mysqli