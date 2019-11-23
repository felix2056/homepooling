@extends('layouts.app')

@section('content')
<main id="content" class="search_list">
	<div class="page-header" id="headerContent">
		<div id="search_map">
			<div id="gmaps"></div>
			<div id="search_bar" class="d-flex align-items-start">
				<input type="hidden" id="lat" name="lat">
				<input type="hidden" id="long" name="long">
				<input id="autocomplete" name="location" class="location" @if(Auth::check() && isset(Auth::user()->preferences) && isset(Auth::user()->preferences->location)) value="{{Auth::user()->location}}" @endif />
				<div id="budget_select" class="drop">
					<div class="fake_dropdown d-flex align-items-center @if(Auth::check() && isset(Auth::user()->preferences) && isset(Auth::user()->preferences->budget)) bold @endif">Budget <i class="fa fa-caret-down"></i></div>
					<div class="fake_pane budget d-flex-column align-items-start justify-content-start">
						<input class="default" type="radio" name="budget" id="budget_0" @if(!Auth::check()||!isset(Auth::user()->preferences)||(Auth::check() && !isset(Auth::user()->preferences->budget))) selected checked @endif><label for="budget_0">Show all</label>
						<input type="radio" name="budget" id="budget_2" value="0-149" @if(Auth::check() && isset(Auth::user()->preferences) && isset(Auth::user()->preferences->budget) && Auth::user()->preferences->budget==='0-149') selected checked @endif><label for="budget_2">0-149</label>
						<input type="radio" name="budget" id="budget_3" value="150-299" @if(Auth::check() && isset(Auth::user()->preferences) && isset(Auth::user()->preferences->budget) && Auth::user()->preferences->budget==='150-299') selected checked @endif><label for="budget_3">150-299</label>
						<input type="radio" name="budget" id="budget_4" value="300-499" @if(Auth::check() && isset(Auth::user()->preferences) && isset(Auth::user()->preferences->budget) && Auth::user()->preferences->budget==='300-499') selected checked @endif><label for="budget_4">300-499</label>
						<input type="radio" name="budget" id="budget_5" value="500-749" @if(Auth::check() && isset(Auth::user()->preferences) && isset(Auth::user()->preferences->budget) && Auth::user()->preferences->budget==='500-749') selected checked @endif><label for="budget_5">500-749</label>
						<input type="radio" name="budget" id="budget_6" value="750-999" @if(Auth::check() && isset(Auth::user()->preferences) && isset(Auth::user()->preferences->budget) && Auth::user()->preferences->budget==='750-999') selected checked @endif><label for="budget_6">750-999</label>
						<input type="radio" name="budget" id="budget_7" value="1000+" @if(Auth::check() && isset(Auth::user()->preferences) && isset(Auth::user()->preferences->budget) && Auth::user()->preferences->budget==='1000+') selected checked @endif><label for="budget_7">1000+</label>
					</div>
				</div>
				<div id="bathroom_select" class="drop">
					<div class="fake_dropdown d-flex align-items-center @if(Auth::check() && isset(Auth::user()->preferences) && isset(Auth::user()->preferences->has_bathroom)) bold @endif">In-room bathroom <i class="fa fa-caret-down"></i></div>
					<div class="fake_pane bathroom d-flex-column align-items-start justify-content-start">
						<input class="default" name="has_bathroom" type="radio" id="bathroom_0" @if(!Auth::check()||!isset(Auth::user()->preferences)||(Auth::check() && !isset(Auth::user()->preferences->has_bathroom))) selected checked @endif><label for="bathroom_0">Show all</label>
						<input name="has_bathroom" type="radio" id="bathroom_2" value="1" @if(Auth::check() && isset(Auth::user()->preferences) && isset(Auth::user()->preferences->has_bathroom) && Auth::user()->preferences->has_bathroom===1) selected checked @endif ><label for="bathroom_2">Yes</label>
						<input name="has_bathroom" type="radio" id="bathroom_3" value="0" @if(Auth::check() && isset(Auth::user()->preferences) && isset(Auth::user()->preferences->has_bathroom) && Auth::user()->preferences->has_bathroom===0) selected checked @endif ><label for="bathroom_3">No</label>
					</div>
				</div>
				<div id="amenities_select" class="drop">
					<div class="fake_dropdown d-flex align-items-center @if(Auth::check() && count($amenities_ids)) bold @endif">Amenities <i class="fa fa-caret-down"></i></div>
					<div class="fake_pane amenities d-flex-column align-items-start justify-content-start">
						@foreach($amenities as $amenity)
							<input name="amenities[]" type="checkbox" id="{{$amenity->name}}" value="{{$amenity->name}}" @if(Auth::check() && isset(Auth::user()->preferences) && isset(Auth::user()->preferences->amenities) && isset($amenities_ids) && in_array($amenity->id,$amenities_ids)) selected checked @endif>
							<label for="{{$amenity->name}}"><i class="fa {{$amenity->icon}}"></i>{{$amenity->label}}</label>
						@endforeach
					</div>
				</div>
				<div id="acceptings_select" class="drop">
					<div class="fake_dropdown d-flex align-items-center">Accepting <i class="fa fa-caret-down"></i></div>
					<div class="fake_pane acceptings d-flex-column align-items-start justify-content-start">
						@foreach($acceptings as $accepting)
							<input name="acceptings[]" type="checkbox" id="{{$accepting->name}}" value="{{$accepting->name}}">
							<label for="{{$accepting->name}}"><i class="fa {{$accepting->icon}}"></i>{{$accepting->label}}</label>
						@endforeach
					</div>
				</div>
				<div id="property_type_select" class="drop">
					<div class="fake_dropdown d-flex align-items-center @if(Auth::check() && isset(Auth::user()->preferences) && isset(Auth::user()->preferences->property_type)) bold @endif">Property type <i class="fa fa-caret-down"></i></div>
					<div class="fake_pane property_type_d d-flex-column align-items-start justify-content-start">
						<input class="default" name="property_type" type="radio" id="property_type_0" @if(!Auth::check()||!isset(Auth::user()->preferences)||(Auth::check() && !isset(Auth::user()->preferences->property_type) && Auth::user()->preferences->property_type!=='house' && Auth::user()->preferences->property_type!=='apartment' && Auth::user()->preferences->property_type!=='other')) selected checked @endif><label for="property_type_0">Show all</label>
						<input name="property_type" type="radio" id="property_type_2" value="house" @if(Auth::check() && isset(Auth::user()->preferences) && isset(Auth::user()->preferences->property_type) && Auth::user()->preferences->property_type==='house') selected checked @endif ><label for="property_type_2">House</label>
						<input name="property_type" type="radio" id="property_type_3" value="apartment" @if(Auth::check() && isset(Auth::user()->preferences) && isset(Auth::user()->preferences->property_type) && Auth::user()->preferences->property_type==='apartment') selected checked @endif ><label for="property_type_3">Apartment</label>
						<input name="property_type" type="radio" id="property_type_4" value="other" @if(Auth::check() && isset(Auth::user()->preferences) && isset(Auth::user()->preferences->property_type) && Auth::user()->preferences->property_type==='other') selected checked @endif ><label for="property_type_4">Other</label>
					</div>
				</div>
				<button class="btn-more">More <i class="fa fa-caret-down"></i></button>
				<button class="reset_form">Reset Form</button>
			</div>
			<div id="search_bar" class="more d-flex align-items-start">
