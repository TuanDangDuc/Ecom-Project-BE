FROM php:8.1-cli

WORKDIR /var/www/html

COPY . .

RUN git config --global --add safe.directory /var/www/html

RUN composer config -g repo.packagist composer https://repo.packagist.org

RUN composer install

EXPOSE 8000
