#!/bin/sh
set -e

rm -rf var/cache/*
bin/console cache:clear --no-warmup
bin/console cache:warmup
chown -R www-data:www-data var

exec "$@"
