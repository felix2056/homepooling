@extends('layouts.app')

@section('content')
<main id="content" class="homepage">
	<?php /*<div class="page-header" id="headerContent" style="padding-top: 200px;padding-bottom: 200px;">
		<div class="parallax-bg" data-velocity=".5" style="box-shadow: inset 80vh 6px 185px -108px rgba(23,117,153,0.66);"></div>
			<div class="header-content container">
			<?php//guest?>
				<h1 class="title" style="text-align: left; line-height: 36px; margin-bottom: 30px">
          <span style="font-family: 'font-proxima-regular'; font-size: 36px">FIND YOUR</span><br>
          <span style="font-size: 48px; line-height: 0px">HOMEPOOL</span>
        </h1>

        <div class="text-left">
          <input type="hidden" id="lat" name="lat">
          <input type="hidden" id="long" name="long">
          <div style="padding-bottom: 10px">
            <input style="padding: 10px; border: none; border-radius: 5px; width: 240px" id="autocomplete" name="location" class="location" @if(Auth::check() && isset(Auth::user()->preferences) && isset(Auth::user()->preferences->location)) value="{{Auth::user()->location}}" @endif />

            <button href="#" class="btn-cta" style="background-color: #FF7B02; border: none; border-radius: 5px; color: #fff">FIND</button>
          </div>
          <a href="/properties/create" class="" style="color: #fff; text-decoration: underline;">POOL YOUR HOME</a>
        </div>
      </div>
    </div>*/ ?>
  			<?php /*else
          <!-- <h1 class="title">Hi, @if(Auth::check()) {{Auth::user()->name}} @else Pooler @endif</h1> -->
  				<h1 class="title">@if(Auth::check()) {{Auth::user()->name}} @else Pooler @endif</h1>
  				<h2 class="subtitle"></h2>
  				<div class="modal-wrapper">
            <!-- <a href="/properties/create" class="btn-cta signup-modal">Pool a Room</a><span> or </span><a href="/wanteds/create" class="btn-cta signup-modal btn-find">Find a Room</a> -->
  					<a href="/properties/create" class="btn-cta signup-modal">Pool a Room</a><span> or </span><a href="/properties" class="btn-cta signup-modal btn-find">Find a Room</a>
  				</div>
  			 endguest*/ ?>

    <div class="parallax">
      <div class="hero-section-content">
        <h1 class="text-shadow">Find your</h1>
        <h1 class="bold text-shadow em3">Homepool</h1>
        <form class="form mt20 loc-form" action="{{ route('properties.index') }}" method="get">
          
          <input type="hidden" id="lat" name="lat">
          <input type="hidden" id="long" name="long">
          <input type="text" class="box-shadow mlr5" style="width: 150px;" placeholder="Location" id="autocomplete" name="location" class="location" @if(Auth::check() && isset(Auth::user()->preferences) && isset(Auth::user()->preferences->location)) value="{{Auth::user()->location}}" @endif />
          <button class="blue-button box-shadow">Find Home</button>
        </form>
        <a href="/properties/create" class="white mt10 text-shadow">Pool your home</a>
      </div>
    </div>

    <?php /*<div class="" style="background-color: #fff">
      <div class="page-content container">
        <h1 class="align-items-center justify-content-center page-title">Why <span style="font-family: 'font-proxima-bold'"> Homepooling</span>?</h1>
        <div class="col-xs-12">
          <div class="row">
            <div class="col-md-4 text-center">
              <img src="/storage/img/home/locked.png"/>
              <h2 class="f-p-b" style="color: #666">We're safe</h2>
              <p class="f-p-l" style="color: #666">Homepooling members are verified via <span class="f-p-b">SMS</span> and <span class="f-p-b">Facebook</span>. We have a reporting feature, that our team monitors for any unsuitable behavior.</p>
            </div>
            <div class="col-md-4 text-center">
              <img src="/storage/img/home/coin.png"/>
              <h2 class="f-p-b" style="color: #666">We're free</h2>
              <p class="f-p-l" style="color: #666">Although you can pay to enhance the visibility of your post, homepooling allows you to post and find a room completely <span class="f-p-b">free</span>.</p>
            </div>
            <div class="col-md-4 text-center">
              <img src="/storage/img/home/checked.png"/>
              <h2 class="f-p-b" style="color: #666">Genuine Listings</h2>
              <p class="f-p-l" style="color: #666">Our team constantly moderates listings to ensure they are <span class="f-p-b">genuine</span>, <span class="f-p-b">up-to-date</span>, and <span class="f-p-b">still available</span>.</p>
            </div>
          </div>
        </div>
      </div>
    </div>*/?>
    <div id="whyhomepooling" class="flex-section whitebg p20">
      <h1 class="p20 title_h1">Why Homepooling?</h1>    
      <div class="flex-container whitebg">
        <div class="box p20">
          <img class="p10" src="/storage/img/home/locked.png">
          <h2>We're Safe</h2>
          <p class="p10">Homepooling members are verified via SMS and Facebook.
          We have a reporting feature, that our team monitors for any unsuitable behavior.</p>
        </div>
        <div class="box p20">
          <img class="p10" src="/storage/img/home/coin.png">
          <h2>We're Free</h2>
          <p class="p10">Although you can pay to enhance the visibility of your post, 
        homepooling allows you to post and find a room completely <strong>free.</strong></p>
        </div>
        <div class="box p20">
          <img class="p10" src="/storage/img/home/checked.png">
          <h2>Genuine Listings</h2>
          <p class="p10">Our team constantly moderates listings to ensure they are genuine, 
          up-to-date, and still available.</p>
        </div>
      </div>
    </div>
    <div class="" style="background-color: #F3F3F3">
      <div class="page-content container">
        <h1 class="align-items-center justify-content-center page-title">Latest <span class="f-p-b">Homepools</span></h1>
        <div class="listings-grid text-center">
        @include('block',['properties'=>$properties,'now'=>$now])
        </div>
        {{--
        @include('latest_wanted')
        <h1 class="page-title">Latest Users</h1>
        <div class="listings-grid users">
        @foreach($users as $user)
          <a href="/profiles/{{$user->id}}" class="user-link">
          @if($user->photo||$user->photo==='')
            <span class="user-image"><img src="{{$user->photo}}" alt="{{$user->name}}"></span>
          @else
            <span class="user-image"><img src="/storage/img/profile_placeholder.png"></span>
          @endif
            <span class="user-name">{{$user->name}}</span>
          </a>
        @endforeach
        --}}
        </div>
      </div>
    </div>
    <div style="background-color: #104E66; padding: 40px 10px">
      <div class="flex-container">
        <div class="title_footer">
          <div><img src="/storage/img/logo.png" style="height: 40px"/></div>
          <div><h2 class="f-p-b">Cities</h2></div>
        </div>
        <div class="footer-block" style="margin-bottom: 0;">
            <a class="f-p-r" style="color: #fff" href="#"><div>Aalst</div></a>
            <a class="f-p-r" style="color: #fff" href="#"><div>Aarschot</div></a>
            <a class="f-p-r" style="color: #fff" href="#"><div>Antwerp</div></a>
            <a class="f-p-r" style="color: #fff" href="#"><div>Arolon</div></a>
            <a class="f-p-r" style="color: #fff" href="#"><div>Ath</div></a>
          </div>
          <div class="footer-block" style="margin-bottom: 0;">
            <a class="f-p-r" style="color: #fff" href="#"><div>Bastogne</div></a>
            <a class="f-p-r" style="color: #fff" href="#"><div>Beaumont</div></a>
            <a class="f-p-r" style="color: #fff" href="#"><div>Bouillon</div></a>
            <a class="f-p-r" style="color: #fff" href="#"><div>Bruges</div></a>
            <a class="f-p-r" style="color: #fff" href="#"><div>Brussels</div></a>
          </div>
          <div class="footer-block" style="margin-bottom: 0;">
            <a class="f-p-r" style="color: #fff" href="#"><div>Chimay</div></a>
            <a class="f-p-r" style="color: #fff" href="#"><div>Couvin</div></a>
            <a class="f-p-r" style="color: #fff" href="#"><div>Damme</div></a>
            <a class="f-p-r" style="color: #fff" href="#"><div>Geel</div></a>
            <a class="f-p-r" style="color: #fff" href="#"><div>Ghent</div></a>
          </div>
          <div class="footer-block" style="margin-bottom: 0;">
            <a class="f-p-r" style="color: #fff" href="#"><div>Kortrijk</div></a>
            <a class="f-p-r" style="color: #fff" href="#"><div>Leuven</div></a>
            <a class="f-p-r" style="color: #fff" href="#"><div>Liege</div></a>
            <a class="f-p-r" style="color: #fff" href="#"><div>Ostend</div></a>
            <a class="f-p-r" style="color: #fff" href="#"><div>Rochefort</div></a>
          </div>
        </div>
      </div>
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
    
//    // Get the place details from the autocomplete object.
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
      {
        types: ['address'],
        componentRestrictions: {
          country: ['BEL', 'NL']
        }
      }
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
<!-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBe42gt5WvTjzvWyc8CmJi9CPrgKcT5NEg&callback=initialize&language=en&region=EN&libraries=places"></script> -->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBjDwgzJELlK_-orfefesAhIMhHtjsuy7E&callback=initialize&language=en&region=EN&libraries=places"></script>
@endsection
