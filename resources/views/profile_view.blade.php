@extends('layouts.app')

@section('content')
<main id="content" class="profile">
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
					<a href="/profiles/{{$user->id}}/edit" class="btn btn-default">Edit profile</a>
					<a href="/profiles/{{$user->id}}/early_bird" class="btn btn-default">Upgrade profile</a>
				@else
					@if(Auth::check() && Auth::user()->id!=$user->id)
						<a class="btn btn-default" href="/profiles/{{$user->id}}/report">Report User <i class="fa fa-exclamation-triangle"></i></a>
					@endif
					<a href="/messages/send/to/{{$user->id}}" class="@if(!$can_receive) disabled @endif btn btn-default">@if($can_receive) Contact @else Can't contact @endif</a>
				@endif
			</div>
		</section>
		<section class="col_dx col-md-10 col-lg-10">
<!--			<div class="profile_video">
				<div class="dummy_video"><i class="fa fa-play"></i></div>
			</div>-->
			<div class="profile_about">
				<h2>Hi, I'm {{$user->name}}!</h2>
				<p>A {{$user->age}} years old {{$user->profession}} from {{$user->origin}}</p>
				<h3>About me</h3>
				<p>{{$user->description}}</p>
			</div>
			@if(isset($invites) && count($invites)>0 && Auth::check() && $user->id==Auth::user()->id)
				<div class="profile_favorite prop_table">
					<h3>Sent Invites</h3>
					<table>
						<thead>
							<tr>
								<td>To</td>
								<td>Address</td>
								<td>Date sent</td>
							</tr>
						</thead>
						<tbody>
							@foreach($invites as $invite)
								<tr>
									<td><a href="/profiles/{{ $invite->user_id_to }}">{{ $invite->to->name.' '.$invite->to->family_name }}</a></td>
									<td><a href="/profiles/{{ $invite->user_id_to }}">{{ $invite->property->address }}</a></td>
									<td><a href="/profiles/{{ $invite->user_id_to }}">{{ Carbon\Carbon::parse($invite->property->created_at)->format('d/m/Y') }}</a></td>
								</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			@endif
			@if(isset($inviteds) && count($inviteds)>0 && Auth::check() && $user->id==Auth::user()->id)
				<div class="profile_favorite prop_table">
					<h3>Invites Received</h3>
					<table>
						<thead>
							<tr>
								<td>From</td>
								<td>Address</td>
								<td>Date received</td>
							</tr>
						</thead>
						<tbody>
							@foreach($inviteds as $invite)
								<tr>
									<td><a href="/properties/{{ $invite->property_id }}">{{ $invite->from->name.' '.$invite->from->family_name }}</a></td>
									<td><a href="/properties/{{ $invite->property_id }}">{{ $invite->property->address }}</a></td>
									<td><a href="/properties/{{ $invite->property_id }}">{{ Carbon\Carbon::parse($invite->property->created_at)->format('d/m/Y') }}</a></td>
								</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			@endif
			@if(isset($favorited) && Auth::check() && $user->id==Auth::user()->id)
			<div class="profile_favorite prop_table">
				<h3>Your favorite properties</h3>
				<table>
					<thead>
						<tr>
							<td>Address</td>
							<td>Number of rooms</td>
							<td>Property Type</td>
							<td>Price</td>
						</tr>
					</thead>
					<tbody>
						@foreach($favorited as $property)
							<tr>
								<td><a href="/properties/{{ $property->id }}">{{ $property->address }}</a></td>
								<td><a href="/properties/{{ $property->id }}">{{ $property->rooms_no }}</a></td>
								<td><a href="/properties/{{ $property->id }}">{{ $property->property_type }}</a></td>
								<td><a href="/properties/{{ $property->id }}">&#8364; {{ isset($property->price) ? $property->price : ((isset($property->rooms) && isset($property->rooms[0]->price)) ? $property->rooms[0]->price : '') }}</a></td>
							</tr>
						@endforeach
					</tbody>
				</table>
			</div>
			@endif
			<div class="profile_properties prop_table">
			@if($properties->count()>0||(Auth::check() && $user->id==Auth::user()->id && $pendings->count()>0))
				<h3>@if(Auth::check() && $user->id==Auth::user()->id)Your @endif Properties</h3>
				<table>
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

			<div class="profile_properties prop_table">
			@if((isset($wanted) && $wanted->status!='pending') ||(Auth::check() && $user->id==Auth::user()->id && isset($wanted) && $wanted->status=='pending'))
				<h3>@if(Auth::check() && $user->id==Auth::user()->id) Your @endif Wanted Ads</h3>
				<table>
					<thead>
						<tr>
							<td>Location</td>
							@if(Auth::check() && $user->id==Auth::user()->id)
								<td>Status</td>
							@endif
							<td>Price Range</td>
							<td>Available From</td>
							<td>Property Type</td>
						</tr>
					</thead>
					<tbody>
							<tr>
								<td><a href="/wanteds/{{ $wanted->id }}">@isset($wanted->location){{ $wanted->location }}@endisset</a></td>
								@if(Auth::check() && $user->id==Auth::user()->id)
									<td><a href="/wanteds/{{ $wanted->id }}">@isset($wanted->status){{ $wanted->status }}@endisset</a></td>
								@endif
								<td><a href="/wanteds/{{ $wanted->id }}">&#8364; @isset($wanted->price_range){{$wanted->price_range}}@endisset</a></td>
								<td><a href="/wanteds/{{ $wanted->id }}">@isset($wanted->avail_from){{$wanted->avail_from}}@endisset</a></td>
								<td><a href="/wanteds/{{ $wanted->id }}">@isset($wanted->type){{$wanted->type}}@endisset</a></td>
							</tr>
					</tbody>
				</table>
			@endif
			</div>
		</section>
	</div>
	
</main>

@endsection
@section('script')
    <script src="{{ asset('js/profile.js') }}"></script>
@endsection
