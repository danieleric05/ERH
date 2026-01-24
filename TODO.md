# TODO - ERH Application

**Dernière mise à jour :** 2026-01-24

## 🔴 Priorité Haute - À faire immédiatement

### Base de données
- [ ] **Vérifier/créer la table `user` dans la base `c1appstat`**
  - Actuellement seule la table `users` existe
  - Le modèle `User.php` cherche la table `user` (singulier)
  - Erreur lors de la connexion : `Table 'c1appstat.user' doesn't exist`
  - **Solutions possibles :**
    1. Renommer le modèle pour utiliser `users`
    2. Créer/importer la table `user` dans `c1appstat`
    3. Pointer vers la bonne base de données dans `.env`

### Déploiement réseau
- [ ] **Configurer l'application pour le partage réseau**
  - Résoudre le problème de la table `user` manquante
  - Tester l'accès depuis d'autres machines du réseau
  - Configurer les permissions et firewall si nécessaire
  - URL actuelle dans `.env` : `http://172.31.96.10:8000`

### Migration SQL photos
- [ ] **Exécuter le script SQL pour ajouter le champ photo**
  ```bash
  mysql -u daniel -p -D c1appstat < site/database/migrations/add_photo_to_travailleur.sql
  ```
- [ ] Vérifier que le champ `photo` existe dans `e_travailleur`
- [ ] Créer le dossier `/rhassets/images/travailleurs/` s'il n'existe pas
- [ ] Ajouter l'image `default.png` dans ce dossier

## 🟡 Priorité Moyenne - Important mais pas urgent

### Tests
- [ ] Tester le système de connexion/déconnexion
- [ ] Tester l'upload de photos pour les travailleurs
- [ ] Tester la modification d'un travailleur avec photo
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
  - [ ] `travailleur/liste.blade.php`
  - [ ] `travailleur/liste_certificat_travail.blade.php`
  - [ ] `travailleur/listejournalierfin_contrat.blade.php`
  - [ ] `contrat/detail_contrat.blade.php`
  - [ ] `profil/index.blade.php`
  - [ ] Pages de santé (consultations, accidents)
  - [ ] Pages de sanctions
  - [ ] Pages de variables

- [ ] Remplacer toutes les occurrences de `images.png` restantes :
  - `profil/index.blade.php`
  - `travailleur/liste_certificat_travail.blade.php`
  - `travailleur/liste.blade.php`
  - `travailleur/listejournalierfin_contrat.blade.php`
  - `contrat/detail_contrat.blade.php`
  - `insert/link.blade.php`

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
- [x] Commit des modifications (`c76c5df`)

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

### Prochaine session recommandée
1. Résoudre le problème de la table `user`
2. Exécuter la migration SQL pour les photos
3. Tester l'application complètement
4. Déployer sur le réseau

---

**Rappel :** Toujours travailler dans la branche `feature/ats-recrutement`
