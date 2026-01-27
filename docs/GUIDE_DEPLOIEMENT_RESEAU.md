# Guide Déploiement & Accès Réseau ERH

**Dernière mise à jour:** 2026-01-27
**Version:** 2.0 (Fusionné)
**Audience:** Administrateurs système, Équipe IT, Développeurs

---

## 🔍 Configuration Actuelle

### Votre réseau
- **Windows IP :** `10.10.60.14` (IP réelle sur le réseau)
- **WSL2 IP :** `172.31.96.10` (IP virtuelle, **change à chaque redémarrage**)
- **Port Laravel :** `8000`

### Accès disponibles
```
http://localhost:8000          ✅ Sur Windows (local)
http://10.10.60.14:8000        ✅ Sur Windows (réseau)
http://10.10.60.14:8000        ✅ Depuis autre PC du réseau
```

---

## ⚡ Démarrage Rapide (2 étapes)

### ÉTAPE 1️⃣ : Démarrer le serveur dans WSL2

```bash
cd /home/daniel/work/projects/erh/site

# Option A : Avec script (recommandé)
./start-server-network.sh

# Option B : Commande manuelle
php artisan serve --host=0.0.0.0 --port=8000
```

**Vérifier que ça tourne :**
```bash
ss -tlnp | grep 8000
# Doit afficher : 0.0.0.0:8000 (PAS 127.0.0.1:8000)
```

**Récupérer l'IP WSL2 actuelle :**
```bash
hostname -I | awk '{print $1}'
# Exemple de résultat : 172.31.96.10
```

### ÉTAPE 2️⃣ : Configurer le port forwarding Windows

**Sur Windows, ouvrir PowerShell en Administrateur** et exécuter :

```powershell
cd C:\Users\daniel.aboussou\path\to\erh\site

# Option A : Avec script
.\setup-wsl-network.ps1

# Option B : Commandes manuelles (voir section ci-dessous)
```

**Si erreur de script :**
```powershell
Set-ExecutionPolicy -ExecutionPolicy RemoteSigned -Scope CurrentUser
.\setup-wsl-network.ps1
```

### 🧪 Vérifier l'accès

```bash
# Sur Windows
curl http://localhost:8000         # ✅ Doit retourner HTML
curl http://10.10.60.14:8000       # ✅ Doit retourner HTML

# Depuis autre PC du réseau
curl http://10.10.60.14:8000       # ✅ Doit retourner HTML
```

---

## 🔧 Configuration Manuelle (Détaillée)

### Prérequis

```bash
# WSL2 doit être actif
wsl -l -v
# Doit afficher Ubuntu en version 2

# PHP doit être disponible
php -v

# Base de données MySQL doit tourner
mysql -u root -p -e "SELECT 1;"
```

### Étape 1 : Arrêter les serveurs existants

**Dans WSL2 :**
```bash
pkill -f "php artisan serve"
ss -tlnp | grep 8000  # Doit ne rien afficher
```

**Sur Windows (PowerShell Admin) :**
```powershell
netsh interface portproxy show all
# Si quelque chose affiche, le supprimer avant de continuer
```

### Étape 2 : Démarrer le serveur Laravel

**Dans WSL2 :**
```bash
cd /home/daniel/work/projects/erh/site

# Nettoyer les caches
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# Vérifier DB
php artisan tinker
>>> DB::connection()->getPDO();
>>> exit

# Démarrer le serveur
php artisan serve --host=0.0.0.0 --port=8000
```

**Garder ce terminal ouvert !**

### Étape 3 : Configurer le port forwarding (Windows)

**Ouvrir un SECOND PowerShell en Administrateur :**

```powershell
# Récupérer l'IP WSL2
wsl hostname -I
# Exemple : 172.31.96.10 (à remplacer dans les commandes ci-dessous)

# Créer la règle de port forwarding
netsh interface portproxy add v4tov4 `
  listenport=8000 `
  listenaddress=0.0.0.0 `
  connectport=8000 `
  connectaddress=172.31.96.10

# Vérifier la configuration
netsh interface portproxy show all
```

### Étape 4 : Ouvrir le pare-feu Windows

**Toujours dans le PowerShell Admin :**

```powershell
# Ouvrir le port 8000
New-NetFirewallRule `
  -DisplayName "Laravel ERH Server" `
  -Direction Inbound `
  -LocalPort 8000 `
  -Protocol TCP `
  -Action Allow

# Vérifier
Get-NetFirewallRule -DisplayName "Laravel ERH Server"
```

### Étape 5 : Tester l'accès

**Dans cmd/PowerShell :**
```powershell
# Local
curl http://localhost:8000

