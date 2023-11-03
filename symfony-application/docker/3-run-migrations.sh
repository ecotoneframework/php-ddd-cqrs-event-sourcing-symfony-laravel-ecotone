#!/bin/bash

set -e

if [ "$(id -u)" = "0" ] && [ "$APP_INSTALL_DEPENDENCIES" = "yes" ]; then
   echo "Migrating" \
   && su deploy -c "(bin/console d:m:migrate --no-interaction)"
elif [ "$APP_INSTALL_DEPENDENCIES" = "yes" ]; then
    echo "Migrating" \
    && bin/console d:m:migrate --no-interaction
else
    echo "Not migrating" \
; fi