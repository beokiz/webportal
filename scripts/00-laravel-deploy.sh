#!/usr/bin/env bash
#
# GorKa Team
# Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
#

echo "Running composer..."
cp /etc/secrets/.env .env
#composer global require hirak/prestissimo
composer install --no-dev --working-dir=/var/www/html

echo "Clearing caches..."
php artisan optimize:clear

echo "Caching config..."
php artisan config:cache

echo "Caching routes..."
php artisan route:cache

echo "Running DB migrations & actions..."
php artisan migrate --force
php artisan actions --force

echo "Done deploying"
