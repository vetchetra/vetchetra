<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ProductRequest;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Subject;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

use Illuminate\Http\Request;

/**
 * Class ProductCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ProductCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Product::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/product');
        CRUD::setEntityNameStrings('product', 'products');
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
//        CRUD::column('category_id');
//        CRUD::column('price');
//        CRUD::column('cost');
//        CRUD::column('description');
//        CRUD::column('created_at');
//        CRUD::column('updated_at');
        $this->crud->addColumn([
            'label' => 'Category-Id',
            'name' => 'category_id',
            'type'=>'select',
            'attribute'=>'name',
            'entity'=>'category',
            'model'=>Category::class,

        ]);
        $this->crud->addColumn([
            'label' => 'Name',
            'name' => 'name',

        ]);
        $this->crud->addColumn([
            'label' => 'Price',
            'name' => 'price',

        ]);
        $this->crud->addColumn([
            'label' => 'Cost',
            'name' => 'cost',

        ]);
        $this->crud->addColumn([
            'label' => 'Description',
            'name' => 'description',

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
        CRUD::setValidation(ProductRequest::class);
//
//        CRUD::field('id');
//        CRUD::field('category_id');
//        CRUD::field('price');
//        CRUD::field('cost');
//        CRUD::field('description');
//        CRUD::field('created_at');
//        CRUD::field('updated_at');
        $this->crud->addField([
            // 'tab'=>'General',
            'label' => 'Category',
            'name' => 'category_id',
            'type' => "select2_from_ajax ",
            'entity'    => 'category',
            'data_source' => url("admin/search-category"),
            'model'     => Category::class, // related model
            'attribute' => 'name',         // foreign key attribute that is shown to user
            'placeholder' => "Select a category",
            'minimum_input_length' => 0,
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ]
        ]);
        $this->crud->addField([
            // 'tab'=>'General',
            'label' => 'Name',
            'name' => 'name',
            //'default' => Sale::getSeqRef(),
            // 'type' => 'enum',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ]
        ]);
        $this->crud->addField([
            // 'tab'=>'General',
            'label' => 'Price',
            'name' => 'price',
            //'default' => Sale::getSeqRef(),
            // 'type' => 'enum',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ]
        ]);
        $this->crud->addField([
            // 'tab'=>'General',
            'label' => 'Cost',
            'name' => 'cost',
            //'default' => Sale::getSeqRef(),
            // 'type' => 'enum',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ]
        ]);
        $this->crud->addField([
            // 'tab'=>'General',
            'label' => 'Description',
            'name' => 'description',
            //'default' => Sale::getSeqRef(),
            // 'type' => 'enum',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ]
        ]);
        $this->crud->addField([
            'label'=>'Customer',
            'name'=>'customersearch',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ]
        ]);

        $this->crud->addField([
            'name'=>'js',
            'type'=>'view',
            'view'=>'searchcustomer',
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

    public function aaaa(Request $request)
    {
        $search_term = $request->input('q');

        if ($search_term)
        {
            $results = Product::where('name', 'LIKE', '%'.$search_term.'%')->paginate(10);
        }
        else
        {
            $results = Product::paginate(10);
        }

        return $results;
    }


    //oy show data jul in table
    public function showw($id)
    {
        $data = Product::where('id', $id)->first();
        return view('vendor/backpack/crud/product_table', ['item'=>$data]);
    }


}
