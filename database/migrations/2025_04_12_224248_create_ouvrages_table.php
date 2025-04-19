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
        Schema::create('ouvrages', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->integer('annee_publication');
            $table->string('niveau');
            $table->string('photo');
            $table->string('description');
            $table->foreignId('categorie_id')->constrained('categories')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ouvrages');
    }
};
