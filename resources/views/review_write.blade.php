@extends('layouts.app')

@section('content')
<main id="content" class="create_message">
	<div class="page-header" id="headerContent">
		@isset($review)
			<h2>Edit your review</h2>
		@else
			<h2>Write a review</h2>
		@endif
	</div>
	<div class="container">
		<div class="new_message">
			<div class="users">
				<div class="col-xs-12 col-sm-12 col-lg-6 col-md-6 user_from">
					<p>From:   <strong>You</strong></p>
					<div class="profile_image">
						@if(isset(Auth::user()->photo))
							<img src="{{Auth::user()->photo}}" alt="{{Auth::user()->name}}">
						@else
							<img src="/storage/img/profile_placeholder.png" alt="{{Auth::user()->name}}">
						@endif
					</div>
				</div>
				<div class="col-xs-12 col-sm-12 col-lg-6 col-md-6 user_to">
					<p>To:   <strong>{{$user->name.' '.$user->family_name}}</strong></p>
					<div class="profile_image">
						@if(isset($user->photo))
							<img src="{{$user->photo}}" alt="{{$user->name}}">
						@else
							<img src="/storage/img/profile_placeholder.png" alt="{{$user->name}}">
						@endif
					</div>
				</div>
			</div>
			<div class="message_text">
				<p>Property address:   <strong>{{$property->address}}</strong></p>
				<form action="/properties/{{$property->id}}/review" method="POST">
					@isset($review)
						<input type="hidden" name="_method" value="PUT" />
					@endisset
					{!! csrf_field() !!}
					<textarea class="text form-control" name="text">@isset($review) {{$review->text}} @endisset</textarea>
					<button class="btn btn-default">@isset($review) Edit @else Send @endisset</button>
				</form>
			</div>
		</div>
	</div>
</main>

@endsection
 
 
