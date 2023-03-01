<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Sample;
use App\Models\DataMap;
use App\Models\Project;
use App\Models\Xlsform;
use App\Models\AnalysisP;
use App\Models\AnalysisPh;
use App\Models\AnalysisAgg;
use App\Models\AnalysisPom;
use Illuminate\Support\Str;
use App\Models\AnalysisPoxc;
use Illuminate\Http\Request;
use App\Helpers\GenericHelper;
use App\Models\ProjectXlsform;
use App\Models\ProjectSubmission;
use Illuminate\Support\Facades\Log;
use App\Events\NewDataVariableSpotted;
use App\Jobs\ImportAttachmentFromKobo;
use App\Models\FarmerField;
use App\Models\NutrientBalance;

class DataMapController extends Controller
{
    public static function newRecord(DataMap $dataMap, array $data, int $projectId = null)
    {
        // temp (create nutrients)
        if ($dataMap->model === "FarmerField") {
            DataMapController::handleNutrients($data, $projectId);
            return;
        }

        $newModel = [
            "project_submission_id" => $data['_id'],
        ];

        // handle sample ID
        if ($dataMap->id == 'sample') {
            $newModel['project_id'] = $projectId ?: null;
            $newModel['id'] = isset($data['sample_id']) ? $data['sample_id'] : null;

            Log::info("dealing with identifiers");
            Log::info($projectId);

            if ($projectId) {
                $project = Project::find($projectId);

                if (is_array($project->identifiers)) {
                    foreach ($project->identifiers as $identifier) {
                        $newModel['identifiers'][$identifier['name']] = isset($data[$identifier['name']]) ? $data[$identifier['name']] : null;
                    }
                }
            }
        } else {
            $newModel['sample_id'] = isset($data['sample_id']) ? $data['sample_id'] : $data['no_bar_code'];
        }


        if ($dataMap->location) {
            if (isset($data['gps_coordinates']) && $data['gps_coordinates']) {
                $location = explode(" ", $data['gps_coordinates']);
            } elseif (isset($data['_geolocation']) && $data['_geolocation']) {
                $location = $data['_geolocation'];
            }
            if ($location) {
                $newModel["longitude"] = isset($location[1]) ? $location[1] : null;
                $newModel["latitude"] = isset($location[0]) ? $location[0] : null;
                $newModel["altitude"] = isset($location[2]) ? $location[2] : null;
                $newModel["accuracy"] = isset($location[3]) ? $location[3] : null;
            }
        }


        foreach ($dataMap->variables as $variable) {
            if (Str::contains($variable['name'], 'balance.')) {
            }


            // if the variable is new (i.e. hasn't been manually added to the database)
            if ($variable['in_db'] == 0) {
                //don't actually process it (as the SQL Insert will fail)
                //just tell the admin about it!
                NewDataVariableSpotted::dispatch($variable['name'], $dataMap);
                continue;
            }

            $variableName = $variable['name'];
            $value = null;

            switch ($variable['type']) {
                case 'boolean':
                    if (isset($data[$variableName]) && $data[$variableName]) {
                        switch ($data[$variableName]) {
                            case 'yes':
                                $value = 1;
                                break;

                            case 'no':
                                $value = 0;
                                break;

                            case "1":
                            case "0":
                                $value = $data[$variableName];
                                break;
                            // error handling in a painfully basic way - set any unhandled values to null;
                            default:
                                $value = null;
                                break;
                        }
                    }
                    break;

                case 'photo':
                    if (isset($data[$variableName]) && $data[$variableName]) {
                        $value = $data[$variableName];
                        ImportAttachmentFromKobo::dispatch($value, $data);
                    }
                    break;

                case 'date':
                    if (isset($data[$variableName]) && $data[$variableName]) {
                        $value = Carbon::parse($data[$variableName]);
                        $value = $value->toDateString();
                    }
                    break;

                case 'datetime':
                    if (isset($data[$variableName]) && $data[$variableName]) {
                        $value = Carbon::parse($data[$variableName]);
                        $value = $value->toDateTimeString();
                    }
                    break;

                case 'select_multiple':
                case 'geopoint':
                    $value = null;
                    break;

                default:
                    $value = isset($data[$variableName]) ? $data[$variableName] : null;
                    break;
            }

            if (!is_null($value)) {
                $newModel[$variableName] = $value;
            }
        }

        $class = 'App\\Models\\' . $dataMap->model;
        $newItem = new $class();

        $newItem->fill($newModel);
        $newItem->save();

        \Log::info($class . " created");
        \Log::info("values: " . json_encode($newModel));
    }

