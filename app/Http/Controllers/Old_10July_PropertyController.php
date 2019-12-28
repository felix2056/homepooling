<?php

namespace App\Http\Controllers;

use ImageOptimizer;
use App\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Notifications\PaymentNotification;
use App\Notifications\ReportNotification;
use App\Http\Requests\PropertiesFSURequest;
use App\Http\Requests\SanitizeRequest;
use App\Http\Requests\MessageSRequest;
use App\Http\Requests\PackageSRequest;
use App\Http\Requests\ReportRequest;
use Intervention\Image\ImageManagerStatic as Image;

class PropertyController extends PaymentController
{

	public function __construct()
	{
		$this->middleware('auth')->only('create','store','update','edit','destroy','fave');
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
		$amenities_ids=[];
		if(\Auth::check()&&isset(\Auth::user()->preferences)&&isset(\Auth::user()->preferences->amenities)&&count(\Auth::user()->preferences->amenities)>0){
			foreach(\Auth::user()->preferences->amenities as $a){
				array_push($amenities_ids,$a->id);
			}
		}
		return view('properties',compact('amenities','acceptings','amenities_ids'));
	}
	public function fetch(SanitizeRequest $request){
		$html='';
		$properties=[];
		$room_params=[]; 
		$geo=[];
		$now=Carbon::now();
		
		$qu=\App\Property::orderBy('on_top_until','DESC')->orderBy('highlighted','DESC')->orderBy('created_at','DESC');
		
		if($request->has('budget')){
			if(strpos($request->budget,'+')){
				$budget_min=(int)substr($request->budget,0,-1);
				$budget_max='1000000';
			}else{
				$budget_min=(int)explode('-',$request->budget)[0];
				$budget_max=(int)explode('-',$request->budget)[1];
			}
			$room_params['budget_min']=$budget_min;
			$room_params['budget_max']=$budget_max;
			$qu->where('price','>=',$budget_min)->where('price','<=',$budget_max)->orWhere('price',NULL);
		}
		if($request->has('has_bathroom')){
			$has_bathroom=$request->has_bathroom;
			$room_params['has_bathroom']=$has_bathroom;
		}
		if($request->has('pref_empty')){
			$room_params['pref_empty']=$request->pref_empty;
		}
		if($request->has('pref_single')){
			$room_params['pref_single']=$request->pref_single;
		}
		if($request->has('property_type')){
			$qu->where('property_type',$request->property_type);
		}
		if($request->has('epc')){
			$arr=array('a','b','c','d','e','f','g');
			$arr=array_slice($arr,0,array_search($request->epc,$arr)+1);
			$qu->whereIn('EPC',$arr);
		}
		if($request->has('amenities')&&count($request->amenities)){
			$amenities=$request->amenities;
			$qu->whereHas('amenities',function($query) use ($amenities){
				$arr=[];
				foreach($amenities as $am){
					array_push($arr,$am);
				}
				$query->where('name',$arr);
			});
		}
		if($request->has('acceptings')&&count($request->acceptings)){
			$acceptings=$request->acceptings;
			$qu->whereHas('acceptings',function($query) use ($acceptings){
				$arr=[];
				foreach($acceptings as $ac){
					array_push($arr,$ac);
				}
				$query->where('name',$arr);
			});
		}
		if($request->has('exceptions')){
			$e_arr=array();
			foreach($request->exceptions as $except){
				if($except=='livein'||$except=='agent'){
					$qu->where('user_type','!=',$except);
				} else {
					array_push($e_arr,$except);
				}
			}
			$room_params['exceptions']=$e_arr;
		}

		if(count($room_params)) $qu->whereHas('rooms',function($query) use ($room_params){
			if(array_key_exists('budget_min',$room_params)&&array_key_exists('budget_max',$room_params)) $query->where('price','>=',$room_params['budget_min'])->where('price','<=',$room_params['budget_max']);
			if(array_key_exists('has_bathroom',$room_params)) $query->where('has_bathroom',$room_params['has_bathroom']);
		});

		if(array_key_exists('exceptions',$room_params)){
			foreach($room_params['exceptions'] as $ex){
				if($ex=='male') 
					$qu->whereHas('rooms',function($query) use ($room_params){
						$query->where('male','>',0);
					},0);
				if($ex=='female')
					$qu->whereHas('rooms',function($query) use ($room_params){
						$query->where('female','>',0);
					},0);
				if($ex=='queer')
					$qu->whereHas('rooms',function($query) use ($room_params){
						$query->where('lgbt','>',0);
					},0);
			}
		}

		if(array_key_exists('pref_empty',$room_params)&&($room_params['pref_empty']==1)){
			$qu->whereHas('rooms',function($query) use ($room_params){
				$query->where('occupants','>',0);
			},0);
		}else if(array_key_exists('pref_empty',$room_params)&&($room_params['pref_empty']==0)){
			$qu->whereHas('rooms',function($query) use ($room_params){
				$query->where('occupants','>',0);
			});
		} 

		if(array_key_exists('pref_single',$room_params)&&($room_params['pref_single']==1)){
			$qu->whereHas('rooms',function($query) use ($room_params){
				$query->where('beds',1);
			},0);
		}else if(array_key_exists('pref_single',$room_params)&&($room_params['pref_single']==0)){
			$qu->whereHas('rooms',function($query) use ($room_params){
				$query->where('beds','>',1);
			});
		} 
		
		if($request->has('location')){
			$qu=$qu->where('status','!=','pending')->get()->filter(function($property) use ($request){
				$distance=$this->distanced((float)$property->lat,(float)$property->long,(float)$request->lat,(float)$request->long,'K');
				if($distance <= 1){
					return true;
				}else{
					return false;
				}
			})->values();
		}else{
			$qu=$qu->where('status','!=','pending')->get();
		}
		
		foreach($qu as $p){
			array_push($properties,$p);
		}
		
// 		paginator/infinite scroll
		$pageLength=12;
		$stop=false;
		
		if(count($properties)){
			foreach($properties as $property){
                                $price=isset($property->price) ? $property->price : ((isset($property->rooms) && isset($property->rooms[0]->price)) ? $property->rooms[0]->price : '');
                                $url='/properties/'.$property->id;
                                $status=$property->status;
				$latlongpriceurlstat=[$property->lat,$property->long,$price,$url,$status];
				array_push($geo,$latlongpriceurlstat);
			}
// 		paginator/infinite scroll
			if(count($properties)>$pageLength){
				$properties=array_slice($properties,(0+(($request->page-1)*$pageLength)),$pageLength);
			}
			if(count($properties)<=$pageLength){
				$stop=true;
			}
			$html=view('block')->with('properties',$properties)->with('now',$now)->render();
		}else{
				$html.='<h2>Sorry, no results found</h2>';
		}

		return response()->json(array('success'=>true,'html'=>$html,'geo'=>$geo,'stop'=>$stop));
	}

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
	public function create(){
		$acceptings=\App\Accepting::all();
		$amenities=\App\Amenity::all();
		return view('share',compact('amenities','acceptings'));
	}

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
	public function store(PropertiesFSURequest $request){
		// creo la Property e ne salvo gli attributi
		$property=new \App\Property;
		$images=array();
		$rooms_no=$request->rooms_no;
		// TODO sanification and validation 

		if(Auth::check()){
			$property->user_id=Auth::user()->id;
		}
		
		$property->address=htmlspecialchars($request->address);
		$property->location=$request->address_long;
		$property->town=$request->town;
		$property->postal_code=$request->postal_code;
		$property->property_type=$request->property_type;
		$property->user_type=$request->user_type;
		$property->epc=$request->epc;
		$property->rooms_no=$rooms_no;
		$property->lat=$request->lat;
		$property->long=$request->long;
		$property->minimum_stay=$request->minimum_stay;
		$property->description=$request->description;
		$property->status='pending';
		$property->early_access=0;
		
		if($request->has('room_price_main')) $property->price=$request->room_price_main;
		if($request->has('room_deposit_main')) $property->deposit=$request->room_deposit_main;
		if($request->has('room_bills_main')) $property->bills=$request->room_bills_main;
		
		$property->save();
		
		$property->amenities()->sync($request->amenities);
		$property->acceptings()->sync($request->acceptings);
		
		// ciclo le stanze, creo gli oggetti e li salvo
		$rooms=array();

		for($i=0;$i<$rooms_no;$i++){
			$room=new \App\Room;
			$beds=$request->{'beds_no_'.($i+1)};

			$room->beds = $request->{'beds_no_'.($i+1)};
			if($request->has('avail_from_'.($i+1))&&$request->{'avail_from_'.($i+1)}!=0) $room->avail_from = \DateTime::createFromFormat('d/m/Y',$request->{'avail_from_'.($i+1)});
			if($request->has('avail_to_'.($i+1))&&$request->{'avail_to_'.($i+1)}!=0) $room->avail_to = \DateTime::createFromFormat('d/m/Y',$request->{'avail_to_'.($i+1)});
			if($request->has('room_price_'.($i+1)) && !$request->has('room_price_main')) $room->price = $request->{'room_price_'.($i+1)};
			if($request->has('room_deposit_'.($i+1)) && !$request->has('room_price_main')) $room->deposit = $request->{'room_deposit_'.($i+1)};
			if($request->has('room_bills_'.($i+1)) && !$request->has('room_price_main')) $room->bills = $request->{'room_bills_'.($i+1)};
			if($request->has('has_bathroom_'.($i+1))){
				$room->has_bathroom = $request->{'has_bathroom_'.($i+1)};
			}else{
				$room->has_bathroom = 0;
			}

			$male=0;
			$female=0;
			$lgbt=0;
			$occupants=0;
			
			for($j=0;$j<$beds;$j++){
				if($request->{'room_'.($i+1).'_occ_'.($j+1)}=='m') $male++;
				if($request->{'room_'.($i+1).'_occ_'.($j+1)}=='f') $female++;
				if($request->{'room_'.($i+1).'_occ_'.($j+1)}=='q') $lgbt++;
				if($request->{'room_'.($i+1).'_occ_'.($j+1)}!='free') $occupants++;
			}
			
			$room->occupants=$occupants;
			$room->male=$male;
			$room->female=$female;
			$room->lgbt=$lgbt;

			$rooms[$i]=$room;
		}
		
		$property->rooms()->saveMany($rooms);

		// salvo le immagini
		if($request->hasFile('photo')){
			$files=$request->file('photo');
			foreach($files as $file){
				$image=new \App\Image;
				$path=$file->store('/public/img');
				$image->url=str_replace('public','/storage',$path);
				$image->alt=$request->address;
				// resizing image to slider size
				$tmp=Image::make(public_path().$image->url);
				if($tmp->width()>$tmp->height()){
					$tmp->resize(1920,null,function($constraint){
						$constraint->aspectRatio();
					});
				}else{
					$tmp->resize(null,1080,function($constraint){
						$constraint->aspectRatio();
					});
				}
				$original_name=basename($image->url,'.jpeg');
				$tmp->save(public_path('/storage/img/slider/'.$original_name.'_up.'.$file->getClientOriginalExtension()));
				ImageOptimizer::optimize(public_path('/storage/img/slider/'.$original_name.'_up.'.$file->getClientOriginalExtension()));

				// resizing image to thumb size

				$tmb=Image::make(public_path().$image->url);
				$tmb->resize(null,180,function($constraint){
					$constraint->aspectRatio();
				});
				$tmb->save(public_path('/storage/img/thumbs/'.$original_name.'_th.'.$file->getClientOriginalExtension()));
				ImageOptimizer::optimize(public_path('/storage/img/thumbs/'.$original_name.'_th.'.$file->getClientOriginalExtension()));

				array_push($images,$image);
			}
			$property->images()->saveMany($images);
		}
		// salvo il contratto
		if($request->hasFile('contract')){
			$file=$request->file('contract');
			$digits = 3;
			$str=str_pad(rand(0, pow(10, $digits)-1), $digits, '0', STR_PAD_LEFT);
			$filename=explode('.',$file->getClientOriginalName())[0].'_'.$str.'.'.$file->getClientOriginalExtension();
			$contract=new \App\Contract;
			$path=$file->storeAs('/public/pdf',$filename);
			$contract->url=str_replace('public','/storage',$path);
			$property->contract()->save($contract);
		}
		if($request->hasFile('epcert')){
			$file=$request->file('epcert');
			$digits = 3;
			$str=str_pad(rand(0, pow(10, $digits)-1), $digits, '0', STR_PAD_LEFT);
			$filename=explode('.',$file->getClientOriginalName())[0].'_'.$str.'.'.$file->getClientOriginalExtension();
			$epcert=new \App\Epcert;
			$path=$file->storeAs('/public/pdf/cert',$filename);
			$epcert->url=str_replace('public','/storage',$path);
			$property->epcert()->save($epcert);
		}
		return $property->id;
	}
	
