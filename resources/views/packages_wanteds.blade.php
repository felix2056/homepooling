@extends('layouts.app')

@section('content')
<main id="content" class="share_room">
	<div class="page-header" id="headerContent">
		<h2>Choose Package</h2>
	</div>
	<div class="container packages_wrapper">
		@if(isset($current)&&$current=='basic')
			<form id="package_form" action="/wanteds/{{$wanted->id}}/packages" method="POST">
				{!! csrf_field() !!}
				<input id="choose_messages" type="radio" name="package" value="messages" selected checked @if(isset($current)&& $current) @if($current=='premium') disabled @endif @endif/>
				<label class="choice @if(isset($current)&& $current) @if($current=='premium') disabled @endif @endif" for="choose_messages">
					<div id="messages" class="package">
						<div class="package_row first">
							<h3>Conversation package</h3>
							<h1>€2.99</h1>
							<h5>5 additional incoming conversations</h5>
						</div>
						<div class="package_row">
							<h5>No Highlights</h5>
							<h5>No Early Access</h5>
						</div>
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
						</script>
					</div>
				</label>
			</form>
		@else
			<form id="package_form" action="/wanteds/{{$wanted->id}}/packages" method="POST">
				{!! csrf_field() !!}
				<input id="choose_basic" type="radio" name="package" value="basic" selected checked @if(isset($current)&& $current) @if($current!='basic') disabled @endif @endif/>
				<label class="choice @if(isset($current)&& $current) @if($current!='basic') disabled @endif @endif" for="choose_basic">
					<div id="basic" class="package">
						<div class="package_row first">
							<h3>Basic</h3>
							<h1>Free</h1>
							<h5>Free of charge standard listing</h5>
							<h5>Owners can't contact you during the first week</h5>
						</div>
						<div class="package_row">
							<h5>No Highlights</h5>
							<h5>No Early Access</h5>
							<h5>Includes 3 free conversations with owners</h5>
							<h5>$2.99 per 5 additional conversations</h5>
						</div>
						<div type="submit" class="btn btn-default @if(isset($current)&& $current) @if($current!='basic') disabled @endif @endif">Choose</div>
					</div>
				</label>
			</form>
		@endif
		@if(isset($current)&&($current=='premium'||$current=='bump'))
			<form id="package_form" action="/wanteds/{{$wanted->id}}/packages" method="POST">
				{!! csrf_field() !!}
				<input id="choose_bump" type="radio" name="package" value="bump" selected checked />
				<label class="choice" for="choose_bump">
					<div id="bump" class="package">
						<div class="package_row first">
							<h3>Bump</h3>
							<h1>€ 1.99</h1>
							<h5>Back on top for one week</h5>
						</div>
						<div class="package_row">
							<h5>Highlighted listing</h5>
						</div>
						<script
							src="https://checkout.stripe.com/checkout.js" class="stripe-button"
							data-key="{{ env('STRIPE_KEY') }}"
							data-amount="199"
							data-name="Stripe.com"
							data-description="Payment Widget"
							data-locale="auto"
							data-currency="eur"
							data-label="Choose and pay"
						>
						</script>
					</div>
				</label>
			</form>
		@else
			<form id="package_form" action="/wanteds/{{$wanted->id}}/packages" method="POST">
				{!! csrf_field() !!}
				<input id="choose_premium" type="radio" name="package" value="premium" selected checked @if(isset($current)&&$current=='premium') disabled @endif />
				<label class="choice @if(isset($current)&&$current=='premium') disabled @endif" for="choose_premium">
					<div id="premium" class="package">
						<div class="package_row first">
							<h3>Premium</h3>
							<h1>€ 12.00</h1>
							<h5>Highlighted listing, on top for one week</h5>
							<h5>Owners can contact you right away!</h5>
						</div>
						<div class="package_row">
							<h5>On Top for a Week</h5>
							<h5>Early Access</h5>
							<h5>Unlimited conversations</h5>
							<h5>Allow early conversation from all or premium owners</h5>
						</div>
						@if(isset($current)&&$current=='premium')
							<div type="submit" class="btn btn-default disabled">Choose</div>
						@else
							<script
								src="https://checkout.stripe.com/checkout.js" class="stripe-button"
								data-key="{{ env('STRIPE_KEY') }}"
								data-amount="1200"
								data-name="Stripe.com"
								data-description="Payment Widget"
								data-locale="auto"
								data-currency="eur"
								data-label="Choose and pay"
							>
							</script>
						@endif
					</div>
				</label>
			</form>
		@endif
	</div>
</main>

@endsection
