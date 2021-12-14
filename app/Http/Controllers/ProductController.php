<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Product;

class ProductController extends Controller {

    protected $defaultPageSize = 10;

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
                return $q->where('price > ?', [$request->input('price-min')]);
            })
            ->when($request->input('price-max'), function ($q) use ($request) {
                return $q->whereRaw('price < ?', [$request->input('price-max')]);
            })
            ->when($request->input('stars-min'), function ($q) use ($request) {
                return $q->whereRaw('avg_stars > ?', [$request->input('stars-min')]);
            })
            ->when($request->input('stars-max'), function ($q) use ($request) {
                return $q->whereRaw('avg_stars < ?', [$request->input('stars-max')]);
            });

        switch ($request->order) {
            case 'price-asc':
                $query = $query->orderByAsc('price');
                break;
            case 'price-desc':
                $query = $query->orderByAsc('price');
                break;
            case 'rate-asc':
                $query = $query->orderByAsc('avg_stars');
                break;
            case 'rate-desc':
                $query = $query->orderByAsc('avg_stars');
                break;
        }

        $count = $query->count();
        $page = ($request->page - 1) ?? 0;
        $pageSize = $request->input('page-size') ?? $this->defaultPageSize;
        $lastPage = ceil($count / $pageSize);
        if ($page > $lastPage) {
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
