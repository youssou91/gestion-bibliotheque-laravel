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
        Schema::table('commentaires', function (Blueprint $table) {
            if (! Schema::hasColumn('commentaires', 'statut')) {
                $table->enum('statut', ['en_attente', 'approuve', 'rejete'])
                      ->default('en_attente')
                      ->after('note');
            }
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down()
{
    Schema::table('commentaires', function (Blueprint $table) {
        if (Schema::hasColumn('commentaires', 'statut')) {
            $table->dropColumn('statut');
        }
    });
}

};
