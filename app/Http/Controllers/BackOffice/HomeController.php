<?php

namespace App\Http\Controllers\BackOffice;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\user;
use App\expert;
use Auth;
class HomeController extends Controller
{
  /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return Response
     */
    public function index()
    {
        //dd(Auth::user()->userable);
        //'type'  =>   get_class(Auth::user()->userable),
    	$data = array(
            'user'  =>    Auth::user(),
            'type'  =>   get_class(Auth::user()->userable),
        );

        return view('BackOffice/home',$data);
    }
}
