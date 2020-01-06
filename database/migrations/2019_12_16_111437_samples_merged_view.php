<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SamplesMergedView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("DROP VIEW IF EXISTS samples_merged");
        DB::statement("
            CREATE VIEW samples_merged AS
            SELECT 
                `samples`.`project_id` AS `project_id`,
                `samples`.`id` AS `sample_id`,
                `samples`.`date` AS `sampling_date`,
                `samples`.`username` AS `username`,
                `samples`.`date` AS `date`,
                `samples`.`depth` AS `depth`,
                `samples`.`texture` AS `texture`,
                `samples`.`at_plot` AS `at_plot`,
                `samples`.`plot_photo` AS `plot_photo`,
                `samples`.`longitude` AS `longitude`,
                `samples`.`latitude` AS `latitude`,
                `samples`.`altitude` AS `altitude`,
                `samples`.`accuracy` AS `accuracy`,
                `samples`.`comment` AS `comment`,
                `samples`.`farmer_quick` AS `farmer_quick`,
                `samples`.`community_quick` AS `community_quick`,
                `samples`.`plot_id` AS `plot_id`,
                `analysis_p`.`analysis_date` AS `analysis_p-date`,
                `analysis_p`.`weight_soil` AS `analysis_p-weight_soil`,
                `analysis_p`.`vol_extract` AS `analysis_p-vol_extract`,
                `analysis_p`.`vol_topup` AS `analysis_p-vol_topup`,
                `analysis_p`.`cloudy` AS `analysis_p-cloudy`,
                `analysis_p`.`color` AS `analysis_p-color`,
                `analysis_p`.`raw_conc` AS `analysis_p-raw_conc`,
                `analysis_p`.`olsen_p` AS `analysis_p-olsen_p`,
                `analysis_p`.`blank_water` AS `analysis_p-blank_water`,
                `analysis_p`.`correct_moisture` AS `analysis_p-correct_moisture`,
                `analysis_p`.`moisture` AS `analysis_p-moisture`,
                `analysis_p`.`olsen_p_corrected` AS `analysis_p-olsen_p_corrected`,
                `analysis_ph`.`analysis_date` AS `analysis_ph-date`,
                `analysis_ph`.`weight_soil` AS `analysis_ph-weight_soil`,
                `analysis_ph`.`vol_water` AS `analysis_ph-vol_water`,
                `analysis_ph`.`reading_ph` AS `analysis_ph-reading_ph`,
                `analysis_ph`.`stability` AS `analysis_ph-stability`,
                `analysis_poxc`.`analysis_date` AS `analysis_poxc-date`,
                `analysis_poxc`.`weight_soil` AS `analysis_poxc-weight_soil`,
                `analysis_poxc`.`color` AS `analysis_poxc-color`,
                `analysis_poxc`.`color100` AS `analysis_poxc-color100`,
                `analysis_poxc`.`conc_digest` AS `analysis_poxc-conc_digest`,
                `analysis_poxc`.`cloudy` AS `analysis_poxc-cloudy`,
                `analysis_poxc`.`colorimeter` AS `analysis_poxc-colorimeter`,
                `analysis_poxc`.`raw_conc` AS `analysis_poxc-raw_conc`,
                `analysis_poxc`.`poxc_soil` AS `analysis_poxc-poxc_soil`,
                `analysis_poxc`.`poxc_sample` AS `analysis_poxc-poxc_sample`,
                `analysis_poxc`.`correct_moisture` AS `analysis_poxc-correct_moisture`,
                `analysis_poxc`.`moisture` AS `analysis_poxc-moisture`,
                `analysis_poxc`.`poxc_soil_corrected` AS `analysis_poxc-poxc_soil_corrected`
                FROM (((`samples` LEFT JOIN `analysis_p` on((`samples`.`id` = `analysis_p`.`sample_id`))) LEFT JOIN`analysis_ph` on((`samples`.`id` = `analysis_ph`.`sample_id`))) LEFT JOIN `analysis_poxc` on((`samples`.`id` = `analysis_poxc`.`sample_id`)))
            ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         DB::statement("DROP VIEW IF EXISTS samples_merged");
    }
}
