<?php

namespace App\Http\Controllers\Admin;

use App\Models\Project;
use App\Helpers\GenericHelper;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ProjectSubmissionRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use App\Http\Controllers\Admin\Operations\ReviseOperation;
use App\Models\Xlsform;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class ProjectSubmissionCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ProjectSubmissionCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation {update as traitUpdate;}
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use ReviseOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\ProjectSubmission::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/projectsubmission');
        CRUD::setEntityNameStrings('projectsubmission', 'project_submissions');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        // $this->crud->query = $this->crud->query->leftJoin('project_xlsform', 'project_xlsform.id', '=', 'project_submissions.project_xlsform_id')
        //     ->leftJoin('projects', 'projects.id', '=', 'project_xlsform.project_id')
        //     ->leftJoin('xlsforms', 'xlsforms.id', '=', 'project_xlsform.xlsform_id');

        if (Auth::user()->isAdmin()) {
            CRUD::column('project_name')->label('project');
        }

        CRUD::column('xlsform_title')->label('XLS Form')->limit(5000)->wrapper([
            'element' => 'div',
            'class' => 'd-block text-wrap',
        ])->orderLogic(function ($query, $column, $columnDirection) {
            return $query->leftJoin('project_xlsform', 'project_xlsform.id', '=', 'project_submissions.project_xlsform_id')
            ->leftJoin('projects', 'projects.id', '=', 'project_xlsform.project_id')
            ->leftJoin('xlsforms', 'xlsforms.id', '=', 'project_xlsform.xlsform_id')
            ->orderBy('projects.name', $columnDirection)
            ->orderBy('xlsforms.title', $columnDirection)
            ->select('project_submissions.*')
            ;
        })->orderable(true)
            ->searchLogic(function ($query, $column, $searchTerm) {
                $query->orWhereHas('project_xlsform', function ($q) use ($column, $searchTerm) {
                    $q->whereHas('xlsform', function ($qx) use ($column, $searchTerm) {
                        $qx->where('title', 'like', '%'.$searchTerm.'%');
                    })
                    ->orWhereHas('project', function ($qp) use ($column, $searchTerm) {
                        $qp->where('name', 'like', '%'.$searchTerm.'%');
                    });
                });
            });


        CRUD::column('id')->label('kobo_submission_id')->searchLogic(function ($query, $column, $searchTerm) {
            $query->orWhere('project_submissions.id', 'like', '%'.$searchTerm.'%');
        });
        CRUD::column('sample_id')->label('Soil Sample ID')->searchLogic(function ($query, $column, $searchTerm) {
            $query->orWhere('project_submissions.content->sample_id', 'like', '%'.$searchTerm.'%')
            ->orWhere('project_submissions.content->no_bar_code', 'like', '%'.$searchTerm.'%');
        });


        CRUD::addFilter(
            [
            'name' => 'project',
            'type' => 'select2',
            'label' => 'Filter by Project',
        ],
            function () {
                return Project::all()->pluck('name', 'id')->toArray();
            },
            function ($value) {
                $this->crud->query = $this->crud->query->where('project_submissions.project_id', $value);
            }
        );

        CRUD::addFilter(
            [
            'name' => 'xlsform',
            'type' => 'select2',
            'label' => 'Filter by Xlsform',
        ],
            function () {
                return Xlsform::all()->pluck('title', 'id')->toArray();
            },
            function ($value) {
                $this->crud->query = $this->crud->query->whereHas('project_xlsform', function ($query) use ($value) {
                    $query->where('project_xlsform.xlsform_id', $value);
                });
            }
        );
    }

    protected function setupShowOperation()
    {
        $content = json_decode($this->crud->getCurrentEntry()->content, true);

        foreach ($content as $field => $value) {
            CRUD::column($field)->type('custom_value')->value($value);
        }
    }

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $content = json_decode($this->crud->getCurrentEntry()->content, true);

        //$content = GenericHelper::remove_group_names_from_kobo_data($content);



        // $dataMap = $this->crud->getCurrentEntry()->project_xlsform->xlsform->data_map;
        // $dataMapVariables = collect($dataMap->variables)->map(fn ($variable) => [ 'name' => $variable['name'], 'label' => $variable['label'] ])->toArray();

        // $projectVariables = $this->crud->getCurrentEntry()->project->identifiers;

        foreach ($content as $field => $value) {
            if (is_array($value)) {
                $value = json_encode($value);
            }
            CRUD::field($field)->type('text')->value((string) $value)->fake('true')->store_in('content');
        }
        // if ($content['sample_id']) {
        //     $sampleVariable = [['name' => 'sample_id', 'label' => 'Soil Sample ID']];
        // } else {
        //     $sampleVariable = [['name' => 'no_bar_code', 'label' => 'Soil Sample ID from manual typing']];
        // }


        // $allVars = array_merge($projectVariables, $dataMapVariables);
        // $allVars = array_merge($sampleVariable, $allVars);

        // foreach ($allVars as $variable) {
        //     $value = isset($content[$variable['name']]) ? $content[$variable['name']] : null;

        //     CRUD::field($variable['name'])->value($value)->label($variable['label'])->fake(true)->store_in('content');
        // }
    }

    /**
     * Update the specified resource in the database.
     * @param ProjectSubmissionRequest $request - type injection used for validation using Requests
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ProjectSubmissionRequest $request)
    {
        return $this->traitUpdate($request);
    }
}
