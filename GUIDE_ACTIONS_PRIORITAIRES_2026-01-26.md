# GUIDE D'ACTIONS PRIORITAIRES - PROJET ERH
## Roadmap détaillée et checklist pour développeurs

**Créé:** 26 Janvier 2026
**Mis à jour:** 26 Janvier 2026
**Branche cible:** `feature/ats-recrutement`
**Audience:** Équipe de développement

---

## GUIDE RAPIDE DE DÉMARRAGE

Si vous êtes nouveau sur le projet, commencez par :

1. **Lire CLAUDE.md** (~20 min) - Comprendre l'architecture et conventions
2. **Lire AUDIT_COMPLET_2026-01-26.md** (~30 min) - Vue d'ensemble complète
3. **Lire ce document** (~15 min) - Savoir ce qui est prioritaire
4. **Exécuter DEMARRAGE_RAPIDE.txt** (~10 min) - Lancer en local

**Temps total:** ~75 minutes pour être opérationnel

---

## PRIORITIES HEBDOMADAIRES

### SEMAINE 1 (26 jan - 02 fev) - BLOCKERS

#### Jour 1 : Lundi 26 Janvier - Diagnostic

**Morning (2 heures)**
```bash
# 1. Vérifier la table user en base de données
mysql -u daniel -p -P 8054 c1appstat
SHOW TABLES LIKE 'user%';      # Vérifier si 'user' ou 'users' existe
DESCRIBE user;                  # ou DESCRIBE users;
SELECT COUNT(*) FROM users;     # Compter les enregistrements
```

**Réponse attendue:**
- Si `users` existe avec données → User.php est incorrect
- Si `user` existe → tout va bien
- Si ni l'un ni l'autre → créer table

**Action si user n'existe pas:**
```bash
# Option 1: Utiliser la table users (Laravel standard)
# Éditer app/User.php
# Supprimer protected $table = 'user';

# Option 2: Renommer la table
ALTER TABLE users RENAME TO user;
```

**Aftermoon (2 heures)**
```bash
# 2. Tester la connexion/déconnexion
cd site
php artisan serve

# Browser: http://localhost:8000/se-connecter
# Test: Login + Logout
# Vérifier: Pas d'erreur "Table ... doesn't exist"
# Vérifier: Déconnexion redirige vers /se-connecter
```

**Livrables jour 1:**
- [ ] Issue résolue ou documentée (créer ISSUE si problème)
- [ ] Authentification testée et fonctionnelle
- [ ] Commit: `fix: clarifier table user vs users`

---

#### Jour 2 : Mardi 27 Janvier - Système de photos

**Morning (3 heures)**
```bash
# 1. Exécuter script SQL migration
mysql -u daniel -p -P 8054 c1appstat < site/database/migrations/add_photo_to_travailleur.sql

# 2. Vérifier que la colonne existe
mysql -u daniel -p -P 8054 c1appstat -e "DESCRIBE e_travailleur;" | grep photo
# Résultat attendu: photo | varchar(255) | YES | NULL |

# 3. Vérifier/créer dossier photos
mkdir -p rhassets/images/travailleurs
chmod 755 rhassets/images/travailleurs
ls -la rhassets/images/travailleurs/
# Devrait être vide ou contenir default.png
```

**Afternoon (2 heures)**
```bash
# 4. Tester upload travailleur journalier
# Browser: http://localhost:8000/ajouter-autres-travailleur
# - Remplir formulaire
# - Sélectionner une image JPG/PNG
# - Vérifier preview affichée
# - Soumettre
# - Vérifier photo sauvegardée en BD
# - Vérifier fichier dans /rhassets/images/travailleurs/

# 5. Vérifier la photo en liste
# Browser: http://localhost:8000/liste-embauches
# Vérifier colonne photo affiche bien la photo du travailleur
```

**Livrables jour 2:**
- [ ] Script SQL exécuté
- [ ] Dossier photos créé avec permissions correctes
- [ ] Upload/affichage teste et fonctionnel
- [ ] Commit: `test: validation système photos`

