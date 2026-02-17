# 📋 GUIDE COMPLET: Correction de l'Architecture des Dossiers `public/`

**Dernière mise à jour:** Février 2026
**Auteur:** Claude Code
**Statut:** Finalisé et documenté

---

## 🎯 Objectif

Aligner l'architecture d'un projet Laravel avec la structure de **production**, en s'assurant que:
- Les fichiers publics (`ajax/`, `css/`, `js/`, `rhassets/`, etc.) sont dans le **dossier parent**, hors du repo git
- Le dossier `public/` n'existe **pas** dans le repo (sauf s'il est à la racine du site/)
- Toutes les branches suivent la **même structure architecturale**

---

## 📊 Structure de Production vs Repo Git

### Structure PRODUCTION (réelle)
```
/var/www/clients/client3/web5/web/        ← Racine web du serveur
├── .htaccess                              ← Fichier de config
├── index.php                              ← Point d'entrée
├── ajax/                                  ← Dossiers publics
├── css/                                   ← (hors du repo)
├── js/
├── insert/
├── produit/
├── rhassets/                              ← Assets (21M+)
├── erhjs/
├── stats/
├── favicon.ico, robots.txt                ← Fichiers publics
├── web.config                             ← Config IIS (à supprimer)
└── site/                                  ← LE REPO GIT
    ├── app/
    ├── config/
    ├── bootstrap/
    ├── .git/                              ← Historique git
    └── ... (Laravel)
```

### Structure REPO GIT (correct)
```
site/                                      ← Root du repo
├── app/                    ✅ GARDER
├── bootstrap/              ✅ GARDER
├── config/                 ✅ GARDER
├── database/               ✅ GARDER
├── resources/              ✅ GARDER
├── routes/                 ✅ GARDER
├── storage/                ✅ GARDER
├── tests/                  ✅ GARDER
├── vendor/                 ✅ GARDER (mais dans .gitignore)
├── node_modules/           ✅ GARDER (mais dans .gitignore)
├── .env                    ✅ GARDER (mais dans .gitignore)
├── .gitignore
├── composer.json
├── package.json
└── ... (fichiers Laravel)

❌ public/                 À SUPPRIMER!
```

---

## ✅ PRÉREQUIS

Avant de commencer, vérifie que tu as:

```bash
# 1. Git installé et configuré
git --version
git config --global user.name
git config --global user.email

# 2. Access au repository
cd /chemin/vers/ton/projet/site
git status

# 3. Access à Azure DevOps ET GitHub (optionnel, pour le push)
# - Token/credentials préparés
# - Remotes configurés
```

---

## 🔍 ÉTAPE 1: DIAGNOSTIC

### Identifier le problème

```bash
# Va dans le répertoire du projet
cd /chemin/vers/ton/projet/site

# Vérifier si le dossier public/ existe
ls -la | grep public

# Voir ce qu'il contient
du -sh public/
find public -type f | wc -l
find public -type d | wc -l
```

### Exemples de problèmes

**Problème A: Dossier `public/` avec beaucoup de fichiers (21M+)**
```bash
du -sh public/
# 21M	public/
```
✅ **Solution:** Supprimer complètement le dossier

**Problème B: Dossier `public/` avec juste `index.php`**
```bash
find public -type f
# public/index.php
```
✅ **Solution:** Supprimer le dossier

**Problème C: Dossier `public/` avec fichiers config (`.htaccess`, `web.config`)**
```bash
ls -la public/
# .htaccess
# web.config
# index.php
# favicon.ico
```
✅ **Solution:** Supprimer le dossier

---

## 🔧 ÉTAPE 2: CORRECTION (PAR BRANCHE)

### Étape 2.1 - Lister toutes les branches

```bash
git branch -a
# * feature/ats-recrutement
#   feature/laravel-11-upgrade
#   laravel-9upgrade
#   main
```

### Étape 2.2 - Identifier la branche de RÉFÉRENCE

La branche de **référence** est celle qui a l'architecture correcte (sans dossier `public/`).

```bash
# Comparer avec la branche de référence
git checkout laravel-9upgrade
ls -la | grep public
# ✅ Pas de dossier public/ = c'est la référence!
```

### Étape 2.3 - Corriger chaque autre branche

**Pour chaque branche qui a un dossier `public/` :**