	public function showPackages(Property $property){
		$current=$property->getHighestOrder();
		return view('packages',compact('property','current'));
	}
	public function choosePackage(Property $property, PackageSRequest $request){
		$now=Carbon::now();
		$order=new \App\Order;
		$order->property_id=$property->id;
		$order->user_id=$property->user_id;
		($property->status!='pending') ? $order->upgrade=1 : $order->upgrade=0;
		$order->type=$request->package;
		$user=\App\User::find($property->user_id);
		if($request->package!='basic'){
			($request->package=='bump' && $property->status=='pending') ? $order->amount=2.99 : $order->amount=1.99;
			if($request->package=='premium') $order->amount=12.00;
			if($request->package=='messages') $order->amount=2.99;
// 			if payment is successful
			if($this->postPayWithStripe($request,$order)){
				$order->status='paid';

				if($request->package=='bump'){
// 					$property->on_top_until=$now;
					$property->on_top_until=$now->addDays(7);
					$property->highlighted=1;
					$property->status=$request->package;
				}elseif($request->package=='premium'){
					$property->on_top_until=$now->addDays(7);
					if($user->msg_in_remain < 9999) $user->msg_in_remain=9999;
					$property->early_access=1;
					$property->highlighted=1;
// 					$user->early_bird=1;
					$property->status=$request->package;
				}elseif($request->package=='messages'){
					$property->highlighted=0;
					$user->msg_in_remain+=5;
				}
				$user->save();
				$property->save();
				$order->save();
				if(!isset(\App\User::find($user->id)->preferences)||isset(\App\User::find($user->id)->preferences->notify_by_mail)&&\App\User::find($user->id)->preferences->notify_by_mail==1){
					$user->notify(new PaymentNotification($order));
				}
				return redirect('/properties/'.$property->id)->with('message','Payment successful!');
			}else{
				return redirect('/properties/'.$property->id)->with('error', 'Your credit card has been declined. Please try again or contact us.');
			}
		}else{
			$user->msg_in_remain == 0 ? $user->msg_in_remain=3 : $user->msg_in_remain+=3;
			$user->save();
			$order->amount=0;
			$order->status='paid';
			$order->save();
			$property->status=$request->package;
			$property->highlighted=0;
			$property->save();
			return redirect('/properties/'.$property->id)->with('message','Basic Property published!');
		}
	}

