<?php

namespace App\Providers;

use App\Models\Address;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Shopper;
use App\Policies\AddressPolicy;
use App\Policies\CouponPolicy;
use App\Policies\UserPolicy;
use App\Policies\OrderPolicy;
use App\Policies\PaymentPolicy;
use App\Policies\PhotoPolicy;
use App\Policies\ProductPolicy;
use App\Policies\ShopperPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
      Address::class => AddressPolicy::class,
      Coupon::class => CouponPolicy::class,
      Order::class => OrderPolicy::class,
      Payment::class => PaymentPolicy::class,
      Photo::class => PhotoPolicy::class,
      Product::class => ProductPolicy::class,
      Shopper::class => ShopperPolicy::class,
      User::class => UserPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('isAdmin', function (User $user) {
          return $user->is_admin;
        });

    }
}
