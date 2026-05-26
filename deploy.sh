#!/bin/bash

# ==============================================================================
# SCRIPT DE DÉPLOIEMENT AUTOMATIQUE - AL JANNAH JET
# ==============================================================================
# Ce script permet de déployer l'application Laravel sur le serveur Hostinger.
# Il effectue la connexion SSH, récupère le code, installe les dépendances,
# exécute les migrations et met à jour le seeder administrateur.
# ==============================================================================

# --- CONFIGURATION ---
SSH_HOST="82.29.186.100"
SSH_PORT="65002"
SSH_USER="u800484123"
BRANCH="main"

# Dossier du projet sur le serveur (sera détecté automatiquement s'il est vide)
REMOTE_DIR="~/domains/aljannahjet.com/laravel_app"

# --- COULEURS ---
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color
BOLD='\033[1m'

echo -e "${BLUE}${BOLD}====================================================${NC}"
echo -e "${BLUE}${BOLD}       DÉPLOIEMENT AL JANNAH JET - BACKEND          ${NC}"
echo -e "${BLUE}${BOLD}====================================================${NC}"

# 1. Vérification de la clé SSH locale
SSH_KEY_FILE="$HOME/.ssh/id_ed25519"
if [ ! -f "$SSH_KEY_FILE" ]; then
    echo -e "${YELLOW}[!] Clé SSH non trouvée. Génération d'une nouvelle clé...${NC}"
    ssh-keygen -t ed25519 -N "" -f "$SSH_KEY_FILE"
fi

PUB_KEY=$(cat "${SSH_KEY_FILE}.pub")

# 2. Test de la connexion SSH
echo -e "\n${BLUE}[*] Test de la connexion SSH vers ${SSH_USER}@${SSH_HOST}:${SSH_PORT}...${NC}"
ssh -p "$SSH_PORT" -o BatchMode=yes -o ConnectTimeout=5 "${SSH_USER}@${SSH_HOST}" "echo 'SSH_OK'" &>/dev/null

if [ $? -ne 0 ]; then
    echo -e "${RED}[X] Erreur : Connexion SSH sans mot de passe impossible.${NC}"
    echo -e "${YELLOW}Veuillez copier la clé publique suivante et l'ajouter dans votre panel Hostinger :${NC}"
    echo -e "\n${BOLD}${PUB_KEY}${NC}\n"
    echo -e "${YELLOW}Panel Hostinger -> Paramètres Avancés -> Accès SSH -> Ajouter une clé SSH.${NC}"
    echo -e "Une fois ajoutée, relancez ce script.\n"
    
    read -p "Souhaitez-vous tenter une connexion avec mot de passe ? (y/n) : " -n 1 -r
    echo
    if [[ ! $REPLY =~ ^[Yy]$ ]]; then
        echo -e "${RED}Déploiement annulé.${NC}"
        exit 1
    fi
fi

# 3. Détection ou définition du dossier distant
if [ -z "$REMOTE_DIR" ]; then
    echo -e "${BLUE}[*] Recherche du dossier du projet sur le serveur distant...${NC}"
    # Recherche courante sur Hostinger
    POSSIBLE_DIRS=(
        "domains/aljannahjet.com/public_html"
        "public_html"
        "aljannah-backend"
        "domains/aljannahjet.com/public_html/aljannah-backend"
    )
    
    for dir in "${POSSIBLE_DIRS[@]}"; do
        echo -e "Vérification de ~/$dir..."
        ssh -p "$SSH_PORT" "${SSH_USER}@${SSH_HOST}" "[ -f ~/$dir/artisan ]" &>/dev/null
        if [ $? -eq 0 ]; then
            REMOTE_DIR="~/$dir"
            echo -e "${GREEN}[V] Projet trouvé dans : $REMOTE_DIR${NC}"
            break
        fi
    done
    
    if [ -z "$REMOTE_DIR" ]; then
        echo -e "${YELLOW}[!] Impossible de détecter automatiquement le dossier du projet.${NC}"
        read -p "Veuillez entrer le chemin absolu du projet sur le serveur (ex: domains/aljannahjet.com/public_html) : " USER_DIR
        REMOTE_DIR="~/$USER_DIR"
    fi
