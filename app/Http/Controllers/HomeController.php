<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
//         $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		$now=Carbon::now();
		$properties=\App\Property::where('status','!=','pending')->orderBy('created_at','DESC')->with('visits')->take(3)->get();
// 		$properties=\App\Property::where('status','!=','pending')->orderBy('early_access','DESC')->orderBy('updated_at','DESC')->orderBy('created_at','DESC')->with('visits')->take(3)->get();
		$users=\App\User::orderBy('created_at','DESC')->take(5)->get();
		$latest=\App\Wanted::orderBy('created_at','DESC')->take(3)->get();
		return view('home',compact('properties','users','now','latest'));
    }
}