---

#### Jour 3-4 : Mercredi-Jeudi 28-29 Janvier - Code archivé

**Décision requise:** Réactiver ou supprimer Précarité (HA01) et Tenues

**Option A: SUPPRIMER (recommandé)**

```bash
# 1. Supprimer routes commentées (web.php)
# Fichier: routes/web.php
# Supprimer lignes 352-373 (précarité)
# Supprimer lignes 513-535 (tenues)
# Supprimer ligne 423 (précarité cache)
# Supprimer ligne 673 (précarité cache)

git diff routes/web.php  # Vérifier avant commit

# 2. Supprimer méthodes contrôleurs
# Fichier: app/Http/Controllers/EmployerController.php
# Supprimer 10 méthodes:
#   - finaliser_ha01()
#   - post_precarite_ho()
#   - post_precarite_finalite()
#   - exels_precarites()
#   - exels_precarites_debut()
#   - historique_ha01()
#   - listetenues()
#   - stock_tenues()
#   - appro_stock()
#   - post_gestion_tenue()
#   - post_appro_stock()

# Attention: ne pas toucher aux autres 25 méthodes!

# 3. Supprimer modèles
rm app/HAO1.php
rm app/Precarites.php
rm app/Tenues.php
rm app/ApproTenues.php
rm app/Services_tenue.php

# 4. Supprimer vues
rm -rf resources/views/precarite/
rm -rf resources/views/tenues/
rm resources/views/contrat/fiche_precarite.blade.php

# 5. Supprimer références dans accueil
# Fichier: resources/views/home/contenu.blade.php
# Supprimer lignes 100 et 382 (commentaires + lien)

# 6. Mettre à jour documentation
# Fichier: CLAUDE.md
# Section "Bugs Connus" → Supprimer références
# Section "Archivé" → Marquer comme supprimé
# Créer commit

git add -A
git commit -m "chore: supprimer code archivé (précarité, tenues)"
git log --oneline -5  # Vérifier
```

**Option B: RÉACTIVER (si décision business)**

```bash
# 1. Décommenter routes
# Fichier: routes/web.php
# Décommenter lignes 352-373 et 513-535

# 2. Vérifier contrôleurs
# Tous les contrôleurs des routes existaient-ils?
# Vérifier: EmployerController methods existent-elles?
# Résultat: oui, il y a 10 méthodes existantes

# 3. Vérifier vues
# Les vues existent-elles? precarite/add.blade.php
# Résultat: oui

# 4. Ajouter au TODO.md avec user story
# "Réactiver et terminer implémentation précarité"
# "Réactiver et terminer implémentation tenues"
```

**Livrables jour 3-4:**
- [ ] Décision: A (supprimer) ou B (réactiver)
- [ ] Si A: Code supprimé, routes nettoyées, documentation mise à jour
- [ ] Si B: Routes décommentées, task créée dans TODO.md
- [ ] Commit: `chore: [action archivé décidée]`
- [ ] Tous les tests passent: `php artisan serve` + browser checks

---

#### Jour 5 : Vendredi 30 Janvier - Validation

**Checklist finale semaine 1 (2 heures):**

```
SYSTÈME
  [ ] Table user vs users résolue
  [ ] Authentification login/logout testée
  [ ] Pas d'erreur dans Laravel.log

PHOTOS
  [ ] Script SQL exécuté
  [ ] Dossier /rhassets/images/travailleurs/ créé
  [ ] Upload travailleur journalier fonctionne
  [ ] Photo affichée en liste
  [ ] Photo affichée en édition
  [ ] Suppression photo fonctionne

CODE ARCHIVÉ
  [ ] Routes précarité/tenues gérées (suppression OU réactivation)
  [ ] Pas de "ARCHIVÉ" en attente dans code
  [ ] CLAUDE.md mis à jour

GIT
  [ ] Tous les commits sur branche feature/ats-recrutement
  [ ] Messages commit clairs et cohérents
  [ ] Pas de erreurs de merge
```

