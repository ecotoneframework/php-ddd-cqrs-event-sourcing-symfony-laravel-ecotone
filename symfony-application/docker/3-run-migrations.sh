#!/bin/bash

set -e

if [ "$RUN_MIGRATION" == "true" ]; then bin/console d:m:migrate --no-interaction; else sleep 5; fi