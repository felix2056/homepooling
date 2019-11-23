<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminController extends Controller
{
	public function home(){
		$now=Carbon::now();
		$week=[
			'properties'=>\App\Property::where('created_at','>=',$now->subWeek())->count(),
			'wanteds'=>\App\Wanted::where('created_at','>=',$now->subWeek())->count(),
			'orders'=>\App\Order::where('created_at','>=',$now->subWeek())->count(),
			'earnings'=>\App\Order::where('created_at','>=',$now->subWeek())->sum('amount')
		];
		$month=[
			'properties'=>\App\Property::where('created_at','>=',$now->subMonth())->count(),
			'wanteds'=>\App\Wanted::where('created_at','>=',$now->subMonth())->count(),
			'orders'=>\App\Order::where('created_at','>=',$now->subMonth())->count(),
			'earnings'=>\App\Order::where('created_at','>=',$now->subMonth())->sum('amount')
		];
		$wanteds=\App\Wanted::orderBy('created_at','DESC')->take(3)->get();
		$users=\App\User::orderBy('created_at','DESC')->take(3)->get();
		$properties=\App\Property::orderBy('created_at','DESC')->take(3)->get();
		$orders=\App\Order::orderBy('created_at','DESC')->take(3)->get();
		
		return view('admin.home',compact('properties','users','orders','week','month','wanteds'));
	}

	public function profile(\App\User $user){
		$amenities=\App\Amenity::all();
		$acceptings=\App\Amenity::all();
		$p_amenities=array();
		if(isset($user->preferences->amenities)){
			foreach($user->preferences->amenities as $a){
				array_push($p_amenities,$a->id);
			}
		}
		return view('admin.profile',compact('user','amenities','acceptings','p_amenities'));
	}
	public function profiles(){
		$users=\App\User::paginate(30);
		return view('admin.profiles',compact('users'));
	}
	public function property(\App\Property $property){
		$p_amenities=array();
		foreach($property->amenities as $a){
			array_push($p_amenities,$a->id);
		}
		$p_acceptings=array();
		foreach($property->acceptings as $acc){
			array_push($p_acceptings,$acc->id);
		}
		
		$rooms=$property->rooms;
		$p_images=$property->images;
		$p_contract=$property->contract();
		$acceptings=\App\Accepting::all();
		$amenities=\App\Amenity::all();
		return view('admin.property',compact('property','rooms','p_amenities','p_acceptings','images','contract','amenities','acceptings'));
	}
	public function properties(){
		$properties=\App\Property::paginate(30);
		return view('admin.properties',compact('properties'));
	}
	public function wanted(\App\Wanted $wanted){
		$p_amenities=array();
		foreach($wanted->amenities as $a){
			array_push($p_amenities,$a->id);
		}
		$p_acceptings=array();
		foreach($wanted->acceptings as $acc){
			array_push($p_acceptings,$acc->id);
		}
		
		$acceptings=\App\Accepting::all();
		$amenities=\App\Amenity::all();
		return view('admin.wanted',compact('wanted','p_amenities','p_acceptings','amenities','acceptings'));
	}
	public function wanteds(){
		$wanteds=\App\Wanted::paginate(30);
		return view('admin.wanteds',compact('wanteds'));
	}
	public function orders(){
		$orders=\App\Order::paginate(20);
		return view('admin.orders',compact('orders'));
	}
	public function reports(){
		$reports=\App\Report::paginate(20);
		return view('admin.reports',compact('reports'));
	}
	public function destroyReport(\App\Report $report,Request $request){
		$report->delete();
		return redirect('/back-office/reports')->with('message','Report successfully deleted!');
	}
}


