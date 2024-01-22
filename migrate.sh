#/usr/bin/bash

composer require ovac/idoc
php artisan vendor:publish --tag=idoc-config
php artisan idoc:generate
