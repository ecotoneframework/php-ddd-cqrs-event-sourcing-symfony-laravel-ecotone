#!/bin/bash

set -e

bin/console ecotone:es:initialize-projection last_prepared_tickets
bin/console ecotone:es:initialize-projection unassigned_tickets