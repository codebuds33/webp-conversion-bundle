#!/bin/sh
set -e

composer install

/usr/local/bin/docker-php-entrypoint "$@"
