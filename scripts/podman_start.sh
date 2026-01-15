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
