<?php

namespace App\Exports;

use App\Models\NutrientBalance;
use App\Models\FarmerField;
use App\Models\Project;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class NutrientBalanceExport implements FromQuery, WithHeadings, WithMapping, WithTitle
{
    private $project;

    public function __construct(Project $project)
    {
        $this->project = $project;
    }

    public function title(): string
    {
        return 'Nutrients';
    }


    /**
     * @return \Illuminate\Support\Query
     */
    public function query()
    {
        return NutrientBalance::where('project_id', $this->project->id);
    }

    public function map($record): array
    {
        $farmerField = $record->farmer_field;

        return [
            $record->farmer_field_id,
            $farmerField->country_id,
            $farmerField->village_community,
            $farmerField->farmer_name,
            $record->year,
            $record->erosion_level,
            $record->erosion_amt_Tha,
            $record->erosionNloss_kgHa,
            $record->erosionPloss_kgHa,
            $record->erosionKloss_kgHa,
            $record->tot_org_Ninput,
            $record->tot_org_Pinput,
            $record->tot_org_Kinput,
            $record->tot_inorg_Ninput,
            $record->tot_inorg_Pinput,
            $record->tot_inorg_Kinput,
            $record->Total_cropNexport,
            $record->Total_cropPexport,
            $record->Total_cropKexport,
            $record->balance_N,
            $record->balance_P,
            $record->balance_K,
        ];
    }


    public function headings(): array
    {
        return [
            'farmer_field_id',
            'country',
            'village',
            'farmer',
            'year',
            'erosion_level',
            'erosion_amt_Tha',
            'erosionNloss_kgHa',
            'erosionPloss_kgHa',
            'erosionKloss_kgHa',
            'tot_org_Ninput',
            'tot_org_Pinput',
            'tot_org_Kinput',
            'tot_inorg_Ninput',
            'tot_inorg_Pinput',
            'tot_inorg_Kinput',
            'Total_cropNexport',
            'Total_cropPexport',
            'Total_cropKexport',
            'balance_N',
            'balance_P',
            'balance_K',
        ];
    }
}
