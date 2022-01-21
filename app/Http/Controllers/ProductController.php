<?php

namespace App\Http\Controllers;

use App\Events\CartUpdate;
use App\Events\WishlistUpdate;
use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exceptions\ApiError;
use App\Models\Category;
use App\Models\Notification;
use Craft\StringHelper;


use App\Models\Product;
use App\Models\Review;
use App\Models\Shopper;
use App\Models\User;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller {


    /**
     * Shows the product for a given id.
     *
     * @param  int  $id
     * @return Response
     */
    public function show(Request $req, $id) {
        $product = Product::findOrFail($id);

        if (!$product->is_active) {
            return abort(404);
        }
        $user = Auth::user();
        $wishlisted = false;
        if ($user && !$user->is_admin) {
            $shopper = Shopper::find($user->id);
            if ($shopper->wishlist->contains($product)) {
                $wishlisted = true;
            }
        }

        $reviews = ReviewController::getProductReviews($id)->paginate($req->review_size ?? 5);
        $reviewCount = ReviewController::getProductReviews($id)->count();

        return view('pages.product', ['product' => $product, 'wishlisted' => $wishlisted, 'reviews' => $reviews, 'reviewCount' => $reviewCount]);
    }

    /**
     * Shows the search products page.
     *
     * @return Response
     */
    public function search() {
        $categories = Category::where('parent_category', NULL)->get();
        return view('pages.search.products', ['categories' => $categories]);
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
        $user = Auth::user();
        try {
            $cat_selected = false;
            $query = Product
                ::with("photos")
                ->when(
                    $user,
                    fn ($q) =>
                    $q->leftJoin('wishlist', fn ($join) =>
                    $join->on('product.id', '=', 'wishlist.product_id')
                        ->where('wishlist.shopper_id', '=', $user->id))
                )
                ->where('is_active', '=', 'true')
                ->leftJoin('product_category', 'product_category.product_id', '=', 'product.id')
                ->join('category', 'product_category.category_id', '=', 'category.id');


            $category_array = $request->input('categories');

            if ($category_array != []) {
                $cat_selected = true;
                $current_idx = 0;
                while (count($category_array) > $current_idx) {
                    $category = Category::find($category_array[$current_idx]);

                    $child_categories = $category->child_categories;
                    foreach ($child_categories as $child) {
                        array_push($category_array, strval($child->id));
                    }
                    $current_idx++;
                }
                $query = $query->whereIn('category.id', $category_array);
            }

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
                default:
                    $query = $query->orderBy('product.name');
            }

            $query = $query->when($request->text, function ($q) use ($request) {
                $words = explode(' ', $request->text);
                foreach ($words as &$word)
                    $word = $word . ':*';
                $val = implode(' & ', $words);
                return $q->where(function ($query) use ($val, $request) {
                    $query->whereRaw('tsvectors @@ to_tsquery(\'simple\', ?)', [$val])
                        ->orWhereRaw('tsvectors @@ plainto_tsquery(\'english\', ?)', [$request->text])
                        ->orderByRaw('ts_rank(tsvectors, plainto_tsquery(\'english\', ?)) DESC', [$request->text])
                        ->orderByRaw('ts_rank(tsvectors, to_tsquery(\'simple\', ?)) DESC', [$val]);
                });
            })
                ->when($request->input('price-min'), function ($q) use ($request) {
                    return $q->where('price', '>=', [$request->input('price-min')]);
                })
                ->when($request->input('price-max'), function ($q) use ($request) {
                    return $q->where('price', '<=', [$request->input('price-max')]);
                })
                ->when($request->input('rate-min'), function ($q) use ($request) {
                    return $q->where('avg_stars', '>=', [$request->input('rate-min')]);
                })
                ->when($request->input('rate-max'), function ($q) use ($request) {
                    return $q->where('avg_stars', '<=', [$request->input('rate-max')]);
                });

            $subquery = Product::selectRaw('name as unique_name, max(id) as max_id')->groupBy('name');

            $query = $query->joinSub($subquery, 'prod', function($join) {
                $join->on('product.name', '=', 'prod.unique_name');
                $join->on('product.id', '=', 'prod.max_id');
            });

            $pageSize = $request->input('page-size');
            $page = $request->page;

            $count = $query->count();

            $lastPage = floor($count / $pageSize);

            if ($request->page > $lastPage) {
                $page = $lastPage;
            }

            $query = $query->skip($page * $pageSize)->take($pageSize);

            $searchParams = json_decode('{}');

            if ($request->input('categories')) {
                $searchParams->catNames = array_map(function ($id) {
                    return Category::find($id)->name;
                }, $request->input('categories'));
            }

            if ($request->input('price-min') != null) $searchParams->minPrice = $request->input('price-min');
            if ($request->input('price-max') != null) $searchParams->maxPrice = $request->input('price-max');
            if ($request->input('rate-min') != null) $searchParams->minRating = $request->input('rate-min');
            if ($request->input('rate-max') != null) $searchParams->maxRating = $request->input('rate-max');

            if ($request->input('order')) $searchParams->order = $request->input('order');

            if ($request->text) $searchParams->text = $request->text;

            $query_params = ['product.*', 'category.name AS cat_name'];

            if ($user) {
                $query_params = array_merge(
                    $query_params,
                    ['wishlist.shopper_id AS wishlisted'],
                );
            }

            $query_obj = $query->get($query_params);

            return response()->json([
                "lastPage" => $lastPage,
                "currentPage" => intval($page),
                "docCount" => $count,
                "query" => $this->serializeQuery($query_obj),
                "searchParams" => $searchParams
            ]);
        } catch (Exception $e) {
            dd($e);
            return response()->json(
                ['message' => 'Unexpected error'],
                401
            );
        }
    }

    public function datatableList(Request $request) {
        $this->authorize('viewAny', Product::class);

        $dc =  new DatatableController();
        return $dc->get($request, DB::table('product_filtered'));
    }

    private function getValidatorAddProduct(Request $request) {
        return Validator::make($request->all(), [
            "name" => "required|string|max:100",
            "variantCheck" => "nullable|string|max:3",
            "originVariantID" => "nullable|integer",
            "colorVariant" => "nullable|string",
            "stock" => "required|integer|min:0",
            "description" => "nullable|string|max:2048",
            "category-id" => "required|integer|min:1|exists:category,id",
            "photos" => "required",
            "price" => "required|numeric|min:0",
        ]);
    }

    private function getValidatorEditProduct(Request $request) {
        return Validator::make($request->all(), [
            "name" => "nullable|string|max:100",
            "attributes" => "nullable|json",
            "stock" => "nullable|integer|min:0",
            "description" => "nullable|string|max:2048",
            "price" => "nullable|numeric|min:0",
            "category-id" => "nullable|integer|min:1|exists:category,id"
        ]);
    }

    private function getValidatorPhotos($photos) {
        $messages = [];

        foreach ($photos as $key => $val) {
            $messages[$key . '.image'] = $val->getClientOriginalName() . " must be an image.";
        }

        return Validator::make($photos, [
            "*" => "file|image"
        ], $messages);
    }

    public function editProduct(Request $request) {

        $this->authorize('update', Product::class);

        $product = Product::findOrFail($request->route('id'));

        $validator = $this->getValidatorEditProduct($request);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $photos = $request->file('photos') ?? [];
        $validator = $this->getValidatorPhotos($photos);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $savedPhotos = [];

        try {
            DB::beginTransaction();

            $oldStock = $product->stock;
            $oldPrice = $product->price;

            $product->update(array_filter(
                [
                    "name" => $request->input('name'),
                    "attributes" => $request->input('attributes'),
                    "stock" => $request->input('stock'),
                    "description" => $request->input('description'),
                    "price" => $request->input('price'),
                ],
                fn ($elem) => $elem != null && $elem !== "",
            ));

            $categoryId = $request['category-id'];

            if ($categoryId) {
                $product->categories()->detach();
                $category = Category::find($categoryId);
                $product->categories()->attach($category);
            }

            if ($oldPrice != $product->price) {

                foreach ($product->usersCart as $userCart) {
                    $user = Shopper::find($userCart->id);
                    $not = new Notification();
                    $not->shopper = $user->id;
                    $not->type = "cart";
                    $not->product_id = $product->id;
                    $not->save();
                    event(new CartUpdate($user->id, $product->id));
                }
            }

            if ($oldStock == 0 && $product->stock > 0) {
                foreach ($product->usersWishlisted as $userWishlist) {
                    $user = Shopper::find($userWishlist->id);
                    $not = new Notification();
                    $not->shopper = $user->id;
                    $not->type = "wishlist";
                    $not->product_id = $product->id;
                    $not->save();

                    event(new WishlistUpdate($user->id, $product->id));
                }
            }


            foreach ($photos as $productPhoto) {
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
        } catch (QueryException $ex) {
            DB::rollBack();

            Storage::disk('public')->delete($savedPhotos);
            return redirect()->back()->withErrors(["product" => "Unexpected Error"])->withInput();
        }

        return redirect(route("getProduct", ["id" => $product->id]));
    }


    private function validateRemoveProductPhoto(Request $request) {
        return Validator::make(
            [
                'id' => $request->route('id'),
                'photo_id' => $request->route('photo_id'),
            ],
            [
                "id" => "required|integer|min:1|exists:product,id",
                "photo_id" => "required|integer|min:1|exists:product_photo,photo_id",
            ]
        );
    }

    public function removeProductPhoto(Request $request) {

        $this->authorize('update', Product::class);

        if (($v = $this->validateRemoveProductPhoto($request))->fails()) {
            return ApiError::validatorError($v->errors());
        }

        $product = Product::findOrFail($request->route("id"));

        if ($product->photos()->count() == 1) {
            return ApiError::notEnoughPhotos();
        }

        $product->photos()->detach($request->route("photo_id"));

        return response("");
    }

    public function addProduct(Request $request) {

        $this->authorize('create', Product::class);

        $validator = $this->getValidatorAddProduct($request);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $photos = $request->file('photos');
        $validator = $this->getValidatorPhotos($photos);
        if ($validator->fails()) {
            $errors = $validator->errors()->messages();
            $response = [];
            $response['photos'] = [];

            foreach ($errors as $key => $value) {
                array_push($response['photos'], $value[0]);
            }

            return redirect()->back()->withErrors($response)->withInput();
        }

        $savedPhotos = [];
        $variants = json_decode('{}', true);

        $colorInfo = $request->input('variantColor');

        if ($request->input('originVariantID')) {
            $origin = Product::find($request->input('originVariantID'));

            if (!$origin || $origin->attributes == '{}') {
                return redirect()->back()->withErrors(["originVariantID" => "No such product with variants"])->withInput();
            }

            $variants = json_decode($origin->attributes, true)['variants'];
        }

        try {
            DB::beginTransaction();

            if ($colorInfo) {
                $attributes = json_decode('{}', true);
                $id = DB::select('SELECT last_value FROM product_id_seq')[0]->last_value;
                $variants[strval($id + 1)] = strToLower(implode("-", explode(" ", $colorInfo)));
                $attributes['variants'] = $variants;
                $attributes['color'] = $colorInfo;
            } else $attributes = json_decode('{}');


            $product = Product::create([
                "name" => $request->input('name'),
                "attributes" => json_encode($attributes),
                "stock" => $request->input('stock'),
                "description" => $request->input('description'),
                "price" => $request->input('price'),
            ]);

            $product->categories()->attach($request->input('category-id'));

            foreach ($variants as $prodID => $color) {
                if ($prodID == $id + 1) continue;
                $productToUpdate = Product::find($prodID);
                $productAttributes = json_decode($productToUpdate->attributes, true);
                $productAttributes['variants'] = $variants;
                $productToUpdate->update(['attributes' => json_encode($productAttributes)]);
            }

            foreach ($photos as $productPhoto) {
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
        } catch (QueryException $ex) {
            DB::rollBack();

            Storage::disk('public')->delete($savedPhotos);

            return redirect()->back()->withErrors(["product" => "Unexpected Error"])->withInput();
        }

        return redirect(route("getProduct", ["id" => $product->id]));
    }

    public function addProductImage(Request $request) {

        $product = Product::findOrFail($request->route('id'));

        $this->authorize('update', Product::class);

        $photos = $request->file('photos') ?? [];

        $validator = $this->getValidatorPhotos($photos);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $savedPhotos = [];

        foreach ($photos as $productPhoto) {
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

        return redirect()->back();
    }

    public function getAddProductPage() {
        $this->authorize('create', Product::class);
        return view('pages.addProduct');
    }

    public function getEditProductPage(Request $request) {
        $product = Product::findOrFail($request->route('id'));

        $this->authorize('update', Product::class);

        return view('pages.editproduct', ['product' => $product]);
    }

    private function validateVariants(Request $request) {
        return Validator::make($request->all(), [
            'code' => 'required|string|min:1',
        ]);
    }

    public function removeProduct(Request $request) {
        $product = Product::findOrFail($request->route('id'));

        try {
            DB::beginTransaction();
            $product->update([
                "is_active" => false,
            ]);

            $product->usersCart()->detach();
            $product->usersWishlisted()->detach();

            DB::commit();
        } catch (Exception $e) {
            dd($e);
            DB::rollBack();
            throw $e;
        }


        return response("");
    }

    /**
     * Retrieves possible variants for a given input
     */
    public function variants(Request $request) {
        if (($v = $this->validateVariants($request))->fails()) {
            return ApiError::validatorError($v->errors());
        }

        $colors = DB::select('select distinct (attributes ->> \'color\') as text from product where LOWER(attributes ->> \'color\') LIKE LOWER(\'%' . $request->code . '%\');');

        foreach ($colors as $color) {
            $color->colorCode = strToLower(implode("-", explode(" ", $color->text)));
        }

        return $colors;
    }
}
