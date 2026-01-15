#!/bin/bash
# Script d'arrêt avec Podman

echo "🛑 Arrêt de PommeHub avec Podman..."
echo ""

cd "$(dirname "$0")/.."

# Détection de la commande podman compose disponible
if command -v podman-compose &> /dev/null; then
    PODMAN_COMPOSE="podman-compose"
elif podman compose version &> /dev/null; then
    PODMAN_COMPOSE="podman compose"
else
    echo "❌ Erreur : ni 'podman-compose' ni 'podman compose' n'est disponible."
    exit 1
fi

$PODMAN_COMPOSE down

echo ""
echo "🔓 Déconnexion de Docker Hub..."
podman logout docker.io

if [ $? -eq 0 ]; then
    echo ""
    echo "✅ Application arrêtée avec succès !"
else
    echo ""
    echo "❌ Erreur lors de l'arrêt."
fi
