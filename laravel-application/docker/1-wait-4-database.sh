#!/bin/bash

set -e

wait4tcp laravel-app-database 3306