    /**
     * Display the specified resource.
     *
     * @param  \App\Property  $property
     * @return \Illuminate\Http\Response
     */
	public function show(Property $property)
	{	
		
		$now=Carbon::now();
		if(\Auth::check() && count(\Auth::user()->favorites)>0){
			$favorited=false;
			foreach(\Auth::user()->favorites as $fav){
				if($fav->property_id==$property->id){
					$favorited=true;
				}
			}
		}
		
// 		if(\Auth::check() && $property->user_id!=\Auth::user()->id){
// 			$visit=new \App\Visit;
// 			$visit->user_id=\Auth::user()->id;
// 			$visit->property_id=$property->id;
// 			$property->visits()->save($visit);
// 		}
// 		
		$rooms=$property->rooms;
		
		$amenities=$property->amenities;
		$acceptings=$property->acceptings;
		$user=$property->user;
		$images=$property->images;
		$contract=$property->contract();
		$visitors=$property->visits()->orderBy('created_at','DESC')->take(4)->get();
		
		$nearbies=\App\Property::from(DB::raw('(SELECT *, SQRT(POW(69.1 * (`lat` - '.$property->lat.'), 2) + POW(69.1 * (73.8432228 - '.$property->long.') * COS(`lat` / 57.3), 2)) AS distance FROM properties ORDER BY distance ASC ) as NEAR_BY_TABLE'))->orderBy('on_top_until','DESC')->orderBy('highlighted','DESC')->orderBy('created_at','DESC')->where('id','!=',$property->id)->where('status','!=','pending')->with('visits')->take(3)->get();
		
		$male=0;
		$female=0;
		$queer=0;
		foreach($rooms as $room){
			$male+=$room->male;
			$female+=$room->female;
			$queer+=$room->lgbt;
		}
		
		if(($property->early_access==1 || (\Auth::check() && \Auth::user()->early_bird==1) || $now->diffInDays($property->created_at)>7 ) && $property->user->msg_in_remain > 0 ){
			$can_receive=true;
		}else{
			$can_receive=false;
		}
		
		return view('property',compact('property','amenities','acceptings','nearbies','images','contract','user', 'male', 'female', 'queer','visitors','now','can_receive'));
	}

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Property  $property
     * @return \Illuminate\Http\Response
     */
	public function edit(Property $property)
	{
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
		return view('share',compact('property','rooms','p_amenities','p_acceptings','images','contract','amenities','acceptings'));
	}

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Property  $property
     * @return \Illuminate\Http\Response
     */
	public function update(PropertiesFSURequest $request, Property $property)
	{
		$rooms_no=$request->rooms_no;
		// TODO sanification and validation 
		$images=array();

		if(Auth::check()&&Auth::user()->is_admin!=1){
			$property->user_id=Auth::user()->id;
		}
		
		$property->address=$request->address;
		$property->location=$request->address_long;
		$property->town=$request->town;
		$property->postal_code=$request->postal_code;
		$property->property_type=$request->property_type;
		$property->user_type=$request->user_type;
		$property->epc=$request->epc;
		$property->rooms_no=$rooms_no;
		$property->lat=$request->lat;
		$property->long=$request->long;
		$property->minimum_stay=$request->minimum_stay;
		$property->description=$request->description;

		if($request->has('room_price_main')){
			$property->price=$request->room_price_main;
			foreach($property->rooms as $room){
				isset($room->price) ? $room->price=null : '';
			}
		} 
		if($request->has('room_deposit_main')){
			$property->deposit=$request->room_deposit_main;
			foreach($property->rooms as $room){
				isset($room->deposit) ? $room->deposit=null : '';
			}
		} 
		if($request->has('room_bills_main')){
			$property->bills=$request->room_bills_main;
			foreach($property->rooms as $room){
				isset($room->bills) ? $room->bills=null : '';
			}
		} 
		
		$property->save();
		
		$property->amenities()->sync($request->amenities);
		$property->acceptings()->sync($request->acceptings);
		
		// ciclo le stanze, creo gli oggetti e li salvo
		$property->rooms()->delete();

		$rooms=array();

		for($i=0;$i<$rooms_no;$i++){
			$room=new \App\Room;
			$beds=$request->{'beds_no_'.($i+1)};

			$room->beds = $request->{'beds_no_'.($i+1)};
			if($request->has('avail_from_'.($i+1))&&$request->{'avail_from_'.($i+1)}!=0) $room->avail_from = \DateTime::createFromFormat('d/m/Y',$request->{'avail_from_'.($i+1)});
			if($request->has('avail_to_'.($i+1))&&$request->{'avail_to_'.($i+1)}!=0) $room->avail_to = \DateTime::createFromFormat('d/m/Y',$request->{'avail_to_'.($i+1)});
			if($request->has('room_price_'.($i+1)) && !$request->has('room_price_main')) $room->price = $request->{'room_price_'.($i+1)};
			if($request->has('room_deposit_'.($i+1)) && !$request->has('room_price_main')) $room->deposit = $request->{'room_deposit_'.($i+1)};
			if($request->has('room_bills_'.($i+1)) && !$request->has('room_price_main')) $room->bills = $request->{'room_bills_'.($i+1)};
			if($request->has('has_bathroom_'.($i+1))){
				$room->has_bathroom = $request->{'has_bathroom_'.($i+1)};
			}else{
				$room->has_bathroom = 0;
			}

			$male=0;
			$female=0;
			$lgbt=0;
			$occupants=0;
			
			for($j=0;$j<$beds;$j++){
				if($request->{'room_'.($i+1).'_occ_'.($j+1)}=='m') $male++;
				if($request->{'room_'.($i+1).'_occ_'.($j+1)}=='f') $female++;
				if($request->{'room_'.($i+1).'_occ_'.($j+1)}=='q') $lgbt++;
				if($request->{'room_'.($i+1).'_occ_'.($j+1)}!='free') $occupants++;
			}
			
			$room->occupants=$occupants;
			$room->male=$male;
			$room->female=$female;
			$room->lgbt=$lgbt;

			$rooms[$i]=$room;
		}
		
		$property->rooms()->saveMany($rooms);

		// carico le immagini già presenti nel db nell'array delle immagini, controllando se sono state impostate per la cancellazione
		if($property->images){
			foreach($property->images()->get() as $image){
				if(isset($request->photo_tbd)){
					if(!in_array($image->id,$request->photo_tbd)){
						$im=new \App\Image;
						$im->url=$image->url;
						$im->alt=$image->alt;
						array_push($images,$im);
					}
				}else{
					$im=new \App\Image;
					$im->url=$image->url;
					$im->alt=$image->alt;
					array_push($images,$im);
				}
			}
		}
		// elimino le immagini da cancellare dal filesystem
		if($request->has('photo_tbd')){
			$img_array=array();
			$tmp_array=array();
			$tmb_array=array();
			foreach($request->photo_tbd as $tbd){
				$url=\App\Image::find((int)$tbd)->url;
				$original_name=basename($url,'.jpeg');
				array_push($img_array,str_replace('/storage/','public/',$url));
				array_push($tmp_array,'public/img/slider/'.$original_name.'_up.jpg');
				array_push($tmb_array,'public/img/thumbs/'.$original_name.'_th.jpg');
			}
			Storage::delete($img_array);
			Storage::delete($tmp_array);
			Storage::delete($tmb_array);
		}
		
		// prendo le immagini nuove dal form
		if($request->hasFile('photo')){
			$files=$request->file('photo');
			foreach($files as $file){
				$image=new \App\Image;
				$path=$file->store('/public/img');
				$image->url=str_replace('public','/storage',$path);
				$image->alt=$request->address;
				// resizing image to slider size
				$tmp=Image::make(public_path().$image->url);
				if($tmp->width()>$tmp->height()){
					$tmp->resize(1920,null,function($constraint){
						$constraint->aspectRatio();
					});
				}else{
					$tmp->resize(null,1080,function($constraint){
						$constraint->aspectRatio();
					});
				}
				$original_name=basename($image->url,'.jpeg');
				$tmp->save(public_path('/storage/img/slider/'.$original_name.'_up.'.$file->getClientOriginalExtension()));
				ImageOptimizer::optimize(public_path('/storage/img/slider/'.$original_name.'_up.'.$file->getClientOriginalExtension()));

				// resizing image to thumb size

				$tmb=Image::make(public_path().$image->url);
				$tmb->resize(null,180,function($constraint){
					$constraint->aspectRatio();
				});
				$tmb->save(public_path('/storage/img/thumbs/'.$original_name.'_th.'.$file->getClientOriginalExtension()));
				ImageOptimizer::optimize(public_path('/storage/img/thumbs/'.$original_name.'_th.'.$file->getClientOriginalExtension()));
				
				array_push($images,$image);
			}
		}
		// elimino tutte le immagini correlate e ricarico il nuovo array, salvando le immagini
		if(count($images)>0){
			$property->images()->delete();
			$property->images()->saveMany($images);
		}else{
			$request->session()->flash('message', 'You can\'t delete all the images!');
			return response()->json(['status' => 'You can\'t delete all the images!','id'=>$property->id]);
		}
		
		// salvo il contratto
		if($request->hasFile('contract')){
			$file=$request->file('contract');
			$digits = 3;
			$str=str_pad(rand(0, pow(10, $digits)-1), $digits, '0', STR_PAD_LEFT);
			$filename=explode('.',$file->getClientOriginalName())[0].'_'.$str.'.'.$file->getClientOriginalExtension();
			$contract=new \App\Contract;
			$path=$file->storeAs('/public/pdf',$filename);
			$contract->url=str_replace('public','/storage',$path);
			$property->contract()->delete();
			$property->contract()->save($contract);
		}
		if($request->hasFile('epcert')){
			$file=$request->file('epcert');
			$digits = 3;
			$str=str_pad(rand(0, pow(10, $digits)-1), $digits, '0', STR_PAD_LEFT);
			$filename=explode('.',$file->getClientOriginalName())[0].'_'.$str.'.'.$file->getClientOriginalExtension();
			$epcert=new \App\Epcert;
			$path=$file->storeAs('/public/pdf/cert',$filename);
			$epcert->url=str_replace('public','/storage',$path);
			$property->epcert()->delete();
			$property->epcert()->save($epcert);
		}
		$request->session()->flash('message', 'Successfully updated!');
		return response()->json(['status' => 'Successfully updated!','id'=>$property->id]);
	}

