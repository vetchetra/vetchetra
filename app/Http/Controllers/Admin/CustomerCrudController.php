<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CustomerRequest;
use App\Models\Customer;
use App\Models\Product;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Http\Request;

/**
 * Class CustomerCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class CustomerCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    //for Clone Multiple Items (Bulk Clone)
    use \Backpack\CRUD\app\Http\Controllers\Operations\BulkCloneOperation;
    //Delete Multiple Items (Bulk Delete)
    use \Backpack\CRUD\app\Http\Controllers\Operations\BulkDeleteOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Customer::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/customer');
        CRUD::setEntityNameStrings('customer', 'customers');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
//        CRUD::column('id');
        CRUD::column('name');
        CRUD::column('description');
        $this->crud->addColumn([
            'label' => 'Profile',
            'name' => 'profile',
            'type' => 'image',
            // 'prefix' => 'uploads/',
            // image from a different disk (like s3 bucket)
//             'disk' => 'uploads',
        ]);

        $this->crud->enableExportButtons();//for Export
        $this->crud->disableResponsiveTable();// for Responsive Table

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
        CRUD::setValidation(CustomerRequest::class);

//        CRUD::field('id');
//        CRUD::field('name');
//        CRUD::field('description');
//        CRUD::field('created_at');
//        CRUD::field('updated_at');
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
        $this->crud->addField(
            [
                'label'       => "Province", // Table column heading
                'type'        => "select2_from_ajax",
                'name'        => 'provinces', // the column that contains the ID of that connected entity
                'entity'      => 'provinces', // the method that defines the relationship in your Model
                'attribute'   => "name", // foreign key attribute that is shown to user
                'data_source' => url("admin/selectprovince"),
                'placeholder' => "Select a Province", // placeholder for the select
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
                'name'        => 'district', // the column that contains the ID of that connected entity
                'entity'      => 'districts', // the method that defines the relationship in your Model
                'data_source' => url("admin/selectdistrict"),
                'attribute'   => "name", // foreign key attribute that is shown to user
                'placeholder' => "Select District", // placeholder for the select
                'minimum_input_length'    => 0, // minimum characters to type before querying results
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-6'
                ],
                'attributes' => [
                    'class' => 'district'
                ],

            ]);
        $this->crud->addField(
            [
                'label'       => "Commune", // Table column heading
                'type'        => "select2_commune_from_ajax",
                'name'        => 'commune', // the column that contains the ID of that connected entity
                'entity'      => 'commune', // the method that defines the relationship in your Model
                'attribute'   => "name", // foreign key attribute that is shown to user
                'data_source' => url("admin/selectcommune"),
                'placeholder' => "Select a Commune", // placeholder for the select
                'minimum_input_length'    => 0, // minimum characters to type before querying results
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-6'
                ],

            ]);
        $this->crud->addField(
            [
                'label'       => "Village", // Table column heading
                //'type'        => "select2_from_ajax",
                'name'        => 'village', // the column that contains the ID of that connected entity
//                'entity'      => 'provinces', // the method that defines the relationship in your Model
//                'attribute'   => "name", // foreign key attribute that is shown to user
//                'data_source' => url("admin/selectprovince"),
//                'placeholder' => "Select a Province", // placeholder for the select
//                'minimum_input_length'    => 0, // minimum characters to type before querying results
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-6'
                ],
            ]);
        $this->crud->addField([
            // 'tab'=>'General',
            'name'  => 'description',
            'label' => 'Description',
            'type'  => 'summernote',
            'options' => [],
        ]);
        $this->crud->addField([
            'label' => 'Profile',
            'name' => 'profile',
            'type' => 'image',
            'crop'      => true,
            'aspect_ratio' => 2,
            'wrapperAttributes' => [
                'class' => 'form-group col-md-12'
            ]
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
    public function appp(Request $request)
    {
        $search_term = $request->input('q');

        if ($search_term)
        {
            $results = Customer::where('name', 'LIKE', '%'.$search_term.'%')->paginate(10);
        }
        else
        {
            $results = Customer::paginate(10);
        }

        return $results;
    }


    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }

    //searchcustomer
    public function searchh(Request $request){
        $results = "";
        $search_term = $request->input('word');
        if ($search_term) {
            $results = Customer::where('name', 'LIKE', '%' . $search_term . '%')->get();
        }
        //dd($results);
        return view('customer_',['result'=>$results]);
    }

    public function show($id)
    {
        $this->crud->hasAccessOrFail('show');

        // get entry ID from Request (makes sure its the last ID for nested resources)
        $id = $this->crud->getCurrentEntryId() ?? $id;
        $setFromDb = $this->crud->get('show.setFromDb');

        // get the info for that entry
        $this->data['entry'] = $this->crud->getEntry($id);
        $this->data['crud'] = $this->crud;
        $this->data['title'] = $this->crud->getTitle() ?? trans('backpack::crud.preview').' '.$this->crud->entity_name;

        return view('vendor/backpack/crud/showcustomer', $this->data);
    }

    // form pi rbos b oy tver
    public function form01()
    {
        return view('revenue_voucher');
    }

}
