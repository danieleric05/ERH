# 🪟 INSTRUCTIONS CONFIGURATION WINDOWS

**Objectif :** Rendre ERH accessible sur `http://10.10.60.14:8000` pour tout le réseau

---

## 📋 ÉTAPES À SUIVRE SUR WINDOWS

### ✅ ÉTAPE 1 : Ouvrir PowerShell en Administrateur

**Option A : Via le menu Démarrer**
1. Clic droit sur le bouton **Démarrer** (Windows)
2. Cliquer sur **"Terminal (Admin)"** ou **"Windows PowerShell (Administrateur)"**
3. Cliquer **"Oui"** sur la fenêtre de contrôle UAC

**Option B : Via la recherche**
1. Appuyer sur **Windows + S**
2. Taper **"PowerShell"**
3. Clic droit sur **"Windows PowerShell"**
4. Sélectionner **"Exécuter en tant qu'administrateur"**
5. Cliquer **"Oui"** sur la fenêtre UAC

**Résultat attendu :** Fenêtre PowerShell avec le titre **"Administrateur: Windows PowerShell"**

---

### ✅ ÉTAPE 2 : Naviguer vers le dossier du projet

Dans PowerShell, taper :

```powershell
# Aller dans le dossier où se trouve le projet ERH
# REMPLACE par ton chemin réel !

cd C:\Users\VotreNom\Documents\erh\site

# OU si c'est sur un autre lecteur :
cd D:\Projets\erh\site

# OU via WSL :
cd \\wsl$\Ubuntu\home\daniel\work\projects\erh\site
```

**💡 Astuce :** Tu peux aussi utiliser l'explorateur Windows pour trouver le dossier, puis :
1. Maintenir **Shift** + Clic droit dans le dossier `site`
2. Sélectionner **"Ouvrir la fenêtre PowerShell ici en tant qu'admin"**

**Vérifier que tu es au bon endroit :**
```powershell
# Lister les fichiers
dir

# Tu dois voir :
# setup-wsl-network.ps1
# start-server-network.sh
# .env
# artisan
```

---

### ✅ ÉTAPE 3 : Exécuter le script de configuration

```powershell
.\setup-wsl-network.ps1
```

**Si tu obtiens une erreur "script désactivé" :**
```powershell
# Autoriser l'exécution pour cette session
Set-ExecutionPolicy -ExecutionPolicy Bypass -Scope Process -Force

# Puis réessayer
.\setup-wsl-network.ps1
```

---

### ✅ ÉTAPE 4 : Vérifier la sortie du script

**Résultat attendu :**

```
================================================
  Configuration réseau WSL2 pour ERH
================================================

1. Récupération de l'IP WSL2...
   IP WSL2 : 172.31.96.10 ✅

2. Récupération de l'IP Windows...
   IP Windows : 10.10.60.14 ✅

3. Nettoyage des anciennes règles...
   Nettoyage terminé ✅

4. Création du port forwarding...
   Windows :8000 -> WSL2 (172.31.96.10):8000
   Port forwarding créé avec succès ! ✅

5. Configuration du pare-feu Windows...
   Règle pare-feu créée avec succès ! ✅

================================================
  Configuration terminée !
================================================

Règles de port forwarding actives :
Listen on ipv4:             Connect to ipv4:
Address         Port        Address         Port
0.0.0.0         8000        172.31.96.10    8000

Adresses d'accès :
  - Localhost       : http://localhost:8000
  - IP Windows      : http://10.10.60.14:8000
  - IP WSL2 (direct): http://172.31.96.10:8000

Partagez l'IP suivante avec vos collègues :
  http://10.10.60.14:8000
```

**Si tout est vert (✅), c'est parfait !**

---

### ✅ ÉTAPE 5 : Tester l'accès depuis Windows

Ouvrir un navigateur (Chrome, Edge, Firefox) et aller sur :

```
http://localhost:8000
```

**Résultat attendu :** Page de connexion ERH

Puis tester :
```
http://10.10.60.14:8000
```

**Résultat attendu :** Page de connexion ERH (même page)

---

## 🔧 DÉPANNAGE

### ❌ Erreur : "L'exécution de scripts est désactivée"

```powershell
Set-ExecutionPolicy -ExecutionPolicy Bypass -Scope Process -Force
.\setup-wsl-network.ps1
```

---

### ❌ Erreur : "Access Denied" ou "Permission refusée"

**Cause :** PowerShell n'est pas en mode Administrateur

**Solution :** Fermer et rouvrir PowerShell en tant qu'Administrateur (voir Étape 1)

---

### ❌ Erreur : "wsl : Le terme n'est pas reconnu"

**Cause :** WSL2 n'est pas installé ou pas dans le PATH

**Solution :**
```powershell
# Vérifier si WSL est installé
wsl --status

# Si erreur : installer WSL2
wsl --install
```

---

### ❌ Erreur : "IP WSL2 : " (vide)

**Cause :** WSL2 n'est pas démarré ou Ubuntu pas lancé

