<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\SaledetailRequest;
use App\Models\Product;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class SaledetailCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class SaledetailCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Saledetail::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/saledetail');
        CRUD::setEntityNameStrings('saledetail', 'saledetails');

        //Code for bit function create update and delete nv ler saledetail
        $this->crud->denyAccess(['create', 'update', 'delete']);
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {


        $this->crud->addColumn([
            'label' => 'Product',
            'name' => 'product_id',
            'entity'    => 'products',
            'model'     => Product::class, // related model
            'attribute' => 'name',         // foreign key attribute that is shown to user
        ]);
        $this->crud->addColumn([
            'label' => 'Sale',
            'name' => 'sale_id',
        ]);
        $this->crud->addColumn([
            'label' => 'Price',
            'name' => 'price',
        ]);
        $this->crud->addColumn([
            'label' => 'Qty',
            'name' => 'qty',
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
        CRUD::setValidation(SaledetailRequest::class);



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
}
