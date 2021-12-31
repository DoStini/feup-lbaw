<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Product;
use Exception;

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
    public function search() {

        $products = Product::all()->take(25);

        return view('pages.search.products', ['products' => $products]);
    }

    /**
     * Search products according to filters in the query
     *
     * @return Response
     */
    public function list(Request $request) {
        try {
            $query = DB::table('product')
                ->whereRaw('stock > 0')
                ->when($request->text, function ($q) use ($request) {
                    $words = explode(' ', $request->text);
                    foreach($words as &$word)
                        $word = $word . ':*';
                    $val = implode(' & ', $words);
                    return $q->whereRaw('tsvectors @@ to_tsquery(\'simple\', ?)', [$val])
                        ->orderByRaw('ts_rank(tsvectors, to_tsquery(\'simple\', ?)) DESC', [$val]);
                })
                ->when($request->input('price-min'), function ($q) use ($request) {
                    return $q->where('price', '>', [$request->input('price-min')]);
                })
                ->when($request->input('price-max'), function ($q) use ($request) {
                    return $q->where('price', '<', [$request->input('price-max')]);
                })
                ->when($request->input('rate-min'), function ($q) use ($request) {
                    return $q->where('avg_stars', '>', [$request->input('rate-min')]);
                })
                ->when($request->input('rate-max'), function ($q) use ($request) {
                    return $q->where('avg_stars', '<', [$request->input('rate-max')]);
                });
            switch ($request->order) {
                case 'price-asc':
                    $query = $query->orderBy('price');
                    break;
                case 'price-desc':
                    $query = $query->orderByDesc('price');
                    break;
                case 'rate-asc':
                    $query = $query->orderBy('avg_stars');
                    break;
                case 'rate-desc':
                    $query = $query->orderByDesc('avg_stars');
                    break;
            }

            $pageSize = $request->input('page-size');
            $page = $request->page;

            $count = $query->count();

            $lastPage = floor($count / $pageSize);

            if ($request->page > $lastPage) {
                $page = $lastPage;
            }

            $query = $query->skip($page * $pageSize)->take($pageSize);

            return response()->json([
                "lastPage" => $lastPage,
                "currentPage" => intval($page),
                "docCount" => $count,
                "query" => $query->get()
            ]);
        } catch (Exception $e) {
            dd($e);
            return response()->json(
                ['message' => 'Unexpected error'],
                401
            );
        }
    }
}