**Réunion fin de semaine (1 heure):**
- Revue des changements
- Vérification pas de régression
- Planning semaine 2

---

### SEMAINE 2 (02 fev - 08 fev) - URGENT

#### P4: Ajouter validation uploads (3 jours)

**Lundi-Mardi:**
```php
// app/Http/Requests/StoreWorkerPhotoRequest.php (NEW)
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreWorkerPhotoRequest extends FormRequest
{
    public function authorize()
    {
        return true; // ou vérifier permission utilisateur
    }

    public function rules()
    {
        return [
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            // 'max:2048' = 2 MB en kilobytes
        ];
    }

    public function messages()
    {
        return [
            'photo.image' => 'Le fichier doit être une image.',
            'photo.mimes' => 'Les formats acceptés sont JPG et PNG.',
            'photo.max' => 'L\'image ne doit pas dépasser 2 MB.',
        ];
    }
}
```

```php
// app/Http/Controllers/EmployerController.php (MODIFIER)
// Avant:
public function post_travailleur_autres(Request $request)
{
    if ($request->hasFile('photo')) {
        // upload sans validation ❌
    }
}

// Après:
public function post_travailleur_autres(StoreWorkerPhotoRequest $request)
{
    // Photo validée automatiquement
    if ($request->hasFile('photo')) {
        // upload avec photo validée ✅
    }
}

// Aussi: post_edit_travailleur()
```

**Mercredi: Tests**
```bash
# Tester cas valides
# - JPG petit (<500 KB)
# - PNG petit (<500 KB)

# Tester cas invalides
# - GIF → message "Format JPG/PNG uniquement"
# - BMP → message "Format JPG/PNG uniquement"
# - Image >2.5 MB → message "Max 2 MB"
# - Fichier texte → message "Fichier invalide"

# Vérifier messages utilisateur affichés
```

**Livrables:**
- [ ] Form Request créée et utilisée
- [ ] Messages d'erreur testés
- [ ] Aucune photo invalide stockée
- [ ] Commit: `feat: ajouter validation uploads photos`

---

#### P5-P6: Refactoriser contrôleurs (4 jours)

**Mercredi-Jeudi: EmployerController**
```php
// app/Services/PhotoService.php (NEW)
<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;

class PhotoService
{
    public function uploadPhoto(UploadedFile $photo, $matricule)
    {
        $photoName = time() . '_' . $matricule . '.' . $photo->getClientOriginalExtension();
        $path = public_path('../rhassets/images/travailleurs');
        $photo->move($path, $photoName);
        return $photoName;
    }

    public function deletePhoto($photoName)
    {
        if (!$photoName || $photoName === 'default.png') {
            return;
        }
        $path = public_path('../rhassets/images/travailleurs/' . $photoName);
        if (file_exists($path)) {
            unlink($path);
        }
    }
}
```

```php
// app/Http/Controllers/EmployerController.php (APRÈS)
// Avant: 36 méthodes volumineux, 1500 lignes
// Après: 20 méthodes, 800 lignes

use App\Services\PhotoService;

class EmployerController extends Controller
{
    private $photoService;

    public function __construct(PhotoService $photoService)
    {
        $this->photoService = $photoService;
    }

    // TRAVAILLEURS (6 méthodes) - garder
    public function post_travailleur() {}
    public function post_travailleur_autres() {}
    public function post_edit_travailleur() {}
    public function delete_photo_travailleur() {}
    public function etapedeuxtravailleur() {}

    // DOCUMENTS (0 méthodes) → SUPPRIMER (créer DocumentController)
    // → telechargerContratJournalier(), etc. → DocumentController

    // LISTES (0 méthodes) → GARDER AILLEURS
    // → Dépendre de quoi sont-elles responsables?

    // ARCHIVÉ (0 méthodes) → SUPPRIMÉ

    // TENUES (0 méthodes) → SUPPRIMÉ
}
```

