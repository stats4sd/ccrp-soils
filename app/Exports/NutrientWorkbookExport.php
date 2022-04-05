<?php

namespace App\Exports;

use App\Models\Project;
use App\Exports\NutrientBalanceExport;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class NutrientWorkbookExport implements WithMultipleSheets
{
    private $project;

    public function __construct(Project $project)
    {
        $this->project = $project;
    }


    public function sheets():array
    {
        return [
            new NutrientBalanceExport($this->project),
        ];
    }
}
