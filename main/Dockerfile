FROM php:8.3-apache

RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli
RUN docker-php-ext-install session && docker-php-ext-enable session

COPY . /var/www/html