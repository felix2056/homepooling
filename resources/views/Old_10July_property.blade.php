@extends('layouts.app')

@section('content')
<main id="content" class="property">
	<div class="page-header" id="headerContent">
		@if($now->diffInDays($property->created_at)<7&&$property->early_access==1) <span class="listing-label label-early">Early</span> @elseif($now->diffInDays($property->created_at)<7)<span class="listing-label label-new">New</span> @endif
		<ul class="bxslider">
			@foreach($images as $image)
				@if(file_exists(public_path(str_replace(basename($image->url),'slider/'.basename($image->url,'.jpeg').'_up.jpg',$image->url))))
					<li><img src="{{str_replace(basename($image->url),'slider/'.basename($image->url,'.jpeg').'_up.jpg',$image->url)}}"></li>
				@else
					<li><img src="{{$image->url}}"></li>
				@endif
			@endforeach
		</ul>
	</div>
	<div class="container">
		<div class="price_overlay">
			<h2>&#8364;{{ isset($property->price) ? $property->price : ((isset($property->rooms) && isset($property->rooms[0]->price)) ? $property->rooms[0]->price : '') }}pm</h2>
			@if($property->rooms)
			<p>{{ isset($property->bills) ? 'Bills Excluded' : ((isset($property->rooms) && isset($property->rooms[0]->bills)) ? 'Bills Excluded' : 'Bills included') }}</p>
			@endif
		</div>
		<div class="viewers">
			<div class="listing-visitors">
						<a href="#" class="visitor visitor-main" style="background-image:url(@isset($property->user->photo) {{ $property->user->photo }} @else /storage/img/profile_placeholder.png @endisset)"><!--<span class="blue_circle"></span>--></a>
						@foreach($property->rooms as $room)
							@if($room->occupants>0 && $room->lgbt>0)
								@if($room->beds > $room->occupants)
									<a href="#" class="visitor" style="background-image:unset;
									background: #76c36a;background: -moz-linear-gradient(-45deg, #76c36a 0%, #76c36a 50%, #ffff00 61%, #e00000 72%, #00ffff 82%, #0000ff 92%, #ee82ee 100%);background: -webkit-linear-gradient(-45deg,#76c36a 0%, #76c36a 50%,#ffff00 61%,#e00000 72%,#00ffff 82%,#0000ff 92%,#ee82ee 100%);background: linear-gradient(135deg,#76c36a 0%, #76c36a 50%,#ffff00 61%,#e00000 72%,#00ffff 82%,#0000ff 92%,#ee82ee 100%);"><i class="fa fa-transgender"></i></a>
								@else
									<a href="#" class="visitor" style="background-image:unset;background: red;background: -webkit-linear-gradient(-45deg, orange , yellow, red, cyan, blue, violet); background: -o-linear-gradient(-45deg, orange, yellow, red, cyan, blue, violet);background: -moz-linear-gradient(-45deg, orange, yellow, red, cyan, blue, violet);background: linear-gradient(135deg, orange , yellow, red, cyan, blue, violet);"><i class="fa fa-transgender"></i></a>
								@endif
							@elseif($room->occupants>0 && $room->female>0)
								@if($room->beds > $room->occupants)
									<a href="#" class="visitor" style="background-image:unset;background: #76c36a;background: -moz-linear-gradient(-45deg, #76c36a 0%,#76c36a 50%,#ff6969 50%, #ff6969 100%); background: -webkit-linear-gradient(-45deg, #76c36a 0%,#76c36a 50%,#ff6969 50%,#ff6969 100%); background: linear-gradient(135deg, #76c36a 0%,#76c36a 50%,#ff6969 50%,#ff6969 100%);"><i class="fa fa-venus"></i></a>
								@else
									<a href="#" class="visitor" style="background-image:unset;background-color: #ff6969;"><i class="fa fa-venus"></i></a>
								@endif
							@elseif($room->occupants>0 && $room->male>0)
								@if($room->beds > $room->occupants)
									<a href="#" class="visitor" style="background-image:unset;background:#009ee3;background: -moz-linear-gradient(-45deg, #76c36a 0%,#76c36a 50%,#009ee3 50%, #009ee3 100%); background: -webkit-linear-gradient(-45deg, #76c36a 0%,#76c36a 50%,#009ee3 50%,#009ee3 100%); background: linear-gradient(135deg, #76c36a 0%,#76c36a 50%,#009ee3 50%,#009ee3 100%);"><i class="fa fa-mars"></i></a>
								@else
									<a href="#" class="visitor" style="background-image:unset;background-color: #009ee3;"><i class="fa fa-venus"></i></a>
								@endif
							@else
								<a href="#" class="visitor empty"><i class="fa fa-genderless"></i></a>
							@endif
						@endforeach
			</div>
		</div>
	</div>
	<div id="sticky_buttons_wrapper" class="contain-row">
		<div class="page-content">
			
			<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
				<div class="user_message" style=" padding: 24px; ">
					<div class="profile_image">
						<img style="width: 120px; height: 120px" src="@isset($property->user->photo) {{ $property->user->photo }} @else /storage/img/profile_placeholder.png @endisset">
					</div>
					<div class="property_description">
						<h5>From {{$user->name}}</h5>
						<p>{{$property->description}}</p>
					</div>
				</div>

				<div class="" style="margin-top: 30px;">
					
				</div>
				
				
				<div class="property_details" style="background: #fff; padding: 24px; border: 1px solid #ccc; border-radius: 4px; margin-bottom: 20px">
					<div class="row">
						<div class="property_address col-lg-6 col-md-6 col-sm-12 col-xs-12">
							<h2 style="font-weight: bold; margin-top: 0; font-size: 26px">{{$property->address}}</h2>
							<h5 style="font-size: 16px">{{$property->postal_code.' '.$property->town}}</h5>
						</div>
					</div>
					<div class='row'>
						<div class="property col-lg-6 col-md-6 col-sm-12 col-xs-12">
							<h5>About the property</h5>
							<p>Property type:<strong> {{$property->property_type}}</strong></p>
							@if(false)<p>Bedrooms:<strong> {{$property->rooms()->count()}}</strong></p>@endif
							@if(isset($property->price))
								<p>Monthly rent:<strong> € {{$property->price}} </strong>
							@endif
							@if(isset($property->deposit))
								<p>Deposit:<strong> € {{$property->deposit}} </strong>
							@endif
							@if(isset($property->bills))
								<p>Average monthly bills:<strong> € {{$property->bills}} </strong>
							@endif
							@if(isset($property->EPC) && false)
								<p>EPC rating:<strong> <span style="text-transform:uppercase">{{$property->EPC}}</span> </strong> @isset($property->epcert)</p> <p>(Download certificate as .pdf <a href="{{$property->contract->url}}" target="_blank"><i class="fa fa-file-pdf-o"></i></a>) @endisset</p>
							@endif
							@if($male>0||$female>0||$queer>0)
								<p>Sharing with:
                  <strong>
								@if($male>0)
									{{$male}} male(s)
								@endif
								@if($male>0 && ($female > 0 || $queer > 0))
								,
								@endif
								@if($female>0)
									{{$female}} female(s)
								@endif
								@if($female>0 && $queer > 0)
								,
								@endif
								@if($queer>0)
									{{$queer}} LGBTQ+ person(s)
								@endif
                  </strong>
								</p>
							@endif
						</div>
						<div class="rules col-lg-6 col-md-6 col-sm-12 col-xs-12">
							<h5>Rules</h5>
							@if(isset($property->contract))
								<p>Contract (download as .pdf):<a href="{{$property->contract->url}}" target="_blank"><i class="fa fa-file-pdf-o"></i></a></p>
							@endif
							<p>Minimum stay:<strong> {{$property->minimum_stay ? $property->minimum_stay : 'None' }}</strong></p>
							@if(isset($acceptings)&&count($acceptings)>0)
								<p>Accepting: 
								@foreach($property->acceptings as $a) 
									<i class="fa {{$a->icon}}" style="margin-right:2px;"></i> 
								@endforeach
								</p>
							@endif
						</div>
					</div>
        </div>

        <div class="property_details" style="background: #fff; padding: 24px; border: 1px solid #ccc; border-radius: 4px">
					<div class='row'>
					@foreach($property->rooms as $k=>$room)
						<div class="room col-lg-4 col-md-4 col-sm-12 col-xs-12">
              <h5>About the room</h5>
							<p>Beds:<strong> {{$room->beds}}</strong></p>
							<p>Bathroom:<strong> {{$room->has_bathroom ? 'Own':'No'}}</strong></p>
							@if(!isset($property->price) && isset($room->price))
								<p>Monthly rent:<strong> &euro; {{$room->price}}</strong></p>
							@endif
							@if(!isset($property->deposit) && isset($room->deposit))
							<p>Deposit:<strong> &euro; {{$room->deposit}}</strong></p>
							@endif
							@if(!isset($property->bills) && isset($room->bills))
							<p>Bills:<strong> {{$room->bills ? '€ '.$room->bills :'Included'}}</strong></p>
							@endif
							@if(isset($room->avail_from))
							<p>Available from:<strong> {{date('d/m/Y', strtotime($room->avail_from))}}</strong></p>
							@endif
							@if(isset($room->avail_to))
							<p>Available to:<strong> {{date('d/m/Y', strtotime($room->avail_to))}}</strong></p>
							@endif
						</div>
					@endforeach
					</div>
					<div class="row">
						<div class="amenity col-lg-12 col-md-12">
							@if(isset($amenities)&&count($amenities)>0)
								<h5>Amenities</h5>
								@foreach($amenities as $amenity)
                <div for="{{$amenity->id}}" style="display: inline-block; border: 1px solid #ccc; margin-bottom: 10px"><img style="height: 30px; margin-right: 10px;" src="{{$amenity->icon}}"/>{{$amenity->label}}</div>
								@endforeach
							@endif
						</div>
					</div>
				</div>
			</div>
			<div id="sticky_buttons" class="user_message col-lg-4 col-md-4 col-sm-12 col-xs-12">
				<div class="property_buttons col-lg-12 col-md-12 col-sm-12 col-xs-12">
					@if(Auth::check() && Auth::user()->id==$property->user_id)
						<a class="btn btn-default" href="/properties/{{$property->id}}/edit">Edit property <i class="fa fa-edit"></i></a>
						<a class="btn btn-default" href="/properties/{{$property->id}}/packages" id="to_package_edit">Upgrade Package</a>
						@if($property->getHighestOrder()=='premium')
							<a class="btn btn-default" href="/properties/{{$property->id}}/invites">Invite Poolers <i class="fa fa-edit"></i></a>
						@endif
					@else
						@if(Auth::check())
							<a href="/messages/send/to/{{$user->id}}" class="@if(!$can_receive) disabled @endif btn btn-default">Message pooler <i class="fa fa-comment"></i></a>
							<a id="favorite_{{$property->id}}" class="@if(isset($favorited)&&$favorited==true) favorited @endif favorite btn btn-default">@if(isset($favorited)&&$favorited==true) Added @else Add @endif to favorites <i class="fa @if(isset($favorited)&&$favorited==true) fa-heart @else fa-heart-o @endif"></i></a>
							@if(Auth::user()->id!=$property->user_id)
								<a class="btn btn-default" href="/properties/{{$property->id}}/report">Report Property <i class="fa fa-exclamation-triangle"></i></a>
							@endif
						@endif
					@endif
					@if(Auth::check() && Auth::user()->id==$property->user_id)
						<form name="delete" action="/properties/{{$property->id}}" method="POST">
							<input type="hidden" name="_method" value="DELETE">
							{!! csrf_field() !!}
							<button class="btn btn-default btn-delete">Delete property <i class="fa fa-close"></i></button>
						</form>
					@else
						@if(isset($user->verified)&&$user->verified==1)
							<button class="btn disabled">Verified <i class="fa fa-check"></i></button>
						@else
							<button class="btn disabled btn-delete">Not verified <i class="fa fa-close"></i></button>
						@endif
					@endif
				</div>
			</div>
			<div id="googleMap" class="mgtop30 col-lg-8 col-md-8 col-sm-12 col-xs-12" style="height:456px;clear:both;"></div>
			<div class="listings-grid col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<h3>Nearby Properties</h3>
				@include('block',['properties'=>$nearbies,'now'=>$now])
			</div>

		</div>
	</div>
</main>
<script src="http://maps.googleapis.com/maps/api/js?key=AIzaSyBe42gt5WvTjzvWyc8CmJi9CPrgKcT5NEg">
</script>
<script>
	function initialize() {
		var latLong = new google.maps.LatLng({{$property->lat}},{{$property->long}});
		var mapProp = {
			center:latLong,
			zoom:16,
			mapTypeId:google.maps.MapTypeId.ROADMAP
		};

		var map=new google.maps.Map(document.getElementById("googleMap"), mapProp);
	
		var marker = new google.maps.Marker({
			position: latLong,
			map: map,
			icon: '/storage/img/map-marker.png'
		});
	}
	google.maps.event.addDomListener(window, 'load', initialize);
</script>

@endsection

@section('script')
@endsection
