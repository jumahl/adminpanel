FROM php:8.3.7-fpm-alpine

RUN apk --no-cache add \
    linux-headers \
    bash \
    git \
    sudo \
    openssh \
    libxml2-dev \
    oniguruma-dev \
    autoconf \
    gcc \
    g++ \
    make \
    npm \
    freetype-dev \
    libjpeg-turbo-dev \
    libpng-dev \
    libzip-dev \
    ssmtp \
    icu-dev

RUN pecl channel-update pecl.php.net && \
    pecl install pcov swoole && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install mbstring xml pcntl gd zip sockets pdo pdo_mysql bcmath soap intl && \
    docker-php-ext-enable mbstring xml gd zip pcov pcntl sockets bcmath pdo pdo_mysql soap swoole


RUN curl -sS https://getcomposer.org/installer | php -- \
    --install-dir=/usr/local/bin --filename=composer

WORKDIR /app

COPY . /app
RUN composer install --no-dev --optimize-autoloader

RUN npm install

COPY .env.dev .env

RUN php artisan key:generate





RUN mkdir -p /app/storage/logs


EXPOSE 8000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]