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
        Schema::table('ouvrages', function (Blueprint $table) {
            // Modifier la colonne description pour accepter un texte plus long
            $table->text('description')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ouvrages', function (Blueprint $table) {
            $table->dropColumn(['auteur', 'editeur', 'isbn', 'prix', 'date_publication']);
            $table->integer('annee_publication');
            $table->string('niveau')->change();
            $table->string('photo')->change();
            $table->string('description')->change();
        });
    }
};
