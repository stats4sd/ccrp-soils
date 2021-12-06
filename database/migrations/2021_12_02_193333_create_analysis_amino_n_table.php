<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnalysisAminoNTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('analysis_amino_n', function (Blueprint $table) {
            $table->id();
            $table->string('sample_id', 100);
            $table->date('analysis_date');
            $table->decimal('weight_soil', 10,4)->nullable();
            $table->decimal('weight_blank_acid_titrant', 10,4)->nullable();
            $table->decimal('weight_sample_acid_titrant', 10,4)->nullable();
            $table->decimal('corrected_acid_titrant', 10,4)->nullable();
            $table->decimal('titer_conversion_microg_n', 10,4)->nullable();
            $table->decimal('mg_kg_aminsugar_n', 10,4)->nullable();
            $table->unsignedBigInteger('project_submission_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('analysis_amino_n');
    }
}