```bash
# 1️⃣ Basculer sur la branche
git checkout [NOM_DE_LA_BRANCHE]

# 2️⃣ Vérifier qu'il y a un dossier public/
ls -la | grep public
# ✅ Si oui, continuer. Sinon, passer à la branche suivante.

# 3️⃣ Supprimer le dossier public/
git rm -r public/

# 4️⃣ Créer un commit
git commit -m "$(cat <<'EOF'
Remove public/ directory - align with production structure

La structure de production place les fichiers publics dans le dossier parent, hors du repo.
Le dossier public/ ne doit pas être trackés par git.

Co-Authored-By: Claude Haiku 4.5 <noreply@anthropic.com>
EOF
)"

# 5️⃣ Vérifier que c'est bon
git log --oneline -3
git status
```

### Exemple complet

```bash
# Corriger la branche 'main'
git checkout main
git rm -r public/
git commit -m "Remove public/ directory - align with production structure"

# Corriger la branche 'feature/ats-recrutement'
git checkout feature/ats-recrutement
git rm -r public/
git commit -m "Remove public/ directory - align with production structure"

# Corriger la branche 'feature/laravel-11-upgrade'
git checkout feature/laravel-11-upgrade
git rm -r public/
git commit -m "Remove public/ directory - align with production structure"
```

---

## ✅ ÉTAPE 3: VALIDATION

### Vérifier que toutes les branches sont correctes

```bash
# Pour chaque branche, vérifier qu'il n'y a plus de dossier public/
for branch in $(git branch | tr -d ' *'); do
  echo "=== $branch ==="
  git checkout $branch 2>/dev/null
  [ -d public ] && echo "❌ Dossier public/ existe" || echo "✅ Pas de public/"
done
```

### Vérifier la structure

```bash
git checkout main
ls -la | grep -E "^d" | awk '{print $NF}'
# Résultat attendu:
# .
# ..
# .claude
# .git
# .idea
# .vscode
# app
# bootstrap
# config
# database
# node_modules
# resources
# routes
# scripts-launcher
# storage
# tests
# vendor
```

### Vérifier les commits

```bash
git log --oneline -5
# Devrait montrer le commit "Remove public/ directory..."
```

---

## 🚀 ÉTAPE 4: POUSSER LES CHANGEMENTS

### 4.1 - Azure DevOps

**Prérequis:**
- Token PAT Azure DevOps valide
- Remote configuré

```bash
# Ajouter le remote (si pas encore fait)
git remote add origin https://dev.azure.com/[ORGANISATION]/[PROJET]/_git/[REPO]

# OU configurer si le remote existe
git remote set-url origin https://dev.azure.com/[ORGANISATION]/[PROJET]/_git/[REPO]

# Pousser toutes les branches
git push origin main
git push origin feature/ats-recrutement
git push origin feature/laravel-11-upgrade
git push origin laravel-9upgrade
```

### 4.2 - GitHub

**Prérequis:**
- Personal Access Token (PAT) GitHub valide
- Repository GitHub créé

```bash
# Ajouter le remote GitHub
git remote add github https://github.com/[USERNAME]/[REPO].git

# OU via HTTPS avec token
git remote add github https://[TOKEN]@github.com/[USERNAME]/[REPO].git

# Pousser toutes les branches
git push github main
git push github feature/ats-recrutement
git push github feature/laravel-11-upgrade
git push github laravel-9upgrade
```

### Commandes complètes (avec authentification)

```bash
# Azure DevOps (avec token)
TOKEN_AZURE="votre-token-ici"
git push https://:${TOKEN_AZURE}@dev.azure.com/[ORG]/[PROJ]/_git/[REPO] main

# GitHub (avec token)
TOKEN_GITHUB="ghp_xxxxx"
git push https://${TOKEN_GITHUB}@github.com/[USERNAME]/[REPO].git main
```

---

## 🔒 SÉCURITÉ - POINTS IMPORTANTS

### ⚠️ JAMAIS en clair

**NE FAIS PAS :**
```bash
# ❌ MAUVAIS - Token visible dans l'historique
git push https://ghp_xxxxxxxxxxxxx@github.com/user/repo.git

# ❌ MAUVAIS - Credentials dans les logs
echo "Token: ghp_xxxxxxxxxxxxx"
```

