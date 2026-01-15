#!/bin/bash
# Script d'arrêt avec Docker

echo "🛑 Arrêt de PommeHub avec Docker..."
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

$DOCKER_COMPOSE down

if [ $? -eq 0 ]; then
    echo ""
    echo "✅ Application arrêtée avec succès !"
else
    echo ""
    echo "❌ Erreur lors de l'arrêt."
fi
