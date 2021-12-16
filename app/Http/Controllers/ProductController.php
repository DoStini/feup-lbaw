<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    public function list(Request $request) {
        $query = DB::table('product')
            ->whereRaw('stock > 0')
            ->when($request->text, function ($q) use ($request) {
                return $q->whereRaw('tsvectors @@ plainto_tsquery(\'english\', ?)', [$request->text])
                    ->orderByRaw('ts_rank(tsvectors, plainto_tsquery(\'english\', ?)) DESC', [$request->text]);
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

        $lastPage = ceil($count / $pageSize);

        if ($request->page > $lastPage) {
            $page = $lastPage - 1;
        }

        $query = $query->skip($page * $pageSize)->take($pageSize);

        return response()->json([
            "lastPage" => $lastPage,
            "docCount" => $count,
            "query" => $query->get()
        ]);
    }
}
