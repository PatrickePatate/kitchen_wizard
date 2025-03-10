#!/bin/bash
mkdir -p /app/storage/{app/public,framework/{cache,sessions,testing,views},logs}
sleep 5
composer install --no-dev --no-interaction --optimize-autoloader
php /app/artisan migrate --force
php /app/artisan optimize
php /app/artisan storage:link

exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
