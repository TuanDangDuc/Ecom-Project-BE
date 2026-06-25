FROM php:8.1-cli

WORKDIR /var/www/html

COPY . .

RUN composer install

EXPOSE 8000
