<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\DataMapStoreRequest;
use App\Http\Requests\DataMapUpdateRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class DataMapCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class DataMapCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel('App\Models\DataMap');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/datamap');
        $this->crud->setEntityNameStrings('datamap', 'data_maps');
    }

    protected function setupListOperation()
    {
        $this->crud->addColumns([
            [
                'name' => 'id',
                'label' => 'value',
            ],
            [
                'name' => 'title',
                'label' => 'label'
            ],
            [
                'name' => 'variables',
                'label' => 'Number of variables',
                'type' => 'closure',
                'function' => function ($entity) {
                    if (isset($entity->variables) && is_countable($entity->variables)) {
                        return count($entity->variables);
                    }
                    return '';
                }
            ]
        ]);
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(DataMapStoreRequest::class);

        $this->crud->addFields([
            [
                'name' => 'id',
                'type' => 'text',
                'label' => 'value',
            ],
            [
                'name' => 'title',
                'type' => 'text',
                'label' => 'label',
            ],
            [
                'name' => 'model',
                'type' => 'select2_from_array',
                'label' => 'What Data Table will this form populate?',
                'options' => [
                    'Sample' => 'Sample Data',
                    'AnalysisAgg' => 'analysis_agg',
                    'AnalysisAminoN' => 'analysis_amino_n',
                    'AnalysisP' => 'analysis_p',
                    'AnalysisPh' => 'analysis_ph',
                    'AnalysisPom' => 'analysis_pom',
                    'AnalysisPoxc' => 'analysis_poxc',
                    'FarmerField' => 'nutrient balance',
                ],
            ],
            [
                'name' => 'location',
                'type' => 'boolean',
                'hint' => 'The ODK variable name should be `location`',
                'label' => 'Does this data map include a `location` field?',
            ],
            [
                'name' => 'variables',
                'type' => 'repeatable',
                'label' => 'Add the other variables the data map should look for in the ODK Form',
                'hint' => 'Every Soils form will automatically look for the following variables:
                    <ul>
                        <li>sample_id</li>
                        <li>no_bar_code (used as the sample_id if no bar code was scanned)</li>
                    </ul>',
                'fields' => [
                    [
                        'name' => 'name',
                        'label' => 'Variable Name',
                        'type' => 'text',
                        'wrapper' => ['class' => 'form-group col-md-4'],
                    ],
                    [
                        'name' => 'label',
                        'label' => 'Label',
                        'type' => 'text',
                        'wrapper' => ['class' => 'form-group col-md-4'],
                    ],
                    [
                        'name' => 'type',
                        'label' => 'Select the variable type',
                        'type' => 'select2_from_array',
                        'wrapper' => ['class' => 'form-group col-md-4'],
                        'options' => [
                            'boolean' => 'Boolean (select_one with yes/no or 1/0 options)',
                            'text' => 'text / select_one',
                            'integer' => 'integer',
                            'decimal' => 'decimal',
                            'select_multiple' => 'select_multiple',
                            'date' => 'date',
                            'timestamp' => 'datetime',
                            'gps' => 'geopoint',
                            'photo' => 'photo',
                        ],
                    ],
                    [
                        'name' => 'in_db',
                        'type' => 'hidden',
                        'value' => 0,
                    ],
                ],
            ],
        ]);
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
        $this->crud->modifyField('id', [
            'attributes' => [
                'readonly' => true,
            ],
        ]);
        $this->crud->setValidation(DataMapUpdateRequest::class);
    }

    protected function setupShowOperation()
    {
        $this->crud->addColumns([
            [
                'name' => 'id',
                'label' => 'value',
            ],
            [
                'name' => 'title',
                'label' => 'label'
            ],
            [
                'name' => 'model',
                'type' => 'text',
                'label' => 'What Data Table will this form populate?',
            ],
            [
                'name' => 'location',
                'type' => 'boolean',
                'label' => 'Does this data map include a `location` field?',
            ],
            [
                'name' => 'variables',
                'label' => 'Variables',
                'type' => 'table',
                'columns' => [
                    'name' => 'Name',
                    'label' => 'Label',
                    'type' => 'Type'
                ]
            ]
        ]);
    }
}
