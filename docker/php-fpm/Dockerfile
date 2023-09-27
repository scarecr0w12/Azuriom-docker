FROM dockette/nodejs:v16 as frontend
WORKDIR /app
COPY /Azuriom/package.json ./
RUN npm install
COPY /Azuriom/webpack.mix.js ./
COPY /Azuriom/resources ./resources
COPY /Azuriom/public ./public
RUN npm run prod

FROM composer:2.1.9 as composer_build
RUN mkdir app
COPY /Azuriom/composer.json /app
RUN composer install --no-dev --no-autoloader --no-scripts
COPY /Azuriom/ ./app
RUN composer install --no-dev --optimize-autoloader

FROM php:8.2-fpm-alpine
COPY docker/php-fpm/php-ini-overrides.ini /usr/local/etc/php-fpm.d/
COPY docker/nginx/default.conf /etc/nginx/conf.d/default.conf

RUN apt-get clean && rm -rf /var/lib/apt/lists/*
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions && \
    install-php-extensions pdo pdo_mysql zip intl opcache bcmath redis


COPY --chown=www-data --from=composer_build /app/ /var/www/html/
COPY --from=frontend /app/ /var/www/html/
RUN  php artisan view:cache
# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]