<!-- 				<input id="available_from" class="datepicker available_from" name="available_from" data-toggle="datepicker" type="text" placeholder="Available from" /> -->
				<div id="empty_select" class="drop">
					<div class="fake_dropdown d-flex align-items-center @if(Auth::check() && isset(Auth::user()->preferences) && isset(Auth::user()->preferences->p_empty)) bold @endif">Prefer empty? <i class="fa fa-caret-down"></i></div>
					<div class="fake_pane empty d-flex-column align-items-start justify-content-start">
						<input class="default" name="pref_empty" type="radio" id="empty_0" @if(!Auth::check()||!isset(Auth::user()->preferences)||(Auth::check() && !isset(Auth::user()->preferences->p_empty))) selected checked @endif><label for="empty_0">Show all</label>
						<input name="pref_empty" type="radio" id="empty_2" value="1" @if(Auth::check() && isset(Auth::user()->preferences) && isset(Auth::user()->preferences->p_empty) && Auth::user()->p_empty==1) selected checked @endif><label for="empty_2">Yes</label>
						<input name="pref_empty" type="radio" id="empty_3" value="0" @if(Auth::check() && isset(Auth::user()->preferences) && isset(Auth::user()->preferences->p_empty) && Auth::user()->p_empty==0) selected checked @endif><label for="empty_3">No</label>
					</div>
				</div>
				<div id="single_select" class="drop">
					<div class="fake_dropdown d-flex align-items-center @if(Auth::check() && isset(Auth::user()->preferences) && isset(Auth::user()->preferences->single)) bold @endif">Prefer single room? <i class="fa fa-caret-down"></i></div>
					<div class="fake_pane empty d-flex-column align-items-start justify-content-start">
						<input class="default" name="pref_single" type="radio" id="single_0" @if(!Auth::check()||!isset(Auth::user()->preferences)||(Auth::check() && !isset(Auth::user()->preferences->single))) selected checked @endif><label for="single_0">Show all</label>
						<input name="pref_empty" type="radio" id="single_2" value="1" @if(Auth::check() && isset(Auth::user()->preferences) && isset(Auth::user()->preferences->single) && Auth::user()->single==1) selected checked @endif><label for="single_2">Yes</label>
						<input name="pref_empty" type="radio" id="single_3" value="0" @if(Auth::check() && isset(Auth::user()->preferences) && isset(Auth::user()->preferences->single) && Auth::user()->single==0) selected checked @endif><label for="single_3">No</label>
					</div>
				</div>
				<div id="epc_select" class="drop">
					<div class="fake_dropdown d-flex align-items-center @if(Auth::check() && isset(Auth::user()->preferences) && isset(Auth::user()->preferences->epc)) bold @endif">Minimum EPC<i class="fa fa-caret-down"></i></div>
					<div class="fake_pane epc d-flex-column align-items-start justify-content-start">
						<input class="default" name="epc" type="radio" id="epc_0" @if(!Auth::check()||!isset(Auth::user()->preferences)||(Auth::check() && !isset(Auth::user()->preferences->epc))) selected checked @endif><label for="epc_0">Show all</label>
						<input name="epc" type="radio" id="epc_1" value="a" @if(Auth::check() && isset(Auth::user()->preferences) && isset(Auth::user()->preferences->epc) && Auth::user()->empty=='a') selected checked @endif><label for="epc_1">a</label>
						<input name="epc" type="radio" id="epc_2" value="b" @if(Auth::check() && isset(Auth::user()->preferences) && isset(Auth::user()->preferences->epc) && Auth::user()->empty=='b') selected checked @endif><label for="epc_2">b</label>
						<input name="epc" type="radio" id="epc_3" value="c" @if(Auth::check() && isset(Auth::user()->preferences) && isset(Auth::user()->preferences->epc) && Auth::user()->empty=='c') selected checked @endif><label for="epc_3">c</label>
						<input name="epc" type="radio" id="epc_4" value="d" @if(Auth::check() && isset(Auth::user()->preferences) && isset(Auth::user()->preferences->epc) && Auth::user()->empty=='d') selected checked @endif><label for="epc_4">d</label>
						<input name="epc" type="radio" id="epc_5" value="e" @if(Auth::check() && isset(Auth::user()->preferences) && isset(Auth::user()->preferences->epc) && Auth::user()->empty=='e') selected checked @endif><label for="epc_5">e</label>
						<input name="epc" type="radio" id="epc_6" value="f" @if(Auth::check() && isset(Auth::user()->preferences) && isset(Auth::user()->preferences->epc) && Auth::user()->empty=='f') selected checked @endif><label for="epc_6">f</label>
						<input name="epc" type="radio" id="epc_7" value="g" @if(Auth::check() && isset(Auth::user()->preferences) && isset(Auth::user()->preferences->epc) && Auth::user()->empty=='g') selected checked @endif><label for="epc_7">g</label>
					</div>
				</div>
				<div id="exception_select" class="drop">
					<div class="fake_dropdown d-flex align-items-center">Exceptions <i class="fa fa-caret-down"></i></div>
					<div class="fake_pane exception d-flex-column align-items-start justify-content-start">
						<input name="exceptions[]" type="checkbox" id="exception_2" value="male"><label for="exception_2">Avoid male mates</label>
						<input name="exceptions[]" type="checkbox" id="exception_3" value="female"><label for="exception_3">Avoid female mates</label>
						<input name="exceptions[]" type="checkbox" id="exception_4" value="queer"><label for="exception_4">Avoid LGBTQ+ mates</label>
						<input name="exceptions[]" type="checkbox" id="exception_5" value="livein"><label for="exception_5">Avoid live-in landlords</label>
						<input name="exceptions[]" type="checkbox" id="exception_6" value="agent"><label for="exception_6">Avoid agents</label>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="container">
		<h2>Rooms matching your search</h2>
		<div class="listings-grid"></div>
	</div>
