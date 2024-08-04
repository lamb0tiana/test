#!/bin/sh

cd /var/www/html
composer install
symfony console cache:clear
yarn install
yarn run build
symfony console d:d:c --if-not-exists
symfony console doctrine:migrations:migrate --no-interaction
/usr/sbin/apache2ctl -D FOREGROUND