<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\SampleRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use App\Http\Controllers\Admin\Operations\ReviseOperation;


/**
 * Class SampleCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class SampleCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
   // use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
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

        CRUD::field('date')->type('date_picker')->date_picker_options(['format' => 'yyyy-mm-dd']);
        CRUD::field('depth')->type('number')->label('Depth of sample (cm)');
        CRUD::field('at_plot')->type('checkbox')->label('Was this record taken while at the actual plot?');
        CRUD::field('gps-title')->type('custom_html')->value('<h5>GPS</h5>');
        CRUD::field('latitude')->type('number')->label('Enter the latitude where the sample was taken ');
        CRUD::field('longitude')->type('number')->label('Enter the longitude where the sample was taken ');
        CRUD::field('altitude')->type('number')->label('Enter the altitude where the sample was taken ');
        CRUD::field('longitude')->type('number')->label('Enter the accuracy of the GPS reading (enter 0 if this is selected from a map rather than automatically collected');
        CRUD::field('comment')->type('textarea')->label('Enter any comments from the collection');
    }
}
