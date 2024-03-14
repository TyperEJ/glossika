#!/bin/bash
set -e

echo "Deployment started ..."

php /bin/composer install

cp .env.example .env

php artisan migrate --force

php artisan db:seed --force

php artisan octane:start --host=0.0.0.0

echo "Deployment finished!"
