<?php

namespace App\Http\Controllers;

use App\Wanted;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Notifications\PaymentNotification;
use App\Http\Requests\WantedsFSURequest;
use App\Http\Requests\SanitizeRequest;
use App\Http\Requests\PackageSRequest;

class WantedController extends PaymentController
{

	public function __construct()
	{
		$this->middleware('auth')->only('create','store','update','edit','destroy');
		$this->middleware('ownoradmin')->only('update','edit','destroy');
	}

	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
	public function index()
	{
		$amenities=\App\Amenity::all();
		$acceptings=\App\Accepting::all();
		return view('wanteds',compact('amenities','acceptings'));
	}

	public function fetch(SanitizeRequest $request){
		$wanteds=Wanted::orderBy('early_access','DESC')->orderBy('on_top_until','DESC')->orderBy('created_at','DESC');
		
		if($request->has('budget')) $wanteds->where('price_range',$request->budget);
		if($request->has('property_type')) $wanteds->where('type',$request->property_type);
		if($request->has('epc')){
			$arr=array('a','b','c','d','e','f','g');
			$arr=array_slice($arr,0,array_search($request->epc,$arr)+1);
			$wanteds->whereIn('epc',$arr);
		}
		if($request->has('age')){
			$wanteds->whereHas('user',function($query) use ($request){
				$ages=explode('-',$request->age);
				if(count($ages)>1){
					$min=$ages[0];
					$max=$ages[1];
				}elseif(count($ages)==1){
					$min=str_replace('+','',$ages[0]);
				}
				
				isset($max) ? $query->whereBetween('age',[$min,$max]) : $query->where('age','>=',$min);
			});
		}
		if($request->has('gender')){
			$wanteds->whereHas('user',function($query) use ($request){
				$query->where('gender',$request->gender);
			});
		}
		if($request->has('prefs')){
			if(in_array('has_bathroom',$request->prefs)) $wanteds->where('has_bathroom',1);
			if(in_array('p_empty',$request->prefs)) $wanteds->where('p_empty',1);
			if(in_array('single',$request->prefs)) $wanteds->where('single',1);
		}
		if($request->has('amenities')&&count($request->amenities)){
			$amenities=$request->amenities;
			$wanteds->whereHas('amenities',function($query) use ($amenities){
				$arr=[];
				foreach($amenities as $am){
					array_push($arr,$am);
				}
				$query->where('name',$arr);
			});
		}
		if($request->has('acceptings')&&count($request->acceptings)){
			$acceptings=$request->acceptings;
			$wanteds->whereHas('acceptings',function($query) use ($acceptings){
				$arr=[];
				foreach($acceptings as $ac){
					array_push($arr,$ac);
				}
				$query->where('name',$arr);
			});
		}
		if($request->has('location')){
			$wanteds=$wanteds->get()->filter(function($wanted) use ($request){
				$distance=$this->distanced((float)$wanted->lat,(float)$wanted->long,(float)$request->lat,(float)$request->long,'K');
				if($distance <= ($wanted->distance)){
					return true;
				}else{
					return false;
				}
			})->values();
		}else{
			$wanteds=$wanteds->get();
		}
		$wanted_arr=[];
		foreach($wanteds as $wanted){
			array_push($wanted_arr,$wanted);
		}
		$pageLength=12;
		$stop=false;
		if(count($wanted_arr)>$pageLength){
			$wanteds=array_slice($wanted_arr,(0+(($request->page-1)*$pageLength)),$pageLength);
		}
		if(count($wanteds)<$pageLength){
			$stop=true;
		}

		$html=view('latest_wanted')->with('latest',$wanteds)->with('rendering',true)->render();
		
		return response()->json(array('success'=>true,'html'=>$html,'stop'=>$stop));
	}

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		$amenities=\App\Amenity::all();
		$acceptings=\App\Accepting::all();
		if(isset(\Auth::user()->wanted)){
			return redirect('/')->with('error','You already published a Wanted Ad!');
		}else{
			return view('wanted_form',compact('amenities','acceptings'));
		}
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(WantedsFSURequest $request)
    {
		if(\Auth::check()) $user=\Auth::user();
		if(!isset($user->wanted)||!count($user->wanted)){
			$wanted=new \App\Wanted;
			$wanted->user_id=$user->id;
			$wanted->location=$request->location;
			$wanted->lat=$request->lat;
			$wanted->long=$request->long;
			$wanted->distance=$request->range;
			$wanted->price_range=$request->price;
			$wanted->people=$request->people;
			$wanted->rooms=$request->rooms;
			$wanted->epc=$request->epc;
			$wanted->status='pending';
			$wanted->early_access=0;
			if($request->has('type')) $wanted->type=$request->type;
			if($request->has('avail_from')) $wanted->avail_from=$request->avail_from;
			if($request->has('avail_to')) $wanted->avail_to=$request->avail_to;
			
			if($request->has('has_bathroom')){
				$wanted->has_bathroom=$request->has_bathroom;
			}else{
				$wanted->has_bathroom=0;
			}
			if($request->has('p_empty')){
				$wanted->p_empty=$request->p_empty;
			}else{
				$wanted->p_empty=0;
			}
			if($request->has('single')){
				$wanted->single=$request->single;
			}else{
				$wanted->single=0;
			}
			
			$wanted->save();
			if($request->has('amenities')) $wanted->amenities()->sync($request->amenities);
			if($request->has('acceptings')) $wanted->acceptings()->sync($request->acceptings);

			return redirect('/wanteds/'.$wanted->id.'/packages');
		}
		
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Wanted  $wanted
     * @return \Illuminate\Http\Response
     */
    public function show(Wanted $wanted)
    {
		$user=\App\User::find($wanted->user_id);
		$latest=Wanted::orderBy('created_at','DESC')->take(3)->get();
		$amenities=$wanted->amenities;
		$acceptings=$wanted->acceptings;
		if($user->msg_in_remain > 0 ){
			$can_receive=true;
		}else{
			$can_receive=false;
		}
		return view('wanted',compact('amenities','acceptings','wanted','user','can_receive','latest'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Wanted  $wanted
     * @return \Illuminate\Http\Response
     */
    public function edit(Wanted $wanted)
    {
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
		return view('wanted_form',compact('amenities','acceptings','p_amenities','p_acceptings','wanted'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Wanted  $wanted
     * @return \Illuminate\Http\Response
     */
    public function update(WantedsFSURequest $request, Wanted $wanted)
    {
		if(\Auth::check()) $user=\Auth::user();
		$wanted->user_id=$user->id;
		$wanted->location=$request->location;
		$wanted->lat=$request->lat;
		$wanted->long=$request->long;
		$wanted->distance=$request->range;
		$wanted->price_range=$request->price;
		$wanted->people=$request->people;
		$wanted->rooms=$request->rooms;
		$wanted->epc=$request->epc;
		if($request->has('type')) $wanted->type=$request->type;
    		if($request->has('avail_from')&&$request->avail_from!=0) $wanted->avail_from=Carbon::createFromFormat('d/m/Y',$request->avail_from);
    		if($request->has('avail_to')&&$request->avail_to!=0) $wanted->avail_to=Carbon::createFromFormat('d/m/Y',$request->avail_to);
		
		if($request->has('has_bathroom')){
			$wanted->has_bathroom=$request->has_bathroom;
		}else{
			$wanted->has_bathroom=0;
		}
		if($request->has('p_empty')){
			$wanted->p_empty=$request->p_empty;
		}else{
			$wanted->p_empty=0;
		}
		if($request->has('single')){
			$wanted->single=$request->single;
		}else{
			$wanted->single=0;
		}
		
		$wanted->save();
		if($request->has('amenities')) $wanted->amenities()->sync($request->amenities);
		if($request->has('acceptings')) $wanted->acceptings()->sync($request->acceptings);

		return redirect('/wanteds/'.$wanted->id)->with('message','Ad successfully updated!');
	}

	public function showPackages(Wanted $wanted){
		$current=$wanted->getHighestOrder();
		return view('packages_wanteds',compact('wanted','current'));
	}
	public function choosePackage(Wanted $wanted, PackageSRequest $request){
		$now=Carbon::now();
		$order=new \App\Order;
		$order->wanted_id=$wanted->id;
		$order->user_id=$wanted->user_id;
		($wanted->status!='pending') ? $order->upgrade=1 : $order->upgrade=0;
		$order->type=$request->package;
		$user=\App\User::find($wanted->user_id);
		if($request->package!=='basic'){
			($request->package==='bump' && $wanted->status=='pending') ? $order->amount=2.99 : $order->amount=1.99;
			if($request->package==='premium') $order->amount=12.00;
			if($request->package=='messages') $order->amount=2.99;
// 			if payment is successful
			if($this->postPayWithStripe($request,$order)){
				$order->status='paid';

				if($request->package==='bump'){
					$wanted->on_top_until=$now;
					if($user->msg_in_remain < 9999) $user->msg_in_remain+=10;
					$wanted->highlighted=1;
					$wanted->status=$request->package;
				}elseif($request->package==='premium'){
					$wanted->on_top_until=$now->addDays(7);
					if($user->msg_in_remain < 9999) $user->msg_in_remain=9999;
					$wanted->early_access=1;
					$wanted->highlighted=1;
					$user->early_bird=1;
					$wanted->status=$request->package;
				}elseif($request->package=='messages'){
					$user->msg_in_remain+=5;
				}
				$user->save();
				$wanted->save();
				$order->save();
				if(!isset(\App\User::find($user->id)->preferences)||isset(\App\User::find($user->id)->preferences->notify_by_mail)&&\App\User::find($user->id)->preferences->notify_by_mail==1){
					$user->notify(new PaymentNotification($order));
				}
				return redirect('/wanteds/'.$wanted->id)->with('message','Payment successful!');
			}else{
				return redirect('/wanteds/'.$wanted->id)->with('error', 'Your credit card has been declined. Please try again or contact us.');
			}
		}else{
			$user->msg_in_remain == 0 ? $user->msg_in_remain=3 : $user->msg_in_remain+=3;
			$user->save();
			$wanted->status=$request->package;
			$wanted->save();
			$order->amount=0;
			$order->status='paid';
			$order->save();
			return redirect('/wanteds/'.$wanted->id)->with('message','Basic Ad published!');
		}

	}
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Wanted  $wanted
     * @return \Illuminate\Http\Response
     */
	public function destroy(Wanted $wanted)
	{
		$wanted->acceptings()->detach();
		$wanted->amenities()->detach();
		$wanted->orders()->delete();
		$wanted->delete();
		return redirect('/')->with('message','Ad deleted!');
	}
}
