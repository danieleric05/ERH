# 🌐 Guide complet : Accès réseau ERH via WSL2

**Date :** 2026-01-24

## 🔍 Diagnostic de la situation

### Votre configuration
- **Windows IP :** `10.10.60.14` (IP réelle sur le réseau)
- **WSL2 IP :** `172.31.96.10` (IP virtuelle, change à chaque redémarrage)
- **Problème :** WSL2 est isolé du réseau Windows par défaut

### Pourquoi ça ne marche pas ?
WSL2 fonctionne comme une machine virtuelle avec sa propre interface réseau. Les autres machines du réseau voient votre **IP Windows** (`10.10.60.14`) mais pas l'**IP WSL2** (`172.31.96.10`).

## ✅ Solution en 3 étapes

### 📍 ÉTAPE 1 : Arrêter le serveur actuel

Dans le terminal WSL actuel :
```bash
# Tuer le processus PHP
pkill -f "php artisan serve"
```

Vérifiez que le serveur est bien arrêté :
```bash
ss -tlnp | grep 8000
# Doit ne rien afficher
```

### 📍 ÉTAPE 2 : Démarrer le serveur accessible sur WSL2

Dans WSL2 :
```bash
cd /home/daniel/work/projects/erh/site
./start-server-network.sh
```

OU manuellement :
```bash
php artisan serve --host=0.0.0.0 --port=8000
```

Vérifiez que le serveur écoute sur `0.0.0.0` :
```bash
ss -tlnp | grep 8000
# Doit afficher : 0.0.0.0:8000 (et NON 127.0.0.1:8000)
```

### 📍 ÉTAPE 3 : Configurer le port forwarding Windows → WSL2

#### Option A : Script PowerShell automatique (RECOMMANDÉ)

