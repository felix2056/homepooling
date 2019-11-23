@foreach($users as $user)
<div class="invites_users nonVis" id="invite_{{$user->id}}">
	<input type="checkbox" name="invited[]" value="{{$user->id}}" id="invited_{{$user->id}}">
	<label for="invited_{{$user->id}}" class="d-flex-column align-items-center justify-content-center">
		@isset($user->photo) <img src="{{$user->photo}}"> @else <img src="/storage/img/profile_placeholder.png"> @endisset
		<h5>{{$user->name}}</h5>
	</label>
</div>
@endforeach