    public static function updateAllRecords(Xlsform $xlsform, Project $project = null)
    {
        Log::info('updating records');
        Log::info('form - ' . $xlsform);
        Log::info('project - ' . $project);

        if ($project) {
            $projectFormIds = $xlsform->project_xlsforms->where('project_id', $project->id)->pluck('id');
        } else {
            $projectFormIds = $xlsform->project_xlsforms->pluck('id');
        }

        $submissions = ProjectSubmission::whereIn('project_xlsform_id', $projectFormIds)
            ->get();

        Log::info('submissions found');
        Log::info($submissions);

        $dataMap = $xlsform->data_map;

        foreach ($submissions as $submission) {
            $model = 'App\\Models\\' . $dataMap->model;


            $model::where('project_submission_id', '=', $submission->id)
                ->delete();

            $content = GenericHelper::remove_group_names_from_kobo_data(json_decode($submission->content, true));
            Log::info($content);

            DataMapController::newRecord($dataMap, $content, $submission->project_xlsform->project->id);
        }

        return count($submissions);
    }


    public static function handleNutrients($data, $projectId)
    {
        $farmerField = FarmerField::create([
            'project_id' => $projectId,
            'project_submission_id' => $data['_id'],
            'uuid' => $data['_uuid'],
            'country_id' => $data['community_id'] ?? null,
            'village_community' => $data['village_community'] ?? null,
            'farmer_name' => $data['farmer_name'] ?? null,
            'size' => $data['field_size_final3'] ?? null,
        ]);

        NutrientBalance::create([
            'project_id' => $projectId,
            'farmer_field_id' => $farmerField->id,
            'year' => '2020',
            'erosion_level' => $data['erosion_level_an4'] ?? null,
            'erosion_amt_Tha' => $data['erosion_amt_Tha_an4'] ?? null,
            'erosionNloss_kgHa' => $data['erosionNloss_kgHa_an4'] ?? null,
            'erosionPloss_kgHa' => $data['erosionPloss_kgHa_an4'] ?? null,
            'erosionKloss_kgHa' => $data['erosionKloss_kgHa_an4'] ?? null,
            'tot_org_Ninput' => $data['tot_org_Ninput_an4'] ?? null,
            'tot_org_Pinput' => $data['tot_org_Pinput_an4'] ?? null,
            'tot_org_Kinput' => $data['tot_org_Kinput_an4'] ?? null,
            'tot_inorg_Ninput' => $data['tot_inorg_Ninput_an4'] ?? null,
            'tot_inorg_Pinput' => $data['tot_inorg_Pinput_an4'] ?? null,
            'tot_inorg_Kinput' => $data['tot_inorg_Kinput_an4'] ?? null,
            'Total_cropNexport' => $data['Total_cropNexport_an4'] ?? null,
            'Total_cropPexport' => $data['Total_cropPexport_an4'] ?? null,
            'Total_cropKexport' => $data['Total_cropKexport_an4'] ?? null,
            'balance_N' => $data['balance_N_an4'] ?? null,
            'balance_P' => $data['balance_P_an4'] ?? null,
            'balance_K' => $data['balance_K_an4'] ?? null,
        ]);

        if (isset($data['additional_an3']) && $data['additional_an3'] === 'yes') {
            NutrientBalance::create([
                'project_id' => $projectId,
                'farmer_field_id' => $farmerField->id,
                'year' => '2019',
                'erosion_level' => $data['erosion_level_an3'] ?? null,
                'erosion_amt_Tha' => $data['erosion_amt_Tha_an3'] ?? null,
                'erosionNloss_kgHa' => $data['erosionNloss_kgHa_an3'] ?? null,
                'erosionPloss_kgHa' => $data['erosionPloss_kgHa_an3'] ?? null,
                'erosionKloss_kgHa' => $data['erosionKloss_kgHa_an3'] ?? null,
                'tot_org_Ninput' => $data['tot_org_Ninput_an3'] ?? null,
                'tot_org_Pinput' => $data['tot_org_Pinput_an3'] ?? null,
                'tot_org_Kinput' => $data['tot_org_Kinput_an3'] ?? null,
                'tot_inorg_Ninput' => $data['tot_inorg_Ninput_an3'] ?? null,
                'tot_inorg_Pinput' => $data['tot_inorg_Pinput_an3'] ?? null,
                'tot_inorg_Kinput' => $data['tot_inorg_Kinput_an3'] ?? null,
                'Total_cropNexport' => $data['Total_cropNexport_an3'] ?? null,
                'Total_cropPexport' => $data['Total_cropPexport_an3'] ?? null,
                'Total_cropKexport' => $data['Total_cropKexport_an3'] ?? null,
                'balance_N' => $data['balance_N_an3'] ?? null,
                'balance_P' => $data['balance_P_an3'] ?? null,
                'balance_K' => $data['balance_K_an3'] ?? null,
            ]);
        }

        if (isset($data['additional_an2']) && $data['additional_an2'] === 'yes') {
            NutrientBalance::create([
                'project_id' => $projectId,
                'farmer_field_id' => $farmerField->id,
                'year' => '2018',
                'erosion_level' => $data['erosion_level_an2'] ?? null,
                'erosion_amt_Tha' => $data['erosion_amt_Tha_an2'] ?? null,
                'erosionNloss_kgHa' => $data['erosionNloss_kgHa_an2'] ?? null,
                'erosionPloss_kgHa' => $data['erosionPloss_kgHa_an2'] ?? null,
                'erosionKloss_kgHa' => $data['erosionKloss_kgHa_an2'] ?? null,
                'tot_org_Ninput' => $data['tot_org_Ninput_an2'] ?? null,
                'tot_org_Pinput' => $data['tot_org_Pinput_an2'] ?? null,
                'tot_org_Kinput' => $data['tot_org_Kinput_an2'] ?? null,
                'tot_inorg_Ninput' => $data['tot_inorg_Ninput_an2'] ?? null,
                'tot_inorg_Pinput' => $data['tot_inorg_Pinput_an2'] ?? null,
                'tot_inorg_Kinput' => $data['tot_inorg_Kinput_an2'] ?? null,
                'Total_cropNexport' => $data['Total_cropNexport_an2'] ?? null,
                'Total_cropPexport' => $data['Total_cropPexport_an2'] ?? null,
                'Total_cropKexport' => $data['Total_cropKexport_an2'] ?? null,
                'balance_N' => $data['balance_N_an2'] ?? null,
                'balance_P' => $data['balance_P_an2'] ?? null,
                'balance_K' => $data['balance_K_an2'] ?? null,
            ]);
        }


        if (isset($data['additional_an1']) && $data['additional_an1'] === 'yes') {
            NutrientBalance::create([
                'project_id' => $projectId,
                'farmer_field_id' => $farmerField->id,
                'year' => '2017',
                'erosion_level' => $data['erosion_level_an1'] ?? null,
                'erosion_amt_Tha' => $data['erosion_amt_Tha_an1'] ?? null,
                'erosionNloss_kgHa' => $data['erosionNloss_kgHa_an1'] ?? null,
                'erosionPloss_kgHa' => $data['erosionPloss_kgHa_an1'] ?? null,
                'erosionKloss_kgHa' => $data['erosionKloss_kgHa_an1'] ?? null,
                'tot_org_Ninput' => $data['tot_org_Ninput_an1'] ?? null,
                'tot_org_Pinput' => $data['tot_org_Pinput_an1'] ?? null,
                'tot_org_Kinput' => $data['tot_org_Kinput_an1'] ?? null,
                'tot_inorg_Ninput' => $data['tot_inorg_Ninput_an1'] ?? null,
                'tot_inorg_Pinput' => $data['tot_inorg_Pinput_an1'] ?? null,
                'tot_inorg_Kinput' => $data['tot_inorg_Kinput_an1'] ?? null,
                'Total_cropNexport' => $data['Total_cropNexport_an1'] ?? null,
                'Total_cropPexport' => $data['Total_cropPexport_an1'] ?? null,
                'Total_cropKexport' => $data['Total_cropKexport_an1'] ?? null,
                'balance_N' => $data['balance_N_an1'] ?? null,
                'balance_P' => $data['balance_P_an1'] ?? null,
                'balance_K' => $data['balance_K_an1'] ?? null,
            ]);
        }
    }
}
