#!/bin/sh
set -e

# if parameters.yml, doesn't exists, it's the first container execution
if [ ! -f /var/www/html/app/config/parameters.yml ]; then
    # Wait other containers are ready
    sleep 10

    # Create log file
    touch var/logs/$SYMFONY_ENV.log

    # Execute scripts
    composer run-script post-install-cmd --no-interaction

    # Clear and warmup the cache
    bin/console cache:clear --no-warmup
    bin/console cache:warmup

    # Change owner of files created before
    chown -R www-data:www-data /var/www/html

    # Migrate databases
    bin/console doctrine:migrations:migrate --no-interaction
fi

exec "$@"
