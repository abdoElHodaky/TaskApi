#/usr/bin/bash

#composer require rakutentech/laravel-request-docs:2.28
php artisan vendor:publish --tag=request-docs-config && php artisan route:cache