**Création PhotoController.php (optionnel):**
```php
// Si vous voulez séparer d'avantage
// app/Http/Controllers/PhotoController.php
class PhotoController extends Controller
{
    // Photo upload/delete isolé
    // Réutilisable pour d'autres modèles
}
```

**Vendredi: Tests et cleanup**
```bash
# 1. Tester que toutes les photos marchent encore
# 2. Vérifier aucune régression
# 3. Code metrics
#    - EmployerController: avant 1500 → après ~1000 lignes
#    - RecruController: rester ~2000 (sera refactorisé semaine 3)
```

**Livrables:**
- [ ] PhotoService créée et testée
- [ ] EmployerController utilisé PhotoService
- [ ] Aucune régression photo
- [ ] Commit: `refactor: extraire PhotoService (EmployerController)`

---

### SEMAINE 3 (09 fev - 15 fev) - IMPORTANT

#### P7: Ajouter relations Eloquent (2 jours)

**Lundi:**
```php
// app/Travailleur.php (AVANT)
<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

class Travailleur extends Model
{
    protected $table = 'e_travailleur';
    // ❌ Aucune relation
}

// app/Travailleur.php (APRÈS)
<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

class Travailleur extends Model
{
    protected $table = 'e_travailleur';

    // Relations
    public function departement()
    {
        return $this->belongsTo(Departement::class, 'departementid');
    }

    public function equipe()
    {
        return $this->belongsTo(Equipes::class, 'equipeid');
    }

    public function unite()
    {
        return $this->belongsTo(Unites::class, 'uniteid');
    }

    public function fonction()
    {
        return $this->belongsTo(Fonction::class, 'fonctionid');
    }

    public function typeContrat()
    {
        return $this->belongsTo(TypeContrat::class, 'type_contratid');
    }

    public function pays()
    {
        return $this->belongsTo(Pays::class, 'paysid');
    }

    // Relations inversées (1:many)
    public function consultations()
    {
        return $this->hasMany(Santes::class, 'travailleurid');
    }

    public function sanctions()
    {
        return $this->hasMany(Sanctions::class, 'travailleurid');
    }

    public function variables()
    {
        return $this->hasMany(Variables::class, 'travailleurid');
    }
}
```

**Mardi:**
```php
// app/Departement.php (et autres)
public function travailleurs()
{
    return $this->hasMany(Travailleur::class, 'departementid');
}
```

**Utilisation dans contrôleurs:**
```php
// Avant (N+1 queries)
$travailleur = Travailleur::find($id);
$dept = Departement::find($travailleur->departementid);
$dept_nom = $dept->nom;

// Après (1 query avec eager loading)
$travailleur = Travailleur::with('departement')->find($id);
$dept_nom = $travailleur->departement->nom;

// Encore mieux (si besoin plusieurs)
$travailleur = Travailleur::with('departement', 'equipe', 'unite')
    ->find($id);
```

**Livrables:**
- [ ] Relations définies dans 10+ modèles
- [ ] Utilisées avec eager loading
- [ ] Performance benchmarkée (moins de queries)
- [ ] Commit: `feat: ajouter relations Eloquent`

---

#### P8: Moderniser vues Tailwind (3 jours)

**Jeudi-Vendredi:**
```bash
# Sélectionner 1 formulaire simple
# Exemple: sante/consultation.blade.php (formulaire + 1 table)

# 1. Copier depuis addautres.blade.php (template moderne)
# 2. Adapter au contexte de santé
# 3. Tester en local
# 4. Commit: "ui: moderniser sante/consultation.blade.php"
```

**Progression recommandée:**
```
Jour 1: Formulaires simples (consultation)
Jour 2: Listes avec tables (listesconsultation)
Jour 3: Formulaires complexes (accidents de travail)
```

**Livrables semaine 3:**
- [ ] 3-5 vues modernisées
- [ ] Tests locaux
- [ ] Commit: `ui: moderniser vues santé`