	public function fave(Property $property,Request $request){
		$id=\Auth::user()->id;
		$fave=new \App\Favorite;
		$fave->user_id=$id;
		$fave->property_id=$property->id;
		$contains=false;
		foreach($property->favorites as $fav){
		    if($fav->user_id==$fave->user_id && $fav->property_id==$fave->property_id){
		        $contains=true;
		    }
		}
		if(!$contains){
			$fave->save();
			$request->session()->flash('message', 'Added to your favorites!!');
			return response()->json(['status' => 'Added to your favorites!','done'=>true]);
		}else{
			$fave=\App\Favorite::where('user_id',$id)->where('property_id',$property->id);
			$fave->delete();
			$request->session()->flash('message', 'Removed from your favorites!');
			return response()->json(['status' => 'Removed from your favorites!','done'=>false]);
		}
		
	}
	
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Property  $property
     * @return \Illuminate\Http\Response
     */
    public function destroy(Property $property)
    {
		$property->favorites()->delete();
		if(isset($property->contract)) $property->contract->delete();
		if(isset($property->epcert)) $property->epcert->delete();
                $property->invites()->delete();
                $property->visits()->delete();
                $property->reviews()->delete();
		$property->rooms()->delete();
		
		$img_array=array();
		$tmp_array=array();
		$tmb_array=array();
		foreach($property->images as $image){
			$original_name=basename($image->url,'.jpeg');
			array_push($img_array,str_replace('/storage/','public/',$image->url));
			array_push($tmp_array,'public/img/slider/'.$original_name.'_up.jpg');
			array_push($tmb_array,'public/img/thumbs/'.$original_name.'_th.jpg');
		}
		Storage::delete($img_array);
		Storage::delete($tmp_array);
		Storage::delete($tmb_array);
		
		$property->images()->delete();
		$property->acceptings()->detach();
		$property->amenities()->detach();
		$property->orders()->delete();
		$property->delete();
		return redirect('/')->with('message','Property deleted!');
    }
    public function createReport(Property $property){
		$user=$property->user;
		return view('report_write',compact('property','user'));
    }
    public function sendReport(Property $property, ReportRequest $request){
		if($property->reporteds()->where('author_id',\Auth::user()->id)->count()==0){
			$report=new \App\Report;
			$report->property_id=$property->id;
			$report->author_id=\Auth::user()->id;
			$report->text=$request->text;
			$report->save();
			$admins=\App\User::where('is_admin',1)->get();
			foreach($admins as $admin){
				$admin->notify(new ReportNotification($report));
			}
			return redirect('/properties/'.$property->id)->with('message','Report successfully sent!');
		}else{
			return redirect('/properties/'.$property->id)->with('error','You already reported this Property!');
		}
    }
    public function writeReview(Property $property){
		$user=$property->user;
		return view('review_write',compact('property','user'));
    }
    public function storeReview(MessageSRequest $request,Property $property){
		$review=new \App\Review;
		$review->property_id=$property->id;
		$review->user_id=\Auth::user()->id;
		$review->text=$request->text;
		$review->save();
		return redirect('/properties/'.$property->id)->with('message','Review successfully sent!');
    }
    public function editReview(Property $property){
		$review=$property->reviews()->where('user_id',\Auth::user()->id)->first();
		$user=$property->user;
		return view('review_write',compact('review','property','user'));
    }
    public function updateReview(MessageSRequest $request,Property $property){
		$review=$property->reviews()->where('user_id',\Auth::user()->id)->first();
		$review->text=$request->text;
		$review->save();
		return redirect('/properties/'.$property->id)->with('message','Review successfully updated!');
    }
    public function destroyReview(Request $request,Property $property){
		$review=$property->reviews()->where('user_id',\Auth::user()->id);
		$review->delete();
		return redirect('/properties/'.$property->id)->with('message','Review successfully deleted!');
    }
}
