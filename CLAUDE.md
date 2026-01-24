# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

**Dernière mise à jour :** 2026-01-24

## Aperçu du Projet

ERH (Employment Resources Human) est un système de gestion des ressources humaines développé avec Laravel 10 et PHP 8.0+. L'application gère les dossiers des employés, les consultations de santé, les sanctions, les missions et d'autres fonctions RH.

### Fonctionnalités principales
- 👥 Gestion des travailleurs (journaliers et embauchés)
- 📸 Système de photos pour travailleurs
- 🏥 Suivi des consultations médicales et accidents de travail
- ⚖️ Gestion des sanctions
- 📋 Gestion des variables de paie
- 📊 Déclarations CNPS
- 📄 Génération de contrats et certificats (PDF)
- 📊 Export Excel des données

## Commandes de Développement

Toutes les commandes doivent être exécutées depuis le répertoire `/site` :

```bash
cd site

# Installation des dépendances
composer install
npm install

# Génération de la clé d'application
php artisan key:generate

# Base de données
php artisan migrate

# Compilation des assets
npm run dev          # Mode développement
npm run watch        # Surveillance avec recompilation automatique
npm run prod         # Mode production

# Tests
./vendor/bin/phpunit                    # Tous les tests
./vendor/bin/phpunit tests/Unit         # Tests unitaires uniquement
./vendor/bin/phpunit tests/Feature      # Tests fonctionnels uniquement
./vendor/bin/phpunit --filter=NomTest   # Test spécifique

# Serveur de développement
php artisan serve    # http://localhost:8000
```

## Architecture du Code

### Structure Principale (`site/`)

