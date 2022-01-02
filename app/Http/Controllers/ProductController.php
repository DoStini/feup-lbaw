<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Product;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

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
        return view('pages.search.products');
    }

    /**
     * Serializes the query
     */
    public function serializeQuery($query) {
        return array_map(function ($entry) {
            $entry->photos = array_map(fn ($photo) => $photo->url, $entry->photos);
            $entry->attributes = json_decode($entry->attributes);
            return $entry;
        }, json_decode(json_encode($query)));
    }

    /**
     * Search products according to filters in the query
     *
     * @return Response
     */
    public function list(Request $request) {
        try {
            $query = Product
                ::with("photos")
                ->whereRaw('stock > 0')
                ->when($request->text, function ($q) use ($request) {
                    $words = explode(' ', $request->text);
                    foreach($words as &$word)
                        $word = $word . ':*';
                    $val = implode(' & ', $words);
                    return $q->whereRaw('tsvectors @@ to_tsquery(\'simple\', ?)', [$val])
                        ->orWhereRaw('tsvectors @@ plainto_tsquery(\'english\', ?)', [$request->text])
                        ->orderByRaw('ts_rank(tsvectors, plainto_tsquery(\'english\', ?)) DESC', [$request->text])
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
                "query" => $this->serializeQuery($query->get())
            ]);
        } catch (Exception $e) {
            return response()->json(
                ['message' => 'Unexpected error'],
                401
            );
        }
    }

    private function getValidatorAddProduct(Request $request) {
        return Validator::make($request->all(), [
            "name" => "required|string|max:100",
            "attributes" => "nullable|json",
            "stock" => "required|integer|min:0",
            "description" => "nullable|string|max:255",
            "photos" => "required",
            "price" => "required|numeric|min:0",
        ]);
    }

    private function getValidatorPhotos($photos) {
        $messages = [];

        foreach ($photos as $key => $val) {
            $messages[$key.'.image'] = $val->getClientOriginalName() . " must be an image.";
        }

        return Validator::make($photos, [
            "*" => "file|image"
        ], $messages);
    }

    public function addProduct(Request $request) {
        $validator = $this->getValidatorAddProduct($request);
        if($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $photos = $request->file('photos');
        $validator = $this->getValidatorPhotos($photos);
        if($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $savedPhotos = [];

        try {
            DB::beginTransaction();

            $product = Product::create([
                "name" => $request->input('name'),
                "attributes" => $request->input('attributes'),
                "stock" => $request->input('stock'),
                "description" => $request->input('description'),
                "price" => $request->input('price'),
            ]);

            foreach($photos as $productPhoto) {
                $path = $productPhoto->storePubliclyAs(
                    "images/product",
                    "product" . $product->id . "-" . uniqid() . "." . $productPhoto->extension(),
                    "public"
                );

                array_push($savedPhotos, $path);

                $public_path = "/storage/" . $path;
                $photo = Photo::create(["url" => $public_path]);

                $product->photos()->attach($photo->id);
            }

            DB::commit();
        } catch(QueryException $ex) {
            DB::rollBack();

            Storage::disk('public')->delete($savedPhotos);
            return redirect()->back()->withErrors(["product" => "Unexpected Error"])->withInput();
        }

        return redirect(route("getProduct", ["id" => $product->id]));
    }
}
