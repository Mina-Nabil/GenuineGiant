<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    public function login()
    {
        $data['username'] = '';
        $data['first'] = true;
        return view('auth/login', $data);
    }

    public function authenticate(Request $request)
    {
        if (Auth::check()) return redirect('/home');

        $userName = $request->input('userName');
        $passWord = $request->input('passWord');

        $data['first'] = true;

        if (isset($userName)) {
            if (Auth::attempt(array('DASH_USNM' => $userName, 'password' => $passWord), true)) {
                return redirect('/home');
            } else {
                $data['first'] = false;
                $data['username'] = $userName;
                return view('auth/login', $data);
            }
        } else {
            redirect("login");
        }
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if (Auth::check()) {
            //Totals by Sub category
            $data['catgGraphs'] =  [
                ['color' => "info", "name" => "Beef"],
                ['color' => "success", "name" => "Chicken"],
            ];
            $data['catgTotals'] =  [
                ["name" => "Beef", "value" => "100", "unit" => "EGP" ],
                ["name" => "Chicken", "value" => "200", "unit" => "EGP" ],
            ];
            $data['catgCardTitle'] =  "Total Sales By Subcategories";
            $data['catgTitle'] =  "Totals Sales";
            $data['catgSubtitle'] =  "Check total money recieved for each subcategory";

            //Totals Sales
            $data['totalGraphs'] =  [];
            $data['totalTotals'] =  [];
            $data['totalCardTitle'] =  "Total Revenue";
            $data['totalTitle'] =  "Overall Sales Total";
            $data['totalSubtitle'] =  "Check total money recieved and number of items sold";

            //Total Sales
            return view('home', $data);
        }
        else return redirect("login");
    }

    public function logout(){
        Auth::logout();
        return redirect("login");
    }
}
