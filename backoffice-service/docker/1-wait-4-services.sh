#!/bin/bash

set -e

wait4tcp backoffice-app-database 5432
wait4tcp rabbitmq 5672
sleep 3