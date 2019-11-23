@extends('layouts.app')

@section('content')
<main id="content" class="profile">
	<div class="page-header" id="headerContent">
	</div>
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
	<div class="container">
		<ul class="nav nav-tabs custom-navtabs col-lg-12 col-xs-12 col-md-12 col-sm-12" role="tablist">
			<li role="presentation" class="active col-lg-3 col-xs-3 col-sm-3 col-md-3">
				<a href="#edit" id="profile_edit_button" role="tab" data-toggle="tab" aria-controls="edit" aria-expanded="true">
						<div>
							<h4>Edit Profile</h4>
						</div>
				</a>
			</li>
			<li role="presentation" class="col-lg-3 col-xs-3 col-sm-3 col-md-3">
				<a href="#search" id="profile_search_button" role="tab" data-toggle="tab" aria-controls="search" aria-expanded="true">
						<div>
							<h4>Search Settings</h4>
						</div>
				</a>
			</li>
		</ul>
	</div>
	<form id="profile_edit" action="/profiles/{{$user->id}}" method="POST" enctype="multipart/form-data">
		<input type="hidden" name="_method" value="PUT">
		{!! csrf_field() !!}
		<div class="tab-content">
			<div role="tabpanel" class="tab-pane fade active in" id="edit" aria-labelledby="edit-tab">
				<div class="container">
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
				</div>
			</div>
			<div role="tabpanel" class="tab-pane fade in" id="search" aria-labelledby="search-tab">
				<div class="container">
					<h5>What are you looking for?</h5>
					<div class="profile_card col-md-8 col-lg-8 col-xs-12 col-sm-12">
						<div class="col_full">
							<div>
								<h5>Location</h5>
								<input id="autocomplete" class="form-control" type="text" name="location" @if(Auth::check() && isset(Auth::user()->preferences) && isset(Auth::user()->preferences->location)) value="{{Auth::user()->location}}" @else placeholder="No location preference" @endif />
							</div>
							<div>
								<h5>Available from</h5>
								<input class="form-control" type="text" name="avail_from" @if(Auth::check() && isset(Auth::user()->preferences) && isset(Auth::user()->preferences->avail_from)) value="{{Auth::user()->preferences->avail_from}}" @else placeholder="No availability preference" @endif />
							</div>
							<div>
								<h5>Budget</h5>
								<div class="sa-lable">
									<select class="form-control" id="budget" name="budget">
										<option name="budget" value="none" @if(!isset(Auth::user()->preferences) || !isset(Auth::user()->preferences->budget) || Auth::user()->preferences->budget=='' || Auth::user()->preferences->budget=='on') selected checked @endif>No preference</option>
										<option name="budget" value="0-149" @if(Auth::check() && isset(Auth::user()->preferences) && isset(Auth::user()->preferences->budget) && Auth::user()->preferences->budget=='0-149') selected checked @endif>0-149</option>
										<option name="budget" value="150-299" @if(Auth::check() && isset(Auth::user()->preferences) && isset(Auth::user()->preferences->budget) && Auth::user()->preferences->budget=='150-299') selected checked @endif>150-299</option>
										<option name="budget" value="300-499" @if(Auth::check() && isset(Auth::user()->preferences) && isset(Auth::user()->preferences->budget) && Auth::user()->preferences->budget=='300-499') selected checked @endif>300-499</option>
										<option name="budget" value="500-749" @if(Auth::check() && isset(Auth::user()->preferences) && isset(Auth::user()->preferences->budget) && Auth::user()->preferences->budget=='500-749') selected checked @endif>500-749</option>
										<option name="budget" value="750-999" @if(Auth::check() && isset(Auth::user()->preferences) && isset(Auth::user()->preferences->budget) && Auth::user()->preferences->budget=='750-999') selected checked @endif>750-999</option>
										<option name="budget" value="1000+" @if(Auth::check() && isset(Auth::user()->preferences) && isset(Auth::user()->preferences->budget) && Auth::user()->preferences->budget=='1000+') selected checked @endif>1000+</option>
									</select>
								</div>
							</div>
							<div>
								<h5>Minimum EPC</h5>
								<div class="sa-lable">
									<select class="form-control" id="epc" name="epc">
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
							</div>
							<div>
								<h5>Property type</h5>
								<div class="sa-lable">
									<select class="form-control" id="property_type" name="property_type">
										<option name="property_type" value="none" @if(!isset(Auth::user()->preferences) || !isset(Auth::user()->preferences->property_type) || Auth::user()->preferences->property_type=='' || Auth::user()->preferences->property_type=='on') selected checked @endif>No preference</option>
										<option name="property_type" value="house" @if(Auth::check() && isset(Auth::user()->preferences) && isset(Auth::user()->preferences->property_type) && Auth::user()->preferences->property_type=='house') selected checked @endif>House</option>
										<option name="property_type" value="apartment" @if(Auth::check() && isset(Auth::user()->preferences) && isset(Auth::user()->preferences->property_type) && Auth::user()->preferences->property_type=='apartment') selected checked @endif>Apartment</option>
										<option name="property_type" value="other" @if(Auth::check() && isset(Auth::user()->preferences) && isset(Auth::user()->preferences->property_type) && Auth::user()->preferences->property_type=='other') selected checked @endif>Other</option>
									</select>
								</div>
							</div>
							<div class="wanted_checkbox checkbox_wrapper">
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
							</div>
							<div style="margin-top:50px">
								<h5>Email Property Digest</h5>
								<div class="sa-lable">
									<select class="form-control" id="digest" name="digest">
										<option name="digest" value="none" @if(!isset(Auth::user()->preferences) || !isset(Auth::user()->preferences->receive_digest) || Auth::user()->preferences->receive_digest==0) selected checked @endif>No digest</option>
										<option name="digest" value="daily" @if(Auth::check() && isset(Auth::user()->preferences) && isset(Auth::user()->preferences->digest_freq) && Auth::user()->preferences->digest_freq=='daily') selected checked @endif>Daily Digest</option>
										<option name="digest" value="weekly" @if(Auth::check() && isset(Auth::user()->preferences) && isset(Auth::user()->preferences->digest_freq) && Auth::user()->preferences->digest_freq=='weekly') selected checked @endif>Weekly Digest</option>
										<option name="digest" value="monthly" @if(Auth::check() && isset(Auth::user()->preferences) && isset(Auth::user()->preferences->digest_freq) && Auth::user()->preferences->digest_freq=='monthly') selected checked @endif>Monthly Digest</option>
									</select>
								</div>
							</div>
							<div class="wanted_checkbox checkbox_wrapper">
									<div class="col-md-4 col-lg-4 col-xs-12 col-sm-12">
										<input type="checkbox" id="notification" name="notification" value="1" @if(Auth::check() && isset(Auth::user()->preferences) && isset(Auth::user()->preferences->has_bathroom) && Auth::user()->preferences->notify_by_mail==1) checked="checked" selected="selected" @endif>
										<label for="notification">Receive Email Notifications</label>
									</div>
							</div>
							<div class="amenities_title">
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
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="container">
			<button id="save" class="btn btn-default">Save</button>
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
