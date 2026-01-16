#!/usr/bin/env bash
set -euo pipefail

APP_DIR=/var/www/html
DB_FILE="$APP_DIR/writable/db_sae.db"

# Détection du runtime de conteneur disponible
detect_runtime() {
    if command -v docker &> /dev/null && docker ps &> /dev/null; then
        echo "docker"
    elif command -v podman &> /dev/null && podman ps &> /dev/null; then
        echo "podman"
    else
        echo "none"
    fi
}

RUNTIME=$(detect_runtime)

# Fonction pour exécuter une commande selon le runtime
run_cmd() {
    local cmd="$1"
    case "$RUNTIME" in
        docker)
            docker exec php bash -lc "$cmd"
            ;;
        podman)
            podman exec php bash -lc "$cmd"
            ;;
        none)
            # Exécution directe (utile si on est déjà dans le conteneur ou sur la machine hôte)
            bash -lc "$cmd"
            ;;
    esac
}

echo "[info] Runtime détecté : $RUNTIME"

echo "[reset] Ensuring writable directory exists"
run_cmd "mkdir -p '$APP_DIR/writable/cache' && chmod -R 777 '$APP_DIR/writable'"

echo "[reset] Removing SQLite database file: $DB_FILE"
run_cmd "rm -f '$DB_FILE'"

echo "[migrate] Running migrations"
run_cmd "cd '$APP_DIR' && php spark migrate"

echo "[seed] Seeding MasterSeeder (users, products, roles)"
run_cmd "cd '$APP_DIR' && php spark db:seed MasterSeeder"

echo "[permissions] Setting correct permissions on database file"
run_cmd "chown www-data:www-data '$DB_FILE' && chmod 664 '$DB_FILE'"

echo "[done] Database reset and seeded successfully"
