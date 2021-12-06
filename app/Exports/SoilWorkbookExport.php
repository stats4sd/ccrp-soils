<?php

namespace App\Exports;

use App\Models\Project;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class SoilWorkbookExport implements WithMultipleSheets
{
    private $project;

    public function __construct(Project $project)
    {
        $this->project = $project;
    }


    public function sheets():array
    {
        return [
            new SampleExport($this->project),
            new AnalysisPExport($this->project),
            new AnalysisPhExport($this->project),
            new AnalysisPomExport($this->project),
            new AnalysisPoxcExport($this->project),
            new AnalysisAminoNExport($this->project),
            new AnalysisAggExport($this->project),
        ];
    }
}
