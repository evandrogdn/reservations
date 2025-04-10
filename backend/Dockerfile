FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
  build-essential \
  libpng-dev \
  libjpeg-dev \ 
  libpq-dev \
  libzip-dev \ 
  zip \
  unzip \
  git \
  curl 

RUN docker-php-ext-install pdo pdo_pgsql zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY  . .

RUN composer install

CMD [ "php", "artisan", "serve", "--host=0.0.0.0", "--port=8000" ]