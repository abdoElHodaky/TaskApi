#/usr/bin/bash

composer install
php artisan migrate:install
php artisan db:wipe --drop-types --force && php artisan migrate:install
php artisan migrate --force
php artisan db:seed --force
