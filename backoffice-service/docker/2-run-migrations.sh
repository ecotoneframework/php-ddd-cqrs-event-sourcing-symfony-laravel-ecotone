#!/bin/bash

set -e

if [ "$(id -u)" = "0" ] && [ "$APP_INSTALL_DEPENDENCIES" = "yes" ]; then
   echo "Migrating" \
   && su deploy -c "(bin/console d:m:migrate --no-interaction)" \
   && su deploy -c "(bin/console ecotone:migration:database:setup --initialize=true --all=true)"
elif [ "$APP_INSTALL_DEPENDENCIES" = "yes" ]; then
    echo "Migrating" \
    && bin/console d:m:migrate --no-interaction \
    && bin/console ecotone:migration:database:setup --initialize=true --all=true
else
    echo "Not migrating" \
; fi