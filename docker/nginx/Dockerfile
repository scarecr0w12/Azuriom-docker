FROM nginx:latest
COPY ./docker/nginx/azuriom.conf /etc/nginx/conf.d/default.conf
COPY ./docker/nginx/nginx.conf /etc/nginx/nginx.conf
COPY ./.env /var/www/html/.env
ARG DOMAIN
RUN sed -i -e 's/placeholder/'"${DOMAIN}"'/g' /etc/nginx/conf.d/default.conf
WORKDIR /var/www/html
RUN touch /var/www/error.log
RUN touch /var/www/access.log
#RUN groupadd -g 1000 nginx
#RUN useradd -u 1000 -ms /bin/bash -g nginx nginx
# Copy existing application directory permissions
COPY --from=azuriom/php-fpm /var/www/html/ ./
RUN chown -R nginx:nginx /var/www/html
RUN chmod -R 777 /var/www/html/storage/
RUN chmod -R 777 /var/www/html/bootstrap/cache/
RUN chgrp -R nginx /var/cache/nginx /var/run /var/log/nginx && \
    chmod -R 770 /var/cache/nginx /var/run /var/log/nginx
#RUN echo 'pid /tmp/nginx.pid' >> /etc/nginx/nginx.conf
CMD ["nginx", "-g", "daemon off;"]