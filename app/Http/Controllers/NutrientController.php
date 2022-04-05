<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\NutrientWorkbookExport;

class NutrientController extends Controller
{
    public function download(Project $project)
    {
        $filename = $project->slug . '-nutrients-' . now()->toDateTimeString() . '.xlsx';

        return Excel::download(new NutrientWorkbookExport($project), $filename);
    }
}
