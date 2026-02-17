#!/bin/bash

################################################################################
#
# Script de correction d'architecture - Suppression du dossier public/
#
# Usage:
#   ./fix_architecture.sh
#   ./fix_architecture.sh [BRANCHE]
#   ./fix_architecture.sh --all
#   ./fix_architecture.sh --push
#
# Description:
#   Supprime le dossier public/ de chaque branche et crée un commit.
#   Aligne l'architecture du projet avec la structure de production.
#
# Auteur: Claude Code
# Date: Février 2026
#
################################################################################

set -e

# Couleurs pour l'output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Variables
SCRIPT_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
REPO_DIR="${SCRIPT_DIR}"
BRANCHES=()
PUSH_AFTER=0
ALL_BRANCHES=0
REFERENCE_BRANCH="laravel-9upgrade"

################################################################################
# Fonctions
################################################################################

print_header() {
  echo -e "\n${BLUE}================================================${NC}"
  echo -e "${BLUE}$1${NC}"
  echo -e "${BLUE}================================================${NC}\n"
}

print_success() {
  echo -e "${GREEN}✅ $1${NC}"
}

print_error() {
  echo -e "${RED}❌ $1${NC}"
}

print_warning() {
  echo -e "${YELLOW}⚠️  $1${NC}"
}

print_info() {
  echo -e "${BLUE}ℹ️  $1${NC}"
}

# Vérifier que nous sommes dans un repo git
check_git_repo() {
  if [ ! -d "$REPO_DIR/.git" ]; then
    print_error "Pas de repo git trouvé dans $REPO_DIR"
    exit 1
  fi
  print_success "Repo git détecté"
}

# Lister toutes les branches
list_branches() {
  cd "$REPO_DIR"
  git branch -a | tr -d ' *' | grep -v HEAD | sort | uniq
}

# Vérifier si une branche a un dossier public/
has_public_folder() {
  local branch=$1
  cd "$REPO_DIR"
  git checkout "$branch" >/dev/null 2>&1
  [ -d "public" ] && return 0 || return 1
}

# Supprimer le dossier public/ d'une branche
remove_public_folder() {
  local branch=$1

  print_info "Traitement de la branche: $branch"

  cd "$REPO_DIR"
  git checkout "$branch" >/dev/null 2>&1

  # Vérifier si public/ existe
  if [ ! -d "public" ]; then
    print_warning "Dossier public/ n'existe pas sur $branch"
    return 1
  fi

  # Compter les fichiers
  local file_count=$(find public -type f | wc -l)
  local dir_count=$(find public -type d | wc -l)

  print_info "  Fichiers à supprimer: $file_count"
  print_info "  Dossiers à supprimer: $dir_count"

  # Supprimer le dossier
  git rm -r public/ >/dev/null 2>&1

  # Créer le commit
  local commit_msg="Remove public/ directory - align with production structure

La structure de production place les fichiers publics dans le dossier parent, hors du repo.
Le dossier public/ ne doit pas être trackés par git.

Co-Authored-By: Claude Haiku 4.5 <noreply@anthropic.com>"

  git commit -m "$commit_msg" >/dev/null 2>&1

  local commit_hash=$(git rev-parse --short HEAD)
  print_success "Branche $branch corrigée (commit: $commit_hash)"

  return 0
}

# Valider que toutes les branches sont correctes
validate_all() {
  print_header "Validation"

  local errors=0
  local all_branches=$(list_branches)

  while IFS= read -r branch; do
    cd "$REPO_DIR"
    git checkout "$branch" >/dev/null 2>&1

    if [ -d "public" ]; then
      print_error "$branch: Dossier public/ existe encore!"
      errors=$((errors + 1))
    else
      print_success "$branch: OK (pas de public/)"
    fi
  done <<< "$all_branches"

  if [ $errors -eq 0 ]; then
    print_success "✨ Toutes les branches sont correctes!"
    return 0
  else
    print_error "❌ $errors branche(s) ont des erreurs"
    return 1
  fi
}

