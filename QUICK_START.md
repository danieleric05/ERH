# ⚡ QUICK START - Correction d'Architecture

**Pour les utilisateurs pressés** - Commandes rapides pour répéter le processus!

---

## 🚀 En 5 minutes

```bash
# 1. Aller dans le repo
cd /chemin/vers/ton/projet/site

# 2. Lancer le script (c'est tout!)
./fix_architecture.sh

# 3. Vérifier que ça a marché
git log --oneline -5

# 4. Pousser
git push origin --all
```

---

## 📋 Avec le script bash

### Options disponibles

```bash
# Corriger les branches avec le problème
./fix_architecture.sh

# Corriger TOUTES les branches
./fix_architecture.sh --all

# Corriger et pousser automatiquement
./fix_architecture.sh --push

# Juste valider (pas de modifications)
./fix_architecture.sh --validate

# Afficher l'aide
./fix_architecture.sh --help
```

---

## 🔧 MANUEL (sans script)

Si tu préfères faire manuellement:

```bash
# Pour chaque branche avec le problème:

# 1. Basculer
git checkout [BRANCHE]

# 2. Vérifier qu'il y a public/
ls -la | grep public

# 3. Supprimer
git rm -r public/

# 4. Commit
git commit -m "Remove public/ directory - align with production structure"

# 5. Repéter pour chaque branche, puis pousser
git push origin [BRANCHE]
```

---

## ✅ Checklist

Avant de considérer c'est fini:

- [ ] Script exécuté sans erreur
- [ ] `git log` montre les commits "Remove public/"
- [ ] `./fix_architecture.sh --validate` passe ✅
- [ ] Branches pushées vers origin/github

---

## 🐛 Erreurs courantes

| Erreur | Cause | Solution |
|--------|-------|----------|
| "Pas de repo git" | Mauvais répertoire | `cd /chemin/vers/site/` |
| "pathspec 'public' did not match" | Déjà supprimé | C'est normal, continuer |
| "fatal: authentication failed" | Token invalide | Créer un nouveau token |
| "Permission denied" | Script pas exécutable | `chmod +x fix_architecture.sh` |

---

## 📞 Plus de détails?

Voir: `GUIDE_CORRECTION_ARCHITECTURE.md`

---

**Prêt?** Lance le script! 🚀
