#!/bin/bash

echo "╔══════════════════════════════════════════════════════════════╗"
echo "║   TNT Construction RATS - Deployment Script                 ║"
echo "╚══════════════════════════════════════════════════════════════╝"

# 1. Pull latest code
echo "📥 Pulling latest code..."
git pull origin main 2>/dev/null || echo "Git not configured, skipping..."

# 2. Install dependencies
echo "📦 Installing PHP dependencies..."
composer install --optimize-autoloader --no-dev

echo "📦 Installing Node dependencies..."
npm ci 2>/dev/null || npm install

# 3. Build frontend
echo "🎨 Building frontend assets..."
npm run build

# 4. Environment setup
echo "⚙️ Setting up environment..."
if [ ! -f .env ]; then
    cp .env.example .env
    php artisan key:generate
fi

# 5. Database migration
echo "🗄️ Running database migrations..."
php artisan migrate --force

# 6. Seed essential data
echo "🌱 Seeding essential data..."
php artisan db:seed --class=RolePermissionSeeder --force
php artisan db:seed --class=AdminUserSeeder --force

# 7. Create storage link
echo "🔗 Creating storage link..."
php artisan storage:link

# 8. Clear and cache
echo "⚡ Optimizing application..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# 9. Set permissions
echo "🔒 Setting file permissions..."
chmod -R 775 storage bootstrap/cache
chmod -R 775 public/build 2>/dev/null

echo ""
echo "✅ Deployment complete!"
echo "🌐 Visit: https://careers.tnt-constructions.com"
