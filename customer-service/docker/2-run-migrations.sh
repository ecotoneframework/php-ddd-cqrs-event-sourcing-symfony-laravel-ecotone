#!/bin/bash

set -e

if [ "$(id -u)" = "0" ] && [ "$APP_INSTALL_DEPENDENCIES" = "yes" ]; then
   echo "Migrating" \
   && su deploy -c "(php artisan migrate && php artisan key:generate)" \
   && su deploy -c "(php artisan ecotone:migration:database:setup --initialize=true --onlyUsed=false)" \
   && su deploy -c "(php artisan ecotone:migration:channel:setup --initialize=true)"
elif [ "$APP_INSTALL_DEPENDENCIES" = "yes" ]; then
    echo "Migrating" \
    && php artisan migrate && artisan key:generate \
    && php artisan ecotone:migration:database:setup --initialize=true --onlyUsed=false \
    && php artisan ecotone:migration:channel:setup --initialize=true
else
    echo "Not migrating" \
; fi
