FROM php:8.2-fpm-alpine
WORKDIR /var/www/html

RUN apk add --no-cache \
    nginx \
    mysql-client \
    git \
    curl \
    unzip \
    libzip-dev \
    libpng-dev \
    jpeg-dev \
    freetype-dev \
    icu-dev \
    && docker-php-ext-install pdo pdo_mysql zip gd intl opcache \
    && rm -rf /var/cache/apk/*

COPY . /var/www/html

RUN composer install --no-dev --optimize-autoloader

RUN chown -R www-data:www-data /var/www/html/storage \
    && chown -R www-data:www-data /var/www/html/bootstrap/cache

EXPOSE 9000
CMD ["php-fpm"]