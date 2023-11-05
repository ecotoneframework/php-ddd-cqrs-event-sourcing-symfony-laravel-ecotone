#!/bin/bash

set -e

if [ "$(id -u)" = "0" ] && [ "$APP_INSTALL_DEPENDENCIES" = "yes" ]; then
   echo "Setting up projections" \
   && su deploy -c "(bin/console ecotone:es:initialize-projection last_prepared_tickets)" \
   && su deploy -c "(bin/console ecotone:es:initialize-projection unassigned_tickets)"
elif [ "$APP_INSTALL_DEPENDENCIES" = "yes" ]; then
    echo "Setting up projections" \
    && bin/console ecotone:es:initialize-projection last_prepared_tickets \
    && bin/console ecotone:es:initialize-projection unassigned_tickets
else
    echo "Not Setting up projections" \
; fi