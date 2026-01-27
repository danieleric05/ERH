# 📋 GUIDE : Importation CSV Sanctions

**Date :** 27 janvier 2026
**Source CSV :** `/mnt/d/Docs/1.REPERTOIRE SANCTION.csv`
**Total sanctions :** 2 527 enregistrements

---

## 🚀 Démarrage rapide

### Option 1 : Commande Artisan (Recommandé)

```bash
cd /home/daniel/work/projects/erh/site

# Importer depuis fichier local
php artisan sanctions:import "/mnt/d/Docs/1.REPERTOIRE SANCTION.csv"

# Ou depuis Windows via /mnt/d/
php artisan sanctions:import "/mnt/d/Docs/1.REPERTOIRE SANCTION.csv"
```

**Réponse attendue :**
```
📂 Fichier détecté: /mnt/d/Docs/1.REPERTOIRE SANCTION.csv
Taille: 602.50 KB
Procéder à l'importation ? (yes/no) [no]:
 > yes

⏳ Importation en cours...
✅ Importation terminée en 45.32s

📊 RÉSUMÉ
  ✅ Importées : 1 473
  ⏭️  Ignorées : 800
  ❌ Erreurs : 50
  ━━━━━━━━━━━━━━━━━
  📈 Total : 2 323
```

---

## 📁 Fichiers créés

### 1. **Classe d'importation**
```
/app/Imports/SanctionImport.php
```

**Responsabilités :**
- Normaliser types sanctions (AVERT, MAP-2 JOURS, etc.)
- Valider clés étrangères (matricule)
- Parser dates (JJ/MM/YYYY → YYYY-MM-DD)
- Calculer fin sanction (début + jours)
- Logger erreurs détaillées

**Exemple normalisation :**
```
"MAP-2 JOURS"  → 3  (code sanction)
"AVERT"        → 1  (avertissement)
"LICENCIEMENT" → 7  (licenciement)
```

### 2. **Commande Artisan**
```
/app/Console/Commands/ImportSanctions.php
```

**Usage :**
```bash
php artisan sanctions:import <chemin-fichier>
```

---

## 📊 Structure importation

```
CSV (2 527 lignes)
    ↓
[Validation clés étrangères]
    ↓
[Normalisation sanctions]
    ↓
[Parsing dates]
    ↓
Sanctions.create() ← Base de données
```

---

## ⚠️ Cas traités

### ✅ Cas valides

```
✓ Matricule existe dans e_travailleur
✓ Date sanction fournie
✓ Type sanction reconnu (normalisé)
✓ Pas de doublon (même matricule + date)
→ Enregistrement créé ✅
```

### ⏭️ Cas ignorés

```
⏭️ Sanction vide (~800 cas)
  Raison : Dossier probablement pas finalisé
  Action : Ignoré silencieusement

⏭️ Doublon détecté
  Raison : Même employé, même date, déjà importé
  Action : Passer au suivant
```

### ❌ Cas erreurs

```
❌ Matricule orphelin
  Exemple : "E99999" n'existe pas dans e_travailleur
  Action : Enregistré dans log, sanction non créée

❌ Date invalide
  Exemple : "32/13/2026" ou vide
  Action : Enregistré dans log, sanction non créée

❌ Exception non gérée
  Action : Enregistré dans log, importation continue
```

---

## 📈 Types sanctions normalisés

| Code | Type Sanction | Variantes CSV reconnues |
|------|---------------|------------------------|
| 1 | **Avertissement** | AVERT, AVERTISSEMENT, AVRT |
| 2 | **MAP 1 jour** | MAP-01JR, MAP-1 JOUR, MAP-1JR, MAP-1 JOURS |
| 3 | **MAP 2 jours** | MAP-2 JOURS, MAP-2JRS, MAP-2 Jours |
| 4 | **MAP 3 jours** | MAP-3 JOURS, MAP-3JRS |
| 5 | **MAP 5 jours** | MAP-5 JOURS, MAP-5JRS |
| 6 | **MAP 7 jours** | MAP-7 JOURS, MAP-7JRS |
| 7 | **Licenciement** | LICENCIEMENT |
| NULL | **En attente** | (colonne vide) |

---

## 🔄 Mappage colonnes CSV → e_sanction

| CSV | e_sanction | Traitement |
|-----|-----------|-----------|
| Od | - | Ignorée (numéro séquentiel) |
| **MATLE** | **employeid** | ✓ Clé étrangère requise |
| Civilité | - | Ignorée (info redondante) |
| Nom, Prénom | - | Ignorées (dans e_travailleur) |
| Intitulé département | - | Ignorée (dans e_travailleur) |
| Fonction | - | Ignorée (dans e_travailleur) |
| **date courrier** | **datesanction** | ✓ Format JJ/MM/YYYY |
| date reponse | - | ⚠️ Non mappée |
| **Motif** | **expose_motif** | ✓ Longtext |
| **Date Sanction** | **datefautes** | ✓ Date du fait |
| **Date Notification** | **debut** | ✓ Début application |
| **Sanction** | **sanction_applique** | ✓ Normalisée (1-7) |
| - | **nombre_jour** | ✓ Extrait de "Sanction" |
| - | **fin** | ✓ Calculée (debut + jours) |
| MISE EN DEMEURE | - | ⚠️ Non mappée |
| TEXTE | - | ⚠️ Non mappée |

---

## 📝 Logs d'exécution

Les logs sont enregistrés dans :
```
/storage/logs/laravel.log
```

**Afficher logs en temps réel :**
```bash
tail -f /home/daniel/work/projects/erh/site/storage/logs/laravel.log
```

