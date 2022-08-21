#!/bin/bash

set -e

mkdir -p 1000:1000 /data/app/storage /data/app/bootstrap/cache && chown -R 1000:1000 /data/app/storage /data/app/bootstrap/cache