**FAIS ÇA À LA PLACE :**
```bash
# ✅ BON - Utiliser une variable d'environnement
export GIT_TOKEN="ghp_xxxxxxxxxxxxx"
git push https://${GIT_TOKEN}@github.com/user/repo.git

# ✅ BON - Utiliser git credential helper
git config --global credential.helper store
# (le token sera stocké de manière sécurisée)

# ✅ BON - Utiliser SSH keys
git remote set-url origin git@github.com:user/repo.git
```

### 🚨 Si tu as exposé un token

**IMMÉDIATEMENT :**
1. Va sur la plateforme (GitHub, Azure DevOps)
2. Révoque/supprime le token exposé
3. Crée un **nouveau token**
4. Ne le partage jamais en clair

---

## 📝 CHECKLIST DE COMPLETION

Avant de considérer le projet comme "complété", vérifie:

```bash
# ✅ Toutes les branches n'ont pas de dossier public/
for branch in $(git branch); do
  git checkout $branch 2>/dev/null
  [ ! -d public ] && echo "✅ $branch: OK" || echo "❌ $branch: ERREUR"
done

# ✅ Tous les commits sont créés
git log --oneline -10 | grep "Remove public"
# Devrait montrer au moins 3 commits "Remove public/"

# ✅ Les branches sont pushées
git branch -r
# Devrait montrer origin/main, origin/feature/*, etc.

# ✅ .gitignore est correct
cat .gitignore
# Devrait inclure: vendor/, node_modules/, .env, storage/logs/, etc.

# ✅ Pas de fichiers sensibles tracés
git ls-files | grep -E "\.env|credentials|secret|token"
# Devrait être vide
```

---

## 🐛 TROUBLESHOOTING

### Problème: "fatal: pathspec 'public' did not match any files"

**Cause:** Le dossier `public/` n'existe pas ou a déjà été supprimé

**Solution:**
```bash
# Vérifier si le dossier existe
ls -la | grep public

# Si pas, la branche est déjà correcte!
git status
```

---

### Problème: "error: unable to create file ... Filename too long"

**Cause:** Windows a une limite de 260 caractères pour les chemins

**Solution:**
```bash
# Sur Windows, configurer git pour les longs chemins
git config --global core.longpaths true

# Ensuite refaire la suppression
git rm -r public/
```

---

### Problème: "Authentication failed" lors du push

**Cause:** Token invalide, expiré, ou mal configuré

**Solution:**
```bash
# Vérifier le token avec curl
curl -H "Authorization: token YOUR_TOKEN" https://api.github.com/user

# Si erreur 401, créer un nouveau token et recommencer
```

---

### Problème: "Please make sure you have the correct access rights"

**Cause:** Permissions insuffisantes sur le repository

**Solution:**
```bash
# Vérifier que tu as accès au repository
git ls-remote origin
# Si erreur, vérifier les permissions sur GitHub/Azure DevOps
```

---

## 📊 TEMPLATE POUR AUTRES PROJETS

### Appliquer le même processus à un autre projet

```bash
# 1. Cloner le repository
git clone https://[URL]/ton-projet.git
cd ton-projet

# 2. Vérifier les branches avec le problème
git branch -a

# 3. Identifier la branche de référence (celle sans public/)
git checkout [BRANCHE_REFERENCE]
ls -la | grep public  # ✅ Pas de public/

# 4. Pour chaque autre branche
git checkout [BRANCHE]
if [ -d public ]; then
  git rm -r public/
  git commit -m "Remove public/ directory - align with production structure"
fi

# 5. Pousser
git push origin [BRANCHE]
```

---

## 📚 RESSOURCES

- **Git Documentation:** https://git-scm.com/doc
- **GitHub Docs:** https://docs.github.com
- **Azure DevOps:** https://dev.azure.com
- **Laravel Structure:** https://laravel.com/docs/structure

---

## 📞 BESOIN D'AIDE?

Si tu as des questions:
1. Vérifie la section **TROUBLESHOOTING**
2. Utilise `git status` et `git log` pour diagnostiquer
3. Teste les commandes sur une branche de test d'abord

---

**Version:** 1.0
**Dernière mise à jour:** Février 2026
**Prêt à être utilisé pour d'autres projets!** ✅