**Exemple log :**
```
[2026-01-27 14:32:15] local.INFO: Sanction importée: E01658 - Type: 1
[2026-01-27 14:32:15] local.WARNING: Date invalide: 32/13/2026
[2026-01-27 14:32:16] local.ERROR: Matricule orphelin: E99999

[2026-01-27 14:33:42] local.INFO: === RÉSUMÉ IMPORTATION SANCTIONS ===
[2026-01-27 14:33:42] local.INFO: ✅ Importées: 1473
[2026-01-27 14:33:42] local.INFO: ⏭️ Ignorées: 800
[2026-01-27 14:33:42] local.INFO: ❌ Erreurs: 50
```

---

## 🧪 Tests avant importation réelle

### Test 1 : Vérifier clés étrangères

```bash
# Combien de matricules CSV ne sont pas dans ERH?
cd /home/daniel/work/projects/erh/site

php artisan tinker
>>> $csv = array_map('str_getcsv', file("/mnt/d/Docs/1.REPERTOIRE SANCTION.csv"));
>>> $matricules = array_column($csv, 1);
>>> $missing = array_filter($matricules, fn($m) => !Travailleur::where('matricule', trim($m))->exists());
>>> count($missing); // Affiche nombre matricules orphelins
```

### Test 2 : Vérifier format dates

```bash
php artisan tinker
>>> $csv = array_map('str_getcsv', file("/mnt/d/Docs/1.REPERTOIRE SANCTION.csv"));
>>> $dates = array_column($csv, 9); // Colonne "date courrier"
>>> collect($dates)->filter(fn($d) => !empty($d))->take(5);
```

### Test 3 : Vérifier types sanctions

```bash
php artisan tinker
>>> $csv = array_map('str_getcsv', file("/mnt/d/Docs/1.REPERTOIRE SANCTION.csv"));
>>> $sanctions = array_column($csv, 14); // Colonne "Sanction"
>>> collect($sanctions)->unique()->filter(fn($s) => !empty($s))->values();
```

---

## 🔧 Dépannage

### Problème : "Fichier non trouvé"

```bash
# Vérifier chemin Windows accessible
ls -la "/mnt/d/Docs/"

# Ou copier fichier dans projet
cp "/mnt/d/Docs/1.REPERTOIRE SANCTION.csv" /home/daniel/work/projects/erh/site/storage/

# Puis importer
php artisan sanctions:import "/home/daniel/work/projects/erh/site/storage/1.REPERTOIRE SANCTION.csv"
```

### Problème : "Pas d'utilisateur authentifié"

Le système utilise `auth()->id()` pour `userid` et `demandeurid`.

**Solution :**
```bash
# Importer dans une route authentifiée (optionnel)
# Ou modifier app/Imports/SanctionImport.php ligne 155:
# $sanction->userid = 1; // Admin par défaut
```

### Problème : "Erreurs charset" (caractères accentués)

Le fichier CSV peut avoir encodage ANSI ou UTF-8 incohérent.

**Solution (Windows) :**
```powershell
# Reconvertir CSV en UTF-8
$file = "1.REPERTOIRE SANCTION.csv"
$content = Get-Content -Encoding Default $file
Set-Content -Encoding UTF8 $file -Value $content
```

---

## ✅ Vérification post-importation

**Vérifier les données importées :**

```bash
php artisan tinker

# Compter sanctions importées
>>> Sanctions::count()
1473

# Voir dernière importée
>>> Sanctions::latest()->first()

# Vérifier types sanctions
>>> Sanctions::groupBy('sanction_applique')->selectRaw('sanction_applique, count(*) as count')->get()

# Vérifier année/mois
>>> Sanctions::where('annee', 2026)->where('mois', 1)->count()
```

---

## 📚 Ressources

### Fichiers créés
- `app/Imports/SanctionImport.php` - Classe import
- `app/Console/Commands/ImportSanctions.php` - Commande Artisan
- `ANALYSE_IMPORTATION_CSV_SANCTIONS.md` - Analyse détaillée

### Documentation
- Maatwebsite Excel : https://docs.laravel-excel.com/
- Laravel Artisan Commands : https://laravel.com/docs/11.x/artisan

---

## 🎯 Procédure recommandée

### Phase 1 : Test (30 min)
1. ✅ Copier fichier CSV dans `/storage/`
2. ✅ Exécuter `php artisan sanctions:import` sur 100 lignes (éditer fichier test)
3. ✅ Vérifier base avec `php artisan tinker`
4. ✅ Valider formats dates, clés étrangères

### Phase 2 : Ajustements (30 min)
1. Si erreurs clés étrangères : corriger matricules dans e_travailleur
2. Si problèmes encoding : reconvertir CSV UTF-8
3. Affiner normalisation sanctions si types non reconnus

### Phase 3 : Production (30 min)
1. ✅ Backup base de données
   ```bash
   mysqldump -u daniel -p -P 3306 c1appstat > backup_sanctions_2026-01-27.sql
   ```

2. ✅ Exécuter importation complète
   ```bash
   php artisan sanctions:import "/mnt/d/Docs/1.REPERTOIRE SANCTION.csv"
   ```

3. ✅ Vérifier rapports erreurs
   ```bash
   tail -100 storage/logs/laravel.log
   ```

4. ✅ Notifier RH des anomalies trouvées

---

**Prêt ?** 🚀
Exécutez : `php artisan sanctions:import "/mnt/d/Docs/1.REPERTOIRE SANCTION.csv"`

