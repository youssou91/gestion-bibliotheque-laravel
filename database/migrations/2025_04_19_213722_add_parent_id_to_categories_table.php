<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('categories', function (Blueprint $table) {
            // Colonne parent_id nullable
            $table->unsignedBigInteger('parent_id')->nullable()->after('description');
            // Clé étrangère vers la même table, cascade on delete
            $table->foreign('parent_id')
                  ->references('id')
                  ->on('categories')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('categories', function (Blueprint $table) {
            // D'abord supprimer la contrainte étrangère
            $table->dropForeign(['parent_id']);
            // Puis la colonne
            $table->dropColumn('parent_id');
        });
    }
};