---

### SEMAINE 4+ (16 fev+) - CONTINU

#### P9: Tests (16 heures réparties sur 2 semaines)

```bash
# Structure de test
tests/
├── Unit/
│   └── Services/
│       └── PhotoServiceTest.php          # Test PhotoService
├── Feature/
│   ├── Auth/
│   │   └── LoginTest.php                 # Test authentification
│   ├── Workers/
│   │   ├── CreateWorkerTest.php          # Test création
│   │   ├── EditWorkerTest.php            # Test édition
│   │   ├── DeleteWorkerTest.php          # Test suppression
│   │   └── PhotoUploadTest.php           # Test photos
│   ├── Search/
│   │   └── AdvancedSearchTest.php        # Test recherche
│   └── Export/
│       └── ExcelExportTest.php           # Test export

# Commandes
php artisan test                           # Lancer tous les tests
php artisan test tests/Feature/Workers    # Tests spécifiques
```

---

## CHECKLIST PERSONNALISÉE PAR RÔLE

### Pour le Lead Dev

- [ ] Lire AUDIT_COMPLET_2026-01-26.md complètement
- [ ] Décider: Supprimer ou réactiver code archivé
- [ ] Valider priorities et timeline avec équipe
- [ ] Mettre en place CI/CD pour tests automatiques
- [ ] Organiser code review bi-hebdomadaire

### Pour les développeurs mid-level

- [ ] Lire CLAUDE.md + AUDIT_COMPLET
- [ ] Choisir 1 tâche P4-P8 pour la semaine
- [ ] Commiter avec messages clairs
- [ ] Demander revue avant merge

### Pour les juniors

- [ ] Lire CLAUDE.md + GUIDE_ACTIONS (ce document)
- [ ] Suivre checklist SEMAINE 1 pas à pas
- [ ] Pair programming sur P4 (validation)
- [ ] Pratiquer sur tests petits (P9)

---

## MÉTRIQUES DE SUCCÈS

### Par semaine

**Semaine 1:**
- [x] 3 blockers résolus (user table, photos testées, code archivé décidé)
- [x] Aucune régression authentification
- [x] Aucune perte de photo

**Semaine 2:**
- [x] Validation photos implémentée
- [x] PhotoService créée et testée
- [x] Aucun upload invalide accepté

**Semaine 3:**
- [x] 10+ relations Eloquent définies
- [x] 5-10 vues modernisées
- [x] N+1 queries éliminées (vérification avec Debugbar)

**Semaine 4:**
- [x] 15+ Feature tests passants
- [x] 10+ Unit tests passants
- [x] Coverage ~70% des critiques

### Code health

```
AVANT AUDIT (2026-01-26):
  - Code duplication:      30%
  - Tests:                 0%
  - Controllers > 1000 L:  2
  - Vues Tailwind:         18%

APRÈS SEMAINE 2 (objectif):
  - Code duplication:      25% ↓5%
  - Tests:                 20%
  - Controllers > 1000 L:  0
  - Vues Tailwind:         25% ↑7%

APRÈS SEMAINE 4 (objectif):
  - Code duplication:      15% ↓15%
  - Tests:                 50%
  - Controllers > 1000 L:  0
  - Vues Tailwind:         40% ↑22%
```

---

## RESSOURCES RECOMMANDÉES

### Documentation
- Laravel 11 Docs: https://laravel.com/docs/11.x
- Blade: https://laravel.com/docs/11.x/blade
- Eloquent: https://laravel.com/docs/11.x/eloquent
- Forms Requests: https://laravel.com/docs/11.x/validation#form-request-validation

### Outils
- Laravel Tinker: `php artisan tinker`
- Laravel Debugbar: Inspecteur requêtes SQL
- PHPUnit: Tests natifs Laravel
- Postman/Insomnia: Test API (si API REST future)

