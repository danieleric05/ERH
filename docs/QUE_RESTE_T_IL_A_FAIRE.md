# 🎯 QUE RESTE-T-IL À FAIRE ?
## Projet ERH - Mise à jour 27 Janvier 2026

---

## ✅ CE QUI EST FAIT

### Infrastructure ✅
```
✅ Serveur Laravel actif (0.0.0.0:8000)
✅ Configuration réseau WSL2 → Windows fonctionnelle
✅ Accès http://10.10.60.14:8000 validé
✅ Base de données MySQL connectée (port 3306)
✅ Architecture user/users clarifiée
```

### Documentation ✅
```
✅ 19 documents créés (410 KB)
✅ Audit technique complet (score 5.5/10)
✅ Audit design complet (score 62/100)
✅ Design system avec 50+ composants
✅ Guides réseau WSL2 complets
✅ Plan d'action 4 semaines
```

### Fonctionnalités ✅
```
✅ Système de photos (upload, aperçu, suppression)
✅ Modernisation UI (18% des pages en Tailwind)
✅ Formulaires de recherche alignés
✅ Corrections bugs critiques (logout, modification travailleur)
```

---

## 🔴 CETTE SEMAINE (Priorité HAUTE)

### 1. Valider le système de photos (3h) 🎯
```
[ ] Tester upload avec différents formats (JPG, PNG)
[ ] Tester upload avec fichier > 2 Mo (devrait échouer)
[ ] Tester modification travailleur avec changement de photo
[ ] Tester suppression de photo
[ ] Vérifier que les anciennes photos sont bien supprimées
[ ] Vérifier permissions /rhassets/images/travailleurs/
```

**Action :** Créer une checklist de tests et exécuter

---

### 2. Décider du code archivé (2h) 🎯
```
[ ] Analyser l'usage de Précarité (HA01)
   - Routes commentées : web.php lignes 352-373
   - 6 méthodes contrôleur + 2 modèles + vues complètes

   DÉCISION :
   ⭕ Réactiver (décommenter les routes)
   ⭕ Supprimer définitivement (nettoyer tout le code)

[ ] Analyser l'usage de Tenues
   - Routes commentées : web.php lignes 513-535
   - 4 méthodes contrôleur + 3 modèles + vues complètes

   DÉCISION :
   ⭕ Réactiver (décommenter les routes)
   ⭕ Supprimer définitivement (nettoyer tout le code)
```

**Action :** Consulter les utilisateurs métier pour décider

---

### 3. Préparer tests utilisateurs (2h) 🎯
```
[ ] Créer 5 comptes de test dans la base `user`
   Exemples :
   - testeur1 / password123 (Manager)
   - testeur2 / password123 (RH)
   - testeur3 / password123 (Utilisateur)
   - testeur4 / password123 (Lecture seule)
   - admin_test / password123 (Admin dans `users`)

[ ] Préparer données de test
   - 10 travailleurs fictifs avec photos
   - 5 consultations santé
   - 3 sanctions
   - Quelques variables de paie

[ ] Documenter procédure de test
   - Créer GUIDE_TESTS_UTILISATEURS.md
   - Liste des fonctionnalités à tester
   - Grille d'évaluation

[ ] Envoyer email aux testeurs
   - URL : http://10.10.60.14:8000
   - Credentials
   - Ce qu'il faut tester
   - Comment remonter les bugs
```

**Action :** Exécuter SQL pour créer les comptes et données

---

### 4. Ajouter validation uploads (3h) 🎯
```
[ ] Dans EmployerController@post_travailleur_autres
   Ajouter :
   $request->validate([
       'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
   ]);

[ ] Dans EmployerController@post_edit_travailleur
   Ajouter la même validation

[ ] Traduire messages d'erreur en français
   - Dans resources/lang/fr/validation.php
   - "Le fichier doit être une image"
   - "Taille maximale : 2 Mo"
   - "Formats acceptés : JPG, PNG"

[ ] Tester la validation
   - Upload PNG 1 Mo → ✅ OK
   - Upload JPG 5 Mo → ❌ Erreur affichée
   - Upload PDF → ❌ Erreur affichée
   - Upload GIF → ❌ Erreur affichée
```

