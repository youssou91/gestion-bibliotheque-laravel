<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UpdateNiveauEnumInOuvragesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB
        ::statement("
        ALTER TABLE ouvrages 
        MODIFY COLUMN niveau ENUM('debutant', 'amateur', 'chef', 'intermédiaire', 'avance', 'expert') 
        DEFAULT 'debutant'
    ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ouvrages', function (Blueprint $table) {
            //
        });
    }
}
