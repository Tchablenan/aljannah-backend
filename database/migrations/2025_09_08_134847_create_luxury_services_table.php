<?php

// create_luxury_services_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('luxury_services', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->enum('categorie', [
                'transport_luxe',    // Limousines, voitures de prestige
                'hebergement',       // Hôtels 5*, villas privées
                'restauration',      // Restaurants étoilés, chefs privés
                'divertissement',    // Événements, spectacles, clubs VIP
                'shopping',          // Shopping personnel, stylisme
                'bien_etre',        // Spas, massages, soins
                'business',         // Salles de réunion, bureaux temporaires
                'famille',          // Services enfants, nounous
                'securite',         // Gardes du corps, escortes
                'autre'
            ]);
            $table->text('description');
            $table->decimal('prix_base', 10, 2)->nullable();
            $table->enum('type_prix', ['fixe', 'heure', 'jour', 'forfait', 'sur_devis'])->default('sur_devis');
            $table->json('options_disponibles')->nullable(); // Options configurables
            $table->string('fournisseur')->nullable(); // Partenaire qui fournit le service
            $table->string('contact_fournisseur')->nullable();
            $table->boolean('actif')->default(true);
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('luxury_services');
    }
};