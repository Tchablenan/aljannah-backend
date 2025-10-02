<?php

// create_luxury_package_requests_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('luxury_package_requests', function (Blueprint $table) {
            $table->id();
            
            // Lien avec le package (prédéfini ou personnalisé)
            $table->foreignId('luxury_package_id')->nullable()->constrained('luxury_packages')->onDelete('set null');
            
            // Informations client
            $table->string('client_prenom');
            $table->string('client_nom');
            $table->string('client_email');
            $table->string('client_telephone')->nullable();
            $table->json('preferences_client')->nullable(); // Allergies, préférences spéciales
            
            // Détails de la demande
            $table->string('titre_demande');
            $table->text('description_demande');
            $table->json('services_souhaites'); // Services sélectionnés avec quantités et options
            $table->json('personnalisations_demandees')->nullable(); // Modifications spécifiques
            
            // Dates et destination
            $table->datetime('date_debut_souhaitee');
            $table->datetime('date_fin_souhaitee')->nullable();
            $table->string('destination_principale');
            $table->json('destinations_multiples')->nullable(); // Pour voyages multi-villes
            $table->integer('nombre_personnes');
            
            // Budget et pricing
            $table->decimal('budget_estime', 12, 2)->nullable();
            $table->decimal('prix_propose', 12, 2)->nullable();
            $table->decimal('prix_final', 12, 2)->nullable();
            
            // Statut et workflow
            $table->enum('statut', [
                'nouvelle',          // Demande reçue
                'en_analyse',        // En cours d'étude
                'devis_envoye',     // Devis envoyé au client
                'en_negociation',   // Négociation en cours
                'confirme',         // Confirmé par le client
                'en_preparation',   // Préparation en cours
                'en_cours',         // Services en cours d'exécution
                'termine',          // Terminé avec succès
                'annule'            // Annulé
            ])->default('nouvelle');
            
            $table->enum('priorite', ['normale', 'urgente', 'vip'])->default('normale');
            
            // Gestion interne
            $table->string('concierge_assigne')->nullable();
            $table->text('notes_internes')->nullable();
            $table->datetime('date_confirmation')->nullable();
            $table->datetime('date_expiration_devis')->nullable();
            
            // Référence unique
            $table->string('reference')->unique(); // REF-LUX-000001
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('luxury_package_requests');
    }
};