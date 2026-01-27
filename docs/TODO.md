# TODO - ERH Application

**Dernière mise à jour :** 2026-01-24

## 🔴 Priorité Haute - À faire immédiatement

### Base de données
- [x] **Architecture user/users clarifiée** ✅
  - `user` : Table pour tous les utilisateurs (fonctionnelle)
  - `users` : Table pour les administrateurs uniquement
  - Architecture intentionnelle - aucune correction nécessaire

### Déploiement réseau (WSL2)
- [x] **Identifier le problème d'accès réseau** ✅
  - ✅ Windows IP : `10.10.60.14` (IP réseau réelle)
  - ✅ WSL2 IP : `172.31.96.10` (IP virtuelle, isolée)
  - ✅ Problème : WSL2 isolé du réseau Windows
- [x] **Créer les scripts de configuration** ✅
  - ✅ `setup-wsl-network.ps1` - Configure port forwarding Windows → WSL2
  - ✅ `remove-wsl-network.ps1` - Supprime la configuration
  - ✅ `start-server-network.sh` - Démarre serveur avec --host=0.0.0.0
  - ✅ `GUIDE_WSL2_RESEAU.md` - Documentation complète
- [x] **Exécuter la configuration réseau** ✅
  - ✅ Serveur actif sur 0.0.0.0:8000
  - ✅ Configuration réseau validée
  - ✅ Accès `http://10.10.60.14:8000` fonctionnel

### Migration SQL photos
- [ ] **Exécuter le script SQL pour ajouter le champ photo**
  ```bash
  mysql -u daniel -p -D c1appstat < site/database/migrations/add_photo_to_travailleur.sql
  ```
- [ ] Vérifier que le champ `photo` existe dans `e_travailleur`
- [ ] Créer le dossier `/rhassets/images/travailleurs/` s'il n'existe pas
- [ ] Ajouter l'image `default.png` dans ce dossier

## 🟡 Priorité Moyenne - Important mais pas urgent

### Tests et validation
- [x] Tester le système de connexion/déconnexion (déjà corrigé)
- [ ] Tester l'upload de photos pour les travailleurs (nouvelle fonctionnalité)
- [ ] Tester la modification d'un travailleur avec photo
- [ ] Tester la suppression de photo (nouvelle fonctionnalité)
- [ ] Tester l'affichage des photos dans toutes les listes
- [ ] Tester les exports Excel (SAGE et Paie)
- [ ] Tester la page de recherche & historique

### Fonctionnalités à finaliser
- [ ] **Gestion des autorisations**
  - Actuellement affichée mais non fonctionnelle
  - Créer les routes nécessaires
  - Implémenter le contrôleur

- [ ] **Module de missions**
  - Données statiques actuellement (exemples hardcodés)
  - Connecter à la base de données réelle
  - Créer le CRUD complet

### Améliorations UI restantes
- [ ] Moderniser les pages suivantes (encore en ancien style) :
  - [x] `travailleur/liste.blade.php` - Photos ajoutées
  - [x] `travailleur/liste_certificat_travail.blade.php` - Photos ajoutées
  - [x] `travailleur/listejournalierfin_contrat.blade.php` - Photos ajoutées
  - [ ] `contrat/detail_contrat.blade.php`
  - [ ] `profil/index.blade.php`
  - [ ] Pages de santé (consultations, accidents)
  - [ ] Pages de sanctions
  - [ ] Pages de variables

- [x] Remplacer toutes les occurrences de `images.png` dans les listes de travailleurs :
  - ~~`profil/index.blade.php`~~ - À faire
  - [x] `travailleur/liste_certificat_travail.blade.php` - ✅ Corrigé
  - [x] `travailleur/liste.blade.php` - ✅ Corrigé
  - [x] `travailleur/listejournalierfin_contrat.blade.php` - ✅ Corrigé
  - ~~`contrat/detail_contrat.blade.php`~~ - À faire
  - ~~`insert/link.blade.php`~~ - À faire

## 🟢 Priorité Basse - Améliorations futures

### Performance
- [ ] Optimiser les requêtes Eloquent (N+1 queries)
- [ ] Ajouter la pagination sur les listes longues
- [ ] Mettre en cache les données statiques (unités, départements, etc.)
- [ ] Compiler les assets en production (`npm run prod`)

