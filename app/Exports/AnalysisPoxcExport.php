<?php

namespace App\Exports;

use App\Models\Sample;
use App\Models\Project;
use App\Models\AnalysisAgg;
use App\Models\AnalysisPoxc;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class AnalysisPoxcExport implements FromCollection, WithHeadings, WithMapping, WithTitle
{
    private $project;

    public function __construct(Project $project)
    {
        $this->project = $project;
    }

    public function title(): string
    {
        return 'Analysis POXC';
    }


    public function collection()
    {
        return AnalysisPoxc::whereHas('sample', function (Builder $query) {
            $query->where('project_id', $this->project->id);
        })->with('sample')->get();
    }

    public function map($analysis): array
    {
        $map = [];

        foreach ($this->project->identifiers as $identifier) {
            $map[] = $analysis->sample->identifiers[$identifier['name']];
        }

        $map[] = $analysis->analysis_date;
        $map[] = $analysis->sample_id;
        $map[] = $analysis->weight_soil;
        $map[] = $analysis->color;
        $map[] = $analysis->color100;
        $map[] = $analysis->conc_digest;
        $map[] = $analysis->cloudy;
        $map[] = $analysis->photo;
        $map[] = $analysis->pct_reduction_color;
        $map[] = $analysis->raw_conc;
        $map[] = $analysis->poxc_sample;
        $map[] = $analysis->poxc_soil;
        $map[] = $analysis->correct_moisture;
        $map[] = $analysis->moisture;
        $map[] = $analysis->poxc_soil_corrected;

        return $map;
    }


    public function headings(): array
    {

        $headings = [];

        foreach ($this->project->identifiers as $identifier) {
            $headings[] = $identifier['label'];
        }

        $headings[] = 'analysis_date';
        $headings[] = 'sample_id';
        $headings[] = 'weight_soil';
        $headings[] = 'color';
        $headings[] = 'color100';
        $headings[] = 'conc_digest';
        $headings[] = 'cloudy';
        $headings[] = 'photo';
        $headings[] = 'pct_reduction_color';
        $headings[] = 'raw_conc';
        $headings[] = 'poxc_sample';
        $headings[] = 'poxc_soil';
        $headings[] = 'correct_moisture';
        $headings[] = 'moisture';
        $headings[] = 'poxc_soil_corrected';

        return $headings;
    }
}
