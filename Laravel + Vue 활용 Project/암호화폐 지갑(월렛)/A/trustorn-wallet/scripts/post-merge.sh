#!/bin/sh

echo "Execute post-merge script v0.1.1"

#Update Dependencies
npm ci --unsafe-perm
npm run prod

composer install -o --prefer-source

#Clean, Cache, etc..
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

echo "Exit post-merge script v0.1.1"
