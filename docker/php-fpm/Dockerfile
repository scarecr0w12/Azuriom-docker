FROM dockette/nodejs:v16 as frontend
WORKDIR /app
COPY ./Azuriom/package.json ./
RUN npm install
COPY ./Azuriom/ ./
RUN npm run prod

FROM php:8.2-cli AS setup
# Install dependencies
WORKDIR /app
RUN apt-get update && apt-get install -y \
    git \
    unzip
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions && \
    install-php-extensions pdo pdo_mysql zip intl opcache bcmath redis xml xmlwriter curl mbstring ctype json openssl tokenizer

# Copy the Laravel application code
COPY ./Azuriom/composer.json ./
# Install Composer dependencies
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
    composer install --no-scripts --no-autoloader
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
COPY --chown=www-data --from=setup /app/ ./
COPY --from=frontend /app/ ./
COPY ./.env ./.env
RUN php artisan key:generate
RUN if [ ! -f ./database/migration_flag ]; then \
      php artisan migrate --seed && \
      touch ./database/migration_flag; \
   fi
RUN php artisan view:cache
CMD ["php-fpm", "-F"]
