#!/bin/sh
# Démarrage du conteneur : port, caches, migrations, super admin.
set -e

PORT="${PORT:-10000}"
sed -ri "s/^Listen .*/Listen ${PORT}/" /etc/apache2/ports.conf
sed -ri "s/<VirtualHost \*:[0-9]+>/<VirtualHost *:${PORT}>/" /etc/apache2/sites-available/000-default.conf

cd /var/www/html

# Render peut générer APP_KEY (base64 de 32 octets) sans le préfixe attendu par Laravel
case "${APP_KEY:-}" in
    base64:*) ;;
    "") echo "APP_KEY manquant : définissez-le dans les variables d'environnement." >&2; exit 1 ;;
    *) export APP_KEY="base64:${APP_KEY}" ;;
esac

# Les variables d'environnement de l'hébergeur remplacent le fichier .env
php artisan config:cache
php artisan route:cache
php artisan view:cache

if [ "${RUN_MIGRATIONS:-true}" = "true" ]; then
    php artisan migrate --force
fi

# Crée/met à jour le compte admin à partir de ADMIN_EMAIL et ADMIN_PASSWORD
php artisan db:seed --class=SuperAdminSeeder --force

# Jets d'exemple (une seule fois, par exemple au premier déploiement)
if [ "${SEED_DEMO_JETS:-false}" = "true" ]; then
    php artisan db:seed --class=JetSeeder --force
fi

# Stockage local uniquement : lien public/storage
if [ "${PUBLIC_DISK_DRIVER:-local}" != "s3" ]; then
    php artisan storage:link 2>/dev/null || true
fi

chown -R www-data:www-data storage bootstrap/cache

exec "$@"
