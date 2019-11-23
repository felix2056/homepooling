@extends('layouts.admin')

@section('content')
<main id="content" class="share_room dashboard">
	<div class="container">
		<h1>Edit Wanted Ad</h1>
		@isset($wanted)
			<form id="wanted_form" action="/wanteds/{{$wanted->id}}" method="POST">
			<input type="hidden" id="method" name="_method" value="PUT">
		@else
			<form id="wanted_form" action="/wanteds" method="post">
		@endif
			<input type="hidden" id="lat" name="lat" @isset($wanted) value="{{$wanted->lat}}" @endisset>
			<input type="hidden" id="long" name="long" @isset($wanted) value="{{$wanted->long}}" @endisset>
			{!! csrf_field() !!}
			<div class="wanted_location col-md-12 col-lg-12 col-xs-12 col-sm-12">
				<div class="col-md-6 col-lg-6 col-xs-12 col-sm-12">
					<h4 style="color: #30B0E8; padding: 0;">Location</h4>
					<input id="autocomplete" name="location" type="text" class="form-control" placeholder="Enter the location  of the area you'd like to live in"  @isset($wanted) value="{{$wanted->location}}" @endisset>
				</div>
				<div class="col-md-3 col-lg-3 col-xs-12 col-sm-12">
					<h4 style="color: #30B0E8; padding: 0;">Range</h4>
					<div class="sa-lable">
						<select id="range_rad" class="form-control" name="range">
							<option name="range" value="0.5" @if(!isset($wanted)||(isset($wanted)&&$wanted->distance==0.5)) selected checked @endif>500m</option>
							<option name="range" value="1" @if(isset($wanted)&&$wanted->distance==1) selected checked @endif>1Km</option>
							<option name="range" value="2" @if(isset($wanted)&&$wanted->distance==2) selected checked @endif>2Km</option>
							<option name="range" value="5" @if(isset($wanted)&&$wanted->distance==5) selected checked @endif>5Km</option>
							<option name="range" value="10" @if(isset($wanted)&&$wanted->distance==10) selected checked @endif>10Km</option>
							<option name="range" value="15" @if(isset($wanted)&&$wanted->distance==15) selected checked @endif>15Km</option>
							<option name="range" value="30" @if(isset($wanted)&&$wanted->distance==30) selected checked @endif>30Km</option>
							<option name="range" value="50" @if(isset($wanted)&&$wanted->distance==50) selected checked @endif>50Km</option>
							<option name="range" value="100" @if(isset($wanted)&&$wanted->distance==100) selected checked @endif>100Km</option>
						</select>
					</div>
				</div>
				<div class="col-md-3 col-lg-3 col-xs-12 col-sm-12">
					<h4 style="color: #30B0E8; padding: 0;">Price Range</h4>
					<div class="sa-lable">
						<select id="price_range" class="form-control" name="price">
							<option name="price" value="0" @if(!isset($wanted)||(isset($wanted)&&$wanted->price_range==0)) selected checked @endif>No preference</option>
							<option name="price" value="1-149" @if((isset($wanted)&&$wanted->price_range=='1-149')) selected checked @endif>1-149 €</option>
							<option name="price" value="150-299" @if((isset($wanted)&&$wanted->price_range=='150-299')) selected checked @endif>150-299 €</option>
							<option name="price" value="300-499" @if((isset($wanted)&&$wanted->price_range=='300-499')) selected checked @endif>300-499 €</option>
							<option name="price" value="500-749" @if((isset($wanted)&&$wanted->price_range=='500-749')) selected checked @endif>500-749 €</option>
							<option name="price" value="750-999" @if((isset($wanted)&&$wanted->price_range=='750-999')) selected checked @endif>750-999 €</option>
							<option name="price" value="1000+" @if((isset($wanted)&&$wanted->price_range=='1000+')) selected checked @endif>1000+</option>
						</select>
					</div>
				</div>
			</div>
			<div class="wanted_map col-md-12 col-lg-12 col-xs-12 col-sm-12">
				<div id="gmaps" class="col-lg-12 col-md-12 col-xs-12 col-sm-12" style="width:100%;height:256px;margin:30px 0;"></div>
			</div>
			<div class="wanted_type col-md-12 col-lg-12 col-xs-12 col-sm-12 tab-pane">
				<h4 style="color: #30B0E8; padding: 0;text-align:center;">Property type</h4>
				<div class="property_type">
					<div>
						<input name="type" type="radio" id="type1" value="house" @if(isset($wanted) && $wanted->type==='house') checked="checked" @endif ><label class="btn btn-default btn-lg" for="type1">House</label>
					</div>
					<div>
						<input name="type" type="radio" id="type2" value="apartment" @if(isset($wanted) && $wanted->type==='apartment') checked="checked" @endif><label class="btn btn-default btn-lg" for="type2">Apartment</label>
					</div>
					<div>
						<input name="type" type="radio" id="type3" value="other"
						@if(isset($wanted) && $wanted->type==='other') checked="checked" @endif ><label class="btn btn-default btn-lg" for="type3">Other</label>
					</div>
				</div>
			</div>
			<div class="wanted_people col-md-12 col-lg-12 col-xs-12 col-sm-12">
				<div class="col-md-6 col-lg-6 col-xs-12 col-sm-12">
					<h4 style="color: #30B0E8; padding: 0;">For how many people?</h4>
					<input name="people" type="text" class="form-control" placeholder="Insert # of people" @isset($wanted) value="{{$wanted->people}}" @endisset>
				</div>
				<div class="col-md-6 col-lg-6 col-xs-12 col-sm-12">
					<h4 style="color: #30B0E8; padding: 0;">In how many rooms?</h4>
					<input name="rooms" type="text" class="form-control" placeholder="Insert # of rooms" @isset($wanted) value="{{$wanted->rooms}}" @endisset>
				</div>
			</div>
			<div class="wanted_avails col-md-12 col-lg-12 col-xs-12 col-sm-12">
				<div class="col-md-6 col-lg-6 col-xs-12 col-sm-12">
					<h4 style="color: #30B0E8; padding: 0;">Available from</h4>
					<input name="avail_from" type="text" class="form-control datepicker" data-toggle="datepicker" placeholder="Insert date" @isset($wanted) value="{{\Carbon\Carbon::parse($wanted->avail_from)->format('d/m/Y')}}" @endisset>
				</div>
			</div>
			<div class="wanted_checkbox checkbox_wrapper col-md-12 col-lg-12 col-xs-12 col-sm-12">
				<div class="col-lg-9 col-sm-9 col-md-12 col-xs-12">
					<h4 class="col-lg-12 col-sm-12 col-md-12 col-xs-12" style="color: #30B0E8">Preferences</h4>
					<div class="col-md-4 col-lg-4 col-xs-12 col-sm-12">
						<input type="checkbox" id="has_bathroom" name="has_bathroom" value="1" @if(isset($wanted) && $wanted->has_bathroom==1) checked="checked" selected="selected" @endif>
						<label for="has_bathroom">In room bathroom</label>
					</div>
					<div class="col-md-4 col-lg-4 col-xs-12 col-sm-12">
						<input type="checkbox" id="p_empty" name="p_empty" value="1" @if(isset($wanted) && $wanted->p_empty==1) checked="checked" selected="selected" @endif>
						<label for="p_empty">Seeking empty property</label>
					</div>
					<div class="col-md-4 col-lg-4 col-xs-12 col-sm-12">
						<input type="checkbox" id="single" name="single" value="1" @if(isset($wanted) && $wanted->single==1) checked="checked" selected="selected" @endif>
						<label for="single">Seeking single room</label>
					</div>
				</div>
				<div class="col-lg-3 col-sm-3 col-md-12 col-xs-12">
					<div class="sa-lable">
						<h4 class="col-lg-12 col-sm-12 col-md-12 col-xs-12" style="color: #30B0E8">Minimum EPC</h4>
						<select class="form-control" name="epc" id="epc" >
							<option name="epc" value="a" @isset($wanted->epc) @if($wanted->epc==='a') checked selected @endif @endisset>A</option>
							<option name="epc" value="b" @isset($wanted->epc) @if($wanted->epc==='b') checked selected @endif @endisset>B</option>
							<option name="epc" value="c" @isset($wanted->epc) @if($wanted->epc==='c') checked selected @endif @endisset>C</option>
							<option name="epc" value="d" @isset($wanted->epc) @if($wanted->epc==='d') checked selected @endif @endisset>D</option>
							<option name="epc" value="e" @isset($wanted->epc) @if($wanted->epc==='e') checked selected @endif @endisset>E</option>
							<option name="epc" value="f" @isset($wanted->epc) @if($wanted->epc==='f') checked selected @endif @endisset>F</option>
							<option name="epc" value="g" @isset($wanted->epc) @if($wanted->epc==='g') checked selected @endif @else checked selected @endisset>G</option>
						</select>
					</div>
				</div>
			</div>
			<div class="wanted_amenities col-md-12 col-lg-12 col-xs-12 col-sm-12">
				<h4 class="col-lg-12 col-sm-12 col-md-12 col-xs-12"  style="color: #30B0E8">Required Amenities</h4>
				<div class="col-lg-12 col-xs-12 col-sm-12 col-md-12">
					<div class="row">
						<div class="amenities">
							@foreach($amenities as $am)
								<div class="amenity">
									<input id="{{'am'.$am->id}}" name="amenities[]" type="checkbox" class="amenity_check {{$am->name}}" value="{{$am->id}}" @if( isset($wanted) && in_array($am->id, $p_amenities) ) checked="checked" @endif>
									<label for="{{'am'.$am->id}}"><span><i class="fa {{$am->icon}}"></i></span>{{$am->label}}</label>
								</div>
							@endforeach
						</div>
					</div>
				</div>
			</div>
			<div class="wanted_acceptings col-md-12 col-lg-12 col-xs-12 col-sm-12">
				<h4 class="col-lg-12 col-sm-12 col-md-12 col-xs-12"  style="color: #30B0E8">Required Accepting</h4>
				<div class="col-lg-12 col-xs-12 col-sm-12 col-md-12">
					<div class="row">
						<div class="acceptings">
							@foreach($acceptings as $ac)
								<div class="accepting">
									<input id="{{'ac'.$ac->id}}" name="acceptings[]" type="checkbox" class="acceptings_check {{$ac->name}}" value="{{$ac->id}}" @if( isset($wanted) && in_array($ac->id, $p_acceptings) ) checked="checked" @endif>
									<label for="{{'ac'.$ac->id}}"><span><i class="fa {{$ac->icon}}"></i></span>{{$ac->label}}</label>
								</div>
							@endforeach
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-12 col-xs-12 col-sm-12 col-md-12 d-flex align-items-center justify-content-center">
				@isset($wanted)
					<button class="btn btn-lg btn-primary btn-block btn_submit" style="float:left;" href="#" id="update_wanted">Save Ad</button>
				@else
					<button class="btn btn-lg btn-primary btn-block btn_submit" style="float:left;" href="#" id="to_wanted_package">Choose Package</button>
				@endif
			</div>
		</form>
	</div>
