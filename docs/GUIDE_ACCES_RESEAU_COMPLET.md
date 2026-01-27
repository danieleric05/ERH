# GUIDE COMPLET - ACCÈS RÉSEAU ERH
## WSL2 → Windows → Réseau Local

**Date :** 27 Janvier 2026
**Objectif :** Rendre ERH accessible pour les tests utilisateurs via `http://10.10.60.14:8000`

---

## 📋 PRÉREQUIS

- ✅ WSL2 installé sur Windows
- ✅ Laravel ERH configuré dans `/home/daniel/work/projects/erh/site`
- ✅ PowerShell avec droits Administrateur sur Windows
- ✅ Pare-feu Windows accessible

---

## 🎯 ARCHITECTURE RÉSEAU

```
┌─────────────────────────────────────────────────────────┐
│                    RÉSEAU LOCAL                          │
│                   (10.10.60.x)                          │
│                                                          │
│  ┌──────────────────────────────────────────────┐       │
│  │  UTILISATEURS (Navigateurs)                  │       │
│  │  http://10.10.60.14:8000                    │       │
│  └────────────────┬─────────────────────────────┘       │
│                   │                                      │
│                   ▼                                      │
│  ┌──────────────────────────────────────────────┐       │
│  │         WINDOWS (10.10.60.14)                │       │
│  │                                               │       │
│  │  Port Forwarding: :8000 → WSL2:8000         │       │
│  │  Pare-feu: Autoriser port 8000 TCP          │       │
│  └────────────────┬─────────────────────────────┘       │
│                   │                                      │
└───────────────────┼──────────────────────────────────────┘
                    │
                    ▼
         ┌──────────────────────────┐
         │   WSL2 (172.31.96.10)    │
         │                          │
         │  Laravel Server          │
         │  0.0.0.0:8000            │
         │                          │
         │  MySQL: localhost:3306   │
         └──────────────────────────┘
```

---

## 🚀 PROCÉDURE COMPLÈTE

### ÉTAPE 1 : Démarrer le serveur Laravel dans WSL2

```bash
# 1. Aller dans le répertoire du projet
cd /home/daniel/work/projects/erh/site

# 2. Nettoyer les caches Laravel
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# 3. Démarrer le serveur accessible sur toutes les interfaces (0.0.0.0)
php artisan serve --host=0.0.0.0 --port=8000
```

**OU utiliser le script automatique :**
```bash
cd /home/daniel/work/projects/erh/site
./start-server-network.sh
```

**Résultat attendu :**
```
🚀 Démarrage du serveur Laravel ERH...
📡 Accessible depuis le réseau sur http://172.31.96.10:8000

Server running on [http://0.0.0.0:8000]
Press Ctrl+C to stop the server
```

---

### ÉTAPE 2 : Vérifier l'IP WSL2

Dans un **nouveau terminal WSL2** (garder le serveur actif) :

```bash
# Obtenir l'IP WSL2
hostname -I

# Exemple de sortie : 172.31.96.10
```

**⚠️ IMPORTANT :** Cette IP change à chaque redémarrage de WSL2 !

**Tester l'accès depuis WSL2 :**
```bash
curl http://172.31.96.10:8000
# Doit afficher le HTML de la page de connexion
```

---

### ÉTAPE 3 : Configurer le Port Forwarding sur Windows

Ouvrir **PowerShell en tant qu'Administrateur** sur Windows :

```powershell
# Aller dans le dossier du projet
cd C:\Users\VotreNom\...\erh\site

# Exécuter le script de configuration
.\setup-wsl-network.ps1
```

**Ce script va :**
1. ✅ Récupérer l'IP WSL2 automatiquement
2. ✅ Récupérer l'IP Windows (10.10.60.14)
3. ✅ Créer le port forwarding : Windows:8000 → WSL2:8000
4. ✅ Configurer le pare-feu Windows
5. ✅ Afficher la configuration finale

