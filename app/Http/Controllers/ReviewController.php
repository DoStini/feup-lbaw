<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller {
    private function getReviewValidator($data) {
        return Validator::make($data, [
            "product_id" => "required|min:1|integer|exists:product,id",
            "stars" => "required|min:0|max:5|integer",
            "text" => "required|between:1,512",
            "photos" => "nullable",
        ], [], [
            "product_id" => "product ID",
            "stars" => "rating",
            "text" => "review body",
        ]);
    }

    private function getValidatorPhotos($photos) {
        $messages = [];

        foreach ($photos as $key => $val) {
            $messages[$key . '.image'] = $val->getClientOriginalName() . " must be an image.";
        }

        return Validator::make($photos, [
            "*" => "nullable|file|image"
        ], $messages);
    }


    public function addReview(Request $req, $product_id) {
        $product = Product::findOrFail($product_id);

        $this->authorize('reviewProduct', [Review::class,  $product]);

        $validator = $this->getReviewValidator(array_merge($req->all(), ["product_id" => $product_id]));
        if($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $photos = $req->file('photos');
        if($photos != null) {
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
        }

        $savedPhotos = [];

        try {
            DB::beginTransaction();

            $review = new Review();

            $review->stars = $req->stars;
            $review->text = $req->text;
            $review->product_id = $product_id;
            $review->creator_id = Auth::user()->id;

            $review->save();

            if($photos != null) {
                foreach ($photos as $reviewPhoto) {
                    $path = $reviewPhoto->storePubliclyAs(
                        "images/review",
                        "review" . $review->id . "-" . uniqid() . "." . $reviewPhoto->extension(),
                        "public"
                    );

                    array_push($savedPhotos, $path);

                    $public_path = "/storage/" . $path;
                    $photo = Photo::create(["url" => $public_path]);

                    $review->photos()->attach($photo->id);
                }
            }

            DB::commit();
        } catch (QueryException $ex) {
            DB::rollBack();

            Storage::disk('public')->delete($savedPhotos);
            return redirect()->back()->withErrors(["review" => "Unexpected Error"])->withInput();
        }

        return redirect(route("getProduct", ["id" => $product_id]));
    }
}