**Action :** Coder + tester + commit

---

## 🟠 SEMAINE PROCHAINE (Priorité MOYENNE)

### 5. Standardiser Font Awesome v6 (2h)
```
[ ] Identifier toutes les vues utilisant FA v4.7
[ ] Mapper les icônes v4.7 → v6.4
   Exemples :
   - fa-user → fa-user (même)
   - fa-trash-o → fa-trash
   - fa-pencil → fa-pencil-alt

[ ] Remplacer dans toutes les vues
[ ] Supprimer le CDN Font Awesome v4.7
[ ] Garder uniquement v6.4
[ ] Tester toutes les pages
```

---

### 6. Remplacer `<marquee>` obsolète (1h)
```
[ ] Dans home/contenu.blade.php ligne 31
   Remplacer :
   <marquee>VOUS AUREZ...</marquee>

   Par :
   <div x-data="{ scroll: true }"
        class="overflow-hidden">
     <div x-show="scroll"
          x-transition
          class="animate-scroll">
       VOUS AUREZ...
     </div>
   </div>

[ ] Ajouter CSS animation dans tailwind.config.js
[ ] Tester le rendu
```

---

### 7. Corriger contrastes WCAG (2h)
```
[ ] Vérifier tous les textes secondaires (slate-600 sur slate-50)
[ ] Augmenter contraste si < 7:1 (AAA)
[ ] Utiliser slate-700 au lieu de slate-600
[ ] Tester avec outils accessibilité (WAVE, axe DevTools)
```

---

## 🟡 MOYEN TERME (2-4 semaines)

### 8. Refactoriser contrôleurs (16h)
```
Objectif : Passer de 5.5/10 à 7.0/10

[ ] RecruController (2000 lignes) → Créer services
   - SearchService (recherche avancée)
   - ExportService (Excel SAGE, Paie)
   - ContractService (reconduction contrats)

[ ] EmployerController (1500 lignes) → Créer services
   - WorkerService (CRUD travailleurs)
   - PhotoService (gestion photos)
   - PDFService (génération documents)

[ ] Tester après refactoring (aucune régression)
```

---

### 9. Ajouter relations Eloquent (6h)
```
[ ] Modèle Travailleur
   - belongsTo(Departement)
   - belongsTo(Equipe)
   - belongsTo(Unite)
   - belongsTo(Fonction)
   - hasMany(Santes)
   - hasMany(Sanctions)

[ ] Modèle Santes
   - belongsTo(Travailleur)

[ ] Modèle Sanctions
   - belongsTo(Travailleur)

[ ] Utiliser eager loading
   Travailleur::with('departement', 'equipe')->get()

[ ] Mesurer amélioration performance (avant/après)
```

---

### 10. Migrer pages Bootstrap → Tailwind (10h)
```
Objectif : Passer de 18% à 40% de pages modernisées

[ ] Priorité 1 (5 pages les plus utilisées)
   - Formulaire ajout travailleur
   - Formulaire modification contrat
   - Page détail travailleur
   - Liste consultations santé
   - Liste sanctions

[ ] Utiliser composants du Design System
[ ] Tester responsive (mobile, tablet, desktop)
[ ] Valider avec utilisateurs
```

---

### 11. Ajouter pagination (4h)
```
[ ] Listes de travailleurs (8 listes)
   - ListeembaucheController → paginate(50)
   - ListejournalierController → paginate(50)
   - etc.

[ ] Ajouter liens pagination dans vues
   {{ $travailleurs->links() }}

[ ] Personnaliser style pagination (Tailwind)
```

---

