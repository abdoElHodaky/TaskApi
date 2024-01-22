#/usr/bin/bash

composer require --dev mpociot/laravel-apidoc-generator
php artisan vendor:publish --provider="Mpociot\ApiDoc\ApiDocGeneratorServiceProvider" --tag=apidoc-config
php artisan apidoc:generate