### Commandes utiles
```bash
# Routes
php artisan route:list
php artisan route:list --name=travailleur

# Models
php artisan make:model NomModel

# Controllers
php artisan make:controller NomController

# Requests
php artisan make:request NomRequest

# Services
php artisan make:class Services/NomService

# Tests
php artisan make:test NomTest --feature
php artisan make:test NomTest --unit

# Database
php artisan migrate
php artisan migrate:refresh

# Cache clear
php artisan cache:clear
php artisan view:clear
```

---

## NOTES IMPORTANTES

### Branches Git

**Branche courante:** `feature/ats-recrutement`

```bash
# Créer une branche pour chaque tâche P4-P11
git checkout -b feature/validation-photos
git checkout -b refactor/employer-controller
git checkout -b refactor/recru-controller
git checkout -b feat/eloquent-relations
git checkout -b ui/tailwind-sante

# Après completion
git checkout feature/ats-recrutement
git merge feature/validation-photos
git branch -d feature/validation-photos
```

### Messages commit

```bash
# Format recommandé (Conventional Commits)
git commit -m "feat: [description]"          # Nouvelle fonctionnalité
git commit -m "fix: [description]"           # Bug fix
git commit -m "refactor: [description]"      # Refactoring
git commit -m "ui: [description]"            # Changement UI
git commit -m "test: [description]"          # Tests
git commit -m "docs: [description]"          # Documentation
git commit -m "chore: [description]"         # Maintenance

# Exemples
git commit -m "feat: ajouter validation uploads photos"
git commit -m "refactor: extraire PhotoService"
git commit -m "ui: moderniser page sante/consultation"
git commit -m "test: ajouter tests login/logout"
```

### Révision de code

Avant de merger en `feature/ats-recrutement`:
- [ ] Code compiles sans erreur
- [ ] Pas de var_dump/dd() laissés
- [ ] Pas d'erreurs dans Laravel.log
- [ ] Tests passent (si applicables)
- [ ] Message commit clair
- [ ] Documentation mise à jour si besoin

---

## FOIRE AUX QUESTIONS (FAQ)

**Q: Par où je commence si je suis nouveau?**
A: Lire CLAUDE.md (20 min), puis AUDIT_COMPLET (30 min), puis ce guide (15 min)

**Q: Je dois faire quoi cette semaine?**
A: Voir SEMAINE 1 checklist à la ligne 102

**Q: Combien de temps pour tout terminer?**
A: ~120-160 heures (3-4 semaines avec équipe de 2-3 devs)

**Q: Je peux commencer par P8 (UI Tailwind)?**
A: Non, attendez que P4-P6 (validation, refactoring) soient d'abord faits

**Q: Les photos sont cassées?**
A: Non, c'est déjà testé. Juste à tester en local jour 2 semaine 1

**Q: Je dois supprimer le code archivé?**
A: Decision à prendre jour 3-4 semaine 1 avec Lead Dev

**Q: Où je vois les photos uploadées?**
A: Dans `/rhassets/images/travailleurs/` + en base de données colonne `e_travailleur.photo`

**Q: Comment je teste les photos?**
A: Aller sur `/ajouter-autres-travailleur`, uploader une image, vérifier liste

**Q: Je dois faire un PR (pull request)?**
A: Oui, utiliser branches feature/* et merger en `feature/ats-recrutement`

---

## CONCLUSION

**L'objectif sur 4 semaines:** Transformer ERH de 5.5/10 à 7.5/10

**Points clés:**
1. Résoudre les 3 blockers immédiatement (semaine 1)
2. Renforcer la qualité avec validation et tests (semaine 2-3)
3. Réduire la complexité des contrôleurs (semaine 2-3)
4. Moderniser progressivement l'interface (semaine 3+)

**Succès sera:** Application stable, maintenable, testée, moderne

**Questions?** Consulter CLAUDE.md ou AUDIT_COMPLET_2026-01-26.md

---

**Roadmap créée:** 26 Janvier 2026
**Approuvée par:** Architecture Review
**Prochaine révision:** 16 Février 2026 (fin semaine 3)

