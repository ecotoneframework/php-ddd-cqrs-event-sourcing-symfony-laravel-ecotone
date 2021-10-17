FROM simplycodedsoftware/nginx-php:8.0

ADD docker/wait-4-database.sh /data/entrypoint.d/1-wait-4-database.sh
RUN chmod +x /data/entrypoint.d/1-wait-4-database.sh

RUN mkdir -p /data/app/var/cache && mkdir -p /data/app/var/log && chown -R 1000:1000 /data/app/var/cache /data/app/var/log
VOLUME /data/app/var

ADD . /data/app