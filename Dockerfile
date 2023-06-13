FROM php:7.3-fpm

RUN apt update
RUN apt -y install libzip-dev
RUN apt -y install nodejs
RUN apt -y install npm
RUN apt -y install unzip
RUN apt -y install mariadb-client
RUN docker-php-ext-install zip
RUN pecl install xdebug-3.1.6 && docker-php-ext-enable xdebug

COPY --from=composer:2.3.5 /usr/bin/composer /usr/bin/composer
COPY php.ini /etc/php/8.1/cli/conf.d/99-etc.ini

RUN docker-php-ext-install pdo_mysql

RUN mkdir -p /var/www/laravel/storage/framework/cache/data && \
    mkdir -p /var/www/laravel/storage/framework/app/cache && \
    mkdir -p /var/www/laravel/storage/framework/sessions && \
    mkdir -p /var/www/laravel/storage/framework/views && \
    mkdir -p /var/www/laravel/storage/framework/testing && \
    chmod -R 777 /var/www/laravel/storage

ADD docker-entrypoint.sh ./
RUN chmod +x ./docker-entrypoint.sh
CMD ["./docker-entrypoint.sh"]

WORKDIR /var/www/laravel

