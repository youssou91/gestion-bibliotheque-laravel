<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateStatutColumnInAmendesTable extends Migration
{
    public function up()
    {
        // Supprimer la colonne d'abord dans un bloc distinct
        if (Schema::hasColumn('amendes', 'statut')) {
            Schema::table('amendes', function (Blueprint $table) {
                $table->dropColumn('statut');
            });
        }

        // Puis ajouter la colonne enum dans un autre bloc
        Schema::table('amendes', function (Blueprint $table) {
            $table->enum('statut', ['impayée', 'payée', 'annulée'])
                ->default('impayée')
                ->after('montant');
        });
    }

    public function down()
    {
        Schema::table('amendes', function (Blueprint $table) {
            $table->dropColumn('statut');
        });

        Schema::table('amendes', function (Blueprint $table) {
            $table->string('statut')->default('impayée');
        });
    }
}
