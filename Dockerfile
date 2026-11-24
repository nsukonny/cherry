FROM composer:2.9 AS composer

FROM php:8.4-apache

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
       libonig-dev unzip \
    && docker-php-ext-install -j"$(nproc)" \
       pdo_mysql mbstring \
    && a2enmod rewrite headers \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer /usr/bin/composer /usr/local/bin/composer

WORKDIR /var/www/html

COPY composer.json composer.lock ./

RUN composer install \
    --no-dev \
    --prefer-dist \
    --no-interaction \
    --no-scripts \
    --no-progress

COPY . .

COPY docker/apache.conf /etc/apache2/sites-available/000-default.conf
COPY docker/php.ini /usr/local/etc/php/conf.d/blog.ini

RUN composer dump-autoload \
      --no-dev --classmap-authoritative \
    && mkdir -p storage/templates storage/logs \
    && chown -R www-data:www-data storage

EXPOSE 80