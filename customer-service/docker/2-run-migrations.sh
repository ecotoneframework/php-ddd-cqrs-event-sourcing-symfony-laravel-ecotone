#!/bin/bash

set -e

if [ "$(id -u)" = "0" ] && [ "$APP_INSTALL_DEPENDENCIES" = "yes" ]; then
   echo "Migrating" \
   && su deploy -c "(php artisan migrate && php artisan key:generate)" \
   && su deploy -c "(php artisan ecotone:migration:database:setup --initialize=true --all=true)"
elif [ "$APP_INSTALL_DEPENDENCIES" = "yes" ]; then
    echo "Migrating" \
    && php artisan migrate && artisan key:generate \
    && php artisan ecotone:migration:database:setup --initialize=true --all=true
else
    echo "Not migrating" \
; fi
