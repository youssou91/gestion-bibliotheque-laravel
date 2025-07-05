<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatutToOuvragesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ouvrages', function (Blueprint $table) {
            $table->enum('statut', ['disponible', 'réservé', 'emprunté'])
                  ->default('disponible')
                  ->after('niveau'); // Placez la colonne où vous voulez
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ouvrages', function (Blueprint $table) {
            $table->dropColumn('statut');
        });
    }
}
