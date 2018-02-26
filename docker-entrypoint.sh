#!/bin/sh
set -e

# if parameters.yml, doesn't exists execute scripts
if [ ! -f /var/www/html/app/config/parameters.yml ]; then
    composer run-script post-install-cmd --no-interaction;
    chown -R www-data:www-data /var/www/html;
fi

exec "$@"
