<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

use App/Setting;

class SettingsController extends Controller
{
    /**
     * institute setting section content manage
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function institute(Request $request)
    {
        //for save on POST request
        if ($request->isMethod('post')) {

            //validate form
            $messages = [
                'logo.max' => 'The :attribute size must be under 1MB.',
                'logo.dimensions' => 'The :attribute dimensions max be 230 X 50.',
                'favicon.max' => 'The :attribute size must be under 512kb.',
                'favicon.dimensions' => 'The :attribute dimensions must be 32 X 32.',
            ];

            $rules = [
                'name' => 'required|min:5|max:255',
                'short_name' => 'required|min:3|max:255',
                'logo' => 'mimes:jpeg,jpg,png|max:1024|dimensions:max_width=230,max_height=50',
                'favicon' => 'mimes:png|max:512|dimensions:min_width=32,min_height=32,max_width=32,max_height=32',
                'website_link' => 'max:255',
                'email' => 'nullable|email|max:255',
                'phone_no' => 'required|min:8|max:15',
                'address' => 'required|max:500',
            ];

            $this->validate($request, $rules, $messages);

            if($request->hasFile('logo')) {
                $storagepath = $request->file('logo')->store('img');
                $fileName = basename($storagepath);
                $data['logo'] = $fileName;

                //if file chnage then delete old one
                $oldFile = $request->get('oldLogo','');
                if( $oldFile != ''){
                    $file_path = "/storage/img/".$oldFile;
                    Storage::delete($file_path);
                }
            }
            else{
                $data['logo'] = $request->get('oldLogo','');
            }

            if($request->hasFile('favicon')) {
                $storagepath = $request->file('favicon')->store('public/logo');
                $fileName = basename($storagepath);
                $data['favicon'] = $fileName;

                //if file chnage then delete old one
                $oldFile = $request->get('oldFavicon','');
                if( $oldFile != ''){
                    $file_path = "public/logo/".$oldFile;
                    Storage::delete($file_path);
                }
            }
            else{
                $data['favicon'] = $request->get('oldFavicon','');
            }

            $data['name'] = $request->get('name');
            $data['short_name'] = $request->get('short_name');
            $data['website_link'] = $request->get('website_link');
            $data['email'] = $request->get('email');
            $data['phone_no'] = $request->get('phone_no');
            $data['address'] = $request->get('address');

            Setting::updateOrCreate([
            	'name' => $data['name'],
            	'short_name' => $data['short_name'],
            	'website_link' => $data['website_link'],
            	'email' => $data['email'],
            	'phone_no' => $data['phone_no'],
            	'address' => $data['address'],
            	'logo' => $data['logo'],
            	'favicon' => $data['favicon'],
            ]);

            //now notify the admins about this record
            $msg = "Website settings was recently updated by ".auth()->user()->name;
            $nothing = AppHelper::sendNotificationToAdmins('info', $msg);
            // Notification end

            return redirect()->back()->with('success', 'Settings updated!');
        }

        $settings = Setting::all();

        return view('admin.settings');
    }
}
