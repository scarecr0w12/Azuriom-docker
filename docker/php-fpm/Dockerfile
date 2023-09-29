FROM dockette/nodejs:v16 as frontend
WORKDIR /app
COPY ./Azuriom/package.json ./
RUN npm install
COPY ./Azuriom/ ./
RUN npm run prod

FROM php:8.2-cli-alpine AS setup
COPY ./docker/php-fpm/php-ini-overrides.ini /usr/local/etc/php/99-overrides.ini
COPY ./docker/php-fpm/www.conf /usr/local/etc/php-fpm.d/www.conf

# Install dependencies
WORKDIR /app
#RUN apt-get update && apt-get install -y \
#    git \
#    unzip
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions && \
    install-php-extensions zip bcmath redis xml xmlwriter curl mbstring ctype json openssl tokenizer

# Copy the Laravel application code
COPY ./Azuriom/composer.json ./
# Install Composer dependencies
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
    composer install --no-scripts --no-autoloader
COPY ./Azuriom/ ./
RUN composer install --no-dev --optimize-autoloader
COPY ./.env ./.env
RUN php artisan key:generate


FROM php:8.2-fpm-alpine AS production
RUN addgroup -g 1000 -S nginx && \
    adduser -u 1000 -S nginx -G nginx
COPY ./wait-for-mysql.sh /
COPY ./seed.sh /
RUN chmod +x /seed.sh
RUN chmod +x /wait-for-mysql.sh
COPY ./docker/php-fpm/php-ini-overrides.ini /usr/local/etc/php/conf.d/99-overrides.ini
COPY ./docker/php-fpm/www.conf /usr/local/etc/php-fpm.d/www.conf
COPY ./docker/php-fpm/php-fpm.conf /usr/local/etc/php-fpm.conf

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions && \
    install-php-extensions pdo pdo_mysql zip intl opcache bcmath redis xml xmlwriter curl mbstring ctype json openssl tokenizer
RUN apk update && apk add mysql-client && rm -f /var/cache/apk/*

# Copy the Laravel application code
RUN mkdir -p /var/www/html/storage && \
    mkdir -p /var/www/html/bootstrap && \
    mkdir -p /var/www/html/bootstrap/cache && \
    chown -R nginx:nginx /var/www/html && \
    chown -R nginx:nginx /var/www/html/storage && \
    chown -R nginx:nginx /var/www/html/bootstrap && \
    chown -R nginx:nginx /var/www/html/bootstrap/cache
COPY --chown=nginx --from=setup /app/ /var/www/html/
COPY --from=frontend /app/ /var/www/html/
WORKDIR /var/www/html
ENTRYPOINT ["/seed.sh"]
CMD ["php-fpm", "-F", "-R"]
