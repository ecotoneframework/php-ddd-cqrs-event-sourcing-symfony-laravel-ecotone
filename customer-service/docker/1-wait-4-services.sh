#!/bin/bash

set -e

wait4tcp customer-app-database 3306
wait4tcp rabbitmq 5672
sleep 3