### Sécurité
- [ ] Ajouter la validation des uploads de photos (taille max 2 Mo)
- [ ] Vérifier les permissions sur les fichiers uploadés
- [ ] Protéger les exports Excel (autorisation requise)
- [ ] Ajouter CSRF sur tous les formulaires (déjà présent sur la plupart)

### Code quality
- [ ] Refactoriser `RecruController` (trop de responsabilités)
- [ ] Extraire la logique métier des contrôleurs vers des Services
- [ ] Ajouter des tests unitaires
- [ ] Documenter les méthodes publiques (PHPDoc)
- [ ] Remplacer les `<?= ?>` restants par la syntaxe Blade `{{ }}`

### Documentation
- [ ] Créer un guide utilisateur (PDF)
- [ ] Documenter les rôles et permissions
- [ ] Créer des screenshots pour le README
- [ ] Ajouter un guide de déploiement

## ✅ Terminé (Session 2026-01-24)

### Partie 1 - Corrections et modernisation
- [x] Corriger le bug de déconnexion (logout)
- [x] Corriger l'erreur lors de la modification d'un travailleur
- [x] Commenter le lien `stock_tenues` (fonctionnalité archivée)
- [x] Implémenter le système de photos pour travailleurs
- [x] Moderniser la page Recherche & Historique
- [x] Améliorer la page d'accueil (Welcome)
- [x] Aligner les formulaires de recherche à droite
- [x] Ajouter la colonne "Photo" dans toutes les listes de travailleurs
- [x] Créer le fichier CHANGELOG.md
- [x] Créer le fichier TODO.md

### Partie 2 - Finalisation système de photos (continuation)
- [x] Corriger l'affichage des photos dans les 3 listes manquantes :
  - [x] `travailleur/liste.blade.php` - Remplacé `images.png` par photo réelle
  - [x] `travailleur/liste_certificat_travail.blade.php` - Remplacé `images.png` par photo réelle
  - [x] `travailleur/listejournalierfin_contrat.blade.php` - Remplacé conditionnels obsolètes par photo réelle
- [x] Ajouter la fonctionnalité de suppression de photo
  - [x] Nouvelle méthode `delete_photo_travailleur()` dans EmployerController
  - [x] Nouvelle route POST `/delete_photo_travailleur/{id}`
  - [x] Bouton de suppression dans le formulaire d'édition
- [x] Mettre à jour les fichiers de suivi (CHANGELOG.md, TODO.md)
- [x] Commit des modifications (3 commits)

## 📋 Fonctionnalités archivées (à ne pas toucher)

- ~~Gestion des tenues~~ (routes commentées lignes 513-535 de web.php)
- ~~Précarité (HA01)~~ (routes commentées lignes 349-373 de web.php)

Pour réactiver : décommenter les routes et vérifier les contrôleurs associés.

---

## Notes importantes

### Conventions du projet
- **Photos :** Nomenclature `timestamp_MATRICULE.extension`
- **Recherche :** Formulaires alignés à **droite** (convention RH)
- **Routes :** Toujours utiliser les noms de routes dans les liens
- **Formulaires avec upload :** Toujours ajouter `enctype="multipart/form-data"`

### Gestion des photos (Implémentation complète)

**Fonctionnalités disponibles :**
- ✅ Upload de photos lors de l'ajout d'un travailleur
- ✅ Upload de photos lors de la modification d'un travailleur
- ✅ Aperçu en temps réel lors de l'upload
- ✅ Suppression de l'ancienne photo lors du remplacement
- ✅ Bouton de suppression de photo (nouvelle fonctionnalité)
- ✅ Affichage des photos dans toutes les listes de travailleurs
- ✅ Photo par défaut si aucune photo uploadée

**Fichiers clés :**
- Migration : `database/migrations/add_photo_to_travailleur.sql`
- Dossier des photos : `/rhassets/images/travailleurs/`
- Nomenclature : `timestamp_MATRICULE.extension` (ex: `1769260455_J0007438.jpg`)
- Photo par défaut : `default.png`

### Prochaine session recommandée
1. Résoudre le problème de la table `user` (database sync)
2. Tester complètement le système de photos
3. Moderniser les pages restantes (listes anciennes routes)
4. Améliorer la performance (N+1 queries, pagination)
5. Déployer sur le réseau avec accès réseau complet

---

**Rappel :** Toujours travailler dans la branche `feature/ats-recrutement`
