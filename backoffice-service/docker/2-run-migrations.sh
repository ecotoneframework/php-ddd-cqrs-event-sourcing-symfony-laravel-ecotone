#!/bin/bash

set -e

if [ "$(id -u)" = "0" ] && [ "$APP_INSTALL_DEPENDENCIES" = "yes" ]; then
   echo "Migrating" \
   && su deploy -c "(bin/console d:m:migrate --no-interaction)" \
   && su deploy -c "(bin/console ecotone:migration:database:setup --initialize=true --onlyUsed=false)" \
   && su deploy -c "(bin/console ecotone:migration:channel:setup --initialize=true)"
elif [ "$APP_INSTALL_DEPENDENCIES" = "yes" ]; then
    echo "Migrating" \
    && bin/console d:m:migrate --no-interaction \
    && bin/console ecotone:migration:database:setup --initialize=true --onlyUsed=false \
    && bin/console ecotone:migration:channel:setup --initialize=true
else
    echo "Not migrating" \
; fi