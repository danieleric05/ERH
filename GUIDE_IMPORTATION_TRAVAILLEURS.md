# Guide Importation Travailleurs CSV Sage

**Dernière mise à jour:** 2026-01-27
**Version:** 1.0
**Audience:** Administrateurs RH, Techniciens, Équipe IT

## Commande d'Importation

```bash
cd /home/daniel/work/projects/erh/site
php artisan travailleurs:import /chemin/vers/fichier.csv
```

## Procédure de Base

### 1. Préparer le fichier CSV

**Format requis:**
- Délimiteur: point-virgule (`;`)
- Encodage: ISO-8859-1 (Latin-1) ou UTF-8
- Première ligne: en-têtes
- Dates: format JJ/MM/YYYY (ex: 15/03/2020)

**Colonnes obligatoires:**
- `MATLE` ou `Matricule` - Identifiant unique
- `NOM` ou `Nom` - Nom du travailleur
- `DATE_DEBUT_CONTRAT` - Date de début d'emploi

**Colonnes optionnelles:**
- PRENOM, CIVILITE, DATE_NAISSANCE, TELEPHONE, EMAIL
- DEPARTEMENT, UNITE, EQUIPE, CATEGORIE, FONCTION
- COMMUNE, NATIONALITE, NIVEAU_ETUDE, TYPE_CONTRAT

### 2. Exécuter l'importation

```bash
cd /home/daniel/work/projects/erh/site

php artisan travailleurs:import /tmp/travailleurs.csv
```

Output:
```
📂 Fichier détecté: /tmp/travailleurs.csv
   Taille: 45.30 KB
Procéder à l'importation des travailleurs ? (yes/no) [no]: yes

⏳ Importation en cours...

✅ Importation terminée en 0.45s

📊 RÉSUMÉ
  ✅ Importés   : 48
  ⏭️  Ignorés   : 2
  ❌ Erreurs   : 1
  ━━━━━━━━━━━━━━━━━━━
  📈 Total     : 51
```

### 3. Vérifier les résultats

```bash
# Via interface web: Aller sur "Liste Embauchés"

# Via commande:
cd /home/daniel/work/projects/erh/site
php artisan tinker

>>> App\Travailleur::latest()->take(5)->get()
>>> App\Travailleur::where('matricule', 'J00001')->first()
exit
```

## Procédure Test (avant production)

### Étape 1: Créer un backup

```bash
mysqldump -u daniel -p c1appstat > /tmp/backup_avant_import.sql
```

### Étape 2: Tester avec fichier réduit

```bash
# Exporter 20 premier enregistrements du CSV
head -21 travailleurs_complet.csv > travailleurs_test.csv

cd /home/daniel/work/projects/erh/site
php artisan travailleurs:import ./travailleurs_test.csv
```

### Étape 3: Vérifier

```bash
php artisan tinker
>>> App\Travailleur::where('matricule', 'J00001')->first()
exit
```

## Procédure Production

### Backup

```bash
mysqldump -u daniel -p c1appstat > \
  /home/daniel/backup_avant_import_$(date +%Y%m%d_%H%M%S).sql
```

### Importation

```bash
cd /home/daniel/work/projects/erh/site

php artisan travailleurs:import \
  /home/daniel/work/projects/erh/imports/travailleurs_sage_export.csv
```

### Archivage

```bash
# Copier le fichier importé dans les archives
cp /tmp/travailleurs.csv \
   /home/daniel/work/projects/erh/archives/travailleurs_$(date +%Y%m%d).csv

# Garder le log
tail -200 storage/logs/laravel.log > \
  /home/daniel/work/projects/erh/archives/import_log_$(date +%Y%m%d).txt
```

## Dépannage

### Erreur: Fichier non trouvé

```bash
# Vérifier le chemin
ls -la /chemin/vers/fichier.csv

# Utiliser chemin absolu
php artisan travailleurs:import /home/daniel/travailleurs.csv
```

### Erreur: Matricule vide

**Cause:** Colonne MATLE manquante ou mal orthographiée

**Solution:** Vérifier dans le CSV que la colonne existe et n'est pas vide

### Erreur: Date invalide

**Formats acceptés:**
- JJ/MM/YYYY (15/03/2020)
- YYYY-MM-DD (2020-03-15)
- JJ/MM/YY (15/03/20)

**Correction:** Reformater les dates dans Excel puis ré-exporter

### Erreur: Encodage corrompu

**Symptôme:** Accents mal affichés (é → Ã©)

**Solution 1:** Ré-exporter en UTF-8 depuis Excel

**Solution 2:** Convertir le fichier
```bash
iconv -f ISO-8859-1 -t UTF-8 travailleurs.csv > travailleurs_utf8.csv
php artisan travailleurs:import ./storage/imports/travailleurs_utf8.csv
```

### Erreur: FK invalide (département, etc.)

**Message:**
```
Département invalide: DIRECTION_RH
```

**Solutions:**

Option 1: Ajouter le département en BDD
```bash
php artisan tinker
>>> $dept = new App\Departement();
>>> $dept->label = 'DIRECTION_RH';
>>> $dept->uniteid = 1;
>>> $dept->save();
>>> exit
```

Option 2: Laisser vide dans le CSV (la colonne sera NULL)

Option 3: Corriger le code dans le CSV

### Erreur: Doublons

**Message:**
```
⏭️  Ignorés   : 5
```

**Cause:** Matricules déjà importés

**Solution:**

Option 1: Supprimer les anciennes données
```bash
php artisan tinker
>>> App\Travailleur::where('matricule', 'J00001')->delete();
>>> exit
# Puis ré-importer
php artisan travailleurs:import /tmp/travailleurs.csv
```

Option 2: Modifier le CSV avec matricules différents

### Memory limit dépassé

```bash
# Augmenter la limite
php -d memory_limit=512M artisan travailleurs:import /tmp/travailleurs.csv

# Ou découper le fichier en parties
split -l 1000 travailleurs.csv travailleurs_part_
php artisan travailleurs:import travailleurs_part_aa
php artisan travailleurs:import travailleurs_part_ab
```

## Validation des Données

### Avant importation

```bash
# Vérifier le CSV
head -5 /tmp/travailleurs.csv

# Compter les lignes
wc -l /tmp/travailleurs.csv

# Vérifier l'encodage (doit montrer des caractères lisibles)
file /tmp/travailleurs.csv
```

### Après importation

```bash
# Tous les travailleurs
php artisan tinker
>>> App\Travailleur::count()

# Avec FK NULL (à corriger potentiellement)
>>> App\Travailleur::whereNull('departementid')->count()
>>> App\Travailleur::whereNull('uniteid')->count()

# Vérifier un travailleur
>>> $t = App\Travailleur::where('matricule', 'J00001')->first();
>>> $t->toArray();
```

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

## Performance

### Benchmarks

- 100 travailleurs: ~0.3 secondes
- 500 travailleurs: ~1.5 secondes
- 1000 travailleurs: ~3 secondes
- 5000 travailleurs: ~15 secondes

### Optimisations

- Les FK sont cachées en mémoire (pas de requêtes répétées)
- Les headers sont lus une seule fois
- Les logs sont agrégés à la fin

## FAQ

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

**Voir aussi:** `ANALYSE_IMPORTATION_CSV_TRAVAILLEURS.md` pour les détails techniques
