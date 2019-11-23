@extends('layouts.app')

@section('content')
<main id="content" class="invites">
	<div class="page-header" id="headerContent">
		<h2>Search Wanted Ads</h2>
	</div>
	<div id="search_bar" class="d-flex align-items-start">
		<input type="hidden" id="lat" name="lat">
		<input type="hidden" id="long" name="long">
		<input id="autocomplete" name="location" class="location" placeholder="Filter by Location" @if(Auth::check() && isset(Auth::user()->preferences->location)) value="{{Auth::user()->location}}" @endif />
		<div id="age_select" class="drop">
			<div class="fake_dropdown d-flex align-items-center">Filter by Age<i class="fa fa-caret-down"></i></div>
			<div class="fake_pane age d-flex-column align-items-start justify-content-start">
				<input class="default" name="age" type="radio" id="age_0" selected checked><label for="age_0">Show all</label>
				<input name="age" type="radio" id="age_1" value="18-20"><label for="age_1">18-20</label>
				<input name="age" type="radio" id="age_2" value="21-25"><label for="age_2">21-25</label>
				<input name="age" type="radio" id="age_3" value="26-30"><label for="age_3">26-30</label>
				<input name="age" type="radio" id="age_4" value="31-35"><label for="age_4">31-35</label>
				<input name="age" type="radio" id="age_5" value="36-40"><label for="age_5">36-40</label>
				<input name="age" type="radio" id="age_6" value="40+"><label for="age_6">40+</label>
			</div>
		</div>
		<div id="gender_select" class="drop">
			<div class="fake_dropdown d-flex align-items-center">Filter by Gender<i class="fa fa-caret-down"></i></div>
			<div class="fake_pane gender d-flex-column align-items-start justify-content-start">
				<input class="default" name="gender" type="radio" id="gender_0" selected checked><label for="gender_0">Show all</label>
				<input name="gender" type="radio" id="gender_1" value="m"><label for="gender_1">Male</label>
				<input name="gender" type="radio" id="gender_2" value="f"><label for="gender_2">Female</label>
				<input name="gender" type="radio" id="gender_3" value="q"><label for="gender_3">LGBTQ+</label>
			</div>
		</div>
		<div id="budget_select" class="drop">
			<div class="fake_dropdown d-flex align-items-center">Filter by Budget <i class="fa fa-caret-down"></i></div>
			<div class="fake_pane budget d-flex-column align-items-start justify-content-start">
				<input class="default" type="radio" name="budget" id="budget_0" selected checked><label for="budget_0">Show all</label>
				<input type="radio" name="budget" id="budget_2" value="1-149"><label for="budget_2">1-149</label>
				<input type="radio" name="budget" id="budget_3" value="150-299"><label for="budget_3">150-299</label>
				<input type="radio" name="budget" id="budget_4" value="300-499"><label for="budget_4">300-499</label>
				<input type="radio" name="budget" id="budget_5" value="500-749"><label for="budget_5">500-749</label>
				<input type="radio" name="budget" id="budget_6" value="750-999"><label for="budget_6">750-999</label>
				<input type="radio" name="budget" id="budget_7" value="1000+"><label for="budget_7">1000+</label>
			</div>
		</div>
		<div id="property_type_select" class="drop">
			<div class="fake_dropdown d-flex align-items-center">Property type <i class="fa fa-caret-down"></i></div>
			<div class="fake_pane property_type_d d-flex-column align-items-start justify-content-start">
				<input class="default" name="property_type" type="radio" id="property_type_0" @if(!Auth::check()||(Auth::check() && !isset(Auth::user()->preferences->property_type))) selected checked @endif><label for="property_type_0">Show all</label>
				<input name="property_type" type="radio" id="property_type_2" value="house" @if(Auth::check() && isset(Auth::user()->preferences->property_type) && Auth::user()->property_type==='house') selected checked @endif ><label for="property_type_2">Seeking a House</label>
				<input name="property_type" type="radio" id="property_type_3" value="apartment" @if(Auth::check() && isset(Auth::user()->preferences->property_type) && Auth::user()->property_type==='apartment') selected checked @endif ><label for="property_type_3">Seeking an Apartment</label>
				<input name="property_type" type="radio" id="property_type_4" value="other" @if(Auth::check() && isset(Auth::user()->preferences->property_type) && Auth::user()->property_type==='other') selected checked @endif ><label for="property_type_4">Seeking Other kind of properties</label>
			</div>
		</div>
		<div id="preferences_select" class="drop">
			<div class="fake_dropdown d-flex align-items-center">Preferences<i class="fa fa-caret-down"></i></div>
			<div class="fake_pane exception d-flex-column align-items-start justify-content-start">
				<input name="prefs" type="checkbox" id="prefs_1" value="has_bathroom"><label for="prefs_1">Seeking in-room bathroom</label>
				<input name="prefs" type="checkbox" id="prefs_2" value="p_empty"><label for="prefs_2">Seeking an empty property</label>
				<input name="prefs" type="checkbox" id="prefs_3" value="single"><label for="prefs_3">Seeking a single room</label>
			</div>
		</div>
		<button class="btn-more">More <i class="fa fa-caret-down"></i></button>
		<button class="reset_form">Reset Form</button>
	</div>
	<div id="search_bar" class="more d-flex align-items-start">
		<div id="epc_select" class="drop">
			<div class="fake_dropdown d-flex align-items-center">Minimum EPC<i class="fa fa-caret-down"></i></div>
			<div class="fake_pane epc d-flex-column align-items-start justify-content-start">
				<input class="default" name="epc" type="radio" id="epc_0" selected checked><label for="epc_0">Show all</label>
				<input name="epc" type="radio" id="epc_1" value="a"><label for="epc_1">a</label>
				<input name="epc" type="radio" id="epc_2" value="b"><label for="epc_2">b</label>
				<input name="epc" type="radio" id="epc_3" value="c"><label for="epc_3">c</label>
				<input name="epc" type="radio" id="epc_4" value="d"><label for="epc_4">d</label>
				<input name="epc" type="radio" id="epc_5" value="e"><label for="epc_5">e</label>
				<input name="epc" type="radio" id="epc_6" value="f"><label for="epc_6">f</label>
				<input name="epc" type="radio" id="epc_7" value="g"><label for="epc_7">g</label>
			</div>
		</div>
		<div id="amenities_select" class="drop">
			<div class="fake_dropdown d-flex align-items-center">Required Amenities <i class="fa fa-caret-down"></i></div>
			<div class="fake_pane amenities d-flex-column align-items-start justify-content-start">
				@foreach($amenities as $amenity)
					<input name="amenities" type="checkbox" id="{{$amenity->name}}" value="{{$amenity->name}}">
					<label for="{{$amenity->name}}"><i class="fa {{$amenity->icon}}"></i>{{$amenity->label}}</label>
				@endforeach
			</div>
		</div>
		<div id="acceptings_select" class="drop">
			<div class="fake_dropdown d-flex align-items-center">Required Accepting <i class="fa fa-caret-down"></i></div>
			<div class="fake_pane acceptings d-flex-column align-items-start justify-content-start">
				@foreach($acceptings as $accepting)
					<input name="acceptings" type="checkbox" id="{{$accepting->name}}" value="{{$accepting->name}}">
					<label for="{{$accepting->name}}"><i class="fa {{$accepting->icon}}"></i>{{$accepting->label}}</label>
				@endforeach
			</div>
		</div>
	</div>
	<div class="container">
		<h2>Ads matching your search</h2>
		<section id="wanted_listing_results" class="container wanted_listing d-flex wrapping">
		</section>
	</div>
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
    <script src="{{ asset('js/search.js') }}"></script>
@endsection