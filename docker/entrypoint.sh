#!/bin/sh
set -e

echo "🚀 SIM UKM Jurnalistik — Starting container..."

# ── 1. Ensure storage directories exist ─────────────────
mkdir -p storage/app/public \
         storage/framework/cache \
         storage/framework/sessions \
         storage/framework/views \
         storage/logs \
         bootstrap/cache

# ── 2. Fix permissions ──────────────────────────────────
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# ── 3. Create .env if not exists ─────────────────────────
if [ ! -f .env ]; then
    echo "⚠️  .env not found, copying from .env.example"
    cp .env.example .env
fi

# ── 4. Generate app key if empty ─────────────────────────
if [ -z "$APP_KEY" ]; then
    echo "⚠️  APP_KEY is empty, generating new key..."
    php artisan key:generate --force
fi

# ── 5. Run migrations ────────────────────────────────────
echo "📦 Running migrations..."
php artisan migrate --force --no-interaction

# ── 6. Create storage link ───────────────────────────────
php artisan storage:link 2>/dev/null || true

# ── 7. Cache optimization ────────────────────────────────
if [ "$APP_ENV" = "production" ]; then
    echo "⚡ Optimizing for production..."
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
fi

# ── 8. Seed database if flag is set ──────────────────────
if [ "$SEED_DATABASE" = "true" ]; then
    echo "🌱 Seeding database..."
    php artisan db:seed --force --no-interaction || echo "⚠️  Seeding skipped (data may already exist)"
fi

echo "✅ Setup complete! Starting services..."

# ── 9. Start supervisor (runs nginx + php-fpm + queue) ──
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
