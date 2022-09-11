FROM php:8.0.5-fpm-buster

RUN docker-php-ext-install -j$(nproc) pdo_mysql

RUN apt-get update && apt-get install -y \
    git

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN curl -sS https://get.symfony.com/cli/installer | bash && mv /root/.symfony5/bin/symfony /usr/local/bin/symfony
