#!/bin/bash

set -e

# Must begin by 3 or above to be run after the entrypoint installing dependencies
mkdir -p /data/app/vendor && chown -R 1000:1000 /data/app/vendor
