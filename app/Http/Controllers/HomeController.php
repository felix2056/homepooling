<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Slider;
use App\Setting;
use App\Property;
use App\User;
use App\Wanted;

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
        $sliders = Slider::orderBy('order','asc')->get()->take(10);
        $timeline = Setting::where('meta_key','timeline')->orderBy('id','desc')->get();

		$now=Carbon::now();
		$properties = Property::where('status','!=','pending')->orderBy('created_at','DESC')->with('visits')->take(9)->get();
// 		$properties=\App\Property::where('status','!=','pending')->orderBy('early_access','DESC')->orderBy('updated_at','DESC')->orderBy('created_at','DESC')->with('visits')->take(3)->get();
		$users = User::orderBy('created_at','DESC')->take(5)->get();
		$latest = Wanted::orderBy('created_at','DESC')->take(3)->get();
		
        return view('home',compact('sliders','timeline','properties','users','now','latest'));
    }
}
