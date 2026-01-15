#!/bin/bash
# Affiche les logs du conteneur PHP avec Podman

echo "📋 Logs du conteneur PHP (Podman)..."
echo "   Appuyez sur Ctrl+C pour quitter"
echo ""

podman logs php -f
