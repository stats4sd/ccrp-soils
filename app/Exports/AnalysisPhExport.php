<?php

namespace App\Exports;

use App\Models\Sample;
use App\Models\Project;
use App\Models\AnalysisAgg;
use App\Models\AnalysisPh;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class AnalysisPhExport implements FromCollection, WithHeadings, WithMapping, WithTitle
{
    private $project;

    public function __construct(Project $project)
    {
        $this->project = $project;
    }

    public function title(): string
    {
        return 'Analysis Ph';
    }


    public function collection()
    {
        return AnalysisPh::whereHas('sample', function (Builder $query) {
            $query->where('project_id', $this->project->id);
        })->with('sample')->get();
    }

    public function map($analysis): array
    {

        $map = [];

        foreach ($this->project->identifiers as $identifier) {
            $map[] = $analysis->sample->identifiers[$identifier['name']];
        }


        $map[] = $analysis->sample_id;
        $map[] = $analysis->analysis_date;
        $map[] = $analysis->weight_soil;
        $map[] = $analysis->vol_water;
        $map[] = $analysis->reading_ph;
        $map[] = $analysis->stability;

        return $map;
    }


    public function headings(): array
    {
        $headings = [];

        foreach ($this->project->identifiers as $identifier) {
            $headings[] = $identifier['label'];
        }
        $headings[] = 'sample_id';
        $headings[] = 'analysis_date';
        $headings[] = 'weight_soil';
        $headings[] = 'vol_water';
        $headings[] = 'reading_ph';
        $headings[] = 'stability';

        return $headings;
    }
}
