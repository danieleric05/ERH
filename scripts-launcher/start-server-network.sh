#!/bin/bash

# Script pour démarrer le serveur Laravel accessible depuis le réseau
# Usage: ./start-server-network.sh

echo "🚀 Démarrage du serveur Laravel ERH..."
echo "📡 Accessible depuis le réseau sur http://172.31.96.10:8000"
echo ""
echo "⚠️  Pour arrêter le serveur, appuyez sur Ctrl+C"
echo ""

# Vider les caches avant de démarrer
echo "🧹 Nettoyage des caches..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear

echo ""
echo "✅ Serveur prêt !"
echo ""

# Démarrer le serveur accessible depuis le réseau (0.0.0.0 = toutes les interfaces)
php artisan serve --host=0.0.0.0 --port=8000
