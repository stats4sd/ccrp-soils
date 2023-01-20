<?php

namespace App\Exports;

use App\Models\Sample;
use App\Models\Project;
use App\Models\AnalysisAgg;
use App\Models\AnalysisPom;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class AnalysisPomExport implements FromCollection, WithHeadings, WithMapping, WithTitle
{
    private $project;

    public function __construct(Project $project)
    {
        $this->project = $project;
    }

    public function title(): string
    {
        return 'Analysis POM';
    }


    public function collection()
    {
        return AnalysisPom::whereHas('sample', function (Builder $query) {
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
        $map[] = $analysis->diameter_circ_pom;
        $map[] = $analysis->weigh_pom_yn;
        $map[] = $analysis->weight_cloth;
        $map[] = $analysis->weight_pom;
        $map[] = $analysis->percent_pom;

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
        $headings[] = 'diameter_circ_pom';
        $headings[] = 'weigh_pom_yn';
        $headings[] = 'weight_cloth';
        $headings[] = 'weight_pom';
        $headings[] = 'percent_pom';

        return $headings;
    }
}
