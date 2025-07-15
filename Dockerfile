FROM php:8.2-cli

RUN apt-get update && apt-get install -y libzip-dev unzip curl libonig-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring zip

WORKDIR /app
COPY . .

RUN curl -sS https://getcomposer.org/installer | php && \
    php composer.phar install

CMD ["php", "-S", "0.0.0.0:10000", "-t", "foen_upro"]