# Réseau
curl http://10.10.60.14:8000

# Depuis autre PC du réseau
# curl http://10.10.60.14:8000
```

---

## 📋 Procédure Après Redémarrage Machine

### ⚠️ Attention !

Après un redémarrage Windows/WSL2 :
- ✅ Base de données : reste intacte
- ❌ Serveur Laravel : doit être redémarré
- ⚠️ IP WSL2 : **change à chaque redémarrage**
- ❌ Port forwarding : **doit être reconfiguré avec nouvelle IP**

### Procédure rapide

```bash
# 1. Dans WSL2
cd /home/daniel/work/projects/erh/site
./start-erh.sh
# Le script affichera la nouvelle IP WSL2

# 2. Sur Windows (PowerShell Admin)
.\setup-wsl2-simple.ps1
# Le script va récupérer la nouvelle IP automatiquement
```

### Procédure manuelle

```bash
# ÉTAPE 1 : WSL2 - Démarrer serveur
cd /home/daniel/work/projects/erh/site
php artisan serve --host=0.0.0.0 --port=8000 &

# ÉTAPE 2 : WSL2 - Récupérer l'IP
NEW_IP=$(hostname -I | awk '{print $1}')
echo "IP WSL2 : $NEW_IP"

# ÉTAPE 3 : Windows PowerShell Admin
# Remplacer 172.31.96.10 par la nouvelle IP
wsl hostname -I

# Puis dans PowerShell Admin :
netsh interface portproxy delete v4tov4 listenport=8000 listenaddress=0.0.0.0
netsh interface portproxy add v4tov4 listenport=8000 listenaddress=0.0.0.0 connectport=8000 connectaddress=172.31.96.XX
```

---

## Problèmes Courants

### Problème : "Connection refused"

**Symptôme:** `curl: (7) Failed to connect to 10.10.60.14:8000`

**Solutions :**
```bash
# 1. Vérifier que le serveur tourne
ss -tlnp | grep 8000

# 2. Si rien n'affiche, redémarrer
cd /home/daniel/work/projects/erh/site
php artisan serve --host=0.0.0.0 --port=8000

# 3. Vérifier port forwarding Windows
# Depuis PowerShell Admin
netsh interface portproxy show all
```

### Problème : "Cannot GET /"

**Symptôme:** Erreur 404, la page n'existe pas

**Cause:** Laravel démarre correctement mais routes non chargées

**Solution :**
```bash
cd /home/daniel/work/projects/erh/site
php artisan route:cache
php artisan config:cache
php artisan restart
```

### Problème : "CORS error" ou "Origin not allowed"

**Symptôme:** Erreur dans la console du navigateur

**Solution :** Modifier `.env`
```env
APP_URL=http://10.10.60.14:8000
SESSION_DOMAIN=10.10.60.14
```

Puis :
```bash
php artisan config:clear
php artisan cache:clear
```

### Problème : Base de données inaccessible

**Symptôme:** Erreur "SQLSTATE[HY000] [2002] Connection refused"

**Solutions :**
```bash
# 1. Vérifier que MySQL tourne
mysql -u root -p -e "SELECT 1;"

# 2. Si erreur, démarrer MySQL
# (dépend de votre installation)

# 3. Vérifier .env
cat .env | grep DB_

# 4. Tester depuis WSL2
mysql -h 127.0.0.1 -u root -p
```

### Problème : Port 8000 déjà utilisé

**Symptôme:** "Address already in use"

**Solution :**
```bash
# Tuer tous les processus PHP
pkill -f "php artisan serve"

# Ou utiliser un autre port
php artisan serve --host=0.0.0.0 --port=8001

# Puis reconfigurer port forwarding Windows pour le port 8001
```

### Problème : Windows ne peut pas accéder réseau

**Cause:** Pare-feu Windows bloque le port

**Solution :**
```powershell
# Vérifier la règle firewall
Get-NetFirewallRule -DisplayName "Laravel ERH Server"

# Si elle n'existe pas, la créer
New-NetFirewallRule `
  -DisplayName "Laravel ERH Server" `
  -Direction Inbound `
  -LocalPort 8000 `
  -Protocol TCP `
  -Action Allow
```

### Problème : Autre PC du réseau ne peut pas accéder

**Cause:** Port forwarding ne transmet pas au bon IP WSL2

