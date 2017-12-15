#!/usr/bin/env bash

# If the var/cache/dev|prod folder doesn't exists
if [ ! -f /var/www/html/app/config/parameters.yml ]; then
    # Run scripts
    composer run-script post-install-cmd --no-interaction

    # Change owner
    chown -R www-data:www-data /var/www/html
fi

exit $?
