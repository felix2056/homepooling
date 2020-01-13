<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="robots" content="noindex, nofollow">
	<?php
	$settings = App\Setting::whereIn(
        'meta_key', [
           	'logo',
           	'favicon',
            'name',
            'short_name',
            'description',
            'website_link',
            'email',
            'phone_no',
            'contact_address',
            'contact_email',
            'contact_phone',
            'facebook',
            'twitter',
            'youtube',
            'instagram'
        ]
        )->get();

        $metas = [];

        foreach ($settings as $setting){
            $metas[$setting->meta_key] = $setting->meta_value;
        }
        $siteInfo = $metas;
	?>
	<!-- CSRF Token -->
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<meta name="keywords" content="homepool,home,vacation,homepooling,pool your home,airbnb alternative,house,home,events">

	<title>{{$siteInfo['short_name']}} | @yield('pageTitle')</title>

	<link rel="shortcut icon" href="@if($siteInfo['favicon']){{asset('storage/img/'.$siteInfo['favicon'])}} @else{{ asset('storage/img/logo.png') }}@endif">

	<!-- Styles -->
	<link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap.min.css') }}">
	@if(isset($loadNewStyles))
	<link rel="stylesheet" type="text/css" href="{{ asset('css/reset.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('css/newStyles.css') }}">
	@endif
	<link href="{{ asset('css/app.css') }}" rel="stylesheet">
	<link href="{{ asset('css/nav.css') }}" rel="stylesheet">
	<link href="{{ asset('css/jquery.bxslider.css') }}" rel="stylesheet">
	<link href="{{ asset('css/styles.css') }}" rel="stylesheet">
	<link href="{{ asset('css/marky.css') }}" rel="stylesheet">
	<link href="{{ asset('css/datepicker.min.css') }}" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.2/css/all.css" async>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

	<!--MDB-->
	<!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.7.6/css/mdb.min.css" />-->
	<link rel="stylesheet" type="text/css" href="{{ asset('css/mdb.css') }}">-
	<style type="text/css">
		.envelope.incoming {
			position: relative;
		}
		.envelope.incoming.notify::after {
			content: "";
			display: inline-block;
			height: 10px;
			width: 10px;
			top: -5px;
			right: -5px;
			position: absolute;
			z-index: 1;
			background: red;
			border-radius: 50%;
		}
	</style>