### 12. Écrire tests automatisés (12h)
```
Objectif : Passer de 0% à 20% de couverture

[ ] Tests Feature (8h)
   - AuthenticationTest (login, logout)
   - WorkerTest (create, update, delete)
   - PhotoTest (upload, delete)
   - ExportTest (Excel SAGE, Paie)

[ ] Tests Unit (4h)
   - WorkerService
   - PhotoService
   - SearchService

[ ] Configurer CI (GitHub Actions ou GitLab CI)
```

---

## 🟢 LONG TERME (1-3 mois)

### 13. Optimiser performance (8h)
```
[ ] Implémenter cache Redis
   - Listes statiques (départements, unités, équipes)
   - Résultats recherche fréquents

[ ] Optimiser images
   - Conversion WebP
   - Lazy loading
   - Picture/srcset responsive

[ ] Minimifier assets
   - Build Tailwind CSS (pas de CDN)
   - Minifier JS
   - Compresser avec Brotli/Gzip
```

---

### 14. Améliorer sécurité (6h)
```
[ ] Audit sécurité complet
   - OWASP Top 10
   - SQL Injection
   - XSS
   - CSRF (déjà présent)

[ ] Implémenter rate limiting
[ ] Logs détaillés (actions utilisateurs)
[ ] Backup automatisé base de données
```

---

### 15. Documentation utilisateur (8h)
```
[ ] Guide utilisateur PDF
   - Screenshots de chaque fonctionnalité
   - Procédures pas à pas
   - FAQ

[ ] Vidéos tutorielles
   - Ajout travailleur
   - Recherche avancée
   - Export Excel

[ ] Help inline (tooltips dans l'app)
```

---

## 📊 MÉTRIQUES DE SUCCÈS

### Cette semaine
```
✅ Système photos validé à 100%
✅ Décision prise sur code archivé
✅ 5 testeurs mobilisés
✅ Validation uploads implémentée
```

### Semaine prochaine
```
✅ Font Awesome v6 uniquement
✅ Balise marquee supprimée
✅ Contrastes WCAG corrigés
✅ 0 bug critique remonté par testeurs
```

### Mois 1
```
✅ Score technique : 5.5 → 7.0
✅ Score design : 62 → 80
✅ 40% pages modernisées
✅ 20% couverture tests
✅ Feedback utilisateurs positif (>80%)
```

---

## 🎯 ACTIONS IMMÉDIATES (Aujourd'hui)

### 1. Lire la documentation (30 min)
```
[ ] ETAT_ACTUEL_PROJET.md
[ ] TODO.md (mis à jour)
[ ] QUE_RESTE_T_IL_A_FAIRE.md (ce fichier)
```

### 2. Tester le système de photos (1h)
```
[ ] Suivre checklist point 1 ci-dessus
[ ] Noter les bugs éventuels
[ ] Créer des issues Git si nécessaire
```

### 3. Décision code archivé (30 min)
```
[ ] Consulter équipe métier
[ ] Décider : supprimer ou réactiver
[ ] Documenter la décision
```

---

## 📞 BESOIN D'AIDE ?

### Documentation technique
- `AUDIT_COMPLET_2026-01-26.md`
- `GUIDE_ACTIONS_PRIORITAIRES_2026-01-26.md`

### Documentation design
- `DESIGN_SYSTEM_ERH.md`
- `GUIDE_MIGRATION_UI.md`

### État du projet
- `ETAT_ACTUEL_PROJET.md`
- `TODO.md`

---

**Résumé : 15 tâches prioritaires identifiées**
- ✅ 4 terminées (infrastructure, documentation)
- 🔴 4 cette semaine (photos, archivé, tests, validation)
- 🟠 3 semaine prochaine (FA, marquee, WCAG)
- 🟡 5 moyen terme (refactoring, relations, UI)
- 🟢 3 long terme (performance, sécurité, doc utilisateur)

*Mis à jour : 27 janvier 2026 05:30*