</main>
<script>
	
	var placeSearch, autocomplete, marker, map, lat, long,place;
	var markers=[];
	
	// Remove markers without removing them from the array.
	function hideMarkers() {
		setMapOnAll(null);
	}

	// Shows any markers currently in the array.
	function showMarkers() {
		setMapOnAll(map);
	}
	
	// Empties the markers array
	function deleteMarkers() {
		hideMarkers();
		markers = [];
	}

	function setMapOnAll(map) {
		for (var i = 0; i < markers.length; i++) {
			markers[i].setMap(map);
		}
	}

	
	var componentForm = {
		street_number: 'short_name',
		route: 'short_name',
		locality: 'long_name',
		administrative_area_level_1: 'short_name',
		country: 'long_name',
		postal_code: 'short_name'
	};
	function findAddress() {
		
// 		// Get the place details from the autocomplete object.
		if(autocomplete){
			var place = autocomplete.getPlace();
			var lat=place.geometry.location.lat();
			var lng=place.geometry.location.lng();
		}

		document.getElementById('lat').value = lat;
		document.getElementById('long').value = lng;
		var event = new Event('change');

		// Dispatch it.
		document.getElementById('lat').dispatchEvent(event);
		
		var latLong = new google.maps.LatLng(lat,lng);
		map.setCenter(latLong);
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
		var latLong = new google.maps.LatLng('50.848080', '4.351337');
		var mapProp = {
			center:latLong,
			zoom:13,
			mapTypeId:google.maps.MapTypeId.ROADMAP
		};

		map=new google.maps.Map(document.getElementById("gmaps"), mapProp);
	
		marker = new google.maps.Marker({
			position: latLong,
			map: map,
			icon: '/storage/img/map-marker.png'
		});
		markers.push(marker);
	}

</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBe42gt5WvTjzvWyc8CmJi9CPrgKcT5NEg&callback=initialize&language=en&region=EN&libraries=places"></script>@endsection
 
