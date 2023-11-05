#!/bin/bash

set -e

if [ "$(id -u)" = "0" ] && [ "$APP_INSTALL_DEPENDENCIES" = "yes" ]; then
   echo "Migrating" \
   && su deploy -c "(php artisan migrate && php artisan key:generate)"
elif [ "$APP_INSTALL_DEPENDENCIES" = "yes" ]; then
    echo "Migrating" \
    && php artisan migrate && artisan key:generate
else
    echo "Not migrating" \
; fi
