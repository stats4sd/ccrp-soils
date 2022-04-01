<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Project;
use App\Http\Controllers\SampleMergedController;

/**
 * Generate CREATE VIEW SQL in database view .sql files for projects with merged_view not equal to "samples_merged".
 * For projects with merged_view equal to "samples_merged", CREATE VIEW SQL is stored in existing file database\views\samples_merged.sql.
 * 
 * Reuse code in SampleMergedController::createCustomView() to generate one .sql file for one project,
 * artisan command "updatesql" will be called to execute all existing .sql files in database\views folder.
 * 
 * This program has not been optimized to run .sql file for each project one time only.
 * It is no harm and it should be no impact to re-create the same database view multiple times in a very short time period.
 * 
 */
class GenerateDbViews extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generatedbviews';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate and execute database view .sql files for projects';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->generateDatabaseViewFiles();
    }

    /**
     * Generate database view .sql files for projects with merged_view not equal to "samples_merged"
     */
    public function generateDatabaseViewFiles() {
        // find all projects with merged_view not equal to "samples_merged"
        $projects = Project::where('merged_view', '<>', 'samples_merged')->orderBy('id')->get()->all();

        foreach ($projects as $project) {
            // reuse code to generate CREATE VIEW SQL in database view .sql file and run artisan command "updatesql"
            $projectSnakeName = SampleMergedController::createCustomView($project);
        }
    }

}