**Solution :**
```powershell
# Démarrer WSL2
wsl

# Dans WSL, vérifier l'IP
hostname -I
# Devrait afficher : 172.31.96.10 (ou similaire)

# Redémarrer le script PowerShell
.\setup-wsl-network.ps1
```

---

### ❌ Le site ne s'affiche pas sur localhost:8000

**Cause :** Le serveur Laravel n'est pas démarré dans WSL2

**Solution :**
1. Ouvrir un terminal WSL2 (Ubuntu)
2. Exécuter :
```bash
cd /home/daniel/work/projects/erh/site
php artisan serve --host=0.0.0.0 --port=8000
```

---

### ❌ Le site fonctionne sur localhost mais pas sur 10.10.60.14

**Cause :** Port forwarding ou pare-feu

**Vérifier le port forwarding :**
```powershell
netsh interface portproxy show all
```

**Devrait afficher :**
```
Listen on ipv4:             Connect to ipv4:
0.0.0.0         8000        172.31.96.10    8000
```

**Si vide, réexécuter :**
```powershell
.\setup-wsl-network.ps1
```

**Vérifier le pare-feu :**
```powershell
Get-NetFirewallRule -DisplayName "Laravel ERH Server"
```

**Devrait afficher la règle "Laravel ERH Server" avec Enabled=True**

---

### ❌ Depuis le réseau, ça ne marche pas

**1. Vérifier que Windows a bien l'IP 10.10.60.14 :**
```powershell
ipconfig
```

Chercher la ligne avec `10.10.60.14`

**2. Vérifier le pare-feu réseau :**
```powershell
# Tester depuis Windows
Test-NetConnection -ComputerName 10.10.60.14 -Port 8000
```

**3. Depuis un autre PC du réseau :**
- Ouvrir un navigateur
- Aller sur `http://10.10.60.14:8000`
- Si timeout : problème de pare-feu ou réseau
- Si connexion refusée : serveur pas démarré

---

## 🔄 APRÈS REDÉMARRAGE WSL2

**⚠️ IMPORTANT :** L'IP WSL2 change à chaque redémarrage !

**Procédure :**

1. Dans WSL2 : Vérifier la nouvelle IP
```bash
hostname -I
# Note la nouvelle IP (ex: 172.31.98.5)
```

2. Dans PowerShell (Admin) : Re-exécuter le script
```powershell
.\setup-wsl-network.ps1
# Le script va automatiquement détecter la nouvelle IP
```

3. Tester l'accès

---

## 📊 COMMANDES UTILES

### Voir le port forwarding actif
```powershell
netsh interface portproxy show all
```

### Supprimer le port forwarding
```powershell
netsh interface portproxy delete v4tov4 listenport=8000 listenaddress=0.0.0.0
```

### Voir les règles du pare-feu
```powershell
Get-NetFirewallRule | Where-Object {$_.DisplayName -like "*ERH*"}
```

### Supprimer la règle pare-feu
```powershell
Remove-NetFirewallRule -DisplayName "Laravel ERH Server"
```

### Tester la connexion
```powershell
# Depuis Windows
Test-NetConnection -ComputerName localhost -Port 8000
Test-NetConnection -ComputerName 10.10.60.14 -Port 8000

# Via curl
curl http://localhost:8000
curl http://10.10.60.14:8000
```

---

## ✅ CHECKLIST FINALE

Avant de partager avec les utilisateurs :

- [ ] PowerShell exécuté en tant qu'Administrateur
- [ ] Script `setup-wsl-network.ps1` exécuté sans erreur
- [ ] Serveur Laravel actif dans WSL2
- [ ] `http://localhost:8000` accessible
- [ ] `http://10.10.60.14:8000` accessible
- [ ] Test depuis un autre PC du réseau réussi
- [ ] Credentials de test préparés

---

## 📧 MESSAGE POUR LES TESTEURS

```
Bonjour,

Le site ERH est maintenant accessible pour vos tests à l'adresse suivante :

🔗 http://10.10.60.14:8000

Identifiants de test :
- Pseudo : [à définir]
- Mot de passe : [à définir]

Fonctionnalités à tester en priorité :
✅ Connexion / Déconnexion
✅ Ajout de travailleur (avec photo)
✅ Modification de travailleur
✅ Recherche et filtres
✅ Listes (embauchés, journaliers)
✅ Export Excel

En cas de problème, merci de me fournir :
- La page concernée (URL)
- L'action effectuée
- Le message d'erreur (screenshot si possible)

Le site est accessible de 8h à 18h pendant la phase de test.

Merci pour votre aide !
```

---

## 🎯 RÉSUMÉ ULTRA-RAPIDE

**Sur Windows (PowerShell Admin) :**
```powershell
cd \\wsl$\Ubuntu\home\daniel\work\projects\erh\site
Set-ExecutionPolicy Bypass -Scope Process -Force
.\setup-wsl-network.ps1
```

**Tester :**
```
http://10.10.60.14:8000
```

**Partager :**
```
"Le site est sur http://10.10.60.14:8000"
```

---

**Configuration Windows - Prêt ! 🚀**
