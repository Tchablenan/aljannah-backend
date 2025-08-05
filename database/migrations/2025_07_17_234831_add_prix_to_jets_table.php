<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up(): void
{
    Schema::table('jets', function (Blueprint $table) {
        $table->decimal('prix', 10, 2)->default(0)->after('capacite'); // ou après 'image' selon ta logique
    });
}

public function down(): void
{
    Schema::table('jets', function (Blueprint $table) {
        $table->dropColumn('prix');
    });
}

};
