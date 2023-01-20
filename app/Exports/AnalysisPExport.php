<?php

namespace App\Exports;

use App\Models\Sample;
use App\Models\Project;
use App\Models\AnalysisAgg;
use App\Models\AnalysisP;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class AnalysisPExport implements FromCollection, WithHeadings, WithMapping, WithTitle
{
    private $project;

    public function __construct(Project $project)
    {
        $this->project = $project;
    }

    public function title(): string
    {
        return 'Analysis Olsen P';
    }


    public function collection()
    {
        return AnalysisP::whereHas('sample', function (Builder $query) {
            $query->where('project_id', $this->project->id);
        })->with('sample')->get();
    }

    public function map($analysis): array
    {
        $map = [];

        foreach ($this->project->identifiers ?? [] as $identifier) {
            $map[] = $analysis->sample->identifiers[$identifier['name']];
        }

        $map[] = $analysis->sample_id;
        $map[] = $analysis->analysis_date;
        $map[] = $analysis->weight_soil;
        $map[] = $analysis->vol_extract;
        $map[] = $analysis->vol_topup;
        $map[] = $analysis->color;
        $map[] = $analysis->cloudy;
        $map[] = $analysis->photo;
        $map[] = $analysis->blank_water;
        $map[] = $analysis->raw_conc;
        $map[] = $analysis->correct_moisture;
        $map[] = $analysis->moisture;
        $map[] = $analysis->raw_conc_rounded;
        $map[] = $analysis->moisture_rounded;
        $map[] = $analysis->moisture_level_as_percentage;
        $map[] = $analysis->soil_conc_rounded;
        $map[] = $analysis->olsen_p;
        $map[] = $analysis->olsen_p_corrected;
        $map[] = $analysis->reagents;

        return $map;
    }


    public function headings(): array
    {
        $headings = [];

        foreach ($this->project->identifiers ?? [] as $identifier) {
            $headings[] = $identifier['label'];
        }

        $headings[] = 'sample_id';
        $headings[] = 'analysis_date';
        $headings[] = 'weight_soil';
        $headings[] = 'vol_extract';
        $headings[] = 'vol_topup';
        $headings[] = 'color';
        $headings[] = 'cloudy';
        $headings[] = 'photo';
        $headings[] = 'blank_water';
        $headings[] = 'raw_conc';
        $headings[] = 'correct_moisture';
        $headings[] = 'moisture';
        $headings[] = 'raw_conc_rounded';
        $headings[] = 'moisture_rounded';
        $headings[] = 'moisture_level_as_percentage';
        $headings[] = 'soil_conc_rounded';
        $headings[] = 'olsen_p';
        $headings[] = 'olsen_p_corrected';
        $headings[] = 'reagents';

        return $headings;
    }
}
