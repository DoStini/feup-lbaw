<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\Review;
use App\Models\Shopper;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReviewPolicy
{
    use HandlesAuthorization;

   /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Review $review)
    {
        //
    }

    /**
     * Determine whether the user can create a review to a product.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function reviewProduct(User $user, Product $product)
    {
        if($user->is_admin) {
            return false;
        }

        /**
         * @var Shopper
         */
        $shopper = Shopper::find($user->id);

        $count = $shopper->orders()
        ->leftJoin('order_product_amount', 'order.id', '=', 'order_product_amount.order_id')
        ->where([
            ['product_id', '=', $product->id],
            ['status', '=', 'shipped'],
        ])->count();

        return $count > 0;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Review $review)
    {
        return $review->creator_id === $user->id;
    }

    /**
     * Determine wheter the user can vote on a review
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function voteOnReview(User $user, Review $review) {
        if($user->is_admin) {
            return false;
        }

        return $review->creator_id !== $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Review $review)
    {
        return $user->is_admin || $review->creator_id === $user->id;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Product $product)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Product $product)
    {
        //
    }
}
