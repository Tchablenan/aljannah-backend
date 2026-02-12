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
            // Détails fiscaux (GRA Compliance)
            $table->decimal('base_price', 12, 2)->default(0.00)->after('message');
            $table->decimal('nhil_amount', 12, 2)->default(0.00)->after('base_price');
            $table->decimal('getfund_amount', 12, 2)->default(0.00)->after('nhil_amount');
            $table->decimal('covid_levy_amount', 12, 2)->default(0.00)->after('getfund_amount');
            $table->decimal('vat_amount', 12, 2)->default(0.00)->after('covid_levy_amount');
            $table->decimal('total_taxes_amount', 12, 2)->default(0.00)->after('vat_amount');
            $table->decimal('total_amount_with_taxes', 12, 2)->default(0.00)->after('total_taxes_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropColumn([
                'base_price',
                'nhil_amount',
                'getfund_amount',
                'covid_levy_amount',
                'vat_amount',
                'total_taxes_amount',
                'total_amount_with_taxes'
            ]);
        });
    }
};