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
            $table->text('description');
            $table->string('auteur');
            $table->string('editeur');
            $table->string('isbn')->unique();
            $table->decimal('prix', 8, 2);
            $table->date('date_publication');
            $table->enum('niveau', ['debutant', 'amateur', 'chef', 'intermédiaire', 'avance', 'expert'])->default('debutant');
            // $table->enum('niveau', ['débutant', 'amateur', 'chef'])->default('débutant');
            $table->string('photo')->nullable();
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
