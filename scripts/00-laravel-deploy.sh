#!/usr/bin/env bash
#
# GorKa Team
# Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
#

echo "Installing additional dependencies..."
curl -sSLf \
        -o /usr/local/bin/install-php-extensions \
        https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions && \
    chmod +x /usr/local/bin/install-php-extensions && \
    install-php-extensions bcmath

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
php artisan migrate:refresh --force
php artisan actions:refresh

echo "Done deploying"
