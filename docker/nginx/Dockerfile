FROM nginx:1.19

# Install dependencies
RUN apt-get update && apt-get install -y \
    curl \
    gnupg2 \
    lsb-release \
    ca-certificates \
    apt-transport-https \
    && rm -rf /var/lib/apt/lists/*
RUN mkdir -p /var/www/html
# Copy nginx configuration files
COPY ./docker/nginx/azuriom.conf /etc/nginx/conf.d/default.conf
COPY ./docker/nginx/nginx.conf /etc/nginx/nginx.conf
COPY ./Azuriom/ /var/www/html/
# Set the DOMAIN argument and replace placeholder in the default.conf
ARG DOMAIN
RUN sed -i -e 's/placeholder/'"$DOMAIN"'/g' /etc/nginx/conf.d/default.conf

# Create log files
RUN touch /var/www/error.log
RUN touch /var/www/access.log

# Copy existing application directory permissions
#COPY --from=azuriom/php-fpm /var/www/html/ /var/www/html/
RUN chown -R nginx:nginx /var/www/html

# Set more restrictive permissions
RUN find /var/www/html -type d -exec chmod 755 {} \;
RUN find /var/www/html -type f -exec chmod 644 {} \;

# Set storage and bootstrap/cache directory permissions
RUN chmod -R 777 /var/www/html/storage/
RUN chmod -R 777 /var/www/html/bootstrap/cache/

# Set nginx directory permissions
RUN chgrp -R nginx /var/cache/nginx /var/run /var/log/nginx && \
    chmod -R 770 /var/cache/nginx /var/run /var/log/nginx

# Start nginx
CMD ["nginx", "-g", "daemon off;"]