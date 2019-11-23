@extends('layouts.app')

@section('content')
<main id="content" class="share_room">
  @if(false)
	<div class="page-header" id="headerContent">
	@if(isset($property))
		<h2>Edit Room</h2>
	@else
		<h2>Share a Room</h2>
	@endif
	</div>
  @endif

	<section class="main" style="background-color: #F3F7FB">
	@if(isset($property))
		<form action="/properties/{{$property->id}}" method="POST" enctype="multipart/form-data">
		<input type="hidden" name="_method" value="PUT">
		<input type="hidden" name="property_id" value="{{$property->id}}" id="property_id">
		
	@else
		<form action="/properties" method="POST" enctype="multipart/form-data">
	@endif
	@if(isset($property))
		@foreach($rooms as $room)
			<input type="hidden" name="beds-{{($loop->index+1)}}" class="room-data" value="{{$room->beds}}">
			<input type="hidden" name="occupants-{{($loop->index+1)}}" class="room-data" value="{{$room->occupants}}">
			<input type="hidden" name="males-{{($loop->index+1)}}" class="room-data" value="{{$room->male}}">
			<input type="hidden" name="females-{{($loop->index+1)}}" class="room-data" value="{{$room->female}}">
			<input type="hidden" name="queers-{{($loop->index+1)}}" class="room-data" value="{{$room->lgbt}}">
			<input type="hidden" name="has_bathroom-{{($loop->index+1)}}" class="room-data" value="{{$room->has_bathroom}}">
			@if(!isset($property->price))
				<input type="hidden" name="price-{{($loop->index+1)}}" class="room-data" value="{{$room->price}}">
			@endif
			@if(!isset($property->bills))
				<input type="hidden" name="bills-{{($loop->index+1)}}" class="room-data" value="{{$room->bills}}">
			@endif
			@if(!isset($property->deposit))
				<input type="hidden" name="deposit-{{($loop->index+1)}}" class="room-data" value="{{$room->deposit}}">
			@endif
			@isset($room->avail_from)
				<input type="hidden" name="available_from-{{($loop->index+1)}}" class="room-data" value="{{date('d/m/Y', strtotime($room->avail_from))}}">
			@endisset
			@isset($room->avail_to)
				<input type="hidden" name="available_to-{{($loop->index+1)}}" class="room-data" value="{{date('d/m/Y', strtotime($room->avail_to))}}">
			@endisset
		@endforeach
	@endif
			{!! csrf_field() !!}
			<div class="container">
				<div class="row" >
					<div class="col-lg-10 col-xs-12 col-sm-10 col-md-10" style="padding: 0; border-radius: 10px; margin-bottom: 50px; overflow: hidden; border: 1px solid #D9DEE2">
						<!-- Nav tabs -->
						@include('paneNav')
						<!-- Tab panes -->
						<div class="tab-content"> 
							@include('pane1')
							@include('pane2')
							@include('pane3')
						</div>
					</div> 
				</div>
			</div>
		</form>
	</section>
</main>
<script>
	
	var placeSearch, autocomplete, marker, map, lat, long;
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
		if(place){
			document.getElementById('lat').value = place.geometry.location.lat();
			document.getElementById('long').value = place.geometry.location.lng();
			
			console.log(components);
			var town,address;
			if(components.route){
				address=components.route;
				if(components.street_number) address=address+', '+components.street_number;
				document.getElementById('address').value = address;
			}
 			if(components.locality){
				town=components.locality;
				if(components.political){
					town=town+', '+components.political;
				}
				document.getElementById('town').value = town;
 			} else{
				if(components.political){
					town=components.political;
				} 
				document.getElementById('town').value = town;
 			}
 			if(components.postal_code){
				document.getElementById('postal_code').value = components.postal_code;
 			}
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
	}

</script>
<!-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBe42gt5WvTjzvWyc8CmJi9CPrgKcT5NEg&libraries=places&callback=initialize&language=en&region=EN" async defer></script> -->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBjDwgzJELlK_-orfefesAhIMhHtjsuy7E&libraries=places&callback=initialize&language=en&region=EN" async defer></script>

@endsection
@section('script')
    @if(false)<script src="{{ asset('js/property.js') }}"></script>@endif
@endsection
