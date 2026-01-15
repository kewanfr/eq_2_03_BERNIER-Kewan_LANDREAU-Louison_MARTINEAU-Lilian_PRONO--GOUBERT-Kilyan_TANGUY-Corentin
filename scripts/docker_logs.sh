#!/bin/bash
# Affiche les logs du conteneur PHP avec Docker

echo "📋 Logs du conteneur PHP (Docker)..."
echo "   Appuyez sur Ctrl+C pour quitter"
echo ""

docker logs php -f
