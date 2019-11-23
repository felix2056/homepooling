@extends('layouts.app')

@section('content')
<main id="content" class="create_message">
	<div class="page-header" id="headerContent">
		<h2>Send a message</h2>
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
				<form action="/messages/send" method="POST">
					{!! csrf_field() !!}
					<input type="hidden" class="user_id" name="user_id" value="{{$user->id}}">
					<textarea class="text form-control" name="text"></textarea>
					<button class="btn btn-default">Send</button>
				</form>
			</div>
		</div>
	</div>
</main>

@endsection
@section('script')
    <script src="{{ asset('js/message.js') }}"></script>
@endsection