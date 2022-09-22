<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeNullablesAnalysisPhTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('analysis_ph', function (Blueprint $table) {
            $table->foreignId('project_submission_id')->nullable()->change();
            $table->date('analysis_date')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('analysis_ph', function (Blueprint $table) {
            $table->foreignId('project_submission_id')->change();
            $table->date('analysis_date')->change();
        });
    }
}