# Pousser les branches vers les remotes
push_branches() {
  print_header "Push vers les remotes"

  local all_branches=$(list_branches)

  while IFS= read -r branch; do
    if [ ! -z "$branch" ]; then
      print_info "Pushing $branch..."
      cd "$REPO_DIR"

      # Pousser vers origin (si configuré)
      if git remote | grep -q "origin"; then
        git push origin "$branch" --quiet 2>/dev/null && \
          print_success "  $branch pushé vers origin" || \
          print_warning "  Erreur lors du push vers origin"
      fi

      # Pousser vers github (si configuré)
      if git remote | grep -q "github"; then
        git push github "$branch" --quiet 2>/dev/null && \
          print_success "  $branch pushé vers github" || \
          print_warning "  Erreur lors du push vers github"
      fi
    fi
  done <<< "$all_branches"
}

# Afficher l'aide
show_help() {
  cat << EOF

${BLUE}USAGE:${NC}
  $0 [OPTIONS]

${BLUE}OPTIONS:${NC}
  (vide)       Traiter les branches avec le problème
  --all        Traiter TOUTES les branches
  --push       Pousser après correction
  --validate   Juste valider, pas de correction
  --help       Afficher cette aide

${BLUE}EXEMPLES:${NC}
  # Corriger toutes les branches avec public/
  $0

  # Corriger et pousser
  $0 --push

  # Corriger toutes les branches
  $0 --all

  # Juste valider
  $0 --validate

${BLUE}NOTES:${NC}
  - Les commits sont créés automatiquement
  - Un token git peut être nécessaire pour le push
  - Voir GUIDE_CORRECTION_ARCHITECTURE.md pour plus de détails

EOF
}

################################################################################
# Main
################################################################################

main() {
  print_header "Script de correction d'architecture ERH"

  # Parser les arguments
  while [[ $# -gt 0 ]]; do
    case $1 in
      --all)
        ALL_BRANCHES=1
        shift
        ;;
      --push)
        PUSH_AFTER=1
        shift
        ;;
      --validate)
        validate_all
        exit 0
        ;;
      --help|-h)
        show_help
        exit 0
        ;;
      *)
        print_error "Argument inconnu: $1"
        show_help
        exit 1
        ;;
    esac
  done

  # Vérifier git
  check_git_repo

  # Récupérer les branches
  print_info "Récupération des branches..."

  local all_branches=$(list_branches)
  local branches_to_fix=()

  if [ $ALL_BRANCHES -eq 1 ]; then
    branches_to_fix=($all_branches)
    print_info "Mode: corriger TOUTES les branches"
  else
    # Seulement les branches avec le problème
    while IFS= read -r branch; do
      if has_public_folder "$branch"; then
        branches_to_fix+=("$branch")
      fi
    done <<< "$all_branches"
    print_info "Mode: corriger branches avec public/"
  fi

  if [ ${#branches_to_fix[@]} -eq 0 ]; then
    print_warning "Aucune branche à corriger!"
    exit 0
  fi

  print_info "Branches à traiter: ${#branches_to_fix[@]}"

  # Traiter chaque branche
  print_header "Correction des branches"

  for branch in "${branches_to_fix[@]}"; do
    if remove_public_folder "$branch"; then
      :  # Succès, continuer
    else
      print_warning "Pas de public/ à supprimer sur $branch"
    fi
  done

  # Validation
  validate_all || exit 1

  # Pousser si demandé
  if [ $PUSH_AFTER -eq 1 ]; then
    push_branches
  fi

  print_header "✨ Terminé!"
  print_success "L'architecture a été corrigée avec succès"

  if [ $PUSH_AFTER -eq 0 ]; then
    print_info "Pour pousser les branches, exécute: git push origin --all"
  fi
}

# Lancer le script
main "$@"
