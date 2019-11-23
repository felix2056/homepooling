@extends('layouts.app')

@section('content')
<main id="content" class="profile wanted_view">
	<div class="page-header" id="headerContent">
	</div>
	<div class="container">
		<section class="col_sx col-md-2 col-lg-2 ">
			<div class="profile_image">
				@if(isset($user->photo) && $user->photo!=='')
					<img src="{{$user->photo}}" alt="{{$user->name}}">
				@else
					<img src="/storage/img/profile_placeholder.png">
				@endif
			</div>
			<div class="profile_buttons">
				@if(isset($user->verified) && $user->verified==1)
					<button class="btn verified">Verified <i class="fa fa-check"></i></button>
				@else
					<button class="btn disabled">Not verified <i class="fa fa-close"></i></button>
				@endif
				@if(Auth::check() && $user->id==Auth::user()->id)
					<a href="/wanteds/{{$wanted->id}}/edit" class="btn btn-default">Edit ad</a>
					<a class="btn btn-default" href="/wanteds/{{$wanted->id}}/packages" id="to_wanted_package_edit">Upgrade Package</a>
					<form name="delete" action="/wanteds/{{$wanted->id}}" method="POST">
						<input type="hidden" name="_method" value="DELETE">
						{!! csrf_field() !!}
						<button class="btn btn-default btn-delete">Delete Ad <i class="fa fa-close"></i></button>
					</form>
				@else
					<a href="/messages/send/to/{{$user->id}}" class="@if(!$can_receive) disabled @endif btn btn-default">@if($can_receive) Contact @else Can't contact @endif</a>
				@endif
			</div>
		</section>
		<section class="col_dx col-md-10 col-lg-10">
			<div class="profile_about">
				<h2>Hi, I'm {{$user->name}}!</h2>
				<p>I'm looking for @if($wanted->rooms==1) a room @else {{$wanted->rooms}} rooms @endif near <strong>{{$wanted->location}}</strong> (max. {{$wanted->distance}} km distance) @if($wanted->people>1) for {{$wanted->people}} people @endif</p>
			</div>
			<div class="wanted_map">
				<div id="gmaps" style="width:70%;height:256px;margin:30px 0;"></div>
			</div>
			<div class="profile_about">
				<h3>About me</h3>
				<p>I'm a {{$user->age}} years old {{$user->profession}} from {{$user->origin}}.<br>
				{{$user->description}}</p>
				<h3>What I'm looking for</h3>
				@if(isset($wanted->type))
					<p>Property type: {{$wanted->type}}</p>
				@endif
				@if(isset($wanted->price_range)&&$wanted->price_range!=0)
					<p>Price range: € {{$wanted->price_range}} </p>
				@endif
				@if(isset($wanted->avail_from))
					<p>Available from: {{$wanted->avail_from}} </p>
				@endif
				@if(isset($wanted->epc))
					<p>Minimum EPC: <span style="text-transform:uppercase;">{{$wanted->epc}}<span> </p>
				@endif
				@if((isset($wanted->has_bathroom)&&$wanted->has_bathroom==1)||(isset($wanted->p_empty)&&$wanted->p_empty==1)||(isset($wanted->single)&&$wanted->single==1))
					<p>The room should:</p>
					<ul>
						@if(isset($wanted->has_bathroom)&&$wanted->has_bathroom==1)
							<li>Have a bathroom</li>
						@endif
						@if(isset($wanted->single)&&$wanted->single==1)
							<li>Be single (w/ one bed only)</li>
						@endif
						@if(isset($wanted->p_empty)&&$wanted->p_empty==1)
							<li>Belong to an empty property</li>
						@endif
					</ul>
				@endif
				@if(isset($amenities)&&count($amenities))
					<p style="margin-top:30px;"><strong>Required Amenities</strong></p>
					<div class="amenity wanted_amenities d-flex wrapping">
						@foreach($amenities as $amenity)
							<div class="{{$amenity->name}}"><span><i class="fa {{$amenity->icon}}"></i></span>{{$amenity->label}}</div>
						@endforeach
					</div>
				@endif
				@if(isset($acceptings)&&count($acceptings))
					<p style="margin-top:30px;"><strong>Required Accepting</strong></p>
					<div class="amenity wanted_amenities d-flex wrapping">
						@foreach($acceptings as $accepting)
							<div class="{{$accepting->name}}"><span><i class="fa {{$accepting->icon}}"></i></span>{{$accepting->label}}</div>
						@endforeach
					</div>
				@endif
			</div>
		</section>
		@include('latest_wanted')
	</div>
	
</main>
<script src="http://maps.googleapis.com/maps/api/js?key=AIzaSyBe42gt5WvTjzvWyc8CmJi9CPrgKcT5NEg">
</script>
<script>
	function initialize() {
		var latLong = new google.maps.LatLng({{$wanted->lat}},{{$wanted->long}});
		var mapProp = {
			center:latLong,
			zoom:14,
			mapTypeId:google.maps.MapTypeId.ROADMAP
		};

		var map=new google.maps.Map(document.getElementById("gmaps"), mapProp);
	
		var cityCircle = new google.maps.Circle({
			strokeColor: '#009ee2',
			strokeOpacity: 0.8,
			strokeWeight: 3,
			fillColor: '#009ee2',
			fillOpacity: 0.35,
			map: map,
			center: latLong,
			radius: +{{$wanted->distance}}*1000
		});

		var marker = new google.maps.Marker({
			position: latLong,
			map: map,
			icon: '/storage/img/map-marker.png'
		});
	}
	google.maps.event.addDomListener(window, 'load', initialize);
</script>

@endsection
 
