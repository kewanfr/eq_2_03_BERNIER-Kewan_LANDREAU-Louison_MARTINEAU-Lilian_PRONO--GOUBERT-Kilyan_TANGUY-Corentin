#!/bin/bash
# Script de démarrage avec Podman

echo "🚀 Démarrage de PommeHub avec Podman..."
echo ""

cd "$(dirname "$0")/.."

echo "🔑 Connexion à Docker Hub..."
podman login docker.io

echo ""
echo "📦 Lancement des conteneurs..."

# Détection de la commande podman compose disponible
if command -v podman-compose &> /dev/null; then
    PODMAN_COMPOSE="podman-compose"
elif podman compose version &> /dev/null; then
    PODMAN_COMPOSE="podman compose"
else
    echo "❌ Erreur : ni 'podman-compose' ni 'podman compose' n'est disponible."
    echo "   Installez podman-compose : pip install podman-compose"
    exit 1
fi

$PODMAN_COMPOSE up --detach

if [ $? -eq 0 ]; then
    echo ""
    echo "📚 Installation des dépendances Composer..."
    podman exec php bash -lc "cd /var/www/html && composer install"

    # if writable directory does not exist, create it
    if ! docker exec php bash -lc "[ -f /var/www/html/writable/db_sae.db ]"; then
        echo "📁 Création du répertoire writable et migration de la base de données"
        sh scripts/reset_and_seed.sh
    else
        echo "✅ La base de données existe déjà. Aucune migration nécessaire."
        chmod 775 codeigniter4-framework-68d1a58/writable
        docker exec php bash -lc "chmod -R 777 /var/www/html/writable"
    
    fi
    
    echo ""
    echo "✅ Application démarrée avec succès !"
    echo ""
    echo "🌐 Accédez au site : http://localhost:8080"
    echo "📝 Pour voir les logs : podman logs php -f"
    echo "📝 Logs CodeIgniter : codeigniter4-framework-68d1a58/writable/logs/"
    echo ""
else
    echo ""
    echo "❌ Erreur lors du démarrage."
fi