**Sur Windows** (PowerShell en tant qu'Administrateur) :

1. Ouvrir PowerShell en tant qu'Administrateur :
   - Clic droit sur le bouton Démarrer
   - Choisir "Windows PowerShell (Admin)" ou "Terminal (Admin)"

2. Aller dans le dossier du projet :
   ```powershell
   cd C:\Users\daniel.aboussou\path\to\erh\site
   ```

3. Exécuter le script de configuration :
   ```powershell
   .\setup-wsl-network.ps1
   ```

   Si vous avez une erreur "script désactivé", exécutez d'abord :
   ```powershell
   Set-ExecutionPolicy -ExecutionPolicy RemoteSigned -Scope CurrentUser
   ```

#### Option B : Configuration manuelle

**Sur Windows** (PowerShell en tant qu'Administrateur) :

1. Récupérer l'IP WSL2 :
   ```powershell
   wsl hostname -I
   # Exemple de résultat : 172.31.96.10
   ```

2. Créer le port forwarding :
   ```powershell
   netsh interface portproxy add v4tov4 listenport=8000 listenaddress=0.0.0.0 connectport=8000 connectaddress=172.31.96.10
   ```

3. Ouvrir le pare-feu :
   ```powershell
   New-NetFirewallRule -DisplayName "Laravel ERH Server" -Direction Inbound -LocalPort 8000 -Protocol TCP -Action Allow
   ```

4. Vérifier la configuration :
   ```powershell
   netsh interface portproxy show all
   ```

## 🎯 Test de l'accès

### Depuis votre machine Windows

**Navigateur :**
- http://localhost:8000
- http://10.10.60.14:8000
- http://172.31.96.10:8000

**PowerShell :**
```powershell
# Tester localhost
curl http://localhost:8000

# Tester IP Windows
curl http://10.10.60.14:8000

# Tester IP WSL2
curl http://172.31.96.10:8000
```

### Depuis une autre machine du réseau

**Navigateur :**
```
http://10.10.60.14:8000
```

**Ping test :**
```bash
ping 10.10.60.14
# Doit répondre
```

## 🔧 Vérifications et dépannage

### 1. Vérifier que le serveur Laravel tourne

**Dans WSL2 :**
```bash
ss -tlnp | grep 8000
```

**Résultat attendu :**
```
LISTEN 0  4096  0.0.0.0:8000  0.0.0.0:*  users:(("php8.3",pid=XXX,fd=6))
```

✅ `0.0.0.0:8000` = Bon
❌ `127.0.0.1:8000` = Mauvais (redémarrer avec --host=0.0.0.0)

### 2. Vérifier le port forwarding Windows

**Sur Windows (PowerShell Admin) :**
```powershell
netsh interface portproxy show all
```

**Résultat attendu :**
```
Écouter sur ipv4:             Connexion à ipv4:
Adresse         Port          Adresse         Port
--------------- ----------    --------------- ----------
0.0.0.0         8000          172.31.96.10    8000
```

### 3. Vérifier le pare-feu Windows

**Sur Windows (PowerShell Admin) :**
```powershell
Get-NetFirewallRule -DisplayName "Laravel ERH Server" | Format-List
```

Doit afficher une règle avec `Enabled: True`

### 4. Test de connexion WSL2 → Windows

**Dans WSL2 :**
```bash
# Récupérer l'IP Windows depuis WSL2
cat /etc/resolv.conf | grep nameserver | awk '{print $2}'

# Ou
ip route show | grep -i default | awk '{print $3}'
```

## 🚨 Problèmes courants

### Problème 1 : "Accès refusé" lors du script PowerShell

**Solution :**
```powershell
# Autoriser l'exécution de scripts
Set-ExecutionPolicy -ExecutionPolicy RemoteSigned -Scope CurrentUser

# Puis réessayer
.\setup-wsl-network.ps1
```

### Problème 2 : L'IP WSL2 change après redémarrage

**Cause :** L'IP WSL2 est dynamique et change à chaque redémarrage de WSL2.

**Solution :** Relancer le script `setup-wsl-network.ps1` après chaque redémarrage.

**Automatisation (optionnel) :**
Créer une tâche planifiée Windows qui exécute le script au démarrage.

### Problème 3 : Le port 8000 est déjà utilisé

**Vérifier quel processus utilise le port :**

**Sur Windows :**
```powershell
netstat -ano | findstr :8000
```

**Dans WSL2 :**
```bash
lsof -i :8000
# ou
ss -tlnp | grep 8000
```

**Tuer le processus si nécessaire :**
```bash
kill -9 <PID>
```

### Problème 4 : Le pare-feu d'entreprise bloque le port

**Vérification :**
- Contacter l'administrateur réseau
- Demander l'ouverture du port 8000 en entrée

**Workaround temporaire :**
Utiliser un port non bloqué (ex: 8080, 3000)
```bash
php artisan serve --host=0.0.0.0 --port=8080
```

Et adapter le port forwarding :
```powershell
netsh interface portproxy add v4tov4 listenport=8080 listenaddress=0.0.0.0 connectport=8080 connectaddress=172.31.96.10
```

## 📋 Checklist complète

### Avant de partager l'accès

- [ ] Le serveur Laravel tourne dans WSL2 avec `--host=0.0.0.0`
- [ ] Le port forwarding est configuré (vérifier avec `netsh interface portproxy show all`)
- [ ] Le pare-feu Windows autorise le port 8000
- [ ] L'accès fonctionne depuis `http://10.10.60.14:8000`
- [ ] Le logo et les images s'affichent correctement
- [ ] La table `user` existe dans la base de données

### URLs à partager

Pour les collègues sur le même réseau :
```
http://10.10.60.14:8000
```

Pour vous-même :
- Local : http://localhost:8000
- IP Windows : http://10.10.60.14:8000
- IP WSL2 : http://172.31.96.10:8000

## 🔄 Commandes de maintenance

### Redémarrer toute la configuration

**1. Dans WSL2 :**
```bash
# Arrêter le serveur
pkill -f "php artisan serve"

# Redémarrer
cd /home/daniel/work/projects/erh/site
./start-server-network.sh
```

**2. Sur Windows (PowerShell Admin) :**
```powershell
# Reconfigurer le port forwarding
.\setup-wsl-network.ps1
```

### Supprimer la configuration

**Sur Windows (PowerShell Admin) :**
```powershell
.\remove-wsl-network.ps1
```

OU manuellement :
```powershell
# Supprimer le port forwarding
netsh interface portproxy delete v4tov4 listenport=8000 listenaddress=0.0.0.0

# Supprimer la règle pare-feu
Remove-NetFirewallRule -DisplayName "Laravel ERH Server"
```

## 💡 Alternative : Utiliser Docker (pour éviter WSL2)

Si vous rencontrez trop de problèmes avec WSL2, Docker peut être une alternative :

```dockerfile
# Dockerfile
FROM php:8.3-cli
WORKDIR /app
COPY . .
EXPOSE 8000
CMD ["php", "artisan", "serve", "--host=0.0.0.0"]
```

Puis :
```bash
docker build -t erh .
docker run -p 8000:8000 erh
```

## 📚 Ressources

- [WSL2 Networking Documentation](https://docs.microsoft.com/windows/wsl/networking)
- [netsh portproxy Documentation](https://docs.microsoft.com/windows-server/networking/technologies/netsh/netsh-interface-portproxy)
- [Laravel Server Documentation](https://laravel.com/docs/10.x/deployment)

---

**Créé le :** 2026-01-24
**Dernière mise à jour :** 2026-01-24

## 🆘 Besoin d'aide ?

Si vous rencontrez toujours des problèmes après avoir suivi ce guide :

1. Vérifiez les logs Laravel : `tail -f storage/logs/laravel.log`
2. Vérifiez les logs du serveur PHP
3. Consultez la section Dépannage ci-dessus
4. Contactez le support technique
