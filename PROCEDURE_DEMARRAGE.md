# 🚀 Procédure de démarrage ERH Application

**Date de création :** 2026-01-24
**Mise à jour :** 2026-01-24

---

## 📋 Procédure complète après redémarrage

### Situation après redémarrage de la machine

Quand vous éteignez et rallumez votre machine Windows :
- ✅ La base de données MySQL reste intacte
- ❌ Le serveur Laravel ne tourne plus
- ⚠️ L'IP WSL2 peut avoir changé
- ❌ Le port forwarding Windows doit être reconfiguré

---

## 🎯 MÉTHODE RAPIDE (Recommandée)

### Étape 1 : Démarrer le serveur Laravel (WSL2)

Ouvrez votre terminal WSL2 et exécutez :

```bash
cd ~/work/projects/erh/site
./start-erh.sh
```

Le script va automatiquement :
- ✅ Vérifier si un serveur tourne déjà
- ✅ Nettoyer les caches Laravel
- ✅ Vérifier la connexion à la base de données
- ✅ Démarrer le serveur sur 0.0.0.0:8000
- ✅ Afficher l'IP WSL2 actuelle
- ✅ Vous donner les commandes Windows à exécuter

### Étape 2 : Configurer Windows (PowerShell Administrateur)

Le script `start-erh.sh` vous affichera les commandes exactes à exécuter.

**Sur Windows PowerShell (Administrateur) :**

```powershell
cd \\wsl$\Ubuntu\home\daniel\work\projects\erh\site
.\setup-wsl2-simple.ps1
```

**C'est tout ! ✨**

---

## 🔧 MÉTHODE MANUELLE (Si les scripts ne marchent pas)

### Étape 1 : WSL2 - Démarrer le serveur

```bash
# 1. Aller dans le dossier du projet
cd ~/work/projects/erh/site

# 2. Arrêter tout serveur existant (si nécessaire)
pkill -f "php artisan serve"

# 3. Vider les caches
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# 4. Démarrer le serveur
php artisan serve --host=0.0.0.0 --port=8000 &

# 5. Vérifier qu'il tourne bien
ss -tlnp | grep 8000
# Doit afficher: 0.0.0.0:8000

# 6. Noter l'IP WSL2
hostname -I | awk '{print $1}'
# Exemple: 172.31.96.10
```

### Étape 2 : Windows - Configurer le port forwarding

**PowerShell Administrateur :**

```powershell
# Remplacer 172.31.96.10 par votre IP WSL2 (obtenue à l'étape 1.6)
$wslIP = "172.31.96.10"

# Supprimer l'ancienne règle
netsh interface portproxy delete v4tov4 listenport=8000 listenaddress=0.0.0.0

# Créer la nouvelle règle
netsh interface portproxy add v4tov4 listenport=8000 listenaddress=0.0.0.0 connectport=8000 connectaddress=$wslIP

# Vérifier
netsh interface portproxy show all

# Configurer le pare-feu (si ce n'est pas déjà fait)
New-NetFirewallRule -DisplayName "ERH Laravel Port 8000" -Direction Inbound -LocalPort 8000 -Protocol TCP -Action Allow -ErrorAction SilentlyContinue
```

---

## 🔄 DÉMARRAGE AUTOMATIQUE (Optionnel - Avancé)

### Option 1 : Script batch Windows au démarrage

**Créer le fichier `C:\Scripts\start-erh.bat` :**

```batch
@echo off
echo Démarrage ERH Application...

REM Démarrer WSL2 et le serveur Laravel
wsl -d Ubuntu -u daniel bash -c "cd ~/work/projects/erh/site && ./start-erh.sh"

REM Attendre 10 secondes que le serveur démarre
timeout /t 10 /nobreak

REM Configurer le port forwarding
powershell.exe -ExecutionPolicy Bypass -File "\\wsl$\Ubuntu\home\daniel\work\projects\erh\site\setup-wsl2-simple.ps1"

echo ERH Application démarrée !
pause
```

**Ajouter au démarrage Windows :**
1. Appuyez sur `Win + R`
2. Tapez `shell:startup`
3. Copiez le fichier `start-erh.bat` dans ce dossier

### Option 2 : Tâche planifiée Windows

1. Ouvrir **Planificateur de tâches** Windows
2. Créer une nouvelle tâche
3. **Déclencheur :** Au démarrage du système
4. **Action :** Exécuter `C:\Scripts\start-erh.bat`
5. **Paramètres :** Exécuter avec les privilèges les plus élevés