- **app/** - Logique métier principale
  - **Http/Controllers/** - Contrôleurs (EmployerController, RecruController, ConfigController, SanteController, SanctionController)
  - **Modèles Eloquent** - 28 modèles à la racine de `app/` (Travailleur, Santes, Sanctions, Variables, etc.)
  - **Http/Middleware/** - Authentification, CSRF, sessions
  - **Providers/** - Service providers Laravel

- **routes/web.php** - 156+ routes web définissant tous les endpoints de l'application

- **resources/views/** - Templates Blade organisés par fonctionnalité (travailleur/, sante/, sanctions/, autorisations/, config/, tenues/, variables/, precarite/)

- **config/** - Configuration Laravel (database, auth, mail, dompdf, etc.)

### Point d'Entrée

Le fichier racine `index.php` redirige vers `site/public/index.php`. La configuration Apache/IIS utilise les fichiers `.htaccess` et `web.config`.

### Assets et Ressources Publiques (`rhassets/`)

⚠️ **Important :** Les assets publics sont situés à la racine du projet dans `/rhassets/`, **pas** dans `site/public/`.

```
/rhassets/
├── css/           # Feuilles de style
├── js/            # Scripts JavaScript
├── images/        # Images et ressources visuelles
│   ├── travailleurs/  # Photos des travailleurs (NEW)
│   │   └── default.png
│   ├── images.png     # Image par défaut (legacy)
│   └── logo*.png      # Logos de l'application
├── fonts/         # Polices
└── vendor/        # Bibliothèques tierces
```

**Accès dans les vues Blade :**
```blade
{{ asset('rhassets/images/logo.png') }}
{{ asset('rhassets/css/style.css') }}
```

**Accès PHP (upload) :**
```php
public_path('../rhassets/images/travailleurs')
```

## Conventions du Code

### Base de Données
- Les tables utilisent le préfixe `e_` (ex: `e_travailleur`, `e_consultation`)
- MySQL sur le port 8054 (configurable dans `.env`)
- Charset UTF-8 mb4

### Modèles Eloquent
- Noms de tables personnalisés définis dans chaque modèle
- Convention de nommage française

### Authentification
- Authentification par session via le champ `pseudo` (nom d'utilisateur)
- `statut_id = 1` indique un utilisateur actif
- Middleware `auth` pour protéger les routes

### Export de Données
- PDF via DomPDF (barryvdh/laravel-dompdf)
- Excel via Maatwebsite/Excel

### Gestion des Photos (Nouveau - 2026-01-21)

#### Base de données
- **Table :** `e_travailleur`
- **Champ :** `photo` (VARCHAR 255, nullable)
- **Script de migration :** `site/database/migrations/add_photo_to_travailleur.sql`

#### Stockage
- **Dossier :** `/rhassets/images/travailleurs/`
- **Nomenclature :** `timestamp_MATRICULE.extension` (ex: `1737478800_J00012.jpg`)
- **Photo par défaut :** `default.png`
- **Formats acceptés :** JPG, PNG, JPEG
- **Taille recommandée :** Max 2 Mo

#### Implémentation
**Upload (EmployerController) :**
```php
if ($request->hasFile('photo')) {
    $photo = $request->file('photo');
    $photoName = time() . '_' . $travail->matricule . '.' . $photo->getClientOriginalExtension();
    $photo->move(public_path('../rhassets/images/travailleurs'), $photoName);
    $travail->photo = $photoName;
}
```

**Affichage (Blade) :**
```blade
<img src="{{ $travailleur->photo
    ? asset('rhassets/images/travailleurs/' . $travailleur->photo)
    : asset('rhassets/images/travailleurs/default.png') }}"
     alt="Photo {{ $travailleur->nom }}">
```

#### Formulaires concernés
- `travailleur/edit.blade.php` - Modification avec aperçu
- `travailleur/addautres.blade.php` - Ajout avec aperçu optionnel

#### Méthodes contrôleur
- `EmployerController@post_travailleur_autres` - Upload nouveau travailleur
- `EmployerController@post_edit_travailleur` - Upload + suppression ancienne photo

## Stack Technique

- **Backend**: Laravel 10, PHP 8.0+
- **Frontend**: Vue.js 2.5, Bootstrap 4, jQuery 3.2, Tailwind CSS (pages modernisées)
- **Build**: Laravel Mix (Webpack)
- **Tests**: PHPUnit 9.6

## Bugs Connus et Corrections (2026-01-21)

### ✅ Corrigés

#### 1. Logout non fonctionnel
- **Problème :** Classes CSS sur mauvais élément, route incorrecte
- **Fichiers corrigés :**
  - `layouts/_header.blade.php` (ligne 53-57)
  - `HomeController.php` (ligne 92)
- **Détails :** Voir `CORRECTIONS_BUGS.md`

#### 2. Erreur étape-deux (clic sur travailleur)
- **Problème :** Vue inexistante, données insuffisantes, route dupliquée
- **Fichiers corrigés :**
  - `EmployerController.php@etapedeuxtravailleur` (ligne 893-918)
  - `routes/web.php` (suppression ligne 660)
- **Détails :** Voir `CORRECTIONS_BUGS.md`

#### 3. Route stock_tenues non définie
- **Problème :** Lien vers route archivée
- **Fichier corrigé :** `home/contenu.blade.php` (ligne 382 commentée)

### ⚠️ Fonctionnalités archivées

Certaines fonctionnalités sont archivées mais non supprimées :
- **Tenues :** Routes commentées (lignes 513-535 de web.php)
- **Précarité (HA01) :** Routes commentées (lignes 349-373 de web.php)

Pour réactiver : décommenter les routes et vérifier les contrôleurs associés.

## Bonnes Pratiques du Projet

### 1. Formulaires avec upload
Toujours ajouter `enctype="multipart/form-data"` :
```blade
<form method="POST" action="{{ url('...') }}" enctype="multipart/form-data">
```

### 2. Recherche dans les listes
**Convention :** Formulaires de recherche alignés à **droite** (convention RH)
```blade
<div class="mb-6 flex justify-end">
    <form id="searchForm" class="flex items-center max-w-lg">
        <!-- Champs de recherche -->
    </form>
</div>
```

### 3. Chargement de données pour formulaires
Pour les vues d'édition/ajout, toujours charger **toutes** les collections nécessaires :
```php
public function editForm($id) {
    $edit = Model::findOrFail($id); // Utiliser findOrFail
    $departements = Departement::orderBy('id', 'DESC')->get();
    $unites = Unites::orderBy('id', 'DESC')->get();
    // ... autres collections

    return view('view', compact('edit', 'departements', 'unites', ...));
}
```

### 4. Suppression de fichiers (photos, documents)
Toujours vérifier avant de supprimer :
```php
if ($model->photo && $model->photo !== 'default.png') {
    $oldPath = public_path('../rhassets/images/travailleurs/' . $model->photo);
    if (file_exists($oldPath)) {
        unlink($oldPath);
    }
}
```

### 5. Routes nommées
Toujours utiliser les noms de routes dans les liens :
```blade
<!-- BON -->
<a href="{{ route('listetravailleurs') }}">Liste</a>

<!-- ÉVITER -->
<a href="/liste-travailleurs">Liste</a>
```

### 6. Validation des uploads
Recommandé (à ajouter si nécessaire) :
```php
$request->validate([
    'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
]);
```

## Routes Importantes

### Travailleurs
| Route | Nom | Contrôleur | Description |
|-------|-----|------------|-------------|
| `/liste-embauches` | - | Closure | Liste des embauchés |
| `/etape-deux-travailleur/{id}` | `etapedeuxtravailleur` | `EmployerController@etapedeuxtravailleur` | Modification travailleur |
| `/post_edit_travailleur/{id}` | - | `EmployerController@post_edit_travailleur` | Sauvegarde modifications |
| `/liste-travailleurs-etapes-deux` | `listetravailleurs` | `RecruController@listetravailleurs` | Liste étape 2 |

### Authentification
| Route | Nom | Contrôleur | Description |
|-------|-----|------------|-------------|
| `/se-connecter` | `login` | `HomeController@logining` | Page de connexion |
| `/logout` (POST) | `logout` | `HomeController@logoutUser` | Déconnexion |

### Santé
| Route | Nom | Contrôleur | Description |
|-------|-----|------------|-------------|
| `/liste-accident-travail` | `listesaccident` | `SanteController@listesaccident` | Accidents de travail |
| `/liste-consultations` | `listesconsultation` | `SanteController@listesconsultation` | Consultations |

### Recherche & Historique
| Route | Nom | Contrôleur | Description |
|-------|-----|------------|-------------|
| `/historiques-travailleurs` | `historiques` | `RecruController@historique` | Page de recherche avancée |
| `/post_search` (POST) | - | `RecruController@post_search` | Traitement recherche |
| `/telecharger-exel-travailleurs/{code}` | `excel_download` | `RecruController@excel_download` | Export SAGE |
| `/telecharger-quinzaine/{code}` | `excel_download_quinzaine` | `RecruController@excel_download_quinzaine` | Export Paie |

## Documentation du Projet

### Fichiers de référence
- **`CLAUDE.md`** (ce fichier) - Guide principal
- **`CHANGELOG.md`** - Historique détaillé des modifications
- **`TODO.md`** - Liste des tâches à faire et en cours
- **`RESUME_IMPLEMENTATION_PHOTOS.md`** - Détails techniques système photos (si existant)
- **`CORRECTIONS_BUGS.md`** - Documentation des bugs corrigés (si existant)
- **`HISTORIQUE_CONVERSATIONS.md`** - Journal des modifications (si existant)

### Scripts SQL
- **`site/database/migrations/add_photo_to_travailleur.sql`** - Ajout champ photo

  ⚠️ **À exécuter :**
  ```bash
  mysql -u [user] -p [database] < site/database/migrations/add_photo_to_travailleur.sql
  ```

## Modifications Récentes

### Session du 2026-01-24 : Améliorations UX et modernisation

#### Bugs corrigés
- ✅ Logout fonctionnel (2 fichiers: `_header.blade.php`, `HomeController.php`)
- ✅ Modification travailleur fonctionnelle (2 fichiers: `EmployerController.php`, `web.php`)
- ✅ Route `stock_tenues` commentée (1 fichier: `contenu.blade.php`)

#### Fonctionnalités ajoutées
- ✅ Système de photos complet pour travailleurs
  - Migration SQL pour champ `photo`
  - Upload avec aperçu en temps réel
  - Affichage dans toutes les listes (7 fichiers)
  - Gestion suppression anciennes photos

#### Améliorations UI
- ✅ Page Recherche & Historique complètement modernisée
  - Migration Tailwind CSS + Alpine.js
  - Formulaire dynamique avec champs conditionnels
  - Colonne "Photo" ajoutée
  - Formatage dates avec Carbon
  - Messages d'état élégants
- ✅ Page Welcome améliorée
  - Remplacement images.png par icônes Font Awesome
  - Section Précarité masquée (obsolète)
  - Section Autorisations désactivée (en cours)
- ✅ Formulaires de recherche alignés à droite (5 fichiers)

#### Documentation
- ✅ `CHANGELOG.md` créé avec historique complet
- ✅ `TODO.md` créé avec tâches prioritaires
- ✅ `CLAUDE.md` mis à jour (ce fichier)

**Commit :** `c76c5df - fix: corrections critiques et amélioration UX`
**Total :** 14 fichiers modifiés, +499 insertions, -334 suppressions

### Session du 2026-01-21 : Refonte frontend

- ✅ Migration complète vers Tailwind CSS et Alpine.js
- ✅ Mise à jour Laravel 10 → Laravel 11

## Dépannage (Troubleshooting)

### Problème : Photos ne s'affichent pas
**Causes possibles :**
1. Script SQL non exécuté → Le champ `photo` n'existe pas dans `e_travailleur`
2. Permissions incorrectes sur `/rhassets/images/travailleurs/`
3. Chemin incorrect dans le code

**Solutions :**
```bash
# 1. Vérifier si le champ existe
mysql -u [user] -p [db] -e "DESCRIBE e_travailleur;" | grep photo

# 2. Vérifier/corriger les permissions
chmod 755 /rhassets/images/travailleurs/

# 3. Vérifier le chemin dans asset()
# Doit être : asset('rhassets/images/travailleurs/...')
```

### Problème : Upload échoue silencieusement
**Causes possibles :**
1. Taille fichier dépasse limite PHP
2. Format non accepté
3. `enctype="multipart/form-data"` manquant

**Solutions :**
```bash
# 1. Vérifier limites PHP
php -i | grep -E "upload_max_filesize|post_max_size"

# 2. Augmenter limites dans php.ini si nécessaire
upload_max_filesize = 10M
post_max_size = 10M

# 3. Redémarrer serveur web après modification
```

### Problème : Erreur 404 sur modification travailleur
**Cause :** Route `etapedeuxtravailleur` non définie ou dupliquée

**Solution :**
Vérifier que la route existe **une seule fois** dans `web.php` (ligne 275) :
```php
Route::get("/etape-deux-travailleur/{id}", [EmployerController::class, 'etapedeuxtravailleur'])->name("etapedeuxtravailleur");
```

### Problème : Logout redirige vers page blanche
**Cause :** Route `se-connecter` utilisée au lieu de `login`

**Solution :** Déjà corrigé dans `HomeController.php` ligne 92

### Problème : Variables non définies dans vue edit
**Cause :** Collections non chargées dans la méthode contrôleur

**Solution :** Vérifier que `etapedeuxtravailleur()` charge les 10 variables :
```php
$edit, $departements, $unites, $equipes, $pays, $fonctions,
$commune, $categories, $niveauEtudes, $data_typecontrat
```

## Notes de Développement

### Ajout d'une nouvelle liste de travailleurs

1. **Créer la vue** dans `resources/views/travailleur/`
2. **Ajouter la route** dans `web.php`
3. **Respecter les conventions :**
   - Formulaire de recherche à droite : `<div class="mb-6 flex justify-end">`
   - Affichage photo : utiliser l'opérateur ternaire pour photo par défaut
   - Classes Tailwind pour cohérence visuelle

4. **Template de colonne photo :**
```blade
<td class="px-6 py-4">
    <img src="{{ $travailleur->photo 
        ? asset('rhassets/images/travailleurs/' . $travailleur->photo) 
        : asset('rhassets/images/travailleurs/default.png') }}"
         height="50" width="50"
         class="rounded-full object-cover w-12 h-12"
         alt="Photo {{ $travailleur->nom }}">
</td>
```

### Ajout d'un nouveau champ dans e_travailleur

1. **Créer le script SQL** dans `site/database/migrations/`
2. **Ajouter le champ** dans les formulaires concernés
3. **Modifier les méthodes** de sauvegarde dans les contrôleurs
4. **Mettre à jour** les vues d'affichage
5. **Documenter** dans CLAUDE.md

### Tests recommandés après modifications

#### Tests fonctionnels
- [ ] Connexion/Déconnexion
- [ ] Ajout travailleur avec photo
- [ ] Modification travailleur avec changement photo
- [ ] Affichage photos dans toutes les listes
- [ ] Recherche dans les listes
- [ ] Export PDF/Excel

#### Tests techniques
- [ ] Permissions fichiers (755 pour dossiers, 644 pour fichiers)
- [ ] Taille photos (< 2 Mo)
- [ ] Formats acceptés (JPG, PNG)
- [ ] Suppression anciennes photos lors modification
- [ ] Photo par défaut si aucune photo

## Commandes Utiles

### Développement
```bash
# Lancer le serveur de développement
cd site && php artisan serve

# Compiler les assets en mode watch
cd site && npm run watch

# Vider le cache Laravel
cd site && php artisan cache:clear
cd site && php artisan view:clear
cd site && php artisan config:clear

# Lister toutes les routes
cd site && php artisan route:list
cd site && php artisan route:list --name=travailleur  # Filtrer par nom
```

### Base de données
```bash
# Connexion MySQL
mysql -u [user] -p -P 8054 [database]

# Backup base de données
mysqldump -u [user] -p -P 8054 [database] > backup_$(date +%Y%m%d).sql

# Exécuter un script SQL
mysql -u [user] -p -P 8054 [database] < script.sql

# Vérifier structure table travailleur
mysql -u [user] -p -P 8054 [database] -e "DESCRIBE e_travailleur;"
```

### Gestion des fichiers
```bash
# Vérifier permissions dossier photos
ls -la /rhassets/images/travailleurs/

# Compter les photos uploadées
ls -1 /rhassets/images/travailleurs/*.{jpg,png,jpeg} 2>/dev/null | wc -l

# Trouver les photos orphelines (sans travailleur associé)
# Nécessite une requête SQL custom

# Nettoyer les anciennes photos (backup recommandé avant)
find /rhassets/images/travailleurs/ -name "*.jpg" -mtime +365 -exec ls -lh {} \;
```

## Contacts et Support

### Documentation externe
- **Laravel 10 :** https://laravel.com/docs/10.x
- **Blade Templates :** https://laravel.com/docs/10.x/blade
- **Eloquent ORM :** https://laravel.com/docs/10.x/eloquent

### Logs et débogage
```bash
# Fichiers de logs Laravel
tail -f site/storage/logs/laravel.log

# Logs Apache/Nginx (varie selon config)
tail -f /var/log/apache2/error.log
tail -f /var/log/nginx/error.log
```

### Mode debug
Activer dans `.env` pour développement :
```env
APP_DEBUG=true
APP_ENV=local
```

⚠️ **Production :** Toujours mettre `APP_DEBUG=false` et `APP_ENV=production`

---

**Fin du guide CLAUDE.md**  
Dernière mise à jour : 2026-01-21  
Mainteneur : Claude Sonnet 4.5
