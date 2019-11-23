@extends('layouts.app')

@section('content')
<main id="content" class="create_message">
	<div class="page-header" id="headerContent">
		@isset($property)
			<h2>Report a Property</h2>
		@else
			<h2>Report a Pooler</h2>
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
					<p>To:   <strong>Admin</strong></p>
					<div class="profile_image">
						<img src="/storage/img/profile_placeholder.png" alt="{{$user->name}}">
					</div>
				</div>
			</div>
			<div class="message_text">
				@isset($property)
					<p>Offending Property:   <strong>{{$property->address}}</strong></p>
					<form action="/properties/{{$property->id}}/report" method="POST">
				@else
					<p>Offending Profile:   <strong>{{$user->name.' '.$user->family_name}}</strong></p>
					<form action="/profiles/{{$user->id}}/report" method="POST">
				@endisset
						{!! csrf_field() !!}
						<div class="col-xs-12 col-sm-12 col-lg-12 col-md-12 report_note" style="margin:20px 0">
							<p>You can leave a brief note detailing what, in your opinion, represents a violation of our Terms of Service, or an offence against decency and/or common morality. We will consider thoroughly your abuse report and, if confirmed, take any action needed to remove the offending material. Thank you!</p>
						</div>
						<textarea class="text form-control" name="text"></textarea>
						<button class="btn btn-default">Send Report</button>
				</form>
			</div>
		</div>
	</div>
</main>

@endsection
 
 
