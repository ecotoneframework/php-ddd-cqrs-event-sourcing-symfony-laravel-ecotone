#!/bin/bash

set -e

php artisan migrate
php artisan key:generate
