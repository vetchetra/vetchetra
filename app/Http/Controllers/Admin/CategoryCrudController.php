<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use App\Models\Subject;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Http\Request;

/**
 * Class CategoryCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class CategoryCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Category::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/category');
        CRUD::setEntityNameStrings('category', 'categories');
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
            'label' => 'Name',
            'name' => 'name',

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
        CRUD::setValidation(CategoryRequest::class);

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

    public function api(Request $request)
    {
        $search_term = $request->input('q');

        if ($search_term)
        {
            $results = Category::where('name', 'LIKE', '%'.$search_term.'%')->paginate(10);
        }
        else
        {
            $results = Category::paginate(10);
        }

        return $results;
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
