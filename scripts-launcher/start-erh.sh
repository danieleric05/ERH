#!/bin/bash

# Script de démarrage ERH Application
# Usage: ./start-erh.sh

echo "╔════════════════════════════════════════════════════════════╗"
echo "║          Démarrage Application ERH                         ║"
echo "╚════════════════════════════════════════════════════════════╝"
echo ""

# Couleurs
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Vérifier si un serveur tourne déjà
if ss -tlnp 2>/dev/null | grep -q ":8000"; then
    echo -e "${YELLOW}⚠️  Un serveur tourne déjà sur le port 8000${NC}"
    echo ""
    read -p "Voulez-vous l'arrêter et redémarrer? (o/n): " -n 1 -r
    echo ""
    if [[ $REPLY =~ ^[Oo]$ ]]; then
        echo -e "${YELLOW}Arrêt du serveur actuel...${NC}"
        pkill -f "php artisan serve"
        sleep 2
    else
        echo -e "${RED}Abandon du démarrage${NC}"
        exit 1
    fi
fi

# Vérifier qu'on est dans le bon dossier
if [ ! -f "artisan" ]; then
    echo -e "${RED}❌ Erreur: Fichier artisan non trouvé${NC}"
    echo "Vous devez être dans le dossier du projet Laravel"
    exit 1
fi

# 1. Vider les caches Laravel
echo -e "${BLUE}📋 Étape 1/5: Nettoyage des caches Laravel${NC}"
php artisan config:clear > /dev/null 2>&1
php artisan cache:clear > /dev/null 2>&1
php artisan view:clear > /dev/null 2>&1
php artisan route:clear > /dev/null 2>&1
echo -e "${GREEN}   ✓ Caches vidés${NC}"
echo ""

# 2. Vérifier la connexion base de données
echo -e "${BLUE}📋 Étape 2/5: Vérification base de données${NC}"
if php artisan db:show > /dev/null 2>&1; then
    echo -e "${GREEN}   ✓ Connexion base de données OK${NC}"
else
    echo -e "${YELLOW}   ⚠️  Impossible de vérifier la base de données${NC}"
fi
echo ""

# 3. Afficher les informations réseau
echo -e "${BLUE}📋 Étape 3/5: Informations réseau${NC}"
WSL_IP=$(hostname -I | awk '{print $1}')
echo -e "   IP WSL2: ${GREEN}$WSL_IP${NC}"
echo ""

# 4. Démarrer le serveur Laravel
echo -e "${BLUE}📋 Étape 4/5: Démarrage du serveur Laravel${NC}"
echo -e "   Démarrage sur ${GREEN}0.0.0.0:8000${NC}..."
echo ""

# Créer un fichier log
LOG_FILE="/tmp/erh-laravel-server.log"
php artisan serve --host=0.0.0.0 --port=8000 > "$LOG_FILE" 2>&1 &
SERVER_PID=$!

# Attendre que le serveur démarre
sleep 3

# Vérifier que le serveur tourne
if ss -tlnp 2>/dev/null | grep -q "0.0.0.0:8000"; then
    echo -e "${GREEN}   ✓ Serveur démarré avec succès (PID: $SERVER_PID)${NC}"
else
    echo -e "${RED}   ❌ Erreur lors du démarrage du serveur${NC}"
    echo "   Consultez les logs: tail -f $LOG_FILE"
    exit 1
fi
echo ""

# 5. Instructions pour Windows
echo -e "${BLUE}📋 Étape 5/5: Configuration Windows requise${NC}"
echo ""
echo "╔════════════════════════════════════════════════════════════╗"
echo "║  IMPORTANT: Exécutez maintenant sur Windows PowerShell    ║"
echo "╚════════════════════════════════════════════════════════════╝"
echo ""
echo "1. Ouvrir PowerShell en Administrateur"
echo "2. Exécuter:"
echo ""
echo -e "${YELLOW}   cd \\\\wsl\$\\Ubuntu\\home\\daniel\\work\\projects\\erh\\site${NC}"
echo -e "${YELLOW}   .\\setup-wsl2-simple.ps1${NC}"
echo ""
echo "OU manuellement:"
echo ""
echo -e "${YELLOW}   netsh interface portproxy add v4tov4 listenport=8000 listenaddress=0.0.0.0 connectport=8000 connectaddress=$WSL_IP${NC}"
echo -e "${YELLOW}   New-NetFirewallRule -DisplayName 'ERH Laravel Port 8000' -Direction Inbound -LocalPort 8000 -Protocol TCP -Action Allow${NC}"
echo ""
echo "╔════════════════════════════════════════════════════════════╗"
echo "║                    Serveur démarré !                       ║"
echo "╚════════════════════════════════════════════════════════════╝"
echo ""
echo -e "${GREEN}✓ Accès local (WSL2):${NC}"
echo "  - http://localhost:8000"
echo "  - http://$WSL_IP:8000"
echo ""
echo -e "${YELLOW}⏳ Accès réseau (après config Windows):${NC}"
echo "  - http://10.10.60.14:8000"
echo ""
echo -e "${BLUE}📄 Logs serveur:${NC} tail -f $LOG_FILE"
echo -e "${BLUE}🛑 Arrêter serveur:${NC} pkill -f 'php artisan serve'"
echo ""
