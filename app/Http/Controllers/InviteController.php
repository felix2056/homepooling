<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Property;
use \App\Notifications\InviteNotification;

class InviteController extends Controller
{
	public function index(Property $property){
		$users=\App\User::where('id','!=',\Auth::user()->id)->orderBy('created_at','DESC');
		$lastusers=\App\User::orderBy('created_at','DESC')->take(5)->get();
		return view('invite',compact('property','lastusers','users'));
	}
	public function send(Property $property,Request $request){
		if($request->has('invited')){
			$invites=[];
			foreach($request->invited as $req){
				$inv=new \App\Invite;
				$inv->user_id_from=\Auth::user()->id;
				$inv->user_id_to=$req;
				$inv->property_id=$property->id;
				if($property->invites()->where('user_id_to',$req)->get()->count()==0) array_push($invites,$inv);
			}
			if($property->getHighestOrder()==='premium'){
				$property->invites()->saveMany($invites);
				$users=\App\User::whereIn('id',$request->invited)->get();
				foreach($users as $user){
					if(!isset(\App\User::find($user->id)->preferences)||isset(\App\User::find($user->id)->preferences->notify_by_mail)&&\App\User::find($user->id)->preferences->notify_by_mail==1||!isset(\App\User::find($user->id)->preferences)){
						$user->notify(new InviteNotification($user->name,$property->id,$user->id));
					}
				}
				return redirect('/properties/'.$property->id.'/invites')->with('status', 'Poolers invited!');
			}else{
				return redirect('/properties/'.$property->id.'/invites')->with('error', 'You can\'t invite other Poolers! Please, upgrade Property to Premium!');
			}
		}else{
			return redirect('/properties/'.$property->id.'/invites')->with('error', 'Please, choose some Pooler first!');
		}
	}
	public function destroy(Property $property,Request $request){
	}

    
}
