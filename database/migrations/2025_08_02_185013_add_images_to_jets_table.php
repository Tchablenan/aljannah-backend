<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('jets', function (Blueprint $table) {
            $table->json('images')->nullable(); // Stocker plusieurs images sous forme de tableau JSON
        });
    }

    public function down()
    {
        Schema::table('jets', function (Blueprint $table) {
            $table->dropColumn('images');
        });
    }
};
