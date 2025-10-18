#!/bin/bash

# Clear all Laravel caches

echo "Clearing configuration cache..."
php artisan config:clear

echo "Clearing route cache..."
php artisan route:clear

echo "Clearing view cache..."
php artisan view:clear

echo "Clearing application cache..."
php artisan cache:clear

echo ""
echo "All caches cleared!"
echo ""
echo "Rebuilding caches for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo ""
echo "Done! Your application is ready."
