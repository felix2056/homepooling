@extends('layouts.app')

@section('content')
<style type="text/css">
	.mt-1 {
		margin-top: 0.75em;
	}
	.mt-2 {
		margin-top: 1em;
	}
	.mt-3 {
		margin-top: 1.5em;
	}
	.p-0 {
		padding-left: 0 !important;
	}
	@media screen and (min-width: 992px){
		.text-md-right {
			text-align: right;
		}
	}
</style>
<main id="content" class="register">
	<div class="page-header" id="headerContent"><h2>Login</h2></div>
	<div class="container">
		<div class="panel panel-default">
			<div class="panel-body">
				<form class="row" method="POST" action="{{ route('login') }}">
					{{ csrf_field() }}

						<div class="mb-1 col-xs-12 col-md-4 text-md-right">
							<label for="email">Email</label>
						</div>
						<div class="form-group col-xs-12 col-md-8{{ $errors->has('email') ? ' has-error' : '' }}">
							<input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="Email" required autofocus>

							@if ($errors->has('email'))
							<span class="help-block">
								<strong>{{ $errors->first('email') }}</strong>
							</span>
							@endif
						</div>

						<div class="mb-1 col-xs-12 col-md-4 text-md-right">
							<label for="email">Password</label>
						</div>

						<div class="form-group col-xs-12 col-md-8{{ $errors->has('password') ? ' has-error' : '' }}">
							<input id="password" type="password" class="form-control" name="password" placeholder="Password" required>

							@if ($errors->has('password'))
							<span class="help-block">
								<strong>{{ $errors->first('password') }}</strong>
							</span>
							@endif
							<div class="col-12 p-0 mt-1">
								<a class="forgot" href="{{ route('password.request') }}">Forgot Password?</a>
							</div>
							<div class="col-12 p-0 mt-3">
								<div class="checkbox">
									<input id="remember-me" type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }} checked>
									<label for="remember-me" style="width: auto; display: inline-block; padding-left: 5px; user-select: none;">Remember Me</label>
								</div>
							</div>
						</div>

						<div class="text-center">
							<button type="submit" class="btn btn-primary" style="background-color: #f78a04; border-color: #f78a04; margin-right: 5px;">Log in</button>
							<a class="btn btn-primary fb_login" href="{{url('/redirect')}}">Sign in with <strong>facebook</strong></a>
						</div>
				</form>
			</div>
		</div>
	</div>
</main>

@endsection