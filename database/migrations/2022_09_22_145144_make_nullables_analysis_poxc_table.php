<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeNullablesAnalysisPoxcTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('analysis_poxc', function (Blueprint $table) {
            $table->foreignId('project_submission_id')->nullable()->change();
            $table->integer('correct_moisture')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('analysis_poxc', function (Blueprint $table) {
            $table->foreignId('project_submission_id')->change();
            $table->integer('correct_moisture')->change();
        });
    }
}
