#!/bin/bash
mkdir -p /app/storage/{app/public,framework/{cache,sessions,testing,views},logs}
sleep 5
php /app/artisan migrate
php /app/artisan optimize
php /app/artisan storage:link

exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
