#!/bin/sh

chown -R www-data:www-data /var/www/html/backend/runtime
chown -R www-data:www-data /var/www/html/console/runtime

exec "$@"