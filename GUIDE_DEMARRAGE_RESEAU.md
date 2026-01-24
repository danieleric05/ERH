# 🌐 Guide de démarrage du serveur ERH sur le réseau

**Date :** 2026-01-24

## 🔍 Problème identifié

Le serveur Laravel écoute seulement sur `127.0.0.1:8000` (localhost), ce qui le rend **inaccessible depuis le réseau**.

## ✅ Solution

### Étape 1 : Arrêter le serveur actuel

Dans le terminal où le serveur est en cours d'exécution :
```bash
# Appuyez sur Ctrl+C pour arrêter le serveur
```

Ou si vous ne trouvez pas le terminal :
```bash
# Tuer le processus PHP
pkill -f "php artisan serve"
```

### Étape 2 : Démarrer le serveur accessible sur le réseau

#### Option A : Script automatique (recommandé)
```bash
cd /home/daniel/work/projects/erh/site
./start-server-network.sh
```

#### Option B : Commande manuelle
```bash
cd /home/daniel/work/projects/erh/site
php artisan serve --host=0.0.0.0 --port=8000
```

### Étape 3 : Vérifier l'accès

**Depuis votre machine :**
```
http://localhost:8000
```

**Depuis le réseau :**
```
http://172.31.96.10:8000
```

## 🔧 Corrections apportées

### 1. Lien symbolique vers les assets ✅
```bash
# Créé : /home/daniel/work/projects/erh/site/public/rhassets
# Pointe vers : ../../rhassets
```

Cela permet au serveur web de servir les images, CSS, JS, etc.

### 2. Cache Laravel vidé ✅
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

### 3. Script de démarrage créé ✅
- **Fichier :** `site/start-server-network.sh`
- **Fonction :** Démarre le serveur accessible depuis le réseau
- **Permissions :** Exécutable

## 📝 Vérifications

### Logo sur la page de login
Le logo utilise déjà la bonne fonction :
```php
<img src="{{ asset('rhassets/images/logoERH.png') }}" alt="Logo ERH">
```

**Fichier logo :** `/rhassets/images/logoERH.png` ✅ (existe, 42 KB)
**Image de fond :** `/rhassets/images/auth_bg.jpg` ✅ (existe, 745 KB)

### Configuration réseau
**.env configuré correctement :**
```
APP_URL=http://172.31.96.10:8000
```

## ⚠️ Important

### Différence entre les commandes

| Commande | Accessible depuis |
|----------|-------------------|
| `php artisan serve` | ❌ Localhost uniquement (127.0.0.1) |
| `php artisan serve --host=0.0.0.0` | ✅ Réseau complet (toutes les interfaces) |

### Firewall

Si le serveur est toujours inaccessible après le redémarrage, vérifiez le pare-feu :

**Windows :**
```powershell
# Vérifier si le port 8000 est ouvert
netsh advfirewall firewall show rule name=all | findstr 8000

# Ouvrir le port 8000 (si nécessaire)
netsh advfirewall firewall add rule name="Laravel Server" dir=in action=allow protocol=TCP localport=8000
```

**Linux (WSL) :**
```bash
# Vérifier le pare-feu
sudo ufw status

# Autoriser le port 8000 (si nécessaire)
sudo ufw allow 8000/tcp
```

## 🎯 Test complet

### 1. Depuis votre machine
```bash
curl http://localhost:8000
# Doit afficher la page de login
```

### 2. Depuis le réseau
```bash
curl http://172.31.96.10:8000/rhassets/images/logoERH.png
# Doit télécharger l'image du logo
```

### 3. Navigateur
- **Local :** http://localhost:8000
- **Réseau :** http://172.31.96.10:8000

Le logo et le fond doivent s'afficher correctement.

## 🚀 Démarrage permanent (production)

Pour un serveur de production, utilisez plutôt :

### Nginx + PHP-FPM
```bash
# Installation
sudo apt install nginx php8.3-fpm

# Configuration à créer dans /etc/nginx/sites-available/erh
```

### Apache + mod_php
```bash
# Installation
sudo apt install apache2 libapache2-mod-php8.3

# Activer mod_rewrite
sudo a2enmod rewrite
```

## 📌 Résumé des modifications

| Élément | Avant | Après |
|---------|-------|-------|
| Serveur écoute sur | 127.0.0.1:8000 | 0.0.0.0:8000 |
| Assets accessibles | ❌ Non | ✅ Oui (lien symbolique) |
| Cache Laravel | ⚠️ Ancien | ✅ Vidé |
| Script démarrage | ❌ Non | ✅ `start-server-network.sh` |

## 🆘 Dépannage

### Le logo ne s'affiche toujours pas
```bash
# 1. Vérifier le lien symbolique
ls -la /home/daniel/work/projects/erh/site/public/rhassets

# 2. Vérifier les permissions
chmod 755 /home/daniel/work/projects/erh/rhassets
chmod 644 /home/daniel/work/projects/erh/rhassets/images/logoERH.png

# 3. Tester l'accès direct
curl http://172.31.96.10:8000/rhassets/images/logoERH.png -I
```

### Le serveur n'est pas accessible du réseau
```bash
# 1. Vérifier que le serveur écoute sur 0.0.0.0
ss -tlnp | grep 8000
# Doit afficher : 0.0.0.0:8000 (pas 127.0.0.1:8000)

# 2. Vérifier l'IP de la machine
ip addr show | grep "inet "

# 3. Tester depuis la même machine
curl http://172.31.96.10:8000
```

---

**Créé le :** 2026-01-24
**Dernière mise à jour :** 2026-01-24
