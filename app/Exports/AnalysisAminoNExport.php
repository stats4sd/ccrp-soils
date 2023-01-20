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
        })->with('sample')->get();
    }

    public function map($analysis): array
    {
        $map = [];

        foreach ($this->project->identifiers ?? [] as $identifier) {
            $map[] = $analysis->sample->identifiers[$identifier['name']];
        }
        $map[] = $analysis->sample_id;
        $map[] = $analysis->weight_soil;
        $map[] = $analysis->weight_blank_acid_titrant;
        $map[] = $analysis->weight_sample_acid_titrant;
        $map[] = $analysis->mg_kg_aminsugar_n;
        $map[] = $analysis->analysis_date;
        return $map;
    }


    public function headings(): array
    {
        $headings = [];

        foreach ($this->project->identifiers ?? [] as $identifier) {
            $headings[] = $identifier['label'];
        }

        $headings[] = 'sample_id';
        $headings[] = 'weight_soil';
        $headings[] = 'weight_blank_acid_titrant';
        $headings[] = 'weight_sample_acid_titrant';
        $headings[] = 'mg_kg_aminsugar_n';
        $headings[] = 'analysis_date';

        return $headings;
    }
}
