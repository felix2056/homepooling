		<section class="container wanted_listing d-flex wrapping">
			@if(!isset($rendering)||!$rendering)<h1 class="d-flex align-items-center justify-content-center page-title">Latest Room Wanted Ads @if(isset($properties))<a href="/wanteds">>> show all </a>@endif</h1>@endif
			@foreach($latest as $want)
					<div class="wanted_listing_item d-flex wrapping nonVis">
						@if(isset($want->user->photo))
							<a href="/wanteds/{{$want->id}}">
								<img class="cornered" src="{{$want->user->photo}}">
							</a>
						@else
							<a href="/wanteds/{{$want->id}}">
								<img class="cornered" src="/storage/img/profile_placeholder.png">
							</a>
						@endif
						<a href="/wanteds/{{$want->id}}">
							<div class="wanted_listing_content d-flex align-items-center">Room wanted near <br>{{$want->location}}</div>
						</a>
						{{-- <a href="/wanteds/{{$want->id}}">
							<i class="fa fa-search"></i>
						</a> --}}
					</div>
			@endforeach
		</section>
 
