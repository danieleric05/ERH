# Guide Complet : Importation des Données (Travailleurs & Sanctions)

**Dernière mise à jour:** 2026-01-27
**Version:** 2.0 (Fusionné)
**Audience:** Administrateurs RH, Techniciens, Équipe IT

---

## 📋 Table des matières

1. [Vue d'ensemble](#vue-densemble)
2. [Importation Travailleurs](#importation-travailleurs)
3. [Importation Sanctions](#importation-sanctions)
4. [Dépannage Commun](#dépannage-commun)
5. [Performance & Optimisation](#performance--optimisation)
6. [FAQ](#faq)

---

## Vue d'ensemble

Ce guide consolide les procédures pour importer deux types de données :

| Type | Commande | Format | Colonnes obligatoires |
|------|----------|--------|----------------------|
| **Travailleurs** | `php artisan travailleurs:import` | CSV (`;`) | MATLE, NOM, DATE_DEBUT_CONTRAT |
| **Sanctions** | `php artisan sanctions:import` | CSV (`;`) | MATLE, Motif, Date Sanction |

---

# Importation Travailleurs

## 🚀 Démarrage Rapide

### Commande

```bash
cd /home/daniel/work/projects/erh/site
php artisan travailleurs:import /chemin/vers/fichier.csv
```

### Format de fichier requis

**Délimiteur:** point-virgule (`;`)
**Encodage:** ISO-8859-1 (Latin-1) ou UTF-8
**Première ligne:** en-têtes
**Dates:** JJ/MM/YYYY (ex: `15/03/2020`)

### Colonnes

#### ✅ Obligatoires
- `MATLE` ou `Matricule` - Identifiant unique
- `NOM` ou `Nom` - Nom du travailleur
- `DATE_DEBUT_CONTRAT` - Date de début d'emploi (JJ/MM/YYYY)

#### 📌 Optionnelles
```
PRENOM, CIVILITE, DATE_NAISSANCE, LIEU_NAISSANCE, TELEPHONE, TELEPHONE2, EMAIL
DEPARTEMENT, UNITE, EQUIPE, CATEGORIE, FONCTION
COMMUNE, NATIONALITE, NIVEAU_ETUDE, TYPE_CONTRAT
SITUATION_MAT, NOMBRE_ENFANT, NUMERO_SECURITE, PIECEIDENTITE
```

### Exemple de résultat

```bash
$ php artisan travailleurs:import ./travailleurs.csv

📂 Fichier détecté: ./travailleurs.csv
   Taille: 45.30 KB
Procéder à l'importation? (yes/no) [no]: yes

⏳ Importation en cours...

✅ Importation terminée en 0.45s

📊 RÉSUMÉ
  ✅ Importés   : 48
  ⏭️  Ignorés   : 2
  ❌ Erreurs   : 1
  ━━━━━━━━━━━━━━━━━━━
  📈 Total     : 51
```

---

## Procédure Détaillée

### Étape 1 : Préparer le fichier CSV

```bash
# Vérifier format
head -3 travailleurs.csv
file travailleurs.csv

# Compter lignes
wc -l travailleurs.csv

# Si encodage incorrect, convertir
iconv -f ISO-8859-1 -t UTF-8 travailleurs.csv > travailleurs_utf8.csv
```

### Étape 2 : Backup (recommandé)

```bash
mysqldump -u daniel -p c1appstat > \
  /home/daniel/backup_avant_import_$(date +%Y%m%d_%H%M%S).sql
```

### Étape 3 : Test avec petit fichier

```bash
# Exporter 20 enregistrements
head -21 travailleurs_complet.csv > travailleurs_test.csv

php artisan travailleurs:import ./travailleurs_test.csv
```

### Étape 4 : Vérifier

```bash
php artisan tinker
>>> App\Travailleur::where('matricule', 'J00001')->first()
>>> App\Travailleur::count()
>>> App\Travailleur::whereNull('departementid')->count()
exit
```

### Étape 5 : Production

```bash
php artisan travailleurs:import /home/daniel/travailleurs_sage_export.csv

# Archiver fichier et logs
cp travailleurs_sage_export.csv \
   /home/daniel/work/projects/erh/archives/travailleurs_$(date +%Y%m%d).csv

tail -200 storage/logs/laravel.log > \
  /home/daniel/work/projects/erh/archives/import_log_$(date +%Y%m%d).txt
```

---

## Architecture Technique

### Classe `TravailleurImport`

**Fichier:** `app/Imports/TravailleurImport.php`

**Caractéristiques:**
- Gère encodage ISO-8859-1 → UTF-8 automatiquement
- Cache des tables de référence (évite N+1 queries)
- Parsing flexible des dates (3 formats acceptés)
- Détection des doublons (même matricule)
- Logs détaillés par ligne

**Flux d'importation:**
```
1. Valider fichier existant
2. Convertir ISO-8859-1 → UTF-8
3. Lire et normaliser en-têtes
4. Charger tables de référence en cache
5. Pour chaque ligne:
   - Valider matricule (obligatoire)
   - Vérifier doublon
   - Valider nom (obligatoire)
   - Parser dates
   - Résoudre clés étrangères via cache
   - Sauvegarder en BDD
6. Afficher résumé
```

### Gestion des Clés Étrangères

| Table | Recherche par | Comportement |
|-------|---------------|-------------|
| `e_departement` | `label` (case-insensitive) | Code trouvé → FK ; Code non trouvé → NULL |
| `e_unite` | `label` | idem |
| `e_equipe` | `label` | idem |
| `e_categorie` | `label` | idem |
| `e_fonction` | `label` | idem |
| `e_commune` | `label` | idem |
| `e_pays` | `label` | idem |
| `e_niveau_etude` | `label` | idem |
| `e_typecontrat` | `label` | idem |

**Note:** FK vides ou invalides deviennent NULL (pas d'erreur).

---

# Importation Sanctions

## 🚀 Démarrage Rapide

### Commande

```bash
cd /home/daniel/work/projects/erh/site
php artisan sanctions:import /chemin/vers/fichier.csv
```

### Format de fichier

**Délimiteur:** point-virgule (`;`)
**Encodage:** ISO-8859-1 ou UTF-8
**Dates:** JJ/MM/YYYY

### Colonnes requises

| CSV | Table ERH | Type | Obligatoire |
|-----|-----------|------|------------|
| **MATLE** | `employeid` | String | ✅ |
| **date courrier** | `datesanction` | DATE | ✅ |
| **Motif** | `expose_motif` | TEXT | ✅ |
| **Date Sanction** | `datefautes` | DATE | ✅ |
| **Sanction** | `sanction_applique` | INT (1-7) | ✅ |
| Date Notification | `debut` | DATE | 📌 |

### Exemple de résultat

```bash
$ php artisan sanctions:import ./sanctions.csv

📂 Fichier détecté: ./sanctions.csv
   Taille: 602.50 KB

⏳ Importation en cours...
✅ Importation terminée en 45.32s

📊 RÉSUMÉ
  ✅ Importées : 337
  ⏭️  Ignorées : 613
  ❌ Erreurs : 1390
  ━━━━━━━━━━━━━━━━━━━
  📈 Total : 2340
```

---

## Types Sanctions Normalisés

| Code | Type | Variantes CSV acceptées |
|------|------|----------------------|
| 1 | Avertissement | AVERT, AVERTISSEMENT, AVRT |
| 2 | MAP 1 jour | MAP-01JR, MAP-1 JOUR, MAP-1JR, MAP-1 JOURS |
| 3 | MAP 2 jours | MAP-2 JOURS, MAP-2JRS, MAP-2 Jours |
| 4 | MAP 3 jours | MAP-3 JOURS, MAP-3JRS |
| 5 | MAP 5 jours | MAP-5 JOURS, MAP-5JRS |
| 6 | MAP 7 jours | MAP-7 JOURS, MAP-7JRS |
| 7 | Licenciement | LICENCIEMENT |
| NULL | En attente | (colonne vide) |

---

## Cas Traités

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

## Procédure Détaillée

### Étape 1 : Préparer le fichier

```bash
# Vérifier chemin Windows accessible (WSL2)
ls -la "/mnt/d/Docs/"

# Ou copier dans le projet
cp "/mnt/d/Docs/1.REPERTOIRE SANCTION.csv" \
   /home/daniel/work/projects/erh/site/storage/

# Nettoyer encodage/retours à la ligne si nécessaire
iconv -f ISO-8859-1 -t UTF-8 fichier.csv > fichier_utf8.csv
```

### Étape 2 : Backup

```bash
mysqldump -u daniel -p -P 3306 c1appstat > \
  backup_sanctions_$(date +%Y-%m-%d).sql
```

### Étape 3 : Test

```bash
# Vérifier clés étrangères
php artisan tinker
>>> $csv = array_map('str_getcsv', file("fichier.csv"));
>>> $matricules = array_column($csv, 1);
>>> $missing = array_filter($matricules,
    fn($m) => !Travailleur::where('matricule', trim($m))->exists());
>>> count($missing); // Nombre d'orphelins
exit
```

### Étape 4 : Importation

```bash
echo "yes" | php artisan sanctions:import "/mnt/d/Docs/sanctions.csv"

# Vérifier résultats
php artisan tinker
>>> Sanctions::count()
>>> Sanctions::where('annee', 2026)->where('mois', 1)->count()
exit
```

### Étape 5 : Vérifier logs

```bash
tail -100 storage/logs/laravel.log
grep "RÉSUMÉ IMPORTATION" storage/logs/laravel.log
grep "❌ Erreur" storage/logs/laravel.log
```

---

# Dépannage Commun

## Erreur : Fichier non trouvé

```bash
# Vérifier le chemin
ls -la /chemin/vers/fichier.csv

# Utiliser chemin absolu
php artisan travailleurs:import /home/daniel/travailleurs.csv

# Si sur Windows (WSL2)
ls -la "/mnt/d/Users/daniel.aboussou/Documents/fichier.csv"
php artisan travailleurs:import "/mnt/d/Users/daniel.aboussou/Documents/fichier.csv"
```

## Erreur : Encodage corrompu

**Symptôme:** Accents mal affichés (é → Ã©)

**Solution 1:** Ré-exporter en UTF-8 depuis Excel

**Solution 2:** Convertir le fichier
```bash
iconv -f ISO-8859-1 -t UTF-8 fichier.csv > fichier_utf8.csv
php artisan travailleurs:import ./fichier_utf8.csv
```

## Erreur : array_combine() argument count mismatch

**Cause:** CSV a des retours à la ligne dans les colonnes texte

**Solution:** Nettoyer avec Python
```python
import csv
with open('fichier.csv', 'r', encoding='utf-8') as f:
    reader = csv.reader(f)
    rows = list(reader)

with open('fichier_clean.csv', 'w', encoding='utf-8', newline='') as f:
    writer = csv.writer(f)
    for row in rows:
        cleaned = [col.replace('\n', ' ').replace('\r', ' ') for col in row]
        writer.writerow(cleaned)
```

## Erreur : Matricule vide / Nom vide

**Cause:** Colonnes obligatoires manquantes

**Solution:** Vérifier en-têtes dans le CSV
```bash
head -1 fichier.csv | tr ',' '\n' | nl
```

**Doit contenir:** `MATLE`, `NOM`, `DATE_DEBUT_CONTRAT` (pour travailleurs)

## Erreur : Date invalide

**Formats acceptés:**
- JJ/MM/YYYY (15/03/2020) ✅
- YYYY-MM-DD (2020-03-15) ✅
- JJ/MM/YY (15/03/20) ✅

**Correction:** Reformater les dates dans Excel puis ré-exporter

## Erreur : Matricule orphelin (Sanctions)

**Cause:** Travailleur n'existe pas en base

**Solution 1:** Importer les travailleurs d'abord
```bash
php artisan travailleurs:import ./travailleurs.csv
php artisan sanctions:import ./sanctions.csv
```

**Solution 2:** Ajouter le travailleur manuellement
```bash
php artisan tinker
>>> $t = new App\Travailleur();
>>> $t->matricule = 'E01658';
>>> $t->nom = 'AGNIMEL';
>>> $t->prenom = 'ESSIS';
>>> $t->date_debut_contrat = '2026-01-01';
>>> $t->type_employer = 2; // EMBAUCHE
>>> $t->save();
exit
```

## Erreur : Doublons

**Symptôme:** Même matricule ignoré lors de ré-importation

**Solution 1:** Supprimer les anciennes données
```bash
php artisan tinker
>>> App\Travailleur::where('matricule', 'J00001')->delete();
exit
php artisan travailleurs:import /tmp/travailleurs.csv
```

**Solution 2:** Modifier le CSV avec matricules différents

## Memory limit dépassé

```bash
# Augmenter la limite
php -d memory_limit=512M artisan travailleurs:import fichier.csv

# Ou découper le fichier
split -l 1000 travailleurs.csv travailleurs_part_
php artisan travailleurs:import travailleurs_part_aa
php artisan travailleurs:import travailleurs_part_ab
```

---

# Performance & Optimisation

## Benchmarks

| Volume | Temps |
|--------|-------|
| 100 travailleurs | ~0.3s |
| 500 travailleurs | ~1.5s |
| 1000 travailleurs | ~3s |
| 5000 travailleurs | ~15s |

## Optimisations implémentées

- ✅ FK cachées en mémoire (pas de requêtes répétées)
- ✅ Headers lus une seule fois
- ✅ Logs agrégés à la fin
- ✅ Transactions optimisées

---

## Logs et Débogage

### Consulter les logs

```bash
# 100 dernières lignes
tail -100 storage/logs/laravel.log

# Filtrer par "RÉSUMÉ"
grep "RÉSUMÉ IMPORTATION" storage/logs/laravel.log

# Filtrer par "ERREUR"
grep "❌ Erreur" storage/logs/laravel.log

# Temps réel
tail -f storage/logs/laravel.log
```

### Mode debug

Modifier `.env`:
```env
APP_DEBUG=true
LOG_LEVEL=debug
```

Puis relancer l'importation.

---

# FAQ

**Q: Puis-je ré-importer le même fichier?**
R: Non, les doublons sont rejetés. Supprimer les anciens enregistrements d'abord.

**Q: Les photos sont importées?**
R: Non, les photos doivent être chargées séparément via l'interface web.

**Q: Quel est le formatage des dates?**
R: JJ/MM/YYYY (France) ou YYYY-MM-DD (ISO). Le système supporte 3 formats.

**Q: Comment annuler une importation?**
R: Utiliser le backup: `mysql < /tmp/backup_avant_import.sql`

**Q: Qui a importé quels travailleurs?**
R: Vérifier le champ `userid` et `created_at` dans la BDD.

**Q: Comment modifier après importation?**
R: Via l'interface web ou directement en BDD.

---

**Statut:** Production-ready
**Mainteneur:** Claude Haiku 4.5
**Fusion complétée:** 2026-01-27
