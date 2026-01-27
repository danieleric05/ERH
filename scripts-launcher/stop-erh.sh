#!/bin/bash

# Script d'arrêt ERH Application
# Usage: ./stop-erh.sh

echo "╔════════════════════════════════════════════════════════════╗"
echo "║           Arrêt Application ERH                            ║"
echo "╚════════════════════════════════════════════════════════════╝"
echo ""

# Couleurs
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m'

# Vérifier si un serveur tourne
if ss -tlnp 2>/dev/null | grep -q ":8000"; then
    echo -e "${YELLOW}Arrêt du serveur Laravel...${NC}"
    pkill -f "php artisan serve"
    sleep 2

    # Vérifier que le serveur est bien arrêté
    if ss -tlnp 2>/dev/null | grep -q ":8000"; then
        echo -e "${RED}❌ Le serveur n'a pas pu être arrêté${NC}"
        echo "Essayez: kill -9 \$(lsof -t -i:8000)"
        exit 1
    else
        echo -e "${GREEN}✓ Serveur arrêté avec succès${NC}"
    fi
else
    echo -e "${YELLOW}Aucun serveur en cours d'exécution${NC}"
fi

echo ""
echo -e "${BLUE}Note:${NC} Pour supprimer la configuration Windows, exécutez:"
echo "  .\\remove-wsl-network.ps1 (PowerShell Admin)"
echo ""
