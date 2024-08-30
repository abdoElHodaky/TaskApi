#/usr/bin/bash

composer require spatie/laravel-responsecache
php artisan vendor:publish --tag="responsecache-config"
php artisan vendor:publish --tag=request-docs-config && php artisan route:cache

