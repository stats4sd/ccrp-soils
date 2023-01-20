<?php

namespace App\Exports;

use App\Models\Sample;
use App\Models\Project;
use App\Models\AnalysisAgg;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class AnalysisAggExport implements FromCollection, WithHeadings, WithMapping, WithTitle
{
    private $project;

    public function __construct(Project $project)
    {
        $this->project = $project;
    }

    public function title(): string
    {
        return 'Analysis Aggregates';
    }


    public function collection()
    {
        return AnalysisAgg::whereHas('sample', function (Builder $query) {
            $query->where('project_id', $this->project->id);
        })->with('sample')->get();
    }

    public function map($analysis): array
    {
        $map = [];

        foreach ($this->project->identifiers as $identifier) {
            $map[] = $analysis->sample->identifiers[$identifier['name']];
        }

        $map[] =  $analysis->sample_id;
        $map[] =  $analysis->weight_soil;
        $map[] =  $analysis->weight_cloth;
        $map[] =  $analysis->weight_stones2mm;
        $map[] =  $analysis->weight_2mm_aggreg;
        $map[] =  $analysis->weight_cloth_250micron;
        $map[] =  $analysis->weight_250micron_aggreg;
        $map[] =  $analysis->pct_stones;
        $map[] =  $analysis->twomm_aggreg_pct;
        $map[] =  $analysis->twofiftymicr_aggreg_pct;
        $map[] =  $analysis->twomm_aggreg_pct_result;
        $map[] =  $analysis->twofiftymicron_aggreg_pct_result;
        $map[] =  $analysis->percent_stones;
        $map[] =  $analysis->total_stableaggregates;
        $map[] =  $analysis->total_check;
        $map[] =  $analysis->validation_check;
        $map[] =  $analysis->analysis_date;

        return $map;
    }


    public function headings(): array
    {
        $headings = [];

        foreach ($this->project->identifiers as $identifier) {
            $headings[] = $identifier['label'];
        }

        $headings[] = 'sample_id';
        $headings[] = 'weight_soil';
        $headings[] = 'weight_cloth';
        $headings[] = 'weight_stones2mm';
        $headings[] = 'weight_2mm_aggreg';
        $headings[] = 'weight_cloth_250micron';
        $headings[] = 'weight_250micron_aggreg';
        $headings[] = 'pct_stones';
        $headings[] = 'twomm_aggreg_pct';
        $headings[] = 'twofiftymicr_aggreg_pct';
        $headings[] = 'twomm_aggreg_pct_result';
        $headings[] = 'twofiftymicron_aggreg_pct_result';
        $headings[] = 'percent_stones';
        $headings[] = 'total_stableaggregates';
        $headings[] = 'total_check';
        $headings[] = 'validation_check';
        $headings[] = 'analysis_date';

        return $headings;
    }
}
