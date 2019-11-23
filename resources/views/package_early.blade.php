@extends('layouts.app')

@section('content')
<main id="content" class="share_room">
	<div class="page-header" id="headerContent">
		<h2>Choose Package</h2>
	</div>
	<form id="package_form" action="/profiles/{{$user->id}}/early_bird" method="POST">
	{!! csrf_field() !!}
		<div class="container packages_wrapper">
			<input type="hidden" name="amount" value="4.99" @if(isset($user->early_bird)&&$user->early_bird==1) disabled @endif >
			<input id="choose_basic" type="radio" name="package" value="early" checked selected @if(isset($user->early_bird)&&$user->early_bird==1) disabled @endif />
			<label class="choice @if(isset($user->early_bird)&&$user->early_bird==1) disabled @endif" for="choose_basic">
				<div id="early" class="package">
					<div class="package_row first">
						<h3>Early Bird Access</h3>
						<h1>€ 4.99</h1>
					</div>
					<div class="package_row">
						<h5>Access immediately to all listings</h5>
						<h5>As soon as they are published</h5>
					</div>
					@if(isset($user->early_bird)&&$user->early_bird==1)
						<div type="submit" class="btn btn-default">Choose and pay</div>
					@else
							<script
								src="https://checkout.stripe.com/checkout.js" class="stripe-button"
								data-key="{{ env('STRIPE_KEY') }}"
								data-amount="299"
								data-name="Stripe.com"
								data-description="Payment Widget"
								data-locale="auto"
								data-currency="eur"
								data-label="Choose and pay"
							>
					@endif
				</div>
			</label>
		</div>
	</form>
	<section class="main">
</main>

@endsection
 
