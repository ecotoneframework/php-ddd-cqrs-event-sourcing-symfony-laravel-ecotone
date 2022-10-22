FROM simplycodedsoftware/nginx-php:8.1

ENV APP_INSTALL_DEPENDENCIES "yes"
ENV COMPOSER_HOME /data/app
ENV COMPOSE_HTTP_TIMEOUT 9999

COPY docker/* /data/entrypoint.d/
RUN chmod +x /data/entrypoint.d/ -R

RUN mkdir -p /data/app/var/cache \
    && mkdir -p /data/app/var/log \
    && mkdir -p /data/app/vendor \
    && chown -R 1000:1000 /data/app/var/cache /data/app/var/log /data/app/vendor

VOLUME /data/app/var

RUN apt-get update && apt-get install bash-completion \
    && COMPOSER_HOME=~/.composer composer global require bamarni/symfony-console-autocomplete\
    && echo PATH=$PATH:/root/.composer/vendor/bin/:/data/app/bin >> /root/.bashrc \
    && echo 'eval "$(symfony-autocomplete)"' >> /root/.bashrc

COPY . /data/app
