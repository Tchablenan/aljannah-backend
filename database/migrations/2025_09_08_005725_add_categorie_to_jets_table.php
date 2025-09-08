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
            $table->string('categorie')->nullable()->after('disponible');
        });
    }

    public function down(): void
    {
        Schema::table('jets', function (Blueprint $table) {
            $table->dropColumn('categorie');
        });
    }

};
