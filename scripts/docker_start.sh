#!/bin/bash
# Script de démarrage avec Docker

echo "🚀 Démarrage de PommeHub avec Docker..."
echo ""

cd "$(dirname "$0")/.."


# Détection de la commande docker compose disponible
if command -v docker-compose &> /dev/null; then
    DOCKER_COMPOSE="docker-compose"
elif docker compose version &> /dev/null; then
    DOCKER_COMPOSE="docker compose"
else
    echo "❌ Erreur : ni 'docker-compose' ni 'docker compose' n'est disponible."
    echo "   Vérifiez que Docker est bien installé."
    exit 1
fi

echo "📦 Lancement des conteneurs..."
$DOCKER_COMPOSE up --detach

if [ $? -eq 0 ]; then
    echo ""
    echo "📚 Installation des dépendances Composer..."
    docker exec php bash -lc "cd /var/www/html && composer install"

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
    echo "📝 Pour voir les logs : docker logs php -f"
    echo "📝 Logs CodeIgniter : codeigniter4-framework-68d1a58/writable/logs/"
    echo ""
else
    echo ""
    echo "❌ Erreur lors du démarrage. Vérifiez que Docker est bien installé et lancé."
fi
