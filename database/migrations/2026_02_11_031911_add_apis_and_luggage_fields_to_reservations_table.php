<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            // Données APIS (Advanced Passenger Information System) - Normes GCAA Ghana
            $table->string('passport_number')->nullable()->after('last_name');
            $table->date('passport_expiry')->nullable()->after('passport_number');
            $table->date('date_of_birth')->nullable()->after('passport_expiry');
            $table->string('nationality')->nullable()->after('date_of_birth');

            // Données Bagages (Mass & Balance)
            $table->integer('luggage_count')->default(0)->after('passengers');
            $table->decimal('luggage_weight_kg', 8, 2)->default(0.00)->after('luggage_count');

            // Sécurité Act 843 Ghana (Consentement)
            $table->boolean('data_protection_consent')->default(false)->after('message');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropColumn([
                'passport_number',
                'passport_expiry',
                'date_of_birth',
                'nationality',
                'luggage_count',
                'luggage_weight_kg',
                'data_protection_consent'
            ]);
        });
    }
};