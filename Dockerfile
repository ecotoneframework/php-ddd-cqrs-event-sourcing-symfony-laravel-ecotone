FROM simplycodedsoftware/nginx-php:8.0

ENV APP_INSTALL_DEPENDENCIES "yes"
ENV COMPOSER_HOME /data/app
ENV COMPOSE_HTTP_TIMEOUT 9999

ADD docker/wait-4-database.sh /data/entrypoint.d/1-wait-4-database.sh
ADD docker/fix-permissions.sh /data/entrypoint.d/2-fix-permissions.sh
ADD docker/run-migrations.sh /data/entrypoint.d/3-run-migrations.sh
RUN chmod +x /data/entrypoint.d/1-wait-4-database.sh /data/entrypoint.d/2-fix-permissions.sh /data/entrypoint.d/3-run-migrations.sh

RUN mkdir -p /data/app/var/cache && mkdir -p /data/app/var/log && mkdir -p /data/app/vendor && chown -R 1000:1000 /data/app/var/cache /data/app/var/log /data/app/vendor
VOLUME /data/app/var

ADD . /data/app