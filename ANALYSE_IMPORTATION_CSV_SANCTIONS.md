# 📊 ANALYSE : Importation CSV Sanctions vers ERH

**Fichier source :** `/mnt/d/Docs/1.REPERTOIRE SANCTION.csv`
**Date analyse :** 27 janvier 2026
**Total sanctions :** 2 527 enregistrements

---

## 📋 Structure CSV (16 colonnes)

```
1.  Od                          (Numéro séquentiel)
2.  MATLE                        (Matricule travailleur - Clé)
3.  Civilité                     (Monsieur/Mademoiselle)
4.  Nom                          (Nom complet)
5.  Prénom                       (Prénom)
6.  Autres prénoms               (Prénoms supplémentaires)
7.  Intitulé département         (Département)
8.  Fonction                     (Poste occupé)
9.  date courrier                (Date lettre de sanction)
10. date reponse                 (Date réponse employé)
11. Motif                        (Description détaillée - LONGTEXT)
12. Date Sanction                (Date officielle sanction)
13. Date Notification            (Date notification)
14. Sanction                     (Type sanction - À normaliser)
15. MISE EN DEMEURE              (Colonne optional)
16. TEXTE                        (Notes complémentaires)
```

---

## 🔍 Types de Sanctions Identifiées

### Problème : **Incohérence nommage**

| Type | Fréquence | Variantes identifiées |
|------|-----------|----------------------|
| **Avertissement** | 155 | AVRT, AVERT, AVERTISSEMENT |
| **MAP 1 jour** | 74 | MAP-01JR, MAP-1 jour, MAP-1 JOUR, MAP-1JR, MAP-1 JOURS |
| **MAP 2 jours** | 289 | MAP-2 JOURS, MAP-2 Jours, MAP-2JRS, map-2 jours, MAP-02JRS |
| **MAP 3 jours** | 80 | MAP-3 jours, MAP-3 JOURS, MAP-3JRS |
| **MAP 4 jours** | 16 | MAP-4 JOURS |
| **MAP 5 jours** | 47 | MAP-5 JOURS, MAP-5JRS |
| **MAP 7 jours** | ~10 | MAP-7 JOURS, etc. |
| **Licenciement** | 2 | Licenciement |
| **Vide** | ~800 | (Sanction pas décidée) |

**Total :** 1 473 sanctions traitées / 2 527 enregistrements

---

## 📊 Comparaison CSV ↔ Table `e_sanction`

### ✅ Colonnes CSV → ERH correspondantes

| CSV | ERH | Type | Notes |
|-----|-----|------|-------|
| MATLE | `employeid` | String | ⚠️ Doit matcher `e_travailleur.matricule` |
| date courrier | `datesanction` | DATE | Format JJ/MM/YYYY → YYYY-MM-DD |
| Motif | `expose_motif` | MEDIUMTEXT | Description détaillée |
| Date Sanction | `datesanction` | DATE | Même que "date courrier"? À clarifier |
| Date Notification | `debut` | DATE | Début application sanction? |
| Sanction | `sanction_applique` | INT | Type sanction (1-8) |
| | `nombre_jour` | INT | Nombre jours (MAP) |
| | `datefautes` | DATE | Date du fait fautif |
| | `fin` | DATE | Date fin application |

### ❌ Colonnes CSV non trouvées en ERH

```
- Civilité
- Nom, Prénom, Autres prénoms
- Intitulé département
- Fonction
- date reponse
- Date Notification (colonne 13)
- MISE EN DEMEURE
- TEXTE
```

---

## 🚨 Problèmes identifiés

### 1. **Clé étrangère Matricule**

❌ **Problème :**
```csv
MATLE: "E01658", "EO1482", "OUEHY", "J000726"
```

Ces matricules doivent exister dans `e_travailleur.matricule` !

**Solution :**
```php
// Avant importation : Vérifier correspondance
$matricule = "E01658";
$worker = Travailleur::where('matricule', $matricule)->first();
if (!$worker) {
    throw new Exception("Matricule $matricule non trouvé dans ERH");
}
```

### 2. **Format Date incohérent**

CSV utilise : `JJ/MM/YYYY` (ex: `20/01/2026`)
MySQL attend : `YYYY-MM-DD`

```php
$date = Carbon::createFromFormat('d/m/Y', '20/01/2026')->format('Y-m-d');
// Résultat : 2026-01-20
```

### 3. **Types Sanctions mal formalisés**

```
CSV: "MAP-2 JOURS", "MAP-2JRS", "map-2 jours"  →  Tous = 2 jours ?

Table e_sanction: sanction_applique (INT 1-8)
- 1 = Avertissement
- 2 = MAP 1 jour
- 3 = MAP 2 jours
- 4 = MAP 3 jours
- 5 = MAP 5 jours
- 6 = MAP 7 jours
- 7 = Licenciement
- 8 = Autre
```

### 4. **Nombreuses colonnes vides**

```
~800 sanctions sans "Sanction" appliquée → Sanction en attente?
Date Sanction vide → Dossier pas finalisé
```

