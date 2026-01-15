#!/usr/bin/env bash
set -euo pipefail

APP_DIR=/var/www/html
DB_FILE="$APP_DIR/writable/db_sae.db"

echo "[reset] Stopping containers (optional)"

echo "[reset] Ensuring writable directory exists"
# mkdir -p "$APP_DIR/writable"
# chmod 775 "$APP_DIR/writable"
docker exec php bash -lc "mkdir -p '$APP_DIR/writable/cache' && chmod -R 777 '$APP_DIR/writable'"

echo "[reset] Removing SQLite database file: $DB_FILE"
docker exec php bash -lc "rm -f '$DB_FILE'"

echo "[migrate] Running migrations"
docker exec php bash -lc "cd '$APP_DIR' && php spark migrate"

echo "[seed] Seeding MasterSeeder (users, products, roles)"
docker exec php bash -lc "cd '$APP_DIR' && php spark db:seed MasterSeeder"

echo "[permissions] Setting correct permissions on database file"
docker exec php bash -lc "chown www-data:www-data '$DB_FILE' && chmod 664 '$DB_FILE'"

echo "[done] Database reset and seeded successfully"
