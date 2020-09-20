<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckType
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::user()->DASH_TYPE_ID != 1) {
            $modules = Auth::user()->modules()->pluck('MDUL_NAME')->all();
            $checker = '';
            if (request()->is('clients/*')) {
                $checker = "Clients";
            } else if (request()->is('accounts/*') || request()->is('cash/*') || request()->is('invoice/*')) {
                $checker = "Accounts";
            } else if (request()->is('orders/*')) {
                $checker = 'Orders';
            } else if (request()->is('inventory/*') || request()->is('raw/*')) {
                $checker = "Stock";
            } else if (request()->is('products/*')) {
                $checker = "Settings";
            } else if (request()->is('clients/*')) {
                $checker = "Clients";
            } else if (request()->is('drivers/*')) {
                $checker = "Settings";
            } else if (request()->is('areas/*') || request()->is('paymentoptions/*')) {
                $checker = "Settings";
            } else if (request()->is('slots/*')) {
                $checker = "Settings";
            } else if (request()->is('rawmaterials/*')) {
                $checker = "Accounts";
            } else if (request()->is('categories/*') || request()->is('subcategories/*')) {
                $checker = "Settings";
            } else if (request()->is('dash/*')) {
                $checker = "Dashboard Admins";
            } else if (request()->is('suppliers/*')) {
                $checker = "Suppliers";
            }


            if (!in_array($checker, $modules)) {
                return abort(404);
            }
        }
        return $next($request);
    }
}
