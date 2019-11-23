<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Socialite;
use Twilio;
use Laravel\Socialite\Contracts\User as ProviderUser;
use Carbon\Carbon;
use App\Notifications\PaymentNotification;
use App\Notifications\ReportNotification;
use App\Http\Requests\ProfileURequest;
use App\Http\Requests\ReportRequest;
use App\Http\Requests\SanitizeRequest;
use App\Http\Requests\Package_earlySRequest;

class FBloginService{
	public function createOrGetUser(ProviderUser $providerUser){
		
		$account = \App\FBlogin::whereProvider('facebook')->whereProviderUserId($providerUser->getId())->first();
		if($account)
			return $account->user;
		else {
			$account = new \App\FBlogin([
				'provider_user_id' 	=> $providerUser->getId(),
				'provider' 			=> 'facebook',
			]);
			$user = \App\User::whereEmail($providerUser->getEmail())->first();
			if(!$user) {
				$user = \App\User::create([
					'email'			=> $providerUser->getEmail(),
					'name'			=> $providerUser->getName(),
					'family_name'	=> '',
					'photo'			=> $providerUser->getAvatar(),
					'password'		=> md5(rand(1,10000)),
					'age'			=> 0,
					'gender'		=> 'a',
					'msg_in_remain'	=> 0,
					'early_bird'	=> 0
				]);
			}
			$account->user()->associate($user);
			$account->save();
			return $user;
		}
	}
}

class ProfileController extends PaymentController
{

	public function __construct()
	{
		$this->middleware('auth')->only('create','store','update','edit','destroy');
		$this->middleware('ownoradmin')->only('update','edit','destroy');
	}

