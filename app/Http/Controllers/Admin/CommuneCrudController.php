<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CommuneRequest;
use App\Models\District;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Http\Request;

/**
 * Class CommuneCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class CommuneCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Commune::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/commune');
        CRUD::setEntityNameStrings('commune', 'communes');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::column('id');
        CRUD::column('name');
        CRUD::column('description');
        CRUD::column('provinces_id');
        CRUD::column('district_id');
        CRUD::column('created_at');
        CRUD::column('updated_at');

        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']);
         */
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(CommuneRequest::class);

        CRUD::field('id');

        $this->crud->addField([
            // 'tab'=>'General',
            'label' => 'Name',
            'name' => 'name',
            //'default' => Sale::getSeqRef(),
            // 'type' => 'enum',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-12'
            ]
        ]);
        $this->crud->addField([
            // 'tab'=>'General',
            'label' => 'Description',
            'name' => 'description',
            //'default' => Sale::getSeqRef(),
            // 'type' => 'enum',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-12'
            ]
        ]);

        $this->crud->addField(
            [
                'label'       => "Province", // Table column heading
                'type'        => "select2_from_ajax",
                'name'        => 'provinces_id', // the column that contains the ID of that connected entity
                'entity'      => 'province', // the method that defines the relationship in your Model
                'attribute'   => "name", // foreign key attribute that is shown to user
                'data_source' => url("admin/selprovincefromdistrict"),
                'placeholder' => "Select Province", // placeholder for the select
                'minimum_input_length'    => 0, // minimum characters to type before querying results
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-6'
                ],
                'attributes' => [
                    'class' => 'province'
                ],
            ]);


        $this->crud->addField(
            [
                'label'       => "District", // Table column heading
                'type'        => "select2_district_from_ajax",
                'name'        => 'district_id', // the column that contains the ID of that connected entity
                'entity'      => 'district', // the method that defines the relationship in your Model
                'attribute'   => "name", // foreign key attribute that is shown to user
                'data_source' => url("admin/selectnamedistrick"),
                'placeholder' => "Select a District", // placeholder for the select
                'minimum_input_length'    => 0, // minimum characters to type before querying results
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-6'
                ],

            ]);
        CRUD::field('created_at');
        CRUD::field('updated_at');

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
//    public function selectajax(Request $request)
//    {
//        $search_term = $request->input('q');
//
//        if ($search_term)
//        {
//            $results = District::where('name', 'LIKE', '%'.$search_term.'%')->paginate(5);
//        }
//        else
//        {
//            $results = District::paginate(5);
//        }
//        return $results;
//    }
}
