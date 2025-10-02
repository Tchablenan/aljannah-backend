<?php

// create_luxury_packages_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('luxury_packages', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->text('description');
            $table->enum('type', ['predefinit', 'personnalise'])->default('predefinit');
            
            // Services inclus dans le package
            $table->json('services_inclus'); // Structure: [{"service_id": 1, "quantite": 1, "options": {...}}, ...]
            
            // Prix et personnalisation
            $table->decimal('prix_total', 12, 2)->nullable();
            $table->decimal('prix_estime', 12, 2)->nullable(); // Pour packages personnalisés
            $table->json('personnalisations')->nullable(); // Options choisies par le client
            
            // Informations générales
            $table->string('duree')->nullable(); // "3 jours", "weekend", "semaine"
            $table->integer('nombre_personnes')->default(1);
            $table->string('destination')->nullable(); // Destination principale
            
            // Statut et gestion
            $table->boolean('actif')->default(true);
            $table->boolean('visible_public')->default(true); // Affiché sur le site
            $table->integer('popularite')->default(0); // Score de popularité
            
            // Images et médias
            $table->string('image_principale')->nullable();
            $table->json('galerie_images')->nullable();
            
            // Pour les packages personnalisés
            $table->string('client_email')->nullable(); // Si package créé pour un client spécifique
            $table->datetime('date_expiration')->nullable(); // Validité du devis
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('luxury_packages');
    }
};