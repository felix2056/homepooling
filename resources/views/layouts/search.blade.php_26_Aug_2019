<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="robots" content="noindex, nofollow">

	<!-- CSRF Token -->
	<meta name="csrf-token" content="{{ csrf_token() }}">

	<title>{{ config('app.name', 'Homepooling') }}</title>

	<!-- Styles -->
	<link href="{{ asset('css/app.css') }}" rel="stylesheet">
	<link href="{{ asset('css/bootstrap.css') }}" rel="stylesheet">
	<link href="{{ asset('css/styles.css') }}" rel="stylesheet">
	<link href="{{ asset('css/marky.css') }}" rel="stylesheet">
	<link href="{{ asset('css/datepicker.min.css') }}" rel="stylesheet">
	<link href="{{ asset('css/jquery.bxslider.css') }}" rel="stylesheet">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
</head>
<body>
	<div id="app" class="search_properties">
		<header id="header" role="banner">
			<div class="contain-row">
				<a href="/" class="logo">home<span>pooling</span></a>
				<ul class="nav navbar-nav">
					<!-- Authentication Links -->
					@guest
					<li class="link nolog"><a href="{{ route('register') }}" class="signup-modal">Register</a></li>
					<li class="link nolog dropdown login"><a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><strong>Sign in</strong> <span class="caret"></span></a>
						<div class="dropdown-menu" role="menu">
							@include('auth.login')
						</div>
					</li>
					@else
					<li class="link share_room_btn"><a href="/properties/create"><strong>Pool</strong> a room</a></li>
					<li class="link share_room_btn"><a href="/wanteds/create"><strong>Find</strong> a room</a></li>
					<li class="link heart"><i class="fa fa-heart-o"></i></li>
					<li class="link">
						<span class="dropdown-toggle incoming">
							<i class="fa fa-envelope"></i>
						</span>
						<?php /*<div class="btn-group">
							<a href="#" class="dropdown-toggle incoming" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-comments"></i></a>
							<ul class="dropdown-menu messages_preview" role="menu">
								<i class="fa fa-caret-up"></i>
								<div class="scrollable">
									@forelse(\App\Thread::where('user_id_2',Auth::user()->id)->orWhere('user_id_1',Auth::user()->id)->orderBy('updated_at','DESC')->orderBy('created_at','DESC')->get() as $thread)
										<li>
											<div id="thread_{{$thread->id}}_header" class="preview thread_{{$loop->index}} thread @if($thread->hasUnread()) unread @endif">
													<div class="author_photo">
														<div class="profile_image">
															<a href="/messages">
															@if(isset($thread->getLastMessage()[0]->user->photo))
																<img src="{{$thread->getLastMessage()[0]->user->photo}}" alt="{{$thread->getLastMessage()[0]->user->name}}">
															@else
																<img src="/storage/img/profile_placeholder.png" alt="{{$thread->getLastMessage()[0]->user->name}}">
															@endif
															</a>
														</div>
													</div>
													<div class="text_excerpt">
														<div>
														<a href="/messages">
															@if(isset($thread->getLastMessage()[0]->user->name))
																<h5>{{$thread->getLastMessage()[0]->user->name}}</h5>
															@endif
															@if(isset($thread->getLastMessage()[0]->text))
																<p>{{ str_limit($thread->getLastMessage()[0]->text,$limit=20,$end='...')}}</p>
															@endif
														</a>
														</div>
														<div class="thread_timestamp">
														<a href="/messages">
															@if(isset($thread->getLastMessage()[0]->created_at))
																<p>{{date('j M',($thread->getLastMessage()[0]->created_at)->getTimeStamp())}}</p>
															@endif
														</a>
														</div>
													</div>
											</div>
										</li>
									@empty
										<li>No messages yet!</li>
									@endforelse
								</div>
								<a href="/messages">All messages</i></a>
							</ul>
						</div>*/ ?>
					</li>
					<li class="link greetings" data-userid="{{Auth::user()->id}}">Hi, {{Auth::user()->name}}</li>
					<li class="link dropdown">
						<div class="btn-group">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
								@if(isset( Auth::user()->photo )) <img class="profile_bar_image" src="{{ Auth::user()->photo }}">  @else <img class="profile_bar_image" src="/storage/img/profile_placeholder.png"> @endif <span class="caret"></span>
								</a>
								<ul class="dropdown-menu" role="menu">
								<i class="fa fa-caret-up"></i>
								@if(isset(Auth::user()->is_admin)&&Auth::user()->is_admin==1)
									<li>
										<a href="/back-office">Admin Panel</a>
									</li>
								@endif
								<li>
									<a href="/profiles/{{Auth::user()->id}}">My Account</a>
								</li>
								<li>
									<a href="{{ route('logout') }}"
									onclick="event.preventDefault();
										document.getElementById('logout-form').submit();">
									Logout
									</a>

									<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
									{{ csrf_field() }}
									</form>
								</li>
								</ul>
						</div>
					</li>
					@endguest
				</ul>
			</div>
		</header>
		@if(Session::has('status'))
		<div class="floating alert alert-success alert-dismissable">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			{{ Session::get('status') }}
		</div>
		@endif
		@if(Session::has('message'))
		<div class="floating alert alert-success alert-dismissable">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			{{ Session::get('message') }}
		</div>
		@endif
		@if(Session::has('error'))
		<div class="floating alert alert-danger alert-dismissable">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			{{ Session::get('error') }}
		</div>
		@endif
		@yield('content')
	</div>
    <!-- Scripts -->
    <script src="{{ asset('js/common.js') }}"></script>
    @yield('script')
</body>
</html>
