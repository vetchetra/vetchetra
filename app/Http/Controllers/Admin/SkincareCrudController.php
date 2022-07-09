<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\SkincareRequest;
use App\Models\Product;
use App\Models\Skincare;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class SkincareCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class SkincareCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Skincare::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/skincare');
        CRUD::setEntityNameStrings('skincare', 'skincares');
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
        

        $this->crud->addColumn([
            'name'     => 'price',
            'label'    => 'Price',
            'type'     => 'closure',
            'function' => function($entry) {
                return '$'.$entry->price;
            }
        ]);

        $this->crud->addColumn([
            'label' => 'Image',
            'name' => 'image',
            'type' => 'image',
        ]);

        CRUD::column('description');
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
        CRUD::setValidation(SkincareRequest::class);

        CRUD::field('id');
        CRUD::field('name');
        CRUD::field('price');

        //        CRUD::field('image');
        $this->crud->addField([
            'label' => "Image",
            'name' => "image",
            'type' => 'image',
            'crop' => true, // set to true to allow cropping, false to disable
            'aspect_ratio' => 0, // omit or set to 0 to allow any aspect ratio
            //'disk'      => 's3_bucket', // in case you need to show images from a different disk
            //'prefix'    => 'uploads/' // in case your db value is only the file name (no path), you can use this to prepend your path to the image src (in HTML), before it's shown to the user;
        ]);

        $this->crud->addField([   // Summernote
            'name'  => 'description',
            'label' => 'Description',
            'type'  => 'summernote',
            'options' => [],
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

    public function vvviewskincare($id){
       // dd('ggg');
        $skin = Skincare::find($id);

        return view('frontend.viewskincare',['skincare'=>$skin]);
    }

    public function selectbrand($brand){
         $skincare= Skincare::where('brand', 'LIKE' , '%'.$brand.'%')->paginate(16);
         return view('frontend.brandselect',['skincare'=>$skincare]);
    }
}
