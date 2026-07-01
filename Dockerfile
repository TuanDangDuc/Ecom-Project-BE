FROM composer:2

RUN apk add --no-cache unzip libzip-dev \
    && docker-php-ext-install pdo_mysql zip

WORKDIR /var/www/html
COPY . .

RUN composer config -g repo.packagist composer https://packagist.vn
RUN composer install

EXPOSE 8000
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]
