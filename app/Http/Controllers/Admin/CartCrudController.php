<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CartRequest;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Skincare;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class CartCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class CartCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Cart::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/cart');
        CRUD::setEntityNameStrings('cart', 'carts');
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
        CRUD::column('skincare_id');
        CRUD::column('quantity');
        CRUD::column('amount');
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
        CRUD::setValidation(CartRequest::class);

        CRUD::field('id');
        CRUD::field('skincare_id');
        CRUD::field('quantity');
        CRUD::field('amount');
//        CRUD::field('created_at');
//        CRUD::field('updated_at');

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

    public function showcartt()
    {
       // dd('cart');
        return view('frontend.cart');
    }

    public function addcart($id)
    {
        $prod = Skincare::find($id);
        $check = Cart::where('skincare_id', $id)->first();
        if ( $check == null)
        {
            $data = new Cart();
            $data->skincare_id = $id;
            $data->quantity = 1;
            $data->amount = $prod->price * $data->quantity;
            $data->save();
        }
        else{
            $check->quantity += 1;
            $check->save();
            $check->amount = $prod->price * $check->quantity;
            $check->save();
        }
        return redirect()->back();

    }

    public function minusqty($id)
    {

        $cart = Cart::find($id);

        $prod = Skincare::where('id', $cart->skincare_id)->first();

        $cart->quantity -= 1;
        $cart->amount -= $prod->price;
        $cart->save();
        return view('frontend.cart');
    }

    public function plusqty($id){
        $cart = Cart::find($id);
        $skinn = Skincare::where('id',$cart->skincare_id)->first();

        $cart->quantity +=1;
        $cart->amount += $skinn->price;
        $cart->save();
        return view('frontent.cart');
    }
    public function removecart($id){
        $cart = Cart::find($id);
        $cart->delete();
        return view('frontent.cart');
    }

}
