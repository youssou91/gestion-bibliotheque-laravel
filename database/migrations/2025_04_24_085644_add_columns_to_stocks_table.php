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
        Schema::table('stocks', function (Blueprint $table) {
            $table->integer('seuil_alerte')->default(5);
            $table->string('emplacement')->nullable();
            $table->timestamp('derniere_entree')->nullable();
            $table->timestamp('derniere_sortie')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stocks', function (Blueprint $table) {
            $table->dropColumn(['seuil_alerte', 'emplacement', 'derniere_entree', 'derniere_sortie']);
        });
    }
};
