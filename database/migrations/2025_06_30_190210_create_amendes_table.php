<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAmendesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create('amendes', function (Blueprint $table) {
        //     $table->id();
        //     $table->timestamps();
        // });
        Schema::create('amendes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('utilisateur_id')->constrained('utilisateurs')->onDelete('cascade');
            $table->foreignId('ouvrage_id')->constrained('ouvrages')->onDelete('cascade');
            $table->foreignId('emprunt_id')->nullable()->constrained('emprunts')->onDelete('set null');
            $table->decimal('montant', 8, 2);
            $table->string('statut')->default('impayée'); // ou ->enum('statut', ['impayée', 'payée', 'annulée']) selon vos besoins
            $table->string('motif')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('amendes');
    }
}
