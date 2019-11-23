@extends('layouts.app')

@section('content')
<main id="content" class="invites">
	<div class="page-header" id="headerContent">
		<h2>Invite users</h2>
	</div>
	<div class="container">
		<div class="invite_search col-md-4 col-lg-4 col-xs-12 col-sm-12">
			<input type="text" class="form-control" name="location" id="location" placeholder="Filter by location">
			<select name="age" id="age">
				<option name="age" value="all" selected checked>Filter by Age</option>
				<option name="age" value="18-20">18-20</option>
				<option name="age" value="21-25">21-25</option>
				<option name="age" value="26-30">26-30</option>
				<option name="age" value="31-35">31-35</option>
				<option name="age" value="36-40">36-40</option>
				<option name="age" value="41+">41+</option>
			</select>
			<select name="gender" id="gender">
				<option name="gender" value="all" selected checked>Filter by Gender</option>
				<option name="gender" value="m">Male</option>
				<option name="gender" value="f">Female</option>
				<option name="gender" value="q">LGBTQ+</option>
			</select>
		</div>
		<div class="invite_list col-md-8 col-lg-8 col-xs-12 col-sm-12">
			<form id="invite_list" action="/properties/{{$property->id}}/invites" method="POST">
				{!! csrf_field() !!}
				<div class="invite_results d-flex wrapping">
				</div>
				<div class="invite_buttons">
					<button id="invite_all" class="btn btn-custom">Invite <strong>all</strong></button>
					<button id="invite_selected" class="btn btn-custom">Invite <strong>selected</strong></button>
				</div>
			</form>
		</div>
		<div class="page-content col-md-12 col-lg-12 col-sm-12 col-xs-12">
			<h1 class="page-title">Latest Users</h1>
			<div class="listings-grid users d-flex align-items-center justify-content-center">
			@foreach($lastusers as $user)
				<a href="/profiles/{{$user->id}}" class="user-link">
				@if($user->photo||$user->photo==='')
					<span class="user-image"><img src="{{$user->photo}}" alt="{{$user->name}}"></span>
				@else
					<span class="user-image"><img src="/storage/img/profile_placeholder.png"></span>
				@endif
					<span class="user-name">{{$user->name}}</span>
				</a>
			@endforeach
			</div>
		</div>
	</div>
</main>
@endsection
@section('script')
    <script src="{{ asset('js/search.js') }}"></script>
@endsection