---

## 🛑 Arrêt du serveur

### Méthode rapide

```bash
cd ~/work/projects/erh/site
./stop-erh.sh
```

### Méthode manuelle

**Dans WSL2 :**
```bash
pkill -f "php artisan serve"
```

**Sur Windows (PowerShell Admin) - Optionnel :**
```powershell
netsh interface portproxy delete v4tov4 listenport=8000 listenaddress=0.0.0.0
```

---

## ✅ Vérifications après démarrage

### 1. Vérifier que le serveur Laravel tourne

**WSL2 :**
```bash
ss -tlnp | grep 8000
# Attendu: LISTEN 0 4096 0.0.0.0:8000 0.0.0.0:*
```

### 2. Vérifier le port forwarding Windows

**PowerShell :**
```powershell
netsh interface portproxy show all
```

**Attendu :**
```
Écouter sur ipv4:     Connexion à ipv4:
Adresse      Port     Adresse         Port
0.0.0.0      8000     172.31.96.10    8000
```

### 3. Tester l'accès

**Navigateur :**
- ✅ http://localhost:8000
- ✅ http://10.10.60.14:8000 (votre IP réseau)
- ✅ http://172.31.96.10:8000 (IP WSL2)

---

## 🐛 Dépannage

### Problème 1 : "Port 8000 already in use"

```bash
# Trouver le processus qui utilise le port
lsof -i :8000

# Tuer le processus
kill -9 <PID>

# Ou tuer tous les serveurs PHP
pkill -f "php artisan serve"
```

### Problème 2 : "Permission denied" sur le script

```bash
chmod +x start-erh.sh
chmod +x stop-erh.sh
```

### Problème 3 : L'IP WSL2 a changé

L'IP WSL2 change parfois au redémarrage. C'est normal.

**Solution :**
1. Obtenir la nouvelle IP : `hostname -I | awk '{print $1}'`
2. Reconfigurer Windows avec le script `setup-wsl2-simple.ps1`

### Problème 4 : Base de données non accessible

```bash
# Vérifier que MySQL tourne
sudo service mysql status

# Démarrer MySQL si nécessaire
sudo service mysql start

# Tester la connexion
php artisan db:show
```

### Problème 5 : Le logo/images ne s'affichent pas

```bash
# Vérifier le lien symbolique
ls -la public/rhassets
# Doit pointer vers ../../rhassets

# Recréer si nécessaire
cd public
ln -s ../../rhassets rhassets
```

---

## 📊 État du serveur en un coup d'œil

**Commande rapide :**
```bash
echo "=== État Serveur ERH ===" && \
echo -n "Serveur Laravel: " && (ss -tlnp 2>/dev/null | grep -q "0.0.0.0:8000" && echo "✓ Actif" || echo "✗ Arrêté") && \
echo -n "IP WSL2: " && hostname -I | awk '{print $1}' && \
echo -n "MySQL: " && (sudo service mysql status | grep -q "running" && echo "✓ Actif" || echo "✗ Arrêté")
```

---

## 📁 Scripts disponibles

| Script | Description | Où l'exécuter |
|--------|-------------|---------------|
| `start-erh.sh` | Démarrage complet automatique | WSL2 |
| `stop-erh.sh` | Arrêt du serveur | WSL2 |
| `start-server-network.sh` | Démarrage serveur uniquement | WSL2 |
| `setup-wsl2-simple.ps1` | Configuration Windows | PowerShell Admin |
| `remove-wsl-network.ps1` | Suppression config Windows | PowerShell Admin |

---

## 🎓 Résumé pour les collègues

**Pour accéder à l'application ERH depuis le réseau :**

Donnez-leur simplement cette URL :
```
http://10.10.60.14:8000
```

**Identifiants de test :** (à personnaliser)
- Pseudo : `ADMIN`
- Mot de passe : `admin123`

---

## 🆘 Support

**Logs Laravel :**
```bash
tail -f /tmp/erh-laravel-server.log
tail -f storage/logs/laravel.log
```

**Logs système WSL2 :**
```bash
dmesg | tail -50
```

**Logs Windows Event Viewer :**
- Ouvrir "Observateur d'événements"
- Applications et services > Microsoft > Windows > Subsystem for Linux

---

**Dernière mise à jour :** 2026-01-24
**Auteur :** Configuration automatisée par Claude Sonnet 4.5
