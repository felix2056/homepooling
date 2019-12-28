<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Notification;
use App\Notifications\UserActivity;

use App\Property;
use App\Wanted;
use App\Order;
use App\User;
use App\Image;


use App\Setting;
use App\Slider;

class AdminController extends Controller
{
	public function home(){
		$gaInfo = Setting::where('meta_key', 'ga_key_file')->first();
        $KEY_FILE_LOCATION = null;
        if($gaInfo){
            $KEY_FILE_LOCATION = storage_path('app/secrets/'.$gaInfo->meta_value);
        }
        $googleToken = self::getGoogleAccessToken($KEY_FILE_LOCATION);

        $gaInfo = Setting::where('meta_key', 'ga_id')->first();
        $gaId = null;
        if($gaInfo){
            $gaId = $gaInfo->meta_value;
        }

		$now=Carbon::now();

		$data=[
			'properties'=> Property::count(),
			'wanteds'=> Wanted::count(),
			'orders'=> Order::count(),
			'users' => User::count(),
			'earnings'=> Order::sum('amount')
		];

		$wanteds = Wanted::orderBy('created_at','DESC')->get();
		$users = User::orderBy('created_at','DESC')->get();
		$properties = Property::orderBy('created_at','DESC')->get();
		$orders = Order::orderBy('created_at','DESC')->get();
		
		return view('admin.home', compact('properties','users','orders','data','googleToken',
            'gaId','wanteds'));
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
	public function destroyReport(Request $request){
		$report->delete();
		return redirect('/back-office/reports')->with('message','Report successfully deleted!');
	}

	public function settings(Request $request)
    {
        //for save on POST request
        if ($request->isMethod('post')) {

            //validate form
            $messages = [
            	'name.required' => 'The name field is required',
            	'short_name.required' => 'The short name field is required',
            	'description.required' => 'A good description of your website is used as meta data for SEO',
                'logo.max' => 'The :attribute size must be under 1MB.',
                'logo.dimensions' => 'The :attribute dimensions max be 430 X 80.',
                'favicon.max' => 'The :attribute size must be under 512kb.',
                'favicon.dimensions' => 'The :attribute dimensions must be 32 X 32.',
            ];

            $rules = [
                'name' => 'required|min:5|max:255',
                'short_name' => 'required|min:3|max:255',
                'description' => 'required|min:200|max:1000',
                'logo' => 'mimes:jpeg,jpg,png|max:1024|dimensions:max_width=430,max_height=80',
                'favicon' => 'mimes:png|max:512|dimensions:min_width=32,min_height=32,max_width=32,max_height=32',
                'website_link' => 'max:255',
                'email' => 'nullable|email|max:255',
                'phone_no' => 'min:8|max:15',
                'address' => 'max:500',
            ];

            $this->validate($request, $rules, $messages);

            if($request->hasFile('logo')) {
                $storagepath = $request->file('logo')->store('public/img');
                $fileName = basename($storagepath);
                $logo = $fileName;

                //if file chnage then delete old one
                $oldFile = $request->get('oldLogo','');
                if( $oldFile != ''){
                    $file_path = "public/img/".$oldFile;
                    Storage::delete($file_path);
                }
            }
            else{
                $logo = $request->get('oldLogo','');
            }

            Setting::updateOrCreate(
                ['meta_key' => 'logo'],
                ['meta_value' => $logo]
            );

            if($request->hasFile('favicon')) {
                $storagepath = $request->file('favicon')->store('public/img');
                $fileName = basename($storagepath);
                $favicon = $fileName;

                //if file chnage then delete old one
                $oldFile = $request->get('oldFavicon','');
                if( $oldFile != ''){
                    $file_path = "public/img/".$oldFile;
                    Storage::delete($file_path);
                }
            }
            else{
                $favicon = $request->get('oldFavicon','');
            }

            Setting::updateOrCreate(
                ['meta_key' => 'favicon'],
                ['meta_value' => $favicon]
            );

            $name = $request->get('name');
            Setting::updateOrCreate(
                ['meta_key' => 'name'],
                ['meta_value' => $name]
            );

            $short_name = $request->get('short_name');
            Setting::updateOrCreate(
                ['meta_key' => 'short_name'],
                ['meta_value' => $short_name]
            );

            $description = $request->get('description');
            Setting::updateOrCreate(
                ['meta_key' => 'description'],
                ['meta_value' => $description]
            );

            $website_link = $request->get('website_link');
            Setting::updateOrCreate(
                ['meta_key' => 'website_link'],
                ['meta_value' => $website_link]
            );

            $email = $request->get('email');
            Setting::updateOrCreate(
                ['meta_key' => 'email'],
                ['meta_value' => $email]
            );

            $phone_no = $request->get('phone_no');
            Setting::updateOrCreate(
                ['meta_key' => 'phone_no'],
                ['meta_value' => $phone_no]
            );

            $facebook = $request->get('facebook');
            Setting::updateOrCreate(
                ['meta_key' => 'facebook'],
                ['meta_value' => $facebook]
            );

            $twitter = $request->get('twitter');
            Setting::updateOrCreate(
                ['meta_key' => 'twitter'],
                ['meta_value' => $twitter]
            );

            $youtube = $request->get('youtube');
            Setting::updateOrCreate(
                ['meta_key' => 'youtube'],
                ['meta_value' => $youtube]
            );

            $instagram = $request->get('instagram');
            Setting::updateOrCreate(
                ['meta_key' => 'instagram'],
                ['meta_value' => $instagram]
            );

            //now notify the admins about this record
            $msg = "Website settings was recently updated by ".auth()->user()->name;


            //$alertAdmins = self::sendNotificationToAdmins('info', $msg);

            return redirect()->back()->with('success', 'Settings updated!');
        }

        $settings = Setting::whereIn(
            'meta_key', [
            	'logo',
            	'favicon',
                'name',
                'short_name',
                'description',
                'website_link',
                'email',
                'phone_no',
                'facebook',
                'twitter',
                'youtube',
                'instagram'
        ]
        )->get();

        $metas = [];

        foreach ($settings as $setting){
            $metas[$setting->meta_key] = $setting->meta_value;
        }
        $info = $metas;

        return view('admin.settings', compact('info'));
    }

	public function contactUs(Request $request)
    {
        //for save on POST request
        if ($request->isMethod('post')) {//
            $this->validate($request, [
                'address' => 'required|min:5|max:500',
                'phone_no' => 'required|min:5|max:500',
                'email' => 'required|email|min:5|max:500',
                'latlong' => 'required|min:5|max:500',

            ]);

            //return $request->all();

            //now create or update model
            $content = Setting::updateOrCreate(
                ['meta_key' => 'contact_address'],
                [ 'meta_value' => $request->get('address')]
            );
            $content = Setting::updateOrCreate(
                ['meta_key' => 'contact_phone'],
                [ 'meta_value' => $request->get('phone_no')]
            );
            $content = Setting::updateOrCreate(
                ['meta_key' => 'contact_email'],
                [ 'meta_value' => $request->get('email')]
            );
            $content = Setting::updateOrCreate(
                ['meta_key' => 'contact_latlong'],
                [ 'meta_value' => $request->get('latlong')]
            );

            if ($content) {
            	return redirect()->back()->with('success', 'Information saved!');
            }
            else{
            	return redirect()->back()->with('error', 'Failed to save Information!');
            }
        }

        //for get request
        $address = Setting::where('meta_key', 'contact_address')->first();
        $phone = Setting::where('meta_key', 'contact_phone')->first();
        $email = Setting::where('meta_key', 'contact_email')->first();
        $latlong = Setting::where('meta_key', 'contact_latlong')->first();
        
        return view('admin.contact_us', compact('address', 'phone', 'email', 'latlong'));
    }

	public function analytics(Request $request)
    {
        //for save on POST request
        if ($request->isMethod('post')) {
            //validate form
            $this->validate($request, [
                'ga_tracking_id' => 'required|max:255',
                'ga_report_id' => 'required|max:255',
                'ga_key_file' => 'required|file|mimetypes:text/plain',
            ]);


            $storagepath = $request->file('ga_key_file')->storeAs('secrets', 'ga_key_file.json');
            $fileName = basename($storagepath);

            //now crate
            Setting::updateOrCreate(
                ['meta_key' => 'ga_key_file'],
                ['meta_value' => $fileName]
            );
            Setting::updateOrCreate(
                ['meta_key' => 'ga_tracking_id'],
                ['meta_value' => $request->get('ga_tracking_id')]
            );
            Setting::updateOrCreate(
                ['meta_key' => 'ga_id'],
                ['meta_value' => $request->get('ga_report_id')]
            );

            return redirect()->route('admin.analytics')->with('success', 'Analytics Record Updated!');
        }

        //for get request
        $info = new \stdClass();
        $info->key_file = null;
        $info->ga_id = null;
        $info->ga_tracking_id = null;

        $gaInfo = Setting::where('meta_key', 'ga_key_file')->first();
        if($gaInfo){
            $info->key_file = $gaInfo->meta_value;
        }
        $gaInfo = Setting::where('meta_key', 'ga_id')->first();
        if($gaInfo){
            $info->ga_id = $gaInfo->meta_value;
        }
        $gaInfo = Setting::where('meta_key', 'ga_tracking_id')->first();
        if($gaInfo){
            $info->ga_tracking_id = $gaInfo->meta_value;
        }

        //return view('admin.analytics', compact('info'));
        return view('admin.analytics', compact('info'));
    }

    public function terms(Request $request)
    {
        //for save on POST request
        if ($request->isMethod('post')) {

            //validate form
            $messages = [
                'terms.required' => 'The name field is required'
            ];

            $rules = [
                'terms' => 'required|min:50'
            ];

            $this->validate($request, $rules, $messages);

            Setting::updateOrCreate(
                ['meta_key' => 'terms'],
                ['meta_value' => $request->terms]
            );

            return redirect()->back()->with('success', 'Terms has been updated!');
        }

        $terms = Setting::where('meta_key', 'terms')->pluck('meta_value');


        return view('admin.terms', compact('terms'));
    }

    public function timeline(Request $request)
    {
        //for save on POST request
        if ($request->isMethod('post')) {
            //validate form
            $this->validate($request, [
                'title' => 'required|min:5|max:255',
                'description' => 'required|min:5|max:500',
                'icon' => 'required|mimes:jpeg,jpg,png|max:1024|dimensions:max_width=56,max_height=56',
            ]);

            $data = [
                't' => $request->get('title'),
                'd' => $request->get('description')
            ];

            if($request->hasFile('icon')) {
                $storagepath = $request->file('icon')->store('public/img/home');
                $fileName = basename($storagepath);
                $data['i'] = $fileName;
            }
            //now crate
            Setting::create(
                [
                    'meta_key' => 'timeline',
                    'meta_value' => json_encode($data)
                ]
            );
            return redirect()->route('admin.timeline')->with('success', 'Timeline Record Added!');
        }

        //for get request
        $timeline = Setting::where('meta_key','timeline')->get();
        return view('admin.timeline', compact('timeline'));
    }

    /**
     * timeline section content image delete
     * @return array
     */
    public function timelineDelete($id)
    {

        $timeline = Setting::findOrFail($id);
        $timeline->delete();

        $file_path = "public/img/home/".$timeline->image;

        if ($file_path) {
        	Storage::delete($file_path);
        }

        return redirect()->route('admin.timeline')->with('success', 'Record Deleted!');
    }



    /*==============================================
    * Application Helper methods
    *===============================================
    /**
     *
     *    Send notification to users
     *
     */
    public static function sendNotificationToUsers($users, $type, $message){
        Notification::send($users, new UserActivity($type, $message));

        return true;
    }

    /**
     *
     *    Send notification to Admin users
     *
     */

    public static function sendNotificationToAdmins($type, $message){
    	$admins = User::where('is_admin', '1')->get();

        return self::sendNotificationToUsers($admins, $type, $message);
    }

    public static function base64url_encode($data) {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    public static function getGoogleAccessToken($private_key_file)
    {
        $result = [
            'success' => false,
            'message' => '',
            'token' => null
        ];

        if (Cache::has('google_token')) {
            $result['token'] = Cache::get('google_token');
            $result['success'] = true;
            return $result;
        }

        if(!file_exists($private_key_file)){
            $result['message'] = 'Google json key file missing!';
            return $result;

        }

        $jwtAssertion = self::getJwtAssertion($private_key_file);

        try {

            $client = new Client([
                'base_uri' => 'https://www.googleapis.com',
            ]);
            $payload = [
                'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion' => $jwtAssertion
            ];

            $response = $client->request('POST', 'oauth2/v4/token', [
                'form_params' => $payload
            ]);

            $data = json_decode($response->getBody());
            $result['token'] = $data->access_token;
            $result['success'] = true;

            $expiresAt = now()->addMinutes(58);
            Cache::put('google_token', $result['token'], $expiresAt);

        } catch (RequestException $e) {
            $result['message'] = $e->getMessage();
        }


        return $result;

    }

    public static function getJwtAssertion($private_key_file)
    {

        $json_file = file_get_contents($private_key_file);
        $info = json_decode($json_file);
        $private_key = $info->{'private_key'};

        //{Base64url encoded JSON header}
        $jwtHeader = self::base64url_encode(json_encode(array(
            "alg" => "RS256",
            "typ" => "JWT"
        )));

        //{Base64url encoded JSON claim set}
        $now = time();
        $jwtClaim = self::base64url_encode(json_encode(array(
            "iss" => $info->{'client_email'},
            "scope" => "https://www.googleapis.com/auth/analytics.readonly",
            "aud" => "https://www.googleapis.com/oauth2/v4/token",
            "exp" => $now + 3600,
            "iat" => $now
        )));

        $data = $jwtHeader.".".$jwtClaim;

        // Signature
        $Sig = '';
        openssl_sign($data,$Sig,$private_key,'SHA256');
        $jwtSign = self::base64url_encode($Sig);

        //{Base64url encoded JSON header}.{Base64url encoded JSON claim set}.{Base64url encoded signature}
        $jwtAssertion = $data.".".$jwtSign;
        return $jwtAssertion;
    }
}