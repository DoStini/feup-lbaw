<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Shopper;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Events\Registered;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use stdClass;

class AdminController extends Controller {


    /**
     * Where to redirect after admin registration.
     *
     * @var string
     */
    public function adminRedirectTo() {
        return '/admin/users';
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function registerAdmin(Request $request) {

        $this->authorize('createAdmin', User::class);

        $this->validator($request->all())->validateWithBag('admin_register_form');

        $values = $request->all();

        $values['is_admin'] = true;

        event(new Registered($this->create($values)));

        return $request->wantsJson()
            ? new JsonResponse([], 201)
            : redirect($this->adminRedirectTo());
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data) {
        return Validator::make($data, [
            'name' => 'required|string|max:100',
            'email' => 'required|string|email:rfc,dns|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed'
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data) {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'is_admin' => $data['is_admin'] ?? false
        ]);
    }

    /**
     * Shows the homepage
     *
     * @return Response
     */
    public function getDashboard() {
        Gate::authorize('isAdmin');
        return redirect(route('getOrderDashboard'));
    }

    public function getNewAdminPage() {
        Gate::authorize('isAdmin');

        $user = Auth::user();

        return view('pages.createNewAdmin', ['admin' => $user,]);
    }

    public function getOrderDashboard() {

        Gate::authorize('isAdmin');

        $info = new stdClass();

        $info->updatableOrders = Order::where('status', '<>', 'shipped')
            ->leftJoin('users', 'order.shopper_id', '=', 'users.id')
            ->orderBy('status')
            ->orderBy('timestamp', 'asc')
            ->get(['order.id', 'name', 'shopper_id', 'timestamp', 'total', 'status']); //need to add created_at and updated_at in sql

        $info->finishedOrders = Order::where('status', '=', 'shipped')
            ->leftJoin('users', 'order.shopper_id', '=', 'users.id')
            ->orderBy('status')
            ->orderBy('timestamp', 'asc')
            ->get(['order.id', 'name', 'shopper_id', 'timestamp', 'total', 'status']); //need to add created_at and updated_at in sql

        return view('pages.orderDashboard', ['admin' => Auth::user(), 'info' => $info]);
    }

    public function getUserDashboard() {

        Gate::authorize('isAdmin');

        return view('pages.userDashboard', ['admin' => Auth::user()]);
    }
}
