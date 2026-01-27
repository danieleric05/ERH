# ⚡ DÉMARRAGE RAPIDE - ACCÈS RÉSEAU ERH

**IP Réseau :** `http://10.10.60.14:8000`
**IP WSL2 actuelle :** `172.31.96.10`

---

## 🚀 DÉMARRER EN 2 ÉTAPES

### ✅ ÉTAPE 1 : Dans WSL2 (Terminal Linux)

```bash
cd /home/daniel/work/projects/erh/site
php artisan serve --host=0.0.0.0 --port=8000
```

**OU avec le script :**
```bash
./start-server-network.sh
```

**Résultat :** Serveur démarré sur `http://0.0.0.0:8000`

---

### ✅ ÉTAPE 2 : Dans Windows (PowerShell ADMIN)

```powershell
cd C:\...\erh\site
.\setup-wsl-network.ps1
```

**Résultat :** Port forwarding configuré + pare-feu ouvert

---

## 🧪 TESTER

### Depuis Windows
```
http://localhost:8000       ✅
http://10.10.60.14:8000     ✅
```

### Depuis le réseau (autre PC)
```
http://10.10.60.14:8000     ✅
```

---

## ⚠️ SI ÇA NE MARCHE PAS

### Problème : "Connection refused"
```bash
# Vérifier que le serveur tourne
ps aux | grep "php artisan serve"

# Si rien → redémarrer
cd /home/daniel/work/projects/erh/site
php artisan serve --host=0.0.0.0 --port=8000
```

### Problème : Réseau inaccessible
```powershell
# Re-exécuter le script PowerShell (Admin)
.\setup-wsl-network.ps1
```

---

## 📝 NOTES

- ⚠️ **L'IP WSL2 change** à chaque redémarrage → Re-exécuter le script PowerShell
- ✅ **Le serveur doit rester actif** → Ne pas fermer le terminal WSL2
- 🔐 **Mode développement** → Ne pas utiliser en production

---

## 📖 DOCUMENTATION COMPLÈTE

Pour plus de détails : `GUIDE_ACCES_RESEAU_COMPLET.md`
