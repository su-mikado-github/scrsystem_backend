FROM php:7.3-fpm

RUN apt-get update
RUN apt-get -y install libzip-dev
RUN docker-php-ext-install zip

COPY --from=composer:2.3.5 /usr/bin/composer /usr/bin/composer
COPY php.ini /etc/php/8.1/cli/conf.d/99-etc.ini

RUN docker-php-ext-install pdo_mysql

ADD docker-entrypoint.sh ./
RUN chmod +x ./docker-entrypoint.sh
CMD ["./docker-entrypoint.sh"]

WORKDIR /var/www/laravel

