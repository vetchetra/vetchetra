<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\DistrictRequest;
use App\Models\Category;
use App\Models\Customer;
use App\Models\District;
use App\Models\Province;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Http\Request;

/**
 * Class DistrictCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class DistrictCrudController extends CrudController
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
        CRUD::setModel(\App\Models\District::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/district');
        CRUD::setEntityNameStrings('district', 'districts');
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

        $this->crud->addColumn([
            'label' => 'Provinces-ID',
            'name' => 'provinces_id',
            'type'=>'select',
            'attribute'=>'name',
            'entity'=>'provincess',
            'model'=>Province::class,

        ]);

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
        CRUD::setValidation(DistrictRequest::class);

        CRUD::field('id');
        CRUD::field('name');
        CRUD::field('description');

        $this->crud->addField(
            [
                'name'        => 'provinces_id',
                'label'       => "Provinces-ID", // Table column heading
                'type'        => "select2_from_ajax",
                'attribute'=>'name',
                'entity'=>'provincess',
                'data_source' => url("admin/provincesname"),
                'model'=>Province::class,
                'placeholder' => "Select Province", // placeholder for the select
                'minimum_input_length'    => 0,
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-12'
                ],

            ]);

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
    public function selectajax(Request $request)
    {
        $search_term = $request->input('q');

        if ($search_term)
        {
            $results = Province::where('name', 'LIKE', '%'.$search_term.'%')->paginate(5);
        }
        else
        {
            $results = Province::paginate(5);
        }
        return $results;
    }




}
