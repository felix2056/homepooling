<!-- <i class="fa fa-caret-up"></i> -->
<form class="form-horizontal" method="POST" action="{{ route('login') }}">
	{{ csrf_field() }}

	<div class="login_row">
		<div class="pd5{{ $errors->has('email') ? ' has-error' : '' }}">
			<input id="email" type="email" class="form-control pd520" name="email" value="{{ old('email') }}" placeholder="Email" required autofocus>

			@if($errors->has('email'))
			<span class="help-block">
				<strong>{{ $errors->first('email') }}</strong>
			</span>
			@endif
		</div>

		<div class="pd5{{ $errors->has('password') ? ' has-error' : '' }}">
			<input id="password" type="password" class="form-control pd520" name="password" placeholder="Password" required>

			@if($errors->has('password'))
			<span class="help-block">
				<strong>{{ $errors->first('password') }}</strong>
			</span>
			@endif
		</div>
		<div>
			<a class="forgot" href="{{ route('password.request') }}">Forgot Password?</a>
		</div>

		<div>
			<div class="checkbox checkbox_wrapper pd520">
				<input id="remember" type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }} checked class="hidden">
				<label for="remember" class="d-flex align-items-center">Remember Me</label>
			</div>
		</div>
	</div>
	<button type="submit" class="btn btn-primary w-100 rounded-0 custom-btn login-btn">Log in</button>
	<a class="btn btn-primary fb_login w-100 rounded-0 custom-btn text-white" href="{{url('/redirect')}}">Sign in with <strong>facebook</strong></a>
</form>