</head> 
<body class="d-flex-column">
	<div id="app{{ isset($searchProps) && $searchProps ? ' search_properties' : '' }}">

		<style type="text/css">
			.brand .logo {
			    background: url(/storage/img/logo.png) no-repeat;
			    background-size: contain;
			    background-position: center;
			}	
		</style>

        <section class="navigation clearfix">
            <div class="nav-container">
                <div class="brand">
                    <a href="/" class="logo">home<span>pooling</span></a>
                </div>
                <nav>
                    <div class="nav-mobile"><a id="nav-toggle" href="#!"><span></span></a></div>
                    <ul class="nav-list">
                    	<li class="link">
							<a href="/properties">Browse</a>
	                    </li>
                    	@guest
	                        <li>
	                            <a href="{{ route('register') }}" class="signup-modal">Register</a>
	                        </li>
	                        <li>
	                            <a href="#!">Login</a>
	                            <ul class="nav-dropdown right">
	                                <li>
										@include('auth.login')
									</li>
	                            </ul>
	                        </li>
	                        
						@else
							<li class="link">
								<a href="/properties/create">Pool Home</a>
	                        </li>
							<li class="link">
								<!-- <a href="#"><i class="fa fa-heart-o" aria-hidden="true"></i></a> -->
								<span class="envelope ">
									<i class="fa fa-heart-o" aria-hidden="true"></i>
								</span>
	                        </li>
	                        <li class="link">
								<?php $unrdMsgs = \App\Message::where('receiver_id', auth()->user()->id)->where('seen', 0)->get(); ?>
								<span class="envelope incoming{{-- $unrdMsgs->count() > 0 ? ' notify' : '' --}}">
									<i class="fa fa-envelope"></i>
									<span style="position: absolute;top: 9px;right: 7px;font-size: 12px;padding: 2px 3px;line-height: .9;background: #dd4b39 !important;color: #fff;" class="label label-danger" id="msgcount">{{ $unrdMsgs->count() > 0 ? $unrdMsgs->count() : '' }}</span>
								</span>
	                        <li>
								<a href="#!" class="profile-image" style="background-image: url({{ isset( Auth::user()->photo ) ? Auth::user()->photo : '/storage/img/profile_placeholder.png' }});"></a>
								<ul class="nav-dropdown">
									@if(isset(Auth::user()->is_admin)&&Auth::user()->is_admin==1)
										<li>
											<a href="/back-office">Admin Panel</a>
										</li>
									@endif
									<li>
										<a href="/profiles/{{Auth::user()->id}}/edit">Edit Profile</a>
									</li>
									<li>
										<a href="{{ route('logout') }}" class="logout-link" 
										onclick="event.preventDefault();
											document.getElementById('logout-form').submit();">
										Log Out
										</a>

										<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
										{{ csrf_field() }}
										</form>
									</li>
								</ul>
							</li>
						@endguest
                    </ul>
                </nav>
            </div>
        </section>
		<?php /*<header id="header" role="banner">
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
					<!-- <li class="link share_room_btn"><a href="/properties/create"><strong>Pool</strong> a room</a></li> -->
					<!-- <li class="link share_room_btn"><a href="/wanteds/create"><strong>Find</strong> a room</a></li> -->
					<!-- <li class="link share_room_btn"><a href="/properties"><strong>Find</strong> a room</a></li> -->
					<li class="link heart"><i class="fa fa-heart-o"></i></li>
					<li class="link">
						<?php $unrdMsgs = \App\Message::where('receiver_id', auth()->user()->id)->where('seen', 0)->get(); ?>
						<span class="envelope incoming{{ $unrdMsgs->count() > 0 ? ' notify' : '' }}">
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
						</div>/ ?>
					</li>
					<!-- <li class="link greetings" data-userid="{{Auth::user()->id}}">{{Auth::user()->name}}</li> -->
					<!-- <li class="link greetings" data-userid="{{Auth::user()->id}}">Hi, {{Auth::user()->name}}</li> -->
					<li class="link dropdown">
						<div class="btn-group">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
								<img class="profile_bar_image" src="{{ isset( Auth::user()->photo ) ? Auth::user()->photo : '/storage/img/profile_placeholder.png' }}"> <?php //<span class="caret"></span> ?>
								</a>
								<ul class="dropdown-menu" role="menu">
								<i class="fa fa-caret-up"></i>
								@if(isset(Auth::user()->is_admin)&&Auth::user()->is_admin==1)
									<li>
										<a href="/back-office">Admin Panel</a>
									</li>
								@endif
								<li>
									<a href="/profiles/{{Auth::user()->id}}/edit">Edit Profile</a>
								</li>
								<li>
									<a href="{{ route('logout') }}"
									onclick="event.preventDefault();
										document.getElementById('logout-form').submit();">
									Log Out
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
		</header>*/ ?>
		@if(\Auth()->user())
		@if(Request::segment(3) == 'edit' && Request::segment(1) == 'profiles')
		<section class="menu_bar">
			<div class="nav-container">
				<div class="white mlr23" aria-hidden="true">
					<div class="nfy-container">
						<span> <a class="hvr-underline-from-center menu-3" href="#edit" id="profile_search_button" role="tab" data-toggle="tab" aria-controls="search" aria-expanded="false">Edit Profile</a></span>
						<span> <a class="hvr-underline-from-center menu-3" href="#search" id="profile_search_button" role="tab" data-toggle="tab" aria-controls="search" aria-expanded="false">Search Settings</a></span>
						<span> <a class="hvr-underline-from-center menu-3" href="#favorites" id="profile_search_button" role="tab" data-toggle="tab" aria-controls="search" aria-expanded="false">Favorites</a></span>
						<span> <a class="hvr-underline-from-center menu-3" href="#myPools" id="profile_search_button" role="tab" data-toggle="tab" aria-controls="search" aria-expanded="false">My Pools</a></span>
						<span style="color:#696969"> Packages</span>
					</div>
				</div>
			</div>
		</section>
		
		@else
		<section class="menu_bar">
			<div class="nav-container">
				<div class="white mlr23" aria-hidden="true">
					<div class="nfy-container">
						<span> <a class="hvr-underline-from-center menu-3" href="/profiles/{{\Auth()->user()->id}}/edit?menu=edit" > {{ Request::segment(3) }} Edit Profile</a></span>
						<span> <a class="hvr-underline-from-center menu-3" href="/profiles/{{\Auth()->user()->id}}/edit?menu=search">Search Settings</a></span>
						<span> <a class="hvr-underline-from-center menu-3" href="/profiles/{{\Auth()->user()->id}}/edit?menu=favorites">Favorites</a></span>
						<span> <a class="hvr-underline-from-center menu-3" href="/profiles/{{\Auth()->user()->id}}/edit?menu=myPools">My Pools</a></span>
						<span style="color:#696969"> Packages</span>
					</div>
				</div>
			</div>
		</section>
		@endif
		@endif

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
		@if(Session::has('errors'))
		<div class="floating alert alert-danger alert-dismissable">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        There were errors in the form:<br>
                        @foreach ($errors->all() as $error)
                            {{ $error }}<br/>
                        @endforeach
		</div>
		@endif
		@yield('content')
	</div>
	<footer id="footer" role="contentinfo">
		<div class="footer-nav flex-container">
			<div class="footer-block">
			<ul>
				<li class="footer-block-title">Company</li>
				<li><a href="#">About</a></li>
				<li><a href="#">Careers</a></li>
				<li><a href="#">Blog</a></li>
				<li><a href="#">Policies</a></li>
			</ul>
			</div>
			<div class="footer-block">
			<ul>
				<li class="footer-block-title">Listing</li>
				<li><a href="#">Why List</a></li>
				<li><a href="#">Safety</a></li>
				<li><a href="#">Terms</a></li>
				<li><a href="#">Policies</a></li>
			</ul>
			</div>
			<div class="footer-block">
			<ul>
				<li class="footer-block-title">Address</li>
				<li>@if($siteInfo['contact_address']){{ $siteInfo['contact_address'] }} @else{{ 'Somewhere in Belguim' }}@endif</li>
			</ul>
			</div>
			<div class="footer-block">
			<ul>
				<li class="footer-block-title">Contact us</li>
				<li>@if($siteInfo['contact_phone']){{ $siteInfo['contact_phone'] }} @else{{ '+1 245265424' }}@endif</li>
				<li>@if($siteInfo['contact_email']){{ $siteInfo['contact_email'] }} @else{{ 'info@hp.dzn-studios.com' }}@endif</li>
			</ul>
			</div>
		</div>
      <div class="flex-container" style="margin-top: 30px">
      <div class="col-md-4" style="padding: 0">
	    <div class="footer-social">
		  <a href="@if($siteInfo['facebook']){{ $siteInfo['facebook'] }} @else{{ '#' }}@endif"><i class="fab fa-facebook-f" aria-hidden="true"></i></a>
		  <a href="@if($siteInfo['twitter']){{ $siteInfo['twitter'] }} @else{{ '#' }}@endif"><i class="fab fa-twitter" aria-hidden="true"></i></a>
		  <a href="@if($siteInfo['instagram']){{ $siteInfo['instagram'] }} @else{{ '#' }}@endif"><i class="fab fa-instagram" aria-hidden="true"></i></a>
		  <a href="@if($siteInfo['youtube']){{ $siteInfo['youtube'] }} @else{{ '#' }}@endif"><i class="fab fa-youtube" aria-hidden="true"></i></a>
		</div>
      </div>
      <div class="col-md-4 text-center">
        <div style="margin-left: -6px"><img src="@if($siteInfo['logo']){{asset('storage/img/'.$siteInfo['logo'])}} @else{{ asset('storage/img/logo.png') }}@endif" style="height: 30px"/></div>
      </div>
      <div class="col-md-4">
        <p class="copyright">Copyright @if($siteInfo['website_link']){{ $siteInfo['website_link'] }} @else{{ 'hp.dzn-studios.com' }}@endif</p>
      </div>
    </div>
	</footer>
	<script src="{{ asset('js/jquery.min.js') }}"></script>
	<script src="{{ asset('js/bootstrap.min.js') }}"></script>
	<script src="{{ asset('js/popper.min.js') }}"></script>
	<!--MDB-->
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.7.6/js/mdb.min.js"></script>
	<script src="{{ asset('js/mdb.min.js') }}"></script>
	<script>
		$('.carousel').carousel()
	</script>
	@include('messages_popup')
    <!-- Scripts -->
    @if(isset($searchProps) && $searchProps)
    <script src="{{ asset('js/common.js') }}"></script>
    @endif
    <script src="{{ asset('js/app.js') }}"></script>
    @yield('script')
</body>
</html>
