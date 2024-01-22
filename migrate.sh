#/usr/bin/bash

composer update
php artisan db:wipe --drop-types --force && php artisan migrate:install
php artisan migrate --force
php artisan db:seed --force
