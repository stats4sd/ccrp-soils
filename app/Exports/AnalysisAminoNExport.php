<?php

namespace App\Exports;

use App\Models\Sample;
use App\Models\Project;
use App\Models\AnalysisAminoN;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class AnalysisAminoNExport implements FromCollection, WithHeadings, WithMapping, WithTitle
{
    private $project;

    public function __construct(Project $project)
    {
        $this->project = $project;
    }

    public function title(): string
    {
        return 'Analysis Amino N';
    }


    public function collection()
    {
        return AnalysisAminoN::whereHas('sample', function (Builder $query) {
            $query->where('project_id', $this->project->id);
        })->get();
    }

    public function map($analysis): array
    {
        return [
            $analysis->sample_id,
            $analysis->weight_soil,
            $analysis->weight_blank_acid_titrant,
            $analysis->weight_sample_acid_titrant,
            $analysis->mg_kg_aminsugar_n,
            $analysis->analysis_date,
        ];
    }


    public function headings(): array
    {
        return [
            'sample_id',
            'weight_soil',
            'weight_blank_acid_titrant',
            'weight_sample_acid_titrant',
            'mg_kg_aminsugar_n',
            'analysis_date',
        ];
    }
}