</main>
<script>
	
	var placeSearch, autocomplete, marker, map, lat, long, cityCircle,place;
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
		var place = autocomplete.getPlace();
		var address_components = place.address_components;
		var components={}; 
		jQuery.each(address_components, function(k,v1) {
			jQuery.each(v1.types, function(k2, v2){
				components[v2]=v1.long_name
			});
		});
		var latLong = new google.maps.LatLng(place.geometry.location.lat(),place.geometry.location.lng());
		map.setCenter(latLong);
		marker.setPosition(latLong);
		cityCircle.setCenter(latLong);
		cityCircle.setRadius(+document.getElementById('range_rad').value*1000);
		if(place){
			document.getElementById('lat').value = place.geometry.location.lat();
			document.getElementById('long').value = place.geometry.location.lng();
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
		var latLong = new google.maps.LatLng('50.848080', '4.351337');
		var mapProp = {
			center:latLong,
			zoom:12,
			mapTypeId:google.maps.MapTypeId.ROADMAP
		};

		map=new google.maps.Map(document.getElementById("gmaps"), mapProp);
	
		marker = new google.maps.Marker({
			position: latLong,
			map: map,
			icon: '/storage/img/map-marker.png'
		});

		cityCircle = new google.maps.Circle({
			strokeColor: '#009ee2',
			strokeOpacity: 0.8,
			strokeWeight: 3,
			fillColor: '#009ee2',
			fillOpacity: 0.35,
			map: map,
			center: latLong,
			radius: +document.getElementById('range_rad').value*1000
		});
// 		cityCircle.bindTo('center', marker, 'position');
	}

</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBe42gt5WvTjzvWyc8CmJi9CPrgKcT5NEg&libraries=places&callback=initialize&language=en&region=EN" async defer></script>
@endsection 
 
@section('script')
    <script src="{{ asset('js/wanted.js') }}"></script>
@endsection