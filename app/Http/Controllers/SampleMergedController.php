<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Project;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Exports\SampleMergedExport;
use Artisan;
use Illuminate\Database\Eloquent\Builder;

class SampleMergedController extends Controller
{
    public function download(Project $project)
    {
        $date = Carbon::now()->toDateTimeString();
        return (new SampleMergedExport)->forProject($project)->download(Str::slug($project->name).'-all_sample_data-'.$date.".xlsx");
    }

    public static function createCustomView(Project $project)
    {
        $query = "SELECT
        `samples`.`project_id` AS `project_id`,";

        if ($project->identifiers) {
            foreach ($project->identifiers as $identifier) {
                if ($identifier['name'] && $identifier['name'] != "") {
                    $query .= '`samples`.`identifiers`->"$.' . $identifier['name'] . '" as `' . $identifier['name'] . '`,';
                }
            }
        }
        $query .= "
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
        `samples`.`simple_texture` AS `simple_texture`,
        `samples`.`ball_yn` AS `ball_yn`,
        `samples`.`ribbon_yn` AS `ribbon_yn`,
        `samples`.`ribbon_break_length` AS `ribbon_break_length`,
        `samples`.`usda_gritty` AS `usda_gritty`,
        `samples`.`final_texture_type_usda` AS `final_texture_type_usda`,
        `samples`.`second_texture_type_usda` AS `second_texture_type_usda`,
        `samples`.`ball_yn_fao` AS `ball_yn_fao`,
        `samples`.`sausage_yn_fao` AS `sausage_yn_fao`,
        `samples`.`pencil_fao_yn` AS `pencil_fao_yn`,
        `samples`.`halfcircle_fao_yn` AS `halfcircle_fao_yn`,
        `samples`.`soil_circle_choice` AS `soil_circle_choice`,
        `samples`.`final_texture_type_fao` AS `final_texture_type_fao`,
        `samples`.`second_texture_type_fao` AS `second_texture_type_fao`,
        `analysis_p_lr`.`analysis_date` AS `analysis_p-date`,
        `analysis_p_lr`.`weight_soil` AS `analysis_p-weight_soil`,
        `analysis_p_lr`.`vol_extract` AS `analysis_p-vol_extract`,
        `analysis_p_lr`.`vol_topup` AS `analysis_p-vol_topup`,
        `analysis_p_lr`.`cloudy` AS `analysis_p-cloudy`,
        `analysis_p_lr`.`color` AS `analysis_p-color`,
        `analysis_p_lr`.`raw_conc` AS `analysis_p-raw_conc`,
        `analysis_p_lr`.`olsen_p` AS `analysis_p-olsen_p`,
        `analysis_p_lr`.`blank_water` AS `analysis_p-blank_water`,
        `analysis_p_lr`.`correct_moisture` AS `analysis_p-correct_moisture`,
        `analysis_p_lr`.`moisture` AS `analysis_p-moisture`,
        `analysis_p_lr`.`olsen_p_corrected` AS `analysis_p-olsen_p_corrected`,
        `analysis_p_lr`.`raw_conc_rounded` AS `analysis_p-raw_conc_rounded`,
        `analysis_p_lr`.`moisture_rounded` AS `analysis_p-moisture_rounded`,
        `analysis_p_lr`.`moisture_level_as_percentage` AS `analysis_p-moisture_level_as_percentage`,
        `analysis_p_lr`.`soil_conc_rounded` AS `analysis_p-soil_conc_rounded`,";

        //check if project has any P analyses with High or Custom Reagents
        if ($project->highR) {
            $query .= "
            `analysis_p_hr`.`analysis_date` AS `analysis_p_hr-date`,
            `analysis_p_hr`.`weight_soil` AS `analysis_p_hr-weight_soil`,
            `analysis_p_hr`.`vol_extract` AS `analysis_p_hr-vol_extract`,
            `analysis_p_hr`.`vol_topup` AS `analysis_p_hr-vol_topup`,
            `analysis_p_hr`.`cloudy` AS `analysis_p_hr-cloudy`,
            `analysis_p_hr`.`color` AS `analysis_p_hr-color`,
            `analysis_p_hr`.`raw_conc` AS `analysis_p_hr-raw_conc`,
            `analysis_p_hr`.`olsen_p` AS `analysis_p_hr-olsen_p`,
            `analysis_p_hr`.`blank_water` AS `analysis_p_hr-blank_water`,
            `analysis_p_hr`.`correct_moisture` AS `analysis_p_hr-correct_moisture`,
            `analysis_p_hr`.`moisture` AS `analysis_p_hr-moisture`,
            `analysis_p_hr`.`olsen_p_corrected` AS `analysis_p_hr-olsen_p_corrected`,
            `analysis_p_hr`.`raw_conc_rounded` AS `analysis_p_hr-raw_conc_rounded`,
            `analysis_p_hr`.`moisture_rounded` AS `analysis_p_hr-moisture_rounded`,
            `analysis_p_hr`.`moisture_level_as_percentage` AS `analysis_p_hr-moisture_level_as_percentage`,
            `analysis_p_hr`.`soil_conc_rounded` AS `analysis_p_hr-soil_conc_rounded`,";
        }

        if ($project->customR) {
            $query .= "
            `analysis_p_customr`.`Reagent_type` AS `analysis_p_custom_r-Reagent_type`,
            `analysis_p_customr`.`analysis_date` AS `analysis_p_custom_r-date`,
            `analysis_p_customr`.`weight_soil` AS `analysis_p_custom_r-weight_soil`,
            `analysis_p_customr`.`vol_extract` AS `analysis_p_custom_r-vol_extract`,
            `analysis_p_customr`.`vol_topup` AS `analysis_p_custom_r-vol_topup`,
            `analysis_p_customr`.`cloudy` AS `analysis_p_custom_r-cloudy`,
            `analysis_p_customr`.`color` AS `analysis_p_custom_r-color`,
            `analysis_p_customr`.`raw_conc` AS `analysis_p_custom_r-raw_conc`,
            `analysis_p_customr`.`olsen_p` AS `analysis_p_custom_r-olsen_p`,
            `analysis_p_customr`.`blank_water` AS `analysis_p_custom_r-blank_water`,
            `analysis_p_customr`.`correct_moisture` AS `analysis_p_custom_r-correct_moisture`,
            `analysis_p_customr`.`moisture` AS `analysis_p_custom_r-moisture`,
            `analysis_p_customr`.`olsen_p_corrected` AS `analysis_p_custom_r-olsen_p_corrected`,
            `analysis_p_customr`.`raw_conc_rounded` AS `analysis_p_custom_r-raw_conc_rounded`,
            `analysis_p_customr`.`moisture_rounded` AS `analysis_p_custom_r-moisture_rounded`,
            `analysis_p_customr`.`moisture_level_as_percentage` AS `analysis_p_custom_r-moisture_level_as_percentage`,
            `analysis_p_customr`.`soil_conc_rounded` AS `analysis_p_custom_r-soil_conc_rounded`,";
        }

        $query .= "
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
        `analysis_poxc`.`pct_reduction_color` AS `analysis_poxc-pct_reduction_color`,
        `analysis_poxc`.`raw_conc` AS `analysis_poxc-raw_conc`,
        `analysis_poxc`.`poxc_soil` AS `analysis_poxc-poxc_soil`,
        `analysis_poxc`.`poxc_sample` AS `analysis_poxc-poxc_sample`,
        `analysis_poxc`.`correct_moisture` AS `analysis_poxc-correct_moisture`,
        `analysis_poxc`.`moisture` AS `analysis_poxc-moisture`,
        `analysis_poxc`.`poxc_soil_corrected` AS `analysis_poxc-poxc_soil_corrected`,
        `analysis_pom`.`weight_soil` AS `analysis_pom-weight_soil`,
        `analysis_pom`.`diameter_circ_pom` AS `analayis_pom-diameter_circ_pom`,
        `analysis_pom`.`weigh_pom_yn` AS `analayis_pom-weigh_pom_yn`,
        `analysis_pom`.`weight_cloth` AS `analayis_pom-weight_cloth`,
        `analysis_pom`.`weight_pom` AS `analayis_pom-weight_pom`,
        `analysis_pom`.`percent_pom` AS `analayis_pom-percent_pom`,
        `analysis_pom`.`analysis_date` AS `analayis_pom-analysis_date`,
        `analysis_amino_n`.`weight_soil` AS `analysis_amino_n-weight_soil`,
        `analysis_amino_n`.`weight_blank_acid_titrant` AS `analysis_amino_n-weight_blank_acid_titrant`,
        `analysis_amino_n`.`weight_sample_acid_titrant` AS `analysis_amino_n-weight_sample_acid_titrant`,
        `analysis_amino_n`.`mg_kg_aminsugar_n` AS `analysis_amino_n-mg_kg_aminsugar_n`,
        `analysis_amino_n`.`analysis_date` AS `analysis_amino_n-analysis_date`,
        `analysis_agg`.`weight_soil` AS `analysis_agg-weight_soil`,
        `analysis_agg`.`weight_cloth` AS `analysis_agg-weight_cloth`,
        `analysis_agg`.`weight_stones2mm` AS `analysis_agg-weight_stones2mm`,
        `analysis_agg`.`weight_2mm_aggreg` AS `analysis_agg-weight_2mm_aggreg`,
        `analysis_agg`.`weight_cloth_250micron` AS `analysis_agg-weight_cloth_250micron`,
        `analysis_agg`.`weight_250micron_aggreg` AS `analysis_agg-weight_250micron_aggreg`,
        `analysis_agg`.`pct_stones` AS `analysis_agg-pct_stones`,
        `analysis_agg`.`twomm_aggreg_pct` AS `analysis_agg-twomm_aggreg_pct`,
        `analysis_agg`.`twofiftymicr_aggreg_pct` AS `analysis_agg-twofiftymicr_aggreg_pct`,
        `analysis_agg`.`twomm_aggreg_pct_result` AS `analysis_agg-twomm_aggreg_pct_result`,
        `analysis_agg`.`twofiftymicron_aggreg_pct_result` AS `analysis_agg-twofiftymicron_aggreg_pct_result`,
        `analysis_agg`.`percent_stones` AS `analysis_agg-percent_stones`,
        `analysis_agg`.`total_stableaggregates` AS `analysis_agg-total_stableaggregates`,
        `analysis_agg`.`total_check` AS `analysis_agg-total_check`,
        `analysis_agg`.`validation_check` AS `analysis_agg-validation_check`,
        `analysis_agg`.`analysis_date` AS `analysis_agg-analysis_date`
    FROM ((((((`samples`
                    LEFT JOIN `analysis_p` `analysis_p_lr` ON ((`samples`.`id` = `analysis_p_lr`.`sample_id` and `analysis_p_lr`.`reagents` = 'LR')))
                    LEFT JOIN `analysis_p` `analysis_p_hr` ON ((`samples`.`id` = `analysis_p_hr`.`sample_id` and `analysis_p_hr`.`reagents` = 'HR'))
                    LEFT JOIN `analysis_p` `analysis_p_customr` ON ((`samples`.`id` = `analysis_p_customr`.`sample_id` and `analysis_p_customr`.`reagents` = 'Custom R'))
                LEFT JOIN `analysis_ph` ON ((`samples`.`id` = `analysis_ph`.`sample_id`)))
            LEFT JOIN `analysis_poxc` ON ((`samples`.`id` = `analysis_poxc`.`sample_id`)))
        LEFT JOIN `analysis_pom` ON ((`samples`.`id` = `analysis_pom`.`sample_id`)))
        LEFT JOIN `analysis_amino_n` ON ((`samples`.`id` = `analysis_amino_n`.`sample_id`)))
        LEFT JOIN `analysis_agg` ON ((`samples`.`id` = `analysis_agg`.`sample_id`)))";

        // create or update file in databases views folder
        $projectSnakeName = "samples_merged_".Str::of($project->name)->slug('_');

        file_put_contents(base_path('database/views/'.$projectSnakeName.'.sql'), $query);

        // rerun view creation
        Artisan::call('updatesql');
        return $projectSnakeName;
    }
}
