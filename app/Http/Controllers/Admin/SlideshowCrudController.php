<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\SlideshowRequest;
use App\Models\Skincare;
use App\Models\Slideshow;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class SlideshowCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class SlideshowCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Slideshow::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/slideshow');
        CRUD::setEntityNameStrings('slideshow', 'slideshows');
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

        $this->crud->addColumn([
            'label' => 'Image',
            'name' => 'image',
            'type' => 'image',
        ]);
        CRUD::column('description');
        CRUD::column('active');
//        CRUD::column('created_at');
//        CRUD::column('updated_at');

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
        CRUD::setValidation(SlideshowRequest::class);

        CRUD::field('id');

        $this->crud->addField([
            'label' => "Image",
            'name' => "image",
            'type' => 'image',
            // 'crop' => true, // set to true to allow cropping, false to disable
            // 'aspect_ratio' => 2, // omit or set to 0 to allow any aspect ratio
            //'disk'      => 's3_bucket', // in case you need to show images from a different disk
            //'prefix'    => 'uploads/' // in case your db value is only the file name (no path), you can use this to prepend your path to the image src (in HTML), before it's shown to the user;
        ]);
        CRUD::field('description');

        $this->crud->addField([   // radio
            'name'        => 'active', // the name of the db column
            'label'       => 'Active', // the input label
            'type'        => 'radio',
            'options'     => [
                // the key will be stored in the db, the value will be shown as label;
                'true' => "True",
                'false' => "False"
            ],
            // optional
            'inline'      => true, // show the radios all on the same line?
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
}
