FROM composer:2

WORKDIR /var/www/html
COPY . .

RUN composer config -g repo.packagist composer https://repo.packagist.org

RUN composer install

EXPOSE 8000
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]
