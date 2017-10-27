#!/usr/bin/env bash

# Clear the cache and warm it
/var/www/html/bin/console cache:clear --no-warmup
/var/www/html/bin/console cache:warmup

# Because the script was executed as root, re-define the user
chown -R www-data:www-data /var/www/html/var
