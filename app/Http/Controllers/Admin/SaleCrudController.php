<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\SaleRequest;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Saledetail;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class SaleCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class SaleCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Sale::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/sale');
        CRUD::setEntityNameStrings('sale', 'sales');
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
//        CRUD::column('customer_id');
//        CRUD::column('product_id');
//        CRUD::column('p_date');
//        CRUD::column('description');
//        CRUD::column('total_price');
//        CRUD::column('total_qty');
//        CRUD::column('created_at');
//        CRUD::column('updated_at');

        $this->crud->addColumn([
            'label' => 'Customer-ID',
            'name' => 'customer_id',
        ]);
        $this->crud->addColumn([
            'label' => 'P-Date',
            'name' => 'p_date',
        ]);
        $this->crud->addColumn([
            'label' => 'Description',
            'name' => 'description',
        ]);
        $this->crud->addColumn([
            'label' => 'Total-Price',
            'name' => 'total_price',
            'type'     => 'closure',
            'function' => function($entry) {
                return '$'.$entry->total_price;
            },
        ]);
        $this->crud->addColumn([
            'label' => 'Total-Qty',
            'name' => 'total_qty',
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
        CRUD::setValidation(SaleRequest::class);


        $this->crud->addField([
            // 'tab'=>'General',
            'label' => 'Customer',
            'name' => 'customer_id',
            'type' => "select2_from_ajax ",
            'entity'    => 'customer',
            'data_source' => url("admin/search-customer"),
            'model'     => Customer::class, // related model
            'attribute' => 'name',         // foreign key attribute that is shown to user
            'placeholder' => "Select a customers",
            'minimum_input_length' => 0,
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ]
        ]);
        $this->crud->addField([
            // 'tab'=>'General',
            'label' => 'Product',
            'name' => 'product_id',
            'type' => "select2_from_ajax ",
            'entity'    => 'products',
            'data_source' => url("admin/search-product"),
            'model'     => Product::class, // related model
            'attribute' => 'name',         // foreign key attribute that is shown to user
            'placeholder' => "Select a product",
            'minimum_input_length' => 0,
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ]
        ]);
        $this->crud->addField([
            // 'tab'=>'General',
            'label' => 'P-Date',
            'name' => 'p_date',
            'type'  => 'date_picker',
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
            // 'tab'=>'General',
            'label' => 'Total-Price',
            'name' => 'total_price',
            //'default' => Sale::getSeqRef(),
            // 'type' => 'enum',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ]
        ]);
        $this->crud->addField([
            // 'tab'=>'General',
            'label' => 'Total-Qty',
            'name' => 'total_qty',
            //'default' => Sale::getSeqRef(),
            // 'type' => 'enum',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ]
        ]);

        $this->crud->addField(
            [   // view
                'name' => 'JS',
                'type' => 'view',
                'view' => 'script'
            ]
        );
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

    public function store()
    {
        $this->crud->hasAccessOrFail('create');

        // execute the FormRequest authorization and validation, if one is required
        $request = $this->crud->validateRequest();

        // insert item in the db
        $item = $this->crud->create($this->crud->getStrippedSaveRequest());
        $this->data['entry'] = $this->crud->entry = $item;


        // insert item into sale_detail table db
        $product_id = $request->product_id_;
        $price = $request->price_;
        $qty= $request->qty_;

        if(is_array($product_id)){
            foreach ($product_id as $key => $value){
                $data = new Saledetail();
                $data->sale_id = $this->crud->entry->id;
                $data->product_id = $value;
                $data->price = $price[$key];
                $data->qty = $qty[$key];

                $data->save();
            }
        }

        // show a success message
        \Alert::success(trans('backpack::crud.insert_success'))->flash();

        // save the redirect choice for next time
        $this->crud->setSaveAction();

        return $this->crud->performSaveAction($item->getKey());
    }

    public function update()
    {
        $this->crud->hasAccessOrFail('update');

        // execute the FormRequest authorization and validation, if one is required
        $request = $this->crud->validateRequest();

        // update the row in the db
        $item = $this->crud->update($request->get($this->crud->model->getKeyName()),
            $this->crud->getStrippedSaveRequest());
        $this->data['entry'] = $this->crud->entry = $item;

        // Delete old data from saledetail
        $old = Saledetail::where('sale_id',$this->crud->entry->id )->delete();

        // Insert new data to saledetail
        $product_id = $request->product_id_;
        $price = $request->price_;
        $qty= $request->qty_;

        if(is_array($product_id)){
            foreach ($product_id as $key => $value){
                $data = new Saledetail();
                $data->sale_id = $this->crud->entry->id;
                $data->product_id = $value;
                $data->price = $price[$key];
                $data->qty = $qty[$key];

                $data->save();
            }
        }
        // show a success message
        \Alert::success(trans('backpack::crud.update_success'))->flash();

        // save the redirect choice for next time
        $this->crud->setSaveAction();

        return $this->crud->performSaveAction($item->getKey());
    }

    public function destroy($id)
    {

        $this->crud->hasAccessOrFail('delete');

        // get entry ID from Request (makes sure its the last ID for nested resources)
        $id = $this->crud->getCurrentEntryId() ?? $id;

        // Delete old data from saledetail
        Saledetail::where('sale_id',$id )->delete();

        return $this->crud->delete($id);
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

        //show in preview
        return view('vendor/backpack/crud/show_', $this->data);
    }


}
