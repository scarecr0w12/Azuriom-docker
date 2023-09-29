# cat Dockerfile
FROM mysql/mysql-server:8.0

RUN chown -R mysql:root /var/lib/mysql/
RUN chmod -R 775 /var/lib/mysql/
COPY ./docker/mysql/init.sql /docker-entrypoint-initdb.d/init.sql

ARG MYSQL_DATABASE
ARG MYSQL_USER
ARG MYSQL_PASSWORD
ARG MYSQL_ROOT_PASSWORD

ENV MYSQL_DATABASE=$MYSQL_DATABASE
ENV MYSQL_USER=$MYSQL_USER
ENV MYSQL_PASSWORD=$MYSQL_PASSWORD
ENV MYSQL_ROOT_PASSWORD=$MYSQL_PASSWORD

CMD ["mysqld"]