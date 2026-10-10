#!/bin/sh
# ─────────────────────────────────────────────────────────────────────────────
# Kaazilink container startup script
# Runs once per container start, before nginx + php-fpm are launched.
# ─────────────────────────────────────────────────────────────────────────────
set -e

# Render injects PORT; default to 8080 if not set (local Docker testing)
export PORT="${PORT:-8080}"

echo "→ [1/5] Substituting PORT=${PORT} into nginx config..."
envsubst '${PORT}' < /etc/nginx/nginx.conf.template > /etc/nginx/nginx.conf

echo "→ [2/5] Caching Laravel config, routes, and views..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "→ [3/5] Running database migrations..."
php artisan migrate --force

if [ "$RUN_SEEDERS" = "true" ]; then
    echo "Running seeders..."
    php artisan db:seed --class=ServiceAreaSeeder --no-interaction
    php artisan db:seed --class=ProviderSeeder --no-interaction
    php artisan db:seed --class=ReviewSeeder --no-interaction
    echo "Seeders complete."
fi

echo "→ [4/5] Creating storage symlink and Livewire temp upload directory..."
php artisan storage:link || true
# Livewire temp uploads use the local disk (storage/app/private/livewire-tmp).
# This directory must exist and be writable — Render containers don't persist it.
mkdir -p /var/www/html/storage/app/private/livewire-tmp
chmod -R 775 /var/www/html/storage/app/private

echo "→ [5/5] Starting supervisor (nginx + php-fpm)..."
exec supervisord -c /etc/supervisor/conf.d/supervisord.conf
