FROM nginx:latest
COPY ./docker/nginx/azuriom.conf /etc/nginx/conf.d/default.conf
WORKDIR /var/www/html
RUN touch /var/www/error.log
RUN touch /var/www/access.log
#RUN groupadd -g 1000 nginx
#RUN useradd -u 1000 -ms /bin/bash -g nginx nginx
# Copy existing application directory permissions
COPY --from=azuriom/php-fpm /var/www/ .
RUN chown -R nginx:nginx /var/www/html
RUN chmod -R 777 /var/www/html/storage
RUN chgrp -R nginx /var/cache/nginx /var/run /var/log/nginx && \
    chmod -R 770 /var/cache/nginx /var/run /var/log/nginx
#RUN echo 'pid /tmp/nginx.pid' >> /etc/nginx/nginx.conf