<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jets', function (Blueprint $table) {
            $table->integer('autonomie_km')->nullable()->after('localisation');
        });
    }

    public function down(): void
    {
        Schema::table('jets', function (Blueprint $table) {
            $table->dropColumn('autonomie_km');
        });
    }
};