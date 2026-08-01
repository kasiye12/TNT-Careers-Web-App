#!/bin/bash

echo "========================================="
echo "TNT RATS - Production Setup Script"
echo "========================================="

# Environment checks
echo "Checking environment..."
php --version
composer --version
node --version
npm --version

# Install dependencies
echo "Installing PHP dependencies..."
composer install --optimize-autoloader --no-dev

echo "Installing Node dependencies..."
npm ci

# Build frontend
echo "Building frontend assets..."
npm run build

# Environment setup
echo "Setting up environment..."
cp .env.example .env
php artisan key:generate

# Update .env with production settings
sed -i 's/APP_ENV=local/APP_ENV=production/g' .env
sed -i 's/APP_DEBUG=true/APP_DEBUG=false/g' .env

# Database migration
echo "Running migrations..."
php artisan migrate --force

# Seed essential data
echo "Seeding roles and admin user..."
php artisan db:seed --class=RolePermissionSeeder --force
php artisan db:seed --class=AdminUserSeeder --force

# Create storage link
echo "Creating storage link..."
php artisan storage:link

# Optimize
echo "Optimizing application..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set permissions
echo "Setting file permissions..."
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

echo ""
echo "========================================="
echo "Production setup complete!"
echo "========================================="
echo ""
echo "IMPORTANT:"
echo "1. Update .env with your database credentials"
echo "2. Change default admin password immediately"
echo "3. Configure mail settings in .env"
echo "4. Set up SSL certificate for production"
echo "5. Configure domain in .env (APP_URL, SANCTUM_STATEFUL_DOMAINS, SESSION_DOMAIN)"
echo ""
