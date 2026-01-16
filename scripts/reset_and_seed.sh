#!/usr/bin/env bash
set -euo pipefail

# Chemin du script pour déterminer le chemin local
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_DIR="$(dirname "$SCRIPT_DIR")"
LOCAL_APP_DIR="$PROJECT_DIR/codeigniter4-framework-68d1a58"
CONTAINER_APP_DIR="/var/www/html"

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

# Définir le chemin selon le runtime
if [[ "$RUNTIME" == "none" ]]; then
    APP_DIR="$LOCAL_APP_DIR"
else
    APP_DIR="$CONTAINER_APP_DIR"
fi

DB_FILE="$APP_DIR/writable/db_sae.db"

# Fonction pour vérifier si le conteneur php est en cours d'exécution
is_container_running() {
    case "$RUNTIME" in
        docker)
            docker ps --filter "name=php" --format '{{.Names}}' | grep -q "^php$"
            ;;
        podman)
            podman ps --filter "name=php" --format '{{.Names}}' | grep -q "^php$"
            ;;
        *)
            return 1
            ;;
    esac
}

# Fonction pour démarrer les conteneurs si nécessaire
start_containers_if_needed() {
    if [[ "$RUNTIME" != "none" ]]; then
        if ! is_container_running; then
            echo "[start] Le conteneur php n'est pas lancé, démarrage..."
            case "$RUNTIME" in
                docker)
                    docker compose -f "$PROJECT_DIR/compose.yaml" up -d
                    ;;
                podman)
                    podman-compose -f "$PROJECT_DIR/compose.yaml" up -d
                    ;;
            esac
            echo "[start] Attente du démarrage du conteneur..."
            sleep 3
        else
            echo "[info] Le conteneur php est déjà en cours d'exécution"
        fi
    fi
}

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
echo "[info] Chemin de l'application : $APP_DIR"

# Démarrer les conteneurs si nécessaire (Docker/Podman uniquement)
start_containers_if_needed

echo "[reset] Ensuring writable directory exists"
run_cmd "mkdir -p '$APP_DIR/writable/cache' && chmod -R 777 '$APP_DIR/writable'"

echo "[reset] Removing SQLite database file: $DB_FILE"
run_cmd "rm -f '$DB_FILE'"

echo "[migrate] Running migrations"
run_cmd "cd '$APP_DIR' && php spark migrate"

echo "[seed] Seeding MasterSeeder (users, products, roles)"
run_cmd "cd '$APP_DIR' && php spark db:seed MasterSeeder"

# Permissions uniquement pour Docker/Podman (www-data n'existe pas forcément en local)
if [[ "$RUNTIME" != "none" ]]; then
    echo "[permissions] Setting correct permissions on database file"
    run_cmd "chown www-data:www-data '$DB_FILE' && chmod 664 '$DB_FILE'"
else
    echo "[permissions] Setting permissions on database file (mode local)"
    run_cmd "chmod 664 '$DB_FILE'"
fi

echo "[done] Database reset and seeded successfully"
