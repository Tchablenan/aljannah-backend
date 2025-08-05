<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::create('jets', function (Blueprint $table) {
        $table->id();
        $table->string('nom');
        $table->string('modele')->nullable();
        $table->integer('capacite')->default(1);
        $table->string('image')->nullable(); // chemin de l'image
        $table->text('description')->nullable();
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jets');
    }
};
