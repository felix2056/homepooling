@extends('layouts.app')

@section('content')
<main id="content" class="profile">
	<!-- <div class="page-header" id="headerContent"></div> -->
	@if(!isset($user->verified) || $user->verified==0)
		<div id="verify_popup" class="d-flex align-items-center justify-content-center">
			<div class="verify_inner">
				<div class="close"><i class="fa fa-window-close"></i></div>
				<div class="profile_card d-flex-column align-items-center justify-content-center">
					<div class="phase_1 d-flex-column align-items-center justify-content-center">
						<h3>Enter your phone number</h3>
						<p>We will send you a SMS to verify your identity</p>
						<form id="ver_num" data-user="{{$user->id}}" action="/profiles/{{$user->id}}/sms" method="POST" class="d-flex-column align-items-center justify-content-center">
							<input type="text" id="verify_number" class="form-control" @if(isset($user->phone)) value="{{$user->phone}}" @endif>
							<button class="btn btn-custom">Send</button>
						</form>
					</div>
					<div class="phase_2 d-flex-column align-items-center justify-content-center">
						<h3>Enter the verification code</h3>
						<p>Please, insert the code you received via SMS to verify your account</p>
						<form id="ver_cod" data-user="{{$user->id}}" action="/profiles/{{$user->id}}/verify" method="POST" class="d-flex-column align-items-center justify-content-center">
							{!! csrf_field() !!}
							
							<input type="text" id="verify_code" class="form-control">
							<button class="btn btn-custom">Confirm</button>
						</form>
					</div>
				</div>
			</div>
		</div>
	@endif
	<div class="container-fluid p-0"></div>
		<?php /*<ul class="nav nav-tabs custom-navtabs col-lg-12 col-xs-12 col-md-12 col-sm-12" role="tablist" style="background: none;">
			<li role="presentation" class="active col-lg-3 col-xs-3 col-sm-3 col-md-3 p20">
				<a href="#edit" id="profile_edit_button" role="tab" data-toggle="tab" aria-controls="edit" aria-expanded="true">
					<div>
						<h4>Edit Profile</h4>
					</div>
				</a>
			</li>
			<li role="presentation" class="col-lg-3 col-xs-3 col-sm-3 col-md-3 p20">
				<a href="#search" id="profile_search_button" role="tab" data-toggle="tab" aria-controls="search" aria-expanded="true">
					<div>
						<h4>Search Settings</h4>
					</div>
				</a>
			</li>
		</ul>*/ ?>
		@if(\Auth()->user())
       <!--  <div class="sub-nav col-xs-12">
        	<div class="container">
	            <a class="hvr-underline-from-center" href="#edit" id="profile_search_button" role="tab" data-toggle="tab" aria-controls="search" aria-expanded="false">Edit Profile</a>
	            <a class="hvr-underline-from-center" href="#search" id="profile_search_button" role="tab" data-toggle="tab" aria-controls="search" aria-expanded="false">Search Settings</a>
	            <a class="hvr-underline-from-center" href="#favorites" id="profile_search_button" role="tab" data-toggle="tab" aria-controls="search" aria-expanded="false">Favorites</a>
	            <a class="hvr-underline-from-center" href="#myPools" id="profile_search_button" role="tab" data-toggle="tab" aria-controls="search" aria-expanded="false">My Pools</a> -->
	            <!-- <div class="hvr-underline-from-center" onclick="#">Profile Settings</div> -->
	            <!-- <div class="hvr-underline-from-center" onclick="#">Looking For</div> -->
	            <!-- <div class="hvr-underline-from-center" onclick="#">Packages</div>
        	</div>
        </div> -->
        @endif
        @php
        	$myPools_active = $favorites_active = $search_active = $edit_active =''; 
        	if( Request::get('menu') == 'edit'){
        		$edit_active = 'active';
        	}else if(Request::get('menu') == 'search'){
        		$search_active = 'active';
        	}else if(Request::get('menu') == 'favorites'){
        		$favorites_active = 'active';
        	}else if(Request::get('menu') == 'myPools'){
        		$myPools_active = 'active';
        	}else{
        		$edit_active = 'active';
        	}
        @endphp

	<form id="profile_edit" action="/profiles/{{$user->id}}" method="POST" enctype="multipart/form-data">
		<input type="hidden" name="_method" value="PUT">
		{!! csrf_field() !!}
		<div class="tab-content">
			<!-- My Profile -->
			<div role="tabpanel" class="tab-pane fade in {{ $edit_active }}" id="edit" aria-labelledby="edit-tab">
	            <div class="white-area">
	                <h3 class="p-0">My Profile</h3>
	                <span>Upload your profile images & manage your profile</span>
	                <h3 class="mt50">Profile Image</h3>
	                <div class="d-flex w-100 profile_image profile-image-change mt15 mb-0">
	                    <div class="float-left">
	                        <img class="profile-image" src="{{ isset($user->photo) && $user->photo !== '' ? $user->photo : '/storage/img/profile_placeholder.png' }}">
	                        <!-- <div class="overlay"><i class="fa fa-file-image-o "></i></div> -->
	                    </div>
	                    <div class="float-left">
							<input type="file" id="profile_photo" name="photo" class="hidden" @isset($user->photo) value="{{$user->photo}}" @endisset />
	                    	<label for="profile_photo">Change Image</label>
	                        <p class="m-0">Maximum file size: 2MB</p>
	                    </div>
	                </div>
	                <div class="inp-container d-inline-block mt50">
	                    <label class="d-inline-block w-100 mb7">First Name</label>
	                    <input type="text" placeholder="e.g John" name="name" class="d-inline-block w-100 mb20 inp" value="{{ $user->name }}">
	                </div>
	                <div class="inp-container d-inline-block">
	                    <label class="d-inline-block w-100 mb7">Last Name</label>
	                    <input type="text" placeholder="e.g Smith" name="family_name" class="d-inline-block w-100 mb20 inp" value="{{ $user->family_name }}">
	                </div>
	                <div class="inp-container d-inline-block">
	                    <label class="d-inline-block w-100 mb7">Gender</label>
	                    <!-- <input type="text" placeholder="temporary" name="" class="d-inline-block w-100 mb20 inp"> -->
	                    <select class="form-control mb20" name="gender" id="gender" style="margin: 0; min-height: 38px; border-radius: 3px;">
							<option name="gender" value="m" @isset($user->gender) @if($user->gender=='m') checked selected @endif @endisset>Male</option>
							<option name="gender" value="f" @isset($user->gender) @if($user->gender=='f') checked selected @endif @endisset>Female</option>
							<option name="gender" value="q" @isset($user->gender) @if($user->gender=='q') checked selected @endif @endisset>LGBT+</option>
						</select>
	                </div>
	                <div class="inp-container d-inline-block">
	                    <label class="d-inline-block w-100 mb7">Date of Birth</label>
	                    <input type="text" placeholder="e.g 01/30/1970" name="birthday" class="d-inline-block w-100 mb20 inp" data-toggle="datepicker" value="{{ date('d/m/Y', strtotime($user->birthday)) }}">
	                </div>
	                <div class="inp-container d-inline-block">
	                    <label class="d-inline-block w-100 mb7">Email</label>
	                    <input type="text" placeholder="e.g johnSmith@provider.com" name="email" class="d-inline-block w-100 mb20 inp" value="{{ $user->email }}">
	                </div>
	                <div class="inp-container d-inline-block">
	                    <label class="d-inline-block w-100 mb7">Phone</label>
	                    <input type="text" placeholder="e.g +390000000000" name="phone" class="d-inline-block w-100 mb20 inp" value="{{ $user->phone }}">
	                </div>
	                <div class="inp-container d-inline-block">
	                    <label class="d-inline-block w-100 mb7">Occupation</label>
	                    <input type="text" placeholder="Occupation" name="profession" class="d-inline-block w-100 mb20 inp" value="{{ $user->profession }}">
	                </div>
	                <div class="inp-container d-inline-block">
	                    <label class="d-inline-block w-100 mb7">Language</label>
	                    <select class="form-control mb20" name="language" id="language" style="margin: 0; min-height: 38px; border-radius: 3px; -webkit-appearance: none;">
							<option value="en" @isset($user->language) @if($user->language == 'en') checked selected @endif @endisset>English</option>
							<option value="dut" @isset($user->language) @if($user->language == 'dut') checked selected @endif @endisset>Dutch</option>
							<option value="flm" @isset($user->language) @if($user->language == 'flm') checked selected @endif @endisset>Flemish</option>
						</select>
	                    <!-- <input type="text" placeholder="Language" name="language" class="d-inline-block w-100 mb20 inp"> -->
	                </div>
	                <div class="d-block ">
	                    <h3 class="d-inline-block w-100 mt50">Change Password</h3>
	                    <div class="inp-container d-inline-block mt20">
	                        <label class="d-inline-block w-100 mb7">Current Password</label>
	                        <input type="text" name="password" class="d-inline-block w-100 mb20 inp">
	                    </div>
	                    <div class="inp-container d-inline-block">
	                        <label class="d-inline-block w-100 mb7">New Password</label>
	                        <input type="text" name="new_password" class="d-inline-block w-100 mb20 inp">
	                    </div>
	                    <div class="inp-container d-inline-block">
	                        <label class="d-inline-block w-100 mb7">Confirm Password</label>
	                        <input type="text" name="confirm_password" class="d-inline-block w-100 mb20 inp">
	                    </div>
	                </div>
	                <!-- <div class="">
	                    <button class="blue-button mt20">Save</button>
	                </div> -->

	            </div>
	            <div class="white-area mt50">
	            	<h3>Delete Account</h3>
	            	<p class="mb20">We do our best to give you the best experience and will be sad to see you leave, contact us here if you have any questions.</p>
	            	<p>If you want to proceed with your account deletion type your password below and press delete.</p>
	            	<div class="inp-container d-inline-block mt20">
	            		<input type="password" name="remove-account-pass" class="inp d-inline-block w-100" value="" autocomplete="false">
	            	</div>
	            	<div class="inp-container d-inline-block mt20">
	            		<button id="remove-acc-btn">Remove Account</button>
	            	</div>
	            </div>

				<?php /*<div class="container">
					<div class="profile_image">
						<input type="file" id="profile_photo" name="photo" @isset($user->photo) value="{{$user->photo}}" @endisset />
						<label for="profile_photo">
							@if(isset($user->photo) && $user->photo!=='')
								<img src="{{$user->photo}}" alt="{{$user->name}}">
							@else
								<img src="/storage/img/profile_placeholder.png">
							@endif
							<div class="overlay"><i class="fa fa-file-image-o "></i></div>
						</label>
						<label for="profile_photo">
							<div class="profile_image_change_label">
								<h5>Change Profile Photo</h5>
							</div>
						</label>
					</div>
					<div class="profile_card col-md-8 col-lg-8 col-xs-12 col-sm-12">
						<div class="col_sx">
							<h6>First Name</h6><input class="form-control" name="name" id="name" @isset($user->name) value="{{$user->name}}" @endisset>
							<h6>Gender</h6>
							<div class="sa-lable">
								<select class="form-control" name="gender" id="gender" >
									<option name="gender" value="m" @isset($user->gender) @if($user->gender=='m') checked selected @endif @endisset>Male</option>
									<option name="gender" value="f" @isset($user->gender) @if($user->gender=='f') checked selected @endif @endisset>Female</option>
									<option name="gender" value="q" @isset($user->gender) @if($user->gender=='q') checked selected @endif @endisset>LGBT+</option>
								</select>
							</div>
							<h6>Email</h6><input class="form-control" name="email" id="email" @isset($user->email) value="{{$user->email}}" @endisset>
						</div>
						<div class="col_dx">
							<h6>Last Name</h6><input class="form-control" name="family_name" id="family_name" @isset($user->family_name) value="{{$user->family_name}}" @endisset>
							<h6>Date of Birth</h6><input data-toggle="datepicker" class="form-control" name="birthday" id="birthday" @isset($user->birthday) value="{{ date('d/m/Y', strtotime($user->birthday)) }}" @endisset>
						</div>
						<div class="col_full">
							<h6>Phone Number</h6>
							<div class="verify">
								<input class="form-control" name="phone" id="phone" @isset($user->phone) value="{{$user->phone}}" @endisset>
								<button id="verify" class="btn btn-default @if(isset($user->verified) && $user->verified==1) disabled verified @endif ">@if(isset($user->verified) && $user->verified==1) Verified <i class="fa fa-check"></i> @else Verify @endif</button>
								@if(!isset($user->verified) || $user->verified==0) 
									<a id="reveal_tooltip" class="reveal_tooltip">Why Verify?</a>
									<div id="verify_tooltip" class="nonVis">
										<i class="fa fa-caret-down"></i>
										<p>This gives Homepooling a way to verify our members.</p>
										<p>You might be asked to verify your phone number when listing your property.</p>
										<p>You also need a verified phone number to complete <strong>Verified ID</strong></p>
										<i class="fa fa-close"></i>
									</div> 
								@endif
							</div>
						</div>
					</div>
					<div class="profile_card col-md-8 col-lg-8 col-xs-12 col-sm-12">
						<div class="col_sx">
							<h6>Profession</h6><input class="form-control" name="profession" id="profession" @isset($user->profession) value="{{$user->profession}}" @endisset>
						</div>
						<div class="col_dx">
							<h6>From</h6><input class="form-control" name="origin" id="origin" @isset($user->origin) value="{{$user->origin}}" @endisset>
						</div>
						<div class="bio col_full">
							<h6>Bio</h6><textarea class="form-control" name="description" id="description">@isset($user->description){{$user->description}}@endisset </textarea>
						</div>
					</div>
				</div>*/?>
			</div>

			<!-- Search Settings -->
			<div role="tabpanel" class="tab-pane fade in {{ $search_active }}" id="search" aria-labelledby="search-tab">
				<div class="white-area">
					<h3 class="p-0">Looking For</h3>
					<span>Here you can tell us what kind of property and location you are interested in.</span>
					<div class="d-block mt30">
						<div class="inp-container d-inline-block">
							<label class="d-inline-block w-100 mb7">Location</label>
							<input id="autocomplete" class="d-inline-block w-100 mb20 inp" type="text" name="location" @if(Auth::check() && isset(Auth::user()->preferences) && isset(Auth::user()->preferences->location)) value="{{Auth::user()->location}}" @else placeholder="No location preference" @endif />
						</div>
						<div class="inp-container d-inline-block">
							<label class="d-inline-block w-100 mb7">Available from</label>
							<input class="d-inline-block w-100 mb20 inp" type="text" name="avail_from" @if(Auth::check() && isset(Auth::user()->preferences) && isset(Auth::user()->preferences->avail_from)) value="{{Auth::user()->preferences->avail_from}}" @else placeholder="No availability preference" @endif />
						</div>
						<div class="inp-container d-inline-block">
							<label class="d-inline-block w-100 mb7">Budget</label>
							<select class="d-inline-block w-100 mb20 inp" id="budget" name="budget">
								<option name="budget" value="none" @if(!isset(Auth::user()->preferences) || !isset(Auth::user()->preferences->budget) || Auth::user()->preferences->budget=='' || Auth::user()->preferences->budget=='on') selected checked @endif>No preference</option>
								<option name="budget" value="0-149" @if(Auth::check() && isset(Auth::user()->preferences) && isset(Auth::user()->preferences->budget) && Auth::user()->preferences->budget=='0-149') selected checked @endif>0-149</option>
								<option name="budget" value="150-299" @if(Auth::check() && isset(Auth::user()->preferences) && isset(Auth::user()->preferences->budget) && Auth::user()->preferences->budget=='150-299') selected checked @endif>150-299</option>
								<option name="budget" value="300-499" @if(Auth::check() && isset(Auth::user()->preferences) && isset(Auth::user()->preferences->budget) && Auth::user()->preferences->budget=='300-499') selected checked @endif>300-499</option>
								<option name="budget" value="500-749" @if(Auth::check() && isset(Auth::user()->preferences) && isset(Auth::user()->preferences->budget) && Auth::user()->preferences->budget=='500-749') selected checked @endif>500-749</option>
								<option name="budget" value="750-999" @if(Auth::check() && isset(Auth::user()->preferences) && isset(Auth::user()->preferences->budget) && Auth::user()->preferences->budget=='750-999') selected checked @endif>750-999</option>
								<option name="budget" value="1000+" @if(Auth::check() && isset(Auth::user()->preferences) && isset(Auth::user()->preferences->budget) && Auth::user()->preferences->budget=='1000+') selected checked @endif>1000+</option>
							</select>
						</div>
						<div class="inp-container d-inline-block">
							<label class="d-inline-block w-100 mb7">Minimum EPC</label>
							<select class="d-inline-block w-100 mb20 inp" id="epc" name="epc">
								<option name="epc" value="none" @if(!isset(Auth::user()->preferences) || !isset(Auth::user()->preferences->epc) || Auth::user()->preferences->epc=='' || Auth::user()->preferences->epc=='on') selected checked @endif>No preference</option>
								<option name="epc" value="a" @if(Auth::check() && isset(Auth::user()->preferences) && isset(Auth::user()->preferences->epc) && Auth::user()->preferences->epc=='a') selected checked @endif>A</option>
								<option name="epc" value="b" @if(Auth::check() && isset(Auth::user()->preferences) && isset(Auth::user()->preferences->epc) && Auth::user()->preferences->epc=='b') selected checked @endif>B</option>
								<option name="epc" value="c" @if(Auth::check() && isset(Auth::user()->preferences) && isset(Auth::user()->preferences->epc) && Auth::user()->preferences->epc=='c') selected checked @endif>C</option>
								<option name="epc" value="d" @if(Auth::check() && isset(Auth::user()->preferences) && isset(Auth::user()->preferences->epc) && Auth::user()->preferences->epc=='d') selected checked @endif>D</option>
								<option name="epc" value="e" @if(Auth::check() && isset(Auth::user()->preferences) && isset(Auth::user()->preferences->epc) && Auth::user()->preferences->epc=='e') selected checked @endif>E</option>
								<option name="epc" value="f" @if(Auth::check() && isset(Auth::user()->preferences) && isset(Auth::user()->preferences->epc) && Auth::user()->preferences->epc=='f') selected checked @endif>F</option>
								<option name="epc" value="g" @if(Auth::check() && isset(Auth::user()->preferences) && isset(Auth::user()->preferences->epc) && Auth::user()->preferences->epc=='g') selected checked @endif>G</option>
							</select>
						</div>
						<div class="inp-container d-inline-block">
							<label class="d-inline-block w-100 mb7">Property type</label>
							<select class="d-inline-block w-100 mb20 inp" id="property_type" name="property_type">
								<option name="property_type" value="none" @if(!isset(Auth::user()->preferences) || !isset(Auth::user()->preferences->property_type) || Auth::user()->preferences->property_type=='' || Auth::user()->preferences->property_type=='on') selected checked @endif>No preference</option>
								<option name="property_type" value="house" @if(Auth::check() && isset(Auth::user()->preferences) && isset(Auth::user()->preferences->property_type) && Auth::user()->preferences->property_type=='house') selected checked @endif>House</option>
								<option name="property_type" value="apartment" @if(Auth::check() && isset(Auth::user()->preferences) && isset(Auth::user()->preferences->property_type) && Auth::user()->preferences->property_type=='apartment') selected checked @endif>Apartment</option>
								<option name="property_type" value="other" @if(Auth::check() && isset(Auth::user()->preferences) && isset(Auth::user()->preferences->property_type) && Auth::user()->preferences->property_type=='other') selected checked @endif>Other</option>
							</select>
						</div>
						<?php /*<div class="wanted_checkbox checkbox_wrapper">
								<div class="col-md-4 col-lg-4 col-xs-6 col-sm-6">
									<input type="checkbox" id="has_bathroom" name="has_bathroom" value="1" @if(Auth::check() && isset(Auth::user()->preferences) && isset(Auth::user()->preferences->has_bathroom) && Auth::user()->preferences->has_bathroom==1) checked="checked" selected="selected" @endif>
									<label for="has_bathroom">In room bathroom</label>
								</div>
								<div class="col-md-4 col-lg-4 col-xs-6 col-sm-6">
									<input type="checkbox" id="p_empty" name="p_empty" value="1" @if(Auth::check() && isset(Auth::user()->preferences) && isset(Auth::user()->preferences->p_empty) && Auth::user()->preferences->p_empty==1) checked="checked" selected="selected" @endif>
									<label for="p_empty">Only empty property</label>
								</div>
								<div class="col-md-4 col-lg-4 col-xs-6 col-sm-6">
									<input type="checkbox" id="single" name="single" value="1" @if(Auth::check() && isset(Auth::user()->preferences) && isset(Auth::user()->preferences->single) && Auth::user()->preferences->single==1) checked="checked" selected="selected" @endif>
									<label for="single">Only single room</label>
								</div>
						</div>*/ ?>
					</div>
					<div class="d-block mt50">
						<div class="inp-container d-inline-block mb20">
							<label class="d-inline-block w-100 mb7">Receive Suggestions via Email</label>
							<div class="d-inline-block mr10">
								<input type="radio" id="notification" class="d-inline-block w-100 mb20 inp radio-inp-new" name="notification" value="1" @if(Auth::check() && isset(Auth::user()->preferences) && Auth::user()->preferences->notify_by_mail == 1) checked="checked" selected="selected" @endif>
								<label class="d-inline-block w-100 mb7" for="notification">Yes</label>
							</div>
							<div class="d-inline-block">
								<input type="radio" id="notification-no" class="d-inline-block w-100 mb20 inp radio-inp-new" name="notification" value="0" @if(Auth::check() && isset(Auth::user()->preferences) && Auth::user()->preferences->notify_by_mail == 0) checked="checked" selected="selected" @endif>
								<label class="d-inline-block w-100 mb7" for="notification-no">No</label>
							</div>
						</div>
						<div class="inp-container d-inline-block">
							<label class="d-inline-block w-100 mb7">Frequency</label>
							<select class="d-inline-block w-100 mb20 inp" id="digest" name="digest">
								<option name="digest" value="none" @if(!isset(Auth::user()->preferences) || !isset(Auth::user()->preferences->receive_digest) || Auth::user()->preferences->receive_digest==0) selected checked @endif>No digest</option>
								<option name="digest" value="daily" @if(Auth::check() && isset(Auth::user()->preferences) && isset(Auth::user()->preferences->digest_freq) && Auth::user()->preferences->digest_freq=='daily') selected checked @endif>Daily Digest</option>
								<option name="digest" value="weekly" @if(Auth::check() && isset(Auth::user()->preferences) && isset(Auth::user()->preferences->digest_freq) && Auth::user()->preferences->digest_freq=='weekly') selected checked @endif>Weekly Digest</option>
								<option name="digest" value="monthly" @if(Auth::check() && isset(Auth::user()->preferences) && isset(Auth::user()->preferences->digest_freq) && Auth::user()->preferences->digest_freq=='monthly') selected checked @endif>Monthly Digest</option>
							</select>
						</div>
						<?php /*<div class="amenities_title">
							<h5>Amenities</h5>
						</div>
						<div class="wanted_amenities">
							<div class="col-lg-12 col-xs-12 col-sm-12 col-md-12">
								<div class="row">
									<div class="amenities">
										@foreach($amenities as $am)
											<div class="amenity">
												<input id="{{'am'.$am->id}}" name="amenities[]" type="checkbox" class="amenity_check {{$am->name}}" value="{{$am->id}}" @if(Auth::check() && isset(Auth::user()->preferences) && isset(Auth::user()->preferences->has_bathroom) && in_array($am->id, $p_amenities) ) checked="checked" @endif>
												<label for="{{'am'.$am->id}}"><span><i class="fa {{$am->icon}}"></i></span>{{$am->label}}</label>
											</div>
										@endforeach
									</div>
								</div>
							</div>
						</div>*/ ?>
					</div>
				</div>
			</div>


			<!-- My Pools -->
			<div role="tabpanel" class="tab-pane fade in {{ $myPools_active }}" id="myPools" aria-labelledby="edit-tab">
	            <div class="white-area eight-h">
	            	<h3 class="m-0">My Pools</h3>
	            	<span>Here are your posted properties.</span>
					@if(isset($properties) && Auth::check() && $user->id==Auth::user()->id)
						<div class="prop-list mt20">
							@if($properties->count() > 0)
								<div class="list-prop heading">
									<div class="inner-flex">
										<div class="f-prop-img heading"></div>
										<div class="f-prop-loc p5 text-center"><strong>Location</strong></div>
									</div>
									<div class="inner-flex">
										<div class="f-prop-occ p5 text-center"><strong>Occupancy</strong></div>
										<div class="f-prop-ptype p5 text-center"><strong>Property type</strong></div>
										<div class="f-prop-price p5 text-center"><strong>Price</strong></div>
										<div class="f-prop-actions"></div>
									</div>
								</div>
								@foreach($properties as $property)
									<?php
										$img = $property->images->count() > 0 ? ($property->images[0] ? $property->images[0]->url : '/storage/img/banner.jpeg') : '/storage/img/banner.jpeg';
										$ext = ($img && $img != '/storage/img/banner.jpeg') ? ('.'.pathinfo($img, PATHINFO_EXTENSION)) : '';
										$previewThumb = $ext ? str_replace(basename($img), 'thumbs/'.basename($img, $ext).'_th'.$ext, $img) : $img;
										$price = isset($property->price) ? $property->price : ((isset($property->rooms) && isset($property->rooms[0]->price)) ? $property->rooms[0]->price : '');
									?>
									<div class="list-prop data">
										<div class="inner-flex">
											<a href="{{ route('properties.show', $property->id) }}" class="f-prop-img">
												<div style="background-image: url({{ $previewThumb }});" class="act-img"></div>
											</a>
											<a href="{{ route('properties.show', $property->id) }}" class="f-prop-loc p5 text-center">{{ $property->address }}</a>
										</div>
										<div class="inner-flex">
											<a href="{{ route('properties.show', $property->id) }}" class="f-prop-occ p5 text-center"></a>
											<a href="{{ route('properties.show', $property->id) }}" class="f-prop-ptype p5 text-center">{{ $property->property_type }}</a>
											<a href="{{ route('properties.show', $property->id) }}" class="f-prop-price p5 text-center">&#8364; {{ $price }}</a>
											<div class="f-prop-actions">
												<a class='edit_btn' href="{{ route('properties.edit', $property->id) }}" id="favorite_{{ $property->id }}">
													<!-- <i class="far fa-trash-alt"></i> -->
													<img class="del-icon" src="/storage/icons/edit_icon.png">
												</a>
											</div>
										</div>
									</div>
								@endforeach
							@endif
						</div>
					@else
						<div class="alert aler-danger p-3">No favorites yet!</div>
					@endif
				</div>
			</div>

			<!-- Favorites -->
			<div role="tabpanel" class="tab-pane fade in {{ $favorites_active }}" id="favorites" aria-labelledby="edit-tab">
	            <div class="white-area eight-h">
	            	<h3 class="m-0">Favourites</h3>
	            	<span>Here are your favourited properties.</span>
					@if(isset($favorites) && Auth::check() && $user->id==Auth::user()->id)
						<div class="prop-list mt20">
							@if(count($favorites) > 0)
								<div class="list-prop heading">
									<div class="inner-flex">
										<div class="f-prop-img heading"></div>
										<div class="f-prop-loc p5 text-center"><strong>Location</strong></div>
									</div>
									<div class="inner-flex">
										<div class="f-prop-occ p5 text-center"><strong>Occupancy</strong></div>
										<div class="f-prop-ptype p5 text-center"><strong>Property type</strong></div>
										<div class="f-prop-price p5 text-center"><strong>Price</strong></div>
										<div class="f-prop-actions"></div>
									</div>
								</div>
								@foreach($favorites as $property)
									<?php
										$img = $property->images->count() > 0 ? ($property->images[0] ? $property->images[0]->url : '/storage/img/banner.jpeg') : '/storage/img/banner.jpeg';
										$ext = ($img && $img != '/storage/img/banner.jpeg') ? ('.'.pathinfo($img, PATHINFO_EXTENSION)) : '';
										$previewThumb = $ext ? str_replace(basename($img), 'thumbs/'.basename($img, $ext).'_th'.$ext, $img) : $img;
										$price = isset($property->price) ? $property->price : ((isset($property->rooms) && isset($property->rooms[0]->price)) ? $property->rooms[0]->price : '');
									?>
									<div class="list-prop data">
										<div class="inner-flex">
											<a href="{{ route('properties.show', $property->id) }}" class="f-prop-img">
												<div style="background-image: url({{ $previewThumb }});" class="act-img"></div>
											</a>
											<a href="{{ route('properties.show', $property->id) }}" class="f-prop-loc p5 text-center">{{ $property->address }}</a>
										</div>
										<div class="inner-flex">
											<a href="{{ route('properties.show', $property->id) }}" class="f-prop-occ p5 text-center"></a>
											<a href="{{ route('properties.show', $property->id) }}" class="f-prop-ptype p5 text-center">{{ $property->property_type }}</a>
											<a href="{{ route('properties.show', $property->id) }}" class="f-prop-price p5 text-center">&#8364; {{ $price }}</a>
											<div class="f-prop-actions">
												<a href="javascript:vodi(0);" id="favorite_{{ $property->id }}" class="favorite">
													<!-- <i class="far fa-trash-alt"></i> -->
													<img class="del-icon" src="/storage/icons/trash_icon.png">
												</a>
											</div>
										</div>
									</div>
								@endforeach
							@endif
						<?php /*<div class="profile_favorite prop_table m-0">
							<table width="100%">
								<thead>
									<tr>
										<td>Address</td>
										<td>Number of rooms</td>
										<td>Property Type</td>
										<td>Price</td>
									</tr>
								</thead>
								<tbody>
									@foreach($favorites as $property)
										<tr>
											<td><a href="/properties/{{ $property->id }}">{{ $property->address }}</a></td>
											<td><a href="/properties/{{ $property->id }}">{{ $property->rooms_no }}</a></td>
											<td><a href="/properties/{{ $property->id }}">{{ $property->property_type }}</a></td>
											<td><a href="/properties/{{ $property->id }}">&#8364; {{ isset($property->price) ? $property->price : ((isset($property->rooms) && isset($property->rooms[0]->price)) ? $property->rooms[0]->price : '') }}</a></td>
										</tr>
									@endforeach
								</tbody>
							</table>
						</div>*/ ?>
					@else
						<div class="alert aler-danger p-3">No favorites yet!</div>
					@endif
				</div>
			</div>

			<!-- Properties -->

			<?php /*<div role="tabpanel" class="tab-pane fade active in" id="properties" aria-labelledby="edit-tab">
	            <div class="white-area">
					<div class="profile_properties prop_table m-0">
						@if($properties->count()>0||(Auth::check() && $user->id==Auth::user()->id && $pendings->count()>0))
							<h3>@if(Auth::check() && $user->id==Auth::user()->id)Your @endif Properties</h3>
							<table width="100%">
								<thead>
									<tr>
										<td>Address</td>
										<td>Number of rooms</td>
										<td>Property Type</td>
										@if(Auth::check() && $user->id==Auth::user()->id)
											<td>Status</td>
										@endif
										<td>Price</td>
									</tr>
								</thead>
								<tbody>
									@foreach($properties as $property)
										<tr>
											<td><a href="/properties/{{ $property->id }}">{{ $property->address }}</a></td>
											<td><a href="/properties/{{ $property->id }}">{{ $property->rooms_no }}</a></td>
											<td><a href="/properties/{{ $property->id }}">{{ $property->property_type }}</a></td>
											@if(Auth::check() && $user->id==Auth::user()->id)
												<td><a href="/properties/{{ $property->id }}">{{ $property->status }}</a></td>
											@endif
											<td><a href="/properties/{{ $property->id }}">&#8364; {{ isset($property->price) ? $property->price : ((isset($property->rooms) && isset($property->rooms[0]->price)) ? $property->rooms[0]->price : '') }}</a></td>
										</tr>
									@endforeach
									@if(Auth::check() && $user->id==Auth::user()->id)
										@foreach($pendings as $property)
											<a href="/properties/{{ $property->id }}"><tr>
												<td><a href="/properties/{{ $property->id }}">{{ $property->address }}</a></td>
												<td><a href="/properties/{{ $property->id }}">{{ $property->rooms_no }}</a></td>
												<td><a href="/properties/{{ $property->id }}">{{ $property->property_type }}</a></td>
												<td><a href="/properties/{{ $property->id }}">{{ $property->status }}</a></td>
												<td><a href="/properties/{{ $property->id }}">&#8364; {{ isset($property->price) ? $property->price : ((isset($property->rooms) && isset($property->rooms[0]->price)) ? $property->rooms[0]->price : '') }}</a></td>
											</tr></a>
										@endforeach
									@endif
								</tbody>
							</table>
						@endif
					</div>
				</div>
			</div>*/ ?>
		</div>
		<div class="white-area" style="margin-top: 0; background: none; border: 0;">
			<div class="inp-container d-inline-block">
				<button id="save" class="blue-button" style="margin: 0;">Save</button>
			</div>
		</div>
	</form>
</main>
<script>	
	var autocomplete, place, lat, long,place;
	function findAddress() {
		var place = autocomplete.getPlace();
		var latLong = new google.maps.LatLng(place.geometry.location.lat(),place.geometry.location.lng());
		if(place){
			document.getElementById('lat').value = place.geometry.location.lat();
			document.getElementById('long').value = place.geometry.location.lng();
			var event = new Event('change');

			// Dispatch it.
			document.getElementById('lat').dispatchEvent(event);
		}
	}
	// Bias the autocomplete object to the user's geographical location,
	// as supplied by the browser's 'navigator.geolocation' object.
	function initialize() {
		// Create the autocomplete object, restricting the search to geographical
		// location types.
		autocomplete = new google.maps.places.Autocomplete(
			/** @type {!HTMLInputElement} */
			(document.getElementById('autocomplete')),
			{types: ['address']}
		);
		// When the user selects an address from the dropdown, populate the address
		// fields in the form.
		autocomplete.addListener('place_changed', findAddress);
	}

</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBe42gt5WvTjzvWyc8CmJi9CPrgKcT5NEg&libraries=places&callback=initialize&language=en&region=EN" async defer></script>

@endsection
@section('script')
    <script src="{{ asset('js/profile.js') }}"></script>
@endsection