### 5. **Colonnes optionnelles ignorées en ERH**

```
date reponse
MISE EN DEMEURE
TEXTE
```

### 6. **Table motif manquante?**

```
e_sanction.motif = INT (clé étrangère?)

Mais aucune table e_motif détectée.
Possible valeurs : texte libre dans expose_motif
```

---

## 📝 Données à clarifier avec RH

**À demander avant importation :**

- [ ] Les 2 527 sanctions CSV sont-elles **toutes à importer** ?
- [ ] Qu'en est-il des sanctions **sans type** (~800) ?
- [ ] **Matricules orphelins** (pas dans e_travailleur) : ignorer ou créer travailleur ?
- [ ] Quelle **date utiliser** ?
  - "date courrier" = `datesanction` ?
  - "Date Notification" = `debut` ?
  - Existe-t-il une date du fait fautif (`datefautes`) ?

- [ ] Les colonnes non mappées (date reponse, MISE EN DEMEURE, TEXTE) : **stocker où ?**
- [ ] Table `e_motif` existe-t-elle ? Sinon, comment normaliser `motif` (INT) ?

---

## 🔧 Plan d'importation (4 étapes)

### **Étape 1 : Normalisation CSV**

```php
// Normaliser types sanctions
$sanctionMap = [
    'AVERT' => 1, 'AVERTISSEMENT' => 1,
    'MAP-1 JOUR' => 2, 'MAP-1JR' => 2, 'MAP-1 JOURS' => 2,
    'MAP-2 JOURS' => 3, 'MAP-2JRS' => 3, 'MAP-2 Jours' => 3,
    'MAP-3 JOURS' => 4, 'MAP-3JRS' => 4,
    'MAP-5 JOURS' => 5, 'MAP-5JRS' => 5,
    'LICENCIEMENT' => 7,
];

// Extraire nombre jours
$jours = preg_match('/(\d+)/', 'MAP-2 JOURS', $m) ? $m[1] : 0;
```

### **Étape 2 : Validation avant import**

```php
// Vérifier clés étrangères
foreach ($rows as $row) {
    $worker = Travailleur::where('matricule', $row['MATLE'])->first();
    if (!$worker) {
        Log::warning("Matricule orphelin: {$row['MATLE']} - {$row['Nom']}");
        continue; // Ignorer
    }

    if (empty($row['Date Sanction'])) {
        Log::warning("Date sanction vide: {$row['MATLE']}");
        continue;
    }
}
```

### **Étape 3 : Création enregistrements**

```php
$row = [
    'employeid' => $worker->matricule,
    'expose_motif' => $row['Motif'],
    'datesanction' => Carbon::createFromFormat('d/m/Y', $row['date courrier']),
    'datefautes' => Carbon::createFromFormat('d/m/Y', $row['Date Sanction']),
    'sanction_applique' => $sanctionMap[$row['Sanction']] ?? null,
    'nombre_jour' => $jours,
    'debut' => Carbon::createFromFormat('d/m/Y', $row['Date Notification']),
    'fin' => $fin, // debut + nombre_jour
    'statutid' => 1, // Actif
    'userid' => auth()->id(),
    'annee' => date('Y'),
    'mois' => date('n'),
];

Sanction::create($row);
```

### **Étape 4 : Rapports erreurs**

```
✅ Importées : 1 473
⚠️ Ignorées (sans type sanction) : 800
❌ Erreurs (matricule orphelin) : 50
```

---

## 📚 Fichiers à créer

### 1. **Classe Import**
```bash
php artisan make:class Imports/SanctionImport
```

### 2. **Commande Artisan**
```bash
php artisan make:command ImportSanctions
```

### 3. **Formulaire Web** (optionnel)
Route POST `/import-sanctions` avec upload CSV

---

## ✨ Modèle Sanction (Migration)

À vérifier si table `e_sanction` accepte :

```php
Schema::table('e_sanction', function (Blueprint $table) {
    // Vérifier l'existence de ces colonnes :
    // - motif (INT) -> Clé étrangère e_motif?
    // - expose_motif (MEDIUMTEXT) ✓
    // - datefautes (DATE)
    // - sanction_applique (INT)
    // - nombre_jour (INT)
    // - debut, fin (DATE)
    // - quart (INT) - Champ Q1, Q2, Q3?
});
```

---

## 🎯 Recommandation finale

**Approche par phases :**

### Phase 1 : Préparation (1h)
1. ✅ Normaliser types sanctions
2. ✅ Créer classe d'import
3. ✅ Valider clés étrangères

### Phase 2 : Test (30min)
1. Importer 50 enregistrements
2. Vérifier dans ERH
3. Ajuster mapping si nécessaire

### Phase 3 : Production (30min)
1. Importer tous les 2 527 enregistrements
2. Générer rapports erreurs
3. Notifier RH des anomalies

---

**Prochaines étapes :**

1. Répondre aux questions de clarification RH
2. Créer la classe `SanctionImport`
3. Tester sur échantillon

**Voulez-vous procéder ?** 🚀

