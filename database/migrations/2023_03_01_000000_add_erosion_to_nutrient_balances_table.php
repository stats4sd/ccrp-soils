<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddErosionToNutrientBalancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('nutrient_balances', function (Blueprint $table) {
            $table->string('erosion_level')->nullable();
            $table->decimal('erosion_amt_Tha', 8, 1)->nullable();
            $table->decimal('erosionNloss_kgHa', 8, 1)->nullable();
            $table->decimal('erosionPloss_kgHa', 8, 1)->nullable();
            $table->decimal('erosionKloss_kgHa', 8, 1)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('samples', function (Blueprint $table) {
            $table->dropColumn('erosion_level');
            $table->dropColumn('erosion_amt_Tha');
            $table->dropColumn('erosionNloss_kgHa');
            $table->dropColumn('erosionPloss_kgHa');
            $table->dropColumn('erosionKloss_kgHa');
        });
    }
}
