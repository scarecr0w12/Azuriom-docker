FROM dockette/nodejs:v16 as frontend
CMD mkdir -p /app
WORKDIR /app
COPY ./Azuriom/package.json ./
RUN npm install
COPY ./Azuriom/webpack.mix.js ./
COPY ./Azuriom/resources/ ./resources/
COPY ./Azuriom/public/ ./public/
RUN npm run prod

FROM composer:latest as composer_build
WORKDIR /app
COPY ./Azuriom/composer.json ./
RUN composer install --no-dev --no-autoloader --no-scripts --ignore-platform-reqs
COPY ./Azuriom/ ./
RUN composer install --no-dev --optimize-autoloader

FROM php:8.2-fpm
COPY ./docker/php-fpm/php-ini-overrides.ini /usr/local/etc/php-fpm.d/
COPY ./docker/php-fpm/www.conf /usr/local/etc/php-fpm.d/www.conf
COPY ./docker/php-fpm/php-fpm.conf /usr/local/etc/php-fpm.conf

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions && \
    install-php-extensions pdo pdo_mysql zip intl opcache bcmath redis xml xmlwriter curl mbstring ctype json openssl tokenizer

WORKDIR /var/www/html/

COPY --chown=www-data --from=composer_build /app/ ./
COPY --from=frontend /app/ ./
RUN php artisan view:cache
CMD ["php-fpm", "-F"]