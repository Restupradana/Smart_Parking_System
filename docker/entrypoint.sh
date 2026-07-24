#!/bin/bash

echo "============================================"
echo "  Smart Parking System - Starting..."
echo "============================================"

# ─── Copy .env if not exists ──────────────────────────
if [ ! -f .env ]; then
    echo "[1/5] Creating .env from .env.example..."
    cp .env.example .env
else
    echo "[1/5] .env already exists, skipping..."
fi

# ─── Wait for MySQL ──────────────────────────────────
echo "[2/5] Waiting for MySQL to be ready..."
MAX_ATTEMPTS=30
ATTEMPT=1

while [ $ATTEMPT -le $MAX_ATTEMPTS ]; do
    if php -r "
        try {
            new PDO('mysql:host=${DB_HOST};port=${DB_PORT}', '${DB_USERNAME}', '${DB_PASSWORD}');
            exit(0);
        } catch (PDOException \$e) {
            exit(1);
        }
    " 2>/dev/null; then
        echo "       MySQL is ready!"
        break
    fi

    echo "       Attempt $ATTEMPT/$MAX_ATTEMPTS - waiting..."
    sleep 2
    ATTEMPT=$((ATTEMPT + 1))
done

if [ $ATTEMPT -gt $MAX_ATTEMPTS ]; then
    echo "ERROR: MySQL did not become ready in time."
    exit 1
fi

# ─── Generate APP_KEY ────────────────────────────────
if grep -q "APP_KEY=" .env && [ "$(grep 'APP_KEY=' .env | cut -d'=' -f2)" = "" ]; then
    echo "[3/5] Generating APP_KEY..."
    php artisan key:generate --force
elif ! grep -q "APP_KEY=" .env; then
    echo "[3/5] Generating APP_KEY..."
    php artisan key:generate --force
else
    echo "[3/5] APP_KEY already set, skipping..."
fi

# ─── Storage Link ─────────────────────────────────────
echo "[4/5] Creating storage symlink..."
php artisan storage:link --force 2>/dev/null || true

# ─── Migrate Database ─────────────────────────────────
echo "[5/5] Running database migrations..."
php artisan migrate --force || echo "WARNING: Migration failed or already completed, continuing..."

echo "============================================"
echo "  Smart Parking System is ready!"
echo "  Access: http://localhost:${APP_PORT:-8000}"
echo "============================================"

# ─── Start PHP-FPM ───────────────────────────────────
exec "$@"
