#!/bin/sh

# Install dependencies when not installed
if [ ! -f "vendor/autoload.php" ]; then
    composer install --no-progress --no-interaction
fi

exec php-fpm