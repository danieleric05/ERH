# CHANGELOG - ERH Application

## [Non publié] - 2026-01-24

### 🐛 Corrections de bugs critiques

#### Bug #1 : Système de déconnexion (logout)
- **Fichier :** `resources/views/layouts/_header.blade.php` (ligne 53-57)
- **Fichier :** `app/Http/Controllers/HomeController.php` (ligne 92)
- **Problème :** Classes CSS sur mauvais élément, redirection vers mauvaise route
- **Solution :** Correction de la structure HTML et redirection vers `route('login')`

#### Bug #2 : Erreur lors de la modification d'un travailleur
- **Fichier :** `app/Http/Controllers/EmployerController.php` (ligne 893-918)
- **Fichier :** `routes/web.php` (suppression ligne 660)
- **Problème :** Vue inexistante, données insuffisantes, route dupliquée
- **Solution :** Création de la méthode `etapedeuxtravailleur()` complète avec chargement de toutes les collections nécessaires

#### Bug #3 : Lien vers stock_tenues non défini
- **Fichier :** `resources/views/home/contenu.blade.php` (lignes 100, 382)
- **Problème :** Route archivée mais lien actif
- **Solution :** Commentaire des liens vers la fonctionnalité archivée

### ✨ Nouvelles fonctionnalités

#### Système de photos pour travailleurs
- **Migration SQL :** `database/migrations/add_photo_to_travailleur.sql`
- **Champ ajouté :** `photo` (VARCHAR 255, nullable) dans `e_travailleur`
- **Dossier créé :** `/rhassets/images/travailleurs/`
- **Nomenclature :** `timestamp_MATRICULE.extension`
- **Formats acceptés :** JPG, PNG, JPEG
- **Photo par défaut :** `default.png`

**Implémentation :**
- Upload dans `EmployerController@post_travailleur_autres` et `post_edit_travailleur`
- Aperçu photo en temps réel dans les formulaires
- Suppression automatique des anciennes photos lors de modification
- Affichage dans toutes les listes de travailleurs

**Vues modifiées :**
- `travailleur/addautres.blade.php` - Formulaire d'ajout avec upload
- `travailleur/edit.blade.php` - Formulaire d'édition avec aperçu
- `travailleur/liste_declarations.blade.php` - Affichage photos
- `travailleur/liste_embauches.blade.php` - Affichage photos
- `travailleur/liste_tous_travailleur.blade.php` - Affichage photos
- `travailleur/listecessations.blade.php` - Affichage photos
- `travailleur/listetravailleur.blade.php` - Affichage photos

#### Modernisation de la page Recherche & Historique
- **Fichier :** `resources/views/travailleur/historique.blade.php`
- **Changements :**
  - Migration complète vers Tailwind CSS et Alpine.js
  - Formulaire de recherche avec champs conditionnels dynamiques
  - Ajout de la colonne "Photo" dans les résultats
  - Formatage des dates avec Carbon (dd/mm/yyyy)
  - Message "Aucun résultat" élégant
  - Compteur de résultats
  - Boutons d'export repositionnés
  - Design responsive moderne

### 🎨 Améliorations UI/UX

#### Page d'accueil (Welcome/Bienvenue)
- **Fichier :** `resources/views/home/contenu.blade.php`
- **Changements :**
  - Remplacement des images `images.png` par icônes Font Awesome modernes
  - Section "Précarité" masquée (fonctionnalité obsolète)
  - Section "Autorisations" désactivée visuellement (en cours de développement)
  - Grille responsive optimisée (4 colonnes → 3 colonnes)
  - Effets hover sur les cartes
  - Cohérence visuelle améliorée

#### Formulaires de recherche
- **Convention adoptée :** Alignement à droite (5 fichiers modifiés)
- **Fichiers concernés :**
  - `travailleur/liste_declarations.blade.php`
  - `travailleur/liste_embauches.blade.php`
  - `travailleur/liste_tous_travailleur.blade.php`
  - `travailleur/listecessations.blade.php`
  - `travailleur/listetravailleur.blade.php`

### 📝 Documentation

#### Fichiers créés/mis à jour
- ✅ `CLAUDE.md` - Guide principal (MAJ 2026-01-21)
- ✅ `CHANGELOG.md` - Ce fichier (créé 2026-01-24)
- ✅ `TODO.md` - Liste des tâches restantes (créé 2026-01-24)

#### Affichage des photos dans toutes les listes
- **Session :** 2026-01-24 (continuation)
- **Fichiers corrigés :**
  - `travailleur/liste.blade.php` - Remplacement `images.png` par photo réelle
  - `travailleur/liste_certificat_travail.blade.php` - Remplacement `images.png` par photo réelle
  - `travailleur/listejournalierfin_contrat.blade.php` - Remplacement conditionnels obsolètes par photo réelle
- **Résultat :** Toutes les listes de travailleurs affichent maintenant la vraie photo avec fallback sur `default.png`

#### Suppression de photos en cas d'erreur
- **Nouvelle méthode :** `EmployerController@delete_photo_travailleur()`
- **Nouvelle route :** POST `/delete_photo_travailleur/{id}` (nommée)
- **UI :** Bouton "Supprimer la photo" dans le formulaire d'édition
  - Visible uniquement si une photo existe
  - Demande de confirmation avant suppression
  - Supprime le fichier physique et met la colonne à NULL
- **Fichiers modifiés :**
  - `app/Http/Controllers/EmployerController.php` (+23 lignes)
  - `routes/web.php` (+4 lignes)
  - `resources/views/travailleur/edit.blade.php` (+13 lignes)

### 📊 Statistiques

**Commits principaux :**
- `8b5dce0 - feat: ajout fonctionnalité suppression de photo travailleur`
- `458ede8 - chore: ignorer dossier rhassets dans site/`
- `9df4cc6 - fix: affichage photos réelles dans toutes les listes de travailleurs`
- `c76c5df - fix: corrections critiques et amélioration UX`

**Fichiers modifiés (session 2026-01-24) :** 6 fichiers
- 1 contrôleur (EmployerController) +23 lignes
- 1 fichier de routes (web.php) +4 lignes
- 4 vues de travailleurs +14 lignes
- 1 fichier config (.gitignore) +1 ligne

**Total projet :** 19 fichiers modifiés, ~750 insertions/suppressions

**Lignes modifiées :**
- +499 insertions
- -334 suppressions

---

## [1.0.0] - Versions antérieures

### 2026-01-21
- Refonte frontend : Migration vers Tailwind CSS et Alpine.js

### 2026-01-XX
- Migration de Laravel 10 vers Laravel 11
- Mise à jour de Laravel 9 vers Laravel 10

---

**Légende :**
- 🐛 Corrections de bugs
- ✨ Nouvelles fonctionnalités
- 🎨 Améliorations UI/UX
- 📝 Documentation
- 🔧 Maintenance
- ⚡ Performance
- 🔒 Sécurité
