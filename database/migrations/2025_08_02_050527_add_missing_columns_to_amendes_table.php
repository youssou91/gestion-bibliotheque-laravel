<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMissingColumnsToAmendesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('amendes', function (Blueprint $table) {
            if (!Schema::hasColumn('amendes', 'transaction_id')) {
                $table->string('transaction_id')->nullable()->after('id')->comment('ID de transaction PayPal');
            }
            
            if (!Schema::hasColumn('amendes', 'date_paiement')) {
                $table->timestamp('date_paiement')->nullable()->after('statut');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('amendes', function (Blueprint $table) {
            if (Schema::hasColumn('amendes', 'transaction_id')) {
                $table->dropColumn('transaction_id');
            }
            
            if (Schema::hasColumn('amendes', 'date_paiement')) {
                $table->dropColumn('date_paiement');
            }
        });
    }
}
