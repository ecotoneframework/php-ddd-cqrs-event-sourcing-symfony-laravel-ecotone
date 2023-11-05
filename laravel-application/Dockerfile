FROM simplycodedsoftware/nginx-php:8.2.27

ENV COMPOSER_HOME /data/app
ENV COMPOSE_HTTP_TIMEOUT 9999

COPY docker/* /data/entrypoint.d/
RUN chmod +x /data/entrypoint.d/ -R

RUN mkdir -p /data/app/var/cache \
    && mkdir -p /data/app/var/log \
    && mkdir -p /data/app/vendor \
    && chown -R 1000:1000 /data/app/var/cache /data/app/var/log /data/app/vendor

VOLUME /data/app/var