**Résultat attendu :**
```
================================================
  Configuration réseau WSL2 pour ERH
================================================

1. Récupération de l'IP WSL2...
   IP WSL2 : 172.31.96.10

2. Récupération de l'IP Windows...
   IP Windows : 10.10.60.14

3. Nettoyage des anciennes règles...
   Nettoyage terminé

4. Création du port forwarding...
   Windows :8000 -> WSL2 (172.31.96.10):8000
   Port forwarding créé avec succès !

5. Configuration du pare-feu Windows...
   Règle pare-feu créée avec succès !

================================================
  Configuration terminée !
================================================

Adresses d'accès :
  - Localhost       : http://localhost:8000
  - IP Windows      : http://10.10.60.14:8000
  - IP WSL2 (direct): http://172.31.96.10:8000

Partagez l'IP suivante avec vos collègues :
  http://10.10.60.14:8000
```

---

### ÉTAPE 4 : Tester l'accès depuis Windows

Dans un **navigateur Windows** :

```
http://localhost:8000          ✅ Doit fonctionner
http://10.10.60.14:8000        ✅ Doit fonctionner
http://127.0.0.1:8000          ✅ Doit fonctionner
```

**Si ça ne fonctionne pas**, vérifier :

```powershell
# Vérifier le port forwarding
netsh interface portproxy show all

# Devrait afficher :
# Listen on ipv4:             Connect to ipv4:
# Address         Port        Address         Port
# --------------- ----------  --------------- ----------
# 0.0.0.0         8000        172.31.96.10    8000

# Vérifier le pare-feu
Get-NetFirewallRule -DisplayName "Laravel ERH Server"

# Devrait afficher la règle active
```

---

### ÉTAPE 5 : Tester l'accès depuis le réseau

Sur un **autre ordinateur du réseau** (10.10.60.x) :

```
http://10.10.60.14:8000
```

**Résultat attendu :** Page de connexion ERH

---

## 🔧 DÉPANNAGE

### Problème 1 : "Connection refused" depuis Windows

**Cause :** Port forwarding non configuré

**Solution :**
```powershell
# PowerShell Admin
cd C:\...\erh\site
.\setup-wsl-network.ps1
```

---

### Problème 2 : "Connection timeout" depuis le réseau

**Cause :** Pare-feu Windows bloque le port 8000

**Solution :**
```powershell
# PowerShell Admin
New-NetFirewallRule -DisplayName "Laravel ERH Server" `
    -Direction Inbound `
    -LocalPort 8000 `
    -Protocol TCP `
    -Action Allow
```

---

### Problème 3 : IP WSL2 change après redémarrage

**Cause :** WSL2 obtient une nouvelle IP à chaque démarrage

**Solution :** Ré-exécuter le script PowerShell après chaque redémarrage WSL2
```powershell
.\setup-wsl-network.ps1
```

**OU utiliser le script de démarrage automatique :**
```bash
# Dans WSL2
cd /home/daniel/work/projects/erh/site
./start-erh.sh
```

Ce script :
1. Démarre le serveur Laravel
2. Affiche l'IP WSL2 actuelle
3. Vous rappelle d'exécuter le script PowerShell

---

### Problème 4 : Serveur ne démarre pas

**Erreur :** `Address already in use`

**Solution :**
```bash
# Tuer le processus existant
pkill -f "php artisan serve"

# Redémarrer
php artisan serve --host=0.0.0.0 --port=8000
```

**OU changer de port :**
```bash
php artisan serve --host=0.0.0.0 --port=8001
# Puis mettre à jour setup-wsl-network.ps1 (port 8001)
```

---

### Problème 5 : "Page not found" ou erreurs 500

**Cause :** Cache Laravel ou configuration

**Solution :**
```bash
cd /home/daniel/work/projects/erh/site

# Nettoyer tout
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Vérifier .env
cat .env | grep -E "APP_URL|DB_"

# Redémarrer
php artisan serve --host=0.0.0.0 --port=8000
```

---

## 🔐 SÉCURITÉ

### ⚠️ Mode développement uniquement

Cette configuration est pour **tests uniquateurs en environnement local** uniquement.

**NE PAS utiliser en production !**

Raisons :
- Pas de HTTPS (données en clair)
- Serveur PHP intégré (pas optimisé)
- Pas de protection DDoS
- Debug activé (`APP_DEBUG=true`)

### Pour la production

Utiliser :
- **Nginx** ou **Apache** comme serveur web
- **SSL/TLS** (HTTPS avec certificat)
- **Firewall** configuré strictement
- **VPN** pour accès externe
- **Load balancer** si nécessaire

---

## 📝 SCRIPTS DISPONIBLES

