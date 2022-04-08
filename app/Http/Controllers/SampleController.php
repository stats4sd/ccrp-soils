<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use App\Exports\SampleExport;
use App\Exports\SoilWorkbookExport;
use Maatwebsite\Excel\Facades\Excel;

class SampleController extends Controller
{
    public function download(Project $project)
    {
        $filename = $project->slug . '-samples-' . now()->toDateTimeString() . '.xlsx';

        return Excel::download(new SoilWorkbookExport($project), $filename);
    }
}