	public function index()
    {
        //
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(User $user)
	{
		if(isset($user->birthday)){
			$birthday = \DateTime::createFromFormat('Y-m-d',$user->birthday);
			$birthday = $birthday->format('d/m/Y');
		}
		$properties = $user->properties()->where('status','!=','pending')->get();
		$pendings 	= $user->properties()->where('status','=','pending')->get();
		
		$wanted		= $user->wanted;
		$invites 	= $user->invites;		
		$inviteds	= $user->inviteds;		
		$reviews 	= [];
		foreach($properties as $property)
			foreach($property->reviews()->orderBy('created_at','DESC')->get() as $review)
				array_push($reviews,$review);

		$reviews_sent = $user->reviews()->orderBy('created_at','DESC')->get();
		
		$orders = $user->orders;
		
		if(count($user->favorites) > 0){
			$favorited = [];
			foreach($user->favorites as $fav)
				array_push($favorited, $fav->property);
		}
		$can_receive = $user->msg_in_remain > 0;
		
		return view('profile_view',compact('user','properties','pendings','birthday','favorited','can_receive','inviteds','invites','wanted','reviews','reviews_sent'));
	}
	
	public function fetch(SanitizeRequest $request){
		$html 	= '';
		$users 	= \App\User::where('id','!=', \Auth::user()->id)->orderBy('created_at', 'DESC');
		if($request->has('gender'))
			$users->where('gender', $request->gender);
		if($request->has('age')){
			$ages = explode('-', $request->age);
			if(count($ages) > 1){
				$min = $ages[0];
				$max = $ages[1];
			} elseif(count($ages) == 1)
				$min = str_replace('+','',$ages[0]);
			
			if(isset($max))
				$users->where('age','>=',$min)->where('age','<=',$max);
			else
				$users->where('age','>=',$min);
		}
		$user_arr 	= [];
		$users 		= $users->get();
		foreach($users as $user)
			array_push($user_arr,$user);

		$pageLength = 25;
		$stop 		= false;
		if(count($user_arr) > $pageLength)
			$users = array_slice($user_arr, (0 + (($request->page - 1) * $pageLength)), $pageLength);

		if(count($users) < $pageLength)
			$stop = true;

		$html = view('prof_block')->with('users', $users)->render();
		
		return response()->json(['success' => true, 'html' => $html, 'stop' => $stop]);
	}

	public function edit(User $user)
	{
		$amenities 		= \App\Amenity::all();
		$acceptings 	= \App\Amenity::all();
		$p_amenities 	= [];
		$loadNewStyles 	= true;
		$properties 	= $user->properties()->where('status', '!=', 'pending')->get();
		$pendings 		= $user->properties()->where('status', 'pending')->get();
		$orders 		= $user->orders;
		
		$favorites = [];
		foreach($user->favorites as $fav){
			$fav->property->load([
				'images' => function($q){
					$q->limit(1);
				}
			]);
			array_push($favorites, $fav->property);
		}
		if(isset($user->preferences))
			foreach($user->preferences->amenities as $a)
				array_push($p_amenities, $a->id);

		return view('profile_edit', compact('user', 'amenities', 'acceptings', 'p_amenities', 'loadNewStyles', 'pendings', 'properties', 'orders', 'favorites'));
	}

	public function update(ProfileURequest $request, User $user)
	{
		$now = Carbon::now();
		if($request->has('name')) $user->name = $request->name;
		if($request->has('family_name')) $user->family_name = $request->family_name;
		if($request->has('birthday') && $request->birthday !== 0){
			$user->birthday = Carbon::createFromFormat('d/m/Y',$request->birthday);
			$user->age = $now->diffInYears($user->birthday);
		} 
		if($request->has('phone')) $user->phone = $request->phone;
		if($request->has('gender')) $user->gender = $request->gender;
		if($request->has('profession')) $user->profession = $request->profession;
		if($request->has('origin')) $user->origin = $request->origin;
		if($request->has('description')) $user->description = $request->description;
		if($request->has('language')) $user->language = $request->language;

		
		if($request->hasFile('photo')){
			$file 			= $request->file('photo');
			$path 			= $file->store('/public/img');
			$user->photo 	= str_replace('public', '/storage/', $path);
		}
		
		$user->save();
		
		$pref = new \App\UserPreference;
		
		if($request->has('location'))
			$pref->location = $request->location;

		$pref->notify_by_mail = $request->has('notify_by_mail') ? $request->notification : 1;

		if($request->has('has_bathroom'))
			$pref->has_bathroom = $request->has_bathroom;
		if($request->has('p_empty'))
			$pref->p_empty = $request->p_empty;
		if($request->has('single'))
			$pref->single = $request->single;
		if($request->has('avail_from') && $request->avail_from != 0)
			$pref->avail_from = \DateTime::createFromFormat('d/m/Y',$request->avail_from);

        if($request->has('budget'))
			$pref->budget = $request->budget=='none' ? null : $request->budget;

		if($request->has('epc'))
			$pref->epc = $request->epc == 'none' ? null : $request->epc;

		if($request->has('property_type'))
			$pref->property_type = $request->property_type == 'none' ? null : $request->property_type;

        if($request->has('digest')){
            if($request->digest == 'none'){
                $pref->receive_digest = 0;
                $pref->digest_freq = null;
            } else {
                $pref->receive_digest = 1;
                $pref->digest_freq = $request->digest;
            }
        }
		
		$user->preferences()->delete();
		$user->preferences()->save($pref);
		
		if($request->has('amenities') && count($request->amenities)>0) $pref->amenities()->sync($request->amenities);
		
		if(\Auth::check() && \Auth::user()->is_admin){
			return redirect('/back-office/profiles/'.$user->id)->with(['message' => 'Successfully updated!']);
		}else{
			return redirect('/profiles/'.$user->id)->with(['message' => 'Successfully updated!']);
		}
	}
	
	public function early(User $user){
		return view('package_early',compact('user'));
	}

	public function earlyOrder(Package_earlySRequest $request, User $user){
		$order 			= new \App\Order;
		$order->user_id = $user->id;
		$order->type 	= $request->package;
		$order->amount 	= $request->amount;
		
		if(!isset($user->early_bird) || $user->early_bird == 0){
			if($this->postPayWithStripe($request,$order)){
				$order->status 		= 'Paid';
				$user->early_bird 	= 1;
				$order->upgrade		= 0;
				$user->orders()->save($order);
				$user->save();
				if(!isset(\App\User::find($user->id)->preferences) || isset(\App\User::find($user->id)->preferences->notify_by_mail) && \App\User::find($user->id)->preferences->notify_by_mail == 1 || !isset(\App\User::find($user->id)->preferences))
					$user->notify(new PaymentNotification($order));
			} else
				return redirect('/profiles/'.$user->id.'/edit')->with('error', 'Your credit card was been declined. Please try again or contact us.');
		} else
			return redirect('/profiles/'.$user->id.'/edit')->with(['error' => 'You are already an early bird!!']);
		
		return redirect('/profiles/'.$user->id.'/edit')->with(['message' => 'Successfully updated!']);
	}
	
	public function sms(Request $request,User $user){
		$digits = 5;
		$str 	= str_pad(rand(0, pow(10, $digits)-1), $digits, '0', STR_PAD_LEFT);
		
		$verify 		= new \App\Verify;
		$verify->code 	= $str;
		$user->verifies()->save($verify);
		
		try {
			Twilio::message($request->num,'Homepooling verification code: '.$str);
			return response()->json('ok');
		} catch (\Exception $e) {
			return response()->json($e->getMessage());
		}
	}
	
	public function verify(Request $request,User $user){
                $request->validate([
                    'code'=>'required|integer',
                ]);
		$now=Carbon::now();
		if($user->verifies)	$verify = $user->verifies()->orderBy('created_at','DESC')->first();
		if($request->has('code') && $request->code == $verify->code && $now->diffInHours($verify->created_at) < 1){
			$user->verified = 1;
			$user->save();
			$user->verifies()->delete();
			return response()->json('ok');
		}elseif($request->has('code') && $request->code == $verify->code && $now->diffInHours($verify->created_at) > 1){
			$user->verifies()->delete();
			return response()->json('expired');
		} else {
			$user->verifies()->delete();
			return response()->json('There was a problem verifying your code. Please, try again');
		}
		
	}

	/**
	* Remove the specified resource from storage.
	*
	* @param  \App\User  $user
	* @return \Illuminate\Http\Response
	*/
	public function destroy(User $user)
	{
		if(isset($user->invites) && count($user->invites)){
			$user->invites()->delete();
		}
		if(isset($user->msgs_from) && count($user->msgs_from)){
			$user->msgs_from()->delete();
		}
		if(isset($user->msgs_to) && count($user->msgs_to)){
			$user->msgs_to()->delete();
		}
		if(isset($user->favorites) && count($user->favorites)){
			$user->favorites()->delete();
		}
		if(isset($user->visits) && count($user->visits)){
			$user->visits()->delete();
		}
		if(isset($user->reviews) && count($user->revies)){
			$user->reviews()->delete();
		}
		if(isset($user->properties) && count($user->properties)){
			foreach($user->properties as $prop){
				if(isset($prop->amenities) && count($prop->amenities)) $prop->amenities()->detach();
				if(isset($prop->acceptings) && count($prop->acceptings)) $prop->acceptings()->detach();
			}
			$user->properties()->delete();
		}
		if(isset($user->wanted) && count($user->wanted)){
			foreach($user->wanted as $prop){
				if(isset($prop->amenities) && count($prop->amenities)) $prop->amenities()->detach();
				if(isset($prop->acceptings) && count($prop->acceptings)) $prop->acceptings()->detach();
			}
			$user->wanted()->delete();
		} 
		if(isset($user->orders) && count($user->orders)) $user->orders()->delete();
		$user->delete();
		return redirect('/')->with('message','Property deleted!');
	}

	public function createReport(User $user){
		return view('report_write',compact('user'));
	}

	public function sendReport(User $user, ReportRequest $request){
		if($user->reporteds()->where('author_id',\Auth::user()->id)->count() == 0){
			$report 			= new \App\Report;
			$report->user_id 	= $user->id;
			$report->author_id 	= \Auth::user()->id;
			$report->text 		= $request->text;
			$report->save();
			$admins = \App\User::where('is_admin',1)->get();
			foreach($admins as $admin)
				$admin->notify(new ReportNotification($report));
			return redirect('/profiles/'.$user->id)->with('message','Report successfully sent!');
		} else
			return redirect('/profiles/'.$user->id)->with('error','You already reported this User!');
	}

	public function FBredirect(){
		return Socialite::driver('facebook')->redirect();
	}

	public function FBcallback(FBloginService $service, Request $request){
		if (! $request->input('code')) {
			return redirect('login')->withErrors('Login failed: '.$request->input('error').' - '.$request->input('error_reason'));
		}
		$user = $service->createOrGetUser(Socialite::driver('facebook')->user());
		auth()->login($user);
		return redirect('/');
	}
}