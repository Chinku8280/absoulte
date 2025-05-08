<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller {
    /**
    * Display a listing of the resource.
    */ function __construct() {
        $this->middleware( 'permission:product-list|product-create|product-edit|product-delete', [ 'only' => [ 'index', 'store' ] ] );
        $this->middleware( 'permission:product-create', [ 'only' => [ 'create', 'store' ] ] );
        $this->middleware( 'permission:product-edit', [ 'only' => [ 'edit', 'update' ] ] );
        $this->middleware( 'permission:product-delete', [ 'only' => [ 'destroy' ] ] );
    }

    public function index() {
        $heading_name  = 'Products';
        $products = Product::all();
        return view( 'admin.products.index', compact( 'products','heading_name' ) );
    }

    /**
    * Show the form for creating a new resource.
    */

    public function create() {
        return view( 'admin.products.create' );

    }

    /**
    * Store a newly created resource in storage.
    */

    public function store( Request $request ) {
        $request->validate( [
            'name' => 'required',
            'description' => 'required',
        ] );

        Product::create( $request->all() );

        return redirect()->route( 'products.index' )
        ->with( 'success', 'Product created successfully.' );

    }

    /**
    * Display the specified resource.
    */

    public function show( Product $product ) {
        return view( 'admin.products.show', compact( 'product' ) );

    }

    /**
    * Show the form for editing the specified resource.
    */

    public function edit( Product $product ) {
        return view( 'admin.products.edit', compact( 'product' ) );

    }

    /**
    * Update the specified resource in storage.
    */

    public function update( Request $request, Product $product ) {
        $request->validate( [
            'name' => 'required',
            'description' => 'required',
        ] );

        $product->update( $request->all() );

        return redirect()->route( 'products.index' )
        ->with( 'success', 'Product updated successfully' );
    }

    /**
    * Remove the specified resource from storage.
    */

    public function destroy( Product $product ) {
        $product->delete();

        return redirect()->route( 'products.index' )
        ->with( 'success', 'Product deleted successfully' );
    }
}
