<?php

return [

    /*
     |--------------------------------------------------------------------------
     | Cross-Origin Resource Sharing (CORS) Configuration
     |--------------------------------------------------------------------------
     |
     | Here you may configure your settings for cross-origin resource sharing
     | or "CORS". This determines what cross-origin operations may execute
     | in web browsers. You are free to adjust these settings as needed.
     |
     | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
     |
     */

    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    // Origines du site public. Ajoutez-en via CORS_ALLOWED_ORIGINS dans .env
    // (liste séparée par des virgules), sans redéployer le code.
    'allowed_origins' => array_values(array_filter(array_merge(
        [
            'http://localhost:5174',
            'http://127.0.0.1:5174',
            'https://aljannahjet.com',
            'https://www.aljannahjet.com',
            'https://projet-aljannah.vercel.app',
        ],
        array_map('trim', explode(',', (string) env('CORS_ALLOWED_ORIGINS', '')))
    ))),

    // Déploiements de prévisualisation Vercel du projet (projet-aljannah-xxx.vercel.app)
    'allowed_origins_patterns' => [
        '#^https://projet-aljannah(-[a-z0-9-]+)?\.vercel\.app$#',
    ],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true,

];