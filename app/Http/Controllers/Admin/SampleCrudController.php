<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\SampleRequest;
use App\Models\Sample;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class SampleCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class SampleCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Sample::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/sample');
        CRUD::setEntityNameStrings('sample', 'samples');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        # CRUD::disableResponsiveTable();
        CRUD::enableDetailsRow();


        CRUD::column('id')->label('Sample ID');
        CRUD::column('date')->label('Date Collected');
        CRUD::column('depth')->label('Depth')->suffix(' (cm)');

        CRUD::column('analysis_p')->type('relationship_count')->label('# Analysis P');
        CRUD::column('analysis_ph')->type('relationship_count')->label('# Analysis Ph');
        CRUD::column('analysis_pom')->type('relationship_count')->label('# Analysis POM');
        CRUD::column('analysis_poxc')->type('relationship_count')->label('# Analysis POXC');
        CRUD::column('analysis_agg')->type('relationship_count')->label('# Analysis AGG');
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(SampleRequest::class);



        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number']));
         */
    }

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }

    public function getJson($project_id = null)
    {
        if ($project_id) {
            $data = Sample::where('project_id', $project_id)->get();
        } else {
            $data = Sample::get();
        }

        return $data->toJson();
    }
}