### WSL2 (Bash)

| Script | Description | Usage |
|--------|-------------|-------|
| `start-server-network.sh` | Démarre le serveur accessible réseau | `./start-server-network.sh` |
| `start-erh.sh` | Démarre avec guide complet | `./start-erh.sh` |
| `stop-erh.sh` | Arrête tous les serveurs | `./stop-erh.sh` |

### Windows (PowerShell)

| Script | Description | Droits |
|--------|-------------|--------|
| `setup-wsl-network.ps1` | Configure port forwarding + pare-feu | Admin |
| `remove-wsl-network.ps1` | Supprime la configuration | Admin |
| `setup-wsl2-simple.ps1` | Configuration simplifiée | Admin |

---

## 📊 CHECKLIST DÉMARRAGE

Avant de partager avec les utilisateurs :

- [ ] Serveur Laravel démarré dans WSL2 (`php artisan serve --host=0.0.0.0`)
- [ ] Script PowerShell exécuté (`setup-wsl-network.ps1`)
- [ ] Test localhost Windows (`http://localhost:8000`)
- [ ] Test IP Windows (`http://10.10.60.14:8000`)
- [ ] Test depuis autre machine réseau
- [ ] Base de données accessible
- [ ] Credentials de test préparés
- [ ] Documentation utilisateur prête

---

## 🎯 WORKFLOW QUOTIDIEN

### Matin (Démarrage)

```bash
# 1. Dans WSL2
cd /home/daniel/work/projects/erh/site
./start-erh.sh

# 2. Copier l'IP WSL2 affichée (ex: 172.31.96.10)

# 3. Dans PowerShell Windows (Admin)
cd C:\...\erh\site
.\setup-wsl-network.ps1

# 4. Partager avec les utilisateurs
# "Le site est accessible sur http://10.10.60.14:8000"
```

### Soir (Arrêt)

```bash
# Dans WSL2
cd /home/daniel/work/projects/erh/site
./stop-erh.sh

# OU simplement Ctrl+C dans le terminal du serveur
```

---

## 📞 SUPPORT UTILISATEURS

### Message type pour les testeurs

```
Bonjour,

Le site ERH est accessible pour vos tests à l'adresse :

🔗 http://10.10.60.14:8000

Credentials de test :
- Utilisateur : [à définir]
- Mot de passe : [à définir]

Fonctionnalités à tester :
✅ Connexion/Déconnexion
✅ Ajout de travailleur (avec photo)
✅ Modification de travailleur
✅ Recherche et filtres
✅ Export Excel

Si vous rencontrez un problème, merci de noter :
- La page concernée
- L'action effectuée
- Le message d'erreur (screenshot si possible)

Merci pour votre aide !
```

---

## 🔄 AUTOMATISATION (OPTIONNEL)

### Script de démarrage Windows (Tâche planifiée)

Créer un fichier `start-erh-auto.bat` :

```batch
@echo off
wsl -d Ubuntu -u daniel -- bash -c "cd /home/daniel/work/projects/erh/site && nohup php artisan serve --host=0.0.0.0 --port=8000 > /dev/null 2>&1 &"
timeout /t 3
powershell -ExecutionPolicy Bypass -File "C:\...\erh\site\setup-wsl-network.ps1"
echo ERH démarré avec succès !
pause
```

Ajouter au démarrage Windows pour auto-start.

---

## 📈 MONITORING

### Vérifier que le serveur tourne

```bash
# WSL2
ps aux | grep "php artisan serve"

# Windows
curl http://localhost:8000
```

### Voir les logs en temps réel

```bash
# WSL2
tail -f /home/daniel/work/projects/erh/site/storage/logs/laravel.log
```

---

## ✅ RÉSUMÉ

| Étape | Commande | Où |
|-------|----------|-----|
| 1. Démarrer serveur | `./start-server-network.sh` | WSL2 |
| 2. Config réseau | `.\setup-wsl-network.ps1` | Windows (Admin) |
| 3. Tester local | `http://localhost:8000` | Navigateur Windows |
| 4. Tester réseau | `http://10.10.60.14:8000` | Autre machine |
| 5. Partager | Envoyer l'URL aux testeurs | Email/Slack |

---

**Configuration réseau ERH - Prêt pour les tests ! 🚀**

*Généré le 27 janvier 2026*
