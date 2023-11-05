#!/bin/bash

set -e

mkdir -p 1000:1000 /data/app/storage /data/app/bootstrap/cache && chown -R deploy:www-data /data/app/storage /data/app/bootstrap/cache && chmod -R 775 /data/app/storage /data/app/bootstrap/cache