**Solution :**
```bash
# 1. Vérifier l'IP WSL2 actuelle
wsl hostname -I

# 2. Vérifier que le port forwarding cible cette IP
netsh interface portproxy show all

# 3. Si différent, reconfigurer
netsh interface portproxy delete v4tov4 listenport=8000 listenaddress=0.0.0.0
netsh interface portproxy add v4tov4 listenport=8000 listenaddress=0.0.0.0 connectport=8000 connectaddress=172.31.96.XX
```

---

## Architecture Réseau

```
┌─────────────────────────────────────────────┐
│         RÉSEAU LOCAL (10.10.x.x)            │
│  PC1 (10.10.60.15)   PC2 (10.10.60.16)     │
│         ↓                    ↓              │
│              http://10.10.60.14:8000        │
└─────────────────────────────────────────────┘
                     ↓
        ┌────────────────────────┐
        │ Windows IP: 10.10.60.14│
        │ Port Forwarding :8000  │
        └────────────┬───────────┘
                     ↓
        ┌────────────────────────┐
        │  WSL2 IP : 172.31.96.10│
        │  Laravel Server :8000  │
        └────────────┬───────────┘
                     ↓
        ┌────────────────────────┐
        │  MySQL (127.0.0.1:3306)│
        │  Database : c1appstat  │
        └────────────────────────┘
```

---

## Scripts d'Automatisation

### Script WSL2 : `start-server-network.sh`

```bash
#!/bin/bash
set -e

echo "🚀 Démarrage serveur Laravel ERH..."

# Aller au répertoire du projet
cd /home/daniel/work/projects/erh/site

# Tuer serveur existant
pkill -f "php artisan serve" 2>/dev/null || true

# Nettoyer caches
echo "🧹 Nettoyage caches..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# Vérifier DB
echo "🔌 Vérification connexion DB..."
php artisan tinker << EOF
try {
    DB::connection()->getPDO();
    echo "✅ Database OK\n";
} catch (Exception \$e) {
    echo "❌ Database ERROR\n";
    exit(1);
}
exit;
EOF

# Démarrer serveur
echo "✅ Serveur démarre..."
php artisan serve --host=0.0.0.0 --port=8000

# Afficher IP
echo ""
echo "🌐 IP WSL2 actuelle : $(hostname -I | awk '{print $1}')"
echo "📍 Accessible sur : http://10.10.60.14:8000"
echo ""
echo "⚠️  À exécuter sur Windows (PowerShell Admin) :"
echo "cd C:\...\erh\site && .\setup-wsl-network.ps1"
```

### Script Windows : `setup-wsl-network.ps1`

```powershell
# Récupérer IP WSL2
$wslIp = wsl hostname -I | Select-Object -First 1
$wslIp = $wslIp.Trim()

Write-Host "🌐 IP WSL2 détectée : $wslIp"

# Supprimer ancienne règle si elle existe
Remove-NetFirewallRule -DisplayName "Laravel ERH Server" -ErrorAction SilentlyContinue
netsh interface portproxy delete v4tov4 listenport=8000 listenaddress=0.0.0.0 ErrorAction SilentlyContinue

# Créer nouvelle règle port forwarding
Write-Host "⚙️  Configuration port forwarding..."
netsh interface portproxy add v4tov4 listenport=8000 listenaddress=0.0.0.0 connectport=8000 connectaddress=$wslIp

# Ouvrir pare-feu
Write-Host "🔐 Ouverture pare-feu..."
New-NetFirewallRule -DisplayName "Laravel ERH Server" -Direction Inbound -LocalPort 8000 -Protocol TCP -Action Allow

# Vérifier
Write-Host ""
Write-Host "✅ Configuration complétée !"
Write-Host "🌐 Accès : http://10.10.60.14:8000"
Write-Host ""
netsh interface portproxy show all
```

---

## Checklist : Avant d'aller en production

- [ ] IP Windows fixe configurée (10.10.60.14)
- [ ] Serveur Laravel démarre automatiquement après reboot
- [ ] Port forwarding créé et persistant
- [ ] Pare-feu Windows configuré
- [ ] Accès depuis autre PC du réseau testé
- [ ] Base de données accessible
- [ ] HTTPS activé (certificat auto-signé ou valide)
- [ ] Logs en place et accessibles
- [ ] Backup automatique en place

---

## Documentation Complète

Pour plus de détails sur chaque aspect :
- **Réseau WSL2 :** Voir section "Architecture Réseau"
- **Dépannage :** Voir section "Problèmes Courants"
- **Scripts :** Voir section "Scripts d'Automatisation"

---

**Statut:** Production-ready
**Fusion complétée:** 2026-01-27
**Mainteneur:** Claude Haiku 4.5
