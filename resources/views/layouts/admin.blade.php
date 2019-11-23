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
	<link href="{{ asset('css/datepicker.min.css') }}" rel="stylesheet">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
</head> 
<body>
	<div id="app">
		<div id="admin_menu">
			<a href="/" class="logo">home<span>pooling</span></a>
			<div class="admin_home"><a href="/back-office"><i class="fa fa-home"></i>Dashboard</a></div>
			<div class="admin_profiles"><a href="/back-office/profiles"><i class="fa fa-user"></i>Users</a></div>
			<div class="admin_properties"><a href="/back-office/properties"><i class="fa fa-th-large"></i>Properties</a></div>
			<div class="admin_wanteds"><a href="/back-office/wanteds"><i class="fa fa-search"></i>Ads</a></div>
			<div class="admin_orders"><a href="/back-office/orders"><i class="fa fa-list-alt"></i>Orders</a></div>
			<div class="admin_orders"><a href="/back-office/reports"><i class="fa fa-exclamation-triangle"></i>Reports</a></div>
		</div>
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
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
		
