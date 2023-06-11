<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\services\wialonSystemService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except("login");
    }

    public function login(Request $request)
    {
        $data = $request->all();
        $user = User::first();
        Cache::put("access_token",$data["access_token"]);
        wialonSystemService::login($data["access_token"]);
        Auth::login($user);
        return redirect()->route("home");
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }
}
