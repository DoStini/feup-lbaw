<?php

namespace App\Http\Controllers;

use App\Models\Product;

class ProductController extends Controller {
    /**
     * Shows the product for a given id.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id) {
        $product = Product::find($id);
        return view('pages.product', ['product' => $product]);
    }


    /**
     * Shows the search products page.
     *
     * @return Response
     */
    public function list() {

        $products = Product::all()->take(25);

        return view('pages.search.products', ['products' => $products]);
    }
}
