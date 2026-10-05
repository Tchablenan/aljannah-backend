# Déployer le backend gratuitement (Render + Neon + Supabase Storage)

Le backend (API publique + back-office) tourne dans un conteneur Docker sur
**Render**, la base de données est un PostgreSQL gratuit chez **Neon**, et les
images envoyées depuis le back-office sont stockées dans un bucket S3
(**Supabase Storage**, ou tout autre fournisseur compatible S3).

> Plan gratuit Render : le service s'endort après 15 min sans visite ; la
> visite suivante attend ~50 s le réveil. Le site public patiente jusqu'à 60 s.

## 1. Base de données (Neon)

1. Créer un compte sur <https://neon.tech> puis un projet (région Europe).
2. Copier la *connection string* (`postgresql://…?sslmode=require`).

## 2. Stockage des images (Supabase Storage)

1. Dans un projet Supabase : **Storage > New bucket**, nom `aljannah`, cocher *Public bucket*.
2. **Storage > Settings > S3 Connection** : noter l'*Endpoint* et la *Region*,
   puis **New access key** : noter l'*Access key ID* et le *Secret access key*.
3. Valeurs pour Render :
   - `AWS_ENDPOINT` = `https://<ref>.supabase.co/storage/v1/s3`
   - `AWS_DEFAULT_REGION` = la région affichée (ex. `eu-west-3`)
   - `AWS_BUCKET` = `aljannah`
   - `AWS_URL` = `https://<ref>.supabase.co/storage/v1/object/public/aljannah`
   - `AWS_ACCESS_KEY_ID` / `AWS_SECRET_ACCESS_KEY` = la clé créée

Sans stockage S3, mettre `PUBLIC_DISK_DRIVER=local` : tout fonctionne, mais
les images envoyées sont perdues à chaque redéploiement ou redémarrage.

## 3. Backend (Render)

1. Créer un compte sur <https://render.com> avec GitHub.
2. **New > Blueprint**, choisir le dépôt `aljannah-backend` : Render lit `render.yaml`.
3. Renseigner les variables demandées :
   - `DATABASE_URL` : l'URL Neon de l'étape 1
   - `APP_URL` : l'adresse du service, ex. `https://aljannah-backend.onrender.com`
   - `ADMIN_EMAIL` / `ADMIN_PASSWORD` : le compte du back-office (mot de passe fort)
   - `CORS_ALLOWED_ORIGINS` : l'adresse du site public si elle n'est pas
     déjà dans `config/cors.php` (Vercel et aljannahjet.com y sont déjà)
   - les variables `AWS_*` de l'étape 2
4. Pour le **premier** déploiement, passer `SEED_DEMO_JETS` à `true` (jets
   d'exemple), puis le remettre à `false`.
5. Vérifier : `https://<service>.onrender.com/api/jets` renvoie du JSON, et
   `https://<service>.onrender.com/login` ouvre le back-office.

À chaque démarrage, le conteneur applique les migrations et met à jour le
compte admin à partir de `ADMIN_EMAIL` / `ADMIN_PASSWORD`.

## 4. Site public (Vercel)

Dans le projet Vercel du frontend : **Settings > Environment Variables**,
ajouter `VITE_API_URL` = `https://<service>.onrender.com/api`, puis redéployer.

## Notes

- L'inscription publique (`/register`) est fermée ; seuls les comptes
  `is_admin` accèdent au back-office.
- Emails de confirmation : par défaut `MAIL_MAILER=log` (aucun envoi). Pour
  envoyer de vrais emails, configurer un SMTP (ex. Brevo, gratuit) avec
  `MAIL_MAILER=smtp`, `MAIL_HOST`, `MAIL_PORT`, `MAIL_USERNAME`,
  `MAIL_PASSWORD`, `MAIL_FROM_ADDRESS`.
- Le code fonctionne aussi avec MySQL (`DB_CONNECTION=mysql`).
