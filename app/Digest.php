<?php

namespace App;

use Illuminate\Support\Facades\Mail;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Digest extends Model
{
    protected function distanced($lat1, $lon1, $lat2, $lon2, $unit) {
            $theta = $lon1 - $lon2;
            $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
            $dist = acos($dist);
            $dist = rad2deg($dist);
            $miles = $dist * 60 * 1.1515;
            $unit = strtoupper($unit);

            if ($unit == "K") {
                    return ($miles * 1.609344);
            } else if ($unit == "N") {
                    return ($miles * 0.8684);
            } else {
                    return $miles;
            }

    }
    public function send($users){
		foreach($users as $user){
			$prefs=$user->preferences;
			if(isset($prefs)&&$prefs->receive_digest==1){

                                $html='';
                                $properties=[];
                                $room_params=[]; 
                                $geo=[];
                                $now=Carbon::now();

                                $qu=\App\Property::orderBy('early_access','DESC')->orderBy('on_top_until','DESC')->orderBy('created_at','DESC');

                                if(isset($prefs->budget)&&$prefs->budget!=null){
                                        if(strpos($prefs->budget,'+')){
                                                $budget_min=(int)substr($prefs->budget,0,-1);
                                                $budget_max='1000000';
                                        }else{
                                                $budget_min=(int)explode('-',$prefs->budget)[0];
                                                $budget_max=(int)explode('-',$prefs->budget)[1];
                                        }
                                        $room_params['budget_min']=$budget_min;
                                        $room_params['budget_max']=$budget_max;
                                        $qu->where('price','>=',$budget_min)->where('price','<=',$budget_max)->orWhere('price',NULL);
                                }
                                if(isset($prefs->has_bathroom)&&$prefs->has_bathroom){
                                        $has_bathroom=$prefs->has_bathroom;
                                        $room_params['has_bathroom']=$has_bathroom;
                                }
                                if(isset($prefs->pref_empty)&&$prefs->pref_empty){
                                        $room_params['pref_empty']=$prefs->pref_empty;
                                }
                                if(isset($prefs->pref_single)&&$prefs->pref_single){
                                        $room_params['pref_single']=$prefs->pref_single;
                                }
                                if(isset($prefs->property_type)&&$prefs->property_type!=null){
                                        $qu->where('property_type',$prefs->property_type);
                                }
                                if(isset($prefs->epc)&&$prefs->epc){
                                        $arr=array('a','b','c','d','e','f','g');
                                        $arr=array_slice($arr,0,array_search($prefs->epc,$arr)+1);
                                        $qu->whereIn('EPC',$arr);
                                }
                                if(isset($prefs->amenities)&&count($prefs->amenities)){
                                        $amenities=$prefs->amenities;
                                        $qu->whereHas('amenities',function($query) use ($amenities){
                                                $arr=[];
                                                foreach($amenities as $am){
                                                        array_push($arr,$am->name);
                                                }
                                                $query->where('name',$arr);
                                        });
                                }
                                if(count($room_params)) $qu->whereHas('rooms',function($query) use ($room_params){
                                        if(array_key_exists('budget_min',$room_params)&&array_key_exists('budget_max',$room_params)) $query->where('price','>=',$room_params['budget_min'])->where('price','<=',$room_params['budget_max']);
                                        
                                        if(array_key_exists('has_bathroom',$room_params)) $query->where('has_bathroom',$room_params['has_bathroom']);
                                });

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
                                if(isset($prefs->location)&&$prefs->location){
                                        $qu=$qu->where('status','!=','pending')->get()->filter(function($property) use ($prefs){
                                                $distance=$this->distanced((float)$property->lat,(float)$property->long,(float)$prefs->lat,(float)$prefs->long,'K');
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

                                if(count($properties)){
                                    $data=[
                                            'properties'=>$properties,
                                            'now'=>Carbon::now()
                                    ];
                                    Mail::send('email.block',$data,function($message) use ($user){
                                                         $message->from('homepooling@mugagency.com')->to($user->email)->subject('[Homepooling] Properties matching your preferences');
                                    });
                                }
			}
		}
    }
}