fi

# 4. Déploiement des modifications locales vers Git
echo -e "\n${BLUE}[*] Préparation des modifications locales...${NC}"
git status --porcelain | grep -v "deploy.sh" | grep -q "."
if [ $? -eq 0 ]; then
    echo -e "${YELLOW}[!] Changements locaux détectés. Envoi sur GitHub...${NC}"
    git add .
    git commit -m "feat: update login form with password visibility toggle and updates"
    git push origin "$BRANCH"
    if [ $? -ne 0 ]; then
        echo -e "${RED}[X] Échec du push vers GitHub. Assurez-vous d'avoir configuré vos accès Git.${NC}"
        exit 1
    fi
    echo -e "${GREEN}[V] Modifications envoyées avec succès sur GitHub !${NC}"
else
    echo -e "${GREEN}[V] Aucun changement local en attente (déjà validé).${NC}"
fi

# 5. Exécution des commandes sur le serveur distant
echo -e "\n${BLUE}[*] Connexion SSH au serveur et exécution du déploiement...${NC}"

ssh -p "$SSH_PORT" "${SSH_USER}@${SSH_HOST}" << EOF
    set -e
    echo -e "${BLUE}=== ÉTAPE 1 : Accès au dossier du projet ===${NC}"
    cd $REMOTE_DIR
    pwd

    echo -e "${BLUE}=== ÉTAPE 2 : Récupération des dernières modifications (Git Pull) ===${NC}"
    # Si git utilise HTTPS avec mot de passe, l'utilisateur devra peut-être le saisir ou utiliser un token.
    git pull origin $BRANCH

    echo -e "${BLUE}=== ÉTAPE 3 : Installation des dépendances Composer ===${NC}"
    # Hostinger utilise parfois un chemin spécifique pour Composer ou PHP. On vérifie d'abord.
    if command -v composer &> /dev/null; then
        composer install --no-dev --optimize-autoloader --no-interaction
    elif [ -f composer.phar ]; then
        php composer.phar install --no-dev --optimize-autoloader --no-interaction
    else
        echo -e "${YELLOW}[!] Avertissement : Composer n'est pas installé globalement. Tentative de téléchargement local...${NC}"
        curl -sS https://getcomposer.org/installer | php
        php composer.phar install --no-dev --optimize-autoloader --no-interaction
    fi

    echo -e "${BLUE}=== ÉTAPE 4 : Exécution des migrations de base de données ===${NC}"
    php artisan migrate --force

    echo -e "${BLUE}=== ÉTAPE 5 : Mise à jour du Super Admin (Seeding) ===${NC}"
    php artisan db:seed --class=SuperAdminSeeder --force

    echo -e "${BLUE}=== ÉTAPE 6 : Optimisation du cache de l'application ===${NC}"
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache

    echo -e "${GREEN}=== DÉPLOIEMENT REUSSI SUR LE SERVEUR DISTANT ! ===${NC}"
EOF

if [ $? -eq 0 ]; then
    echo -e "\n${GREEN}${BOLD}====================================================${NC}"
    echo -e "${GREEN}${BOLD}      DÉPLOIEMENT EFFECTUÉ AVEC SUCCÈS ! 🎉          ${NC}"
    echo -e "${GREEN}${BOLD}====================================================${NC}"
else
    echo -e "\n${RED}${BOLD}====================================================${NC}"
    echo -e "${RED}${BOLD}      ERREUR LORS DU DÉPLOIEMENT SUR LE SERVEUR      ${NC}"
    echo -e "${RED}${BOLD}====================================================${NC}"
    exit 1
fi
