<?php

namespace App\Policies;

use App\Models\Shopper;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Auth;

class ShopperPolicy {
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user) {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Shopper  $shopper
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Shopper $shopper) {
        //
    }

    /**
     * Determine whether the user can view the cart.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Shopper  $shopper
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewCart(User $user) {
        return $user->is_admin
            ? Response::deny('No such page for admin.')
            : Response::allow();
    }

    /**
     * Determine whether the user can view its orders.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Shopper  $shopper
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewOrders(User $user) {
        return $user->is_admin
            ? Response::deny('No such page for admin.')
            : Response::allow();
    }

    /**
     * Determine whether the user can view the checkout page.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewCheckout(User $user, Shopper $shopper) {
        if ($user->is_admin)
            return Response::deny('No such page for admin.');

        return Response::allow();
    }

    /**
     * Determine whether the user can view the address the shopper owns.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Shopper  $shopper
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewUserAddresses(User $user, Shopper $shopper) {

        return $user->is_admin || $shopper->id == $user->id;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user) {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Shopper  $shopper
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Shopper $shopper) {
        //
    }

    /**
     * Determine whether the user can update the cart.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Shopper  $shopper
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function updateCart(User $user) {
        return $user->is_admin
            ? Response::deny('No such page for admin.')
            : Response::allow();
    }


    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Shopper  $shopper
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Shopper $shopper) {
        //
    }

        /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Shopper  $shopper
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function block(User $user)
    {
        return $user->is_admin;
    }


    /**
     * Determine whether the user can delete a product from the Cart.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Shopper  $shopper
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function deleteFromCart(User $user) {
        return $user->is_admin
            ? Response::deny('No such page for admin.')
            : Response::allow();
    }

    /**
     * Determine whether the user can manage the Wishlist.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Shopper  $shopper
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function manageWishlist(User $user) {
        return $user->is_admin
            ? Response::deny('No such page for admin.')
            : Response::allow();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Shopper  $shopper
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Shopper $shopper) {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Shopper  $shopper
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Shopper $shopper) {
        //
    }
}
