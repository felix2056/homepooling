@extends('layouts.adminNew')

@section('content')
<style type="text/css">
	.stat_box {
		display: inline-flex !important;
		justify-content: center;
		align-items: center;
		flex-direction: column;
		background: #fff;
		margin: 0 15px 15px 0;
		padding: 15px;
		min-width: 200px;
		min-height: 180px;
		box-shadow: 0px 1px 7px 2px #ccc;
	}
	.stat_icon {
		width: 100%;
		border-bottom: 1px solid #ccc;
		padding-bottom: 10px;
		font-size: 20px;
		margin-bottom: 10px;
		color: #1F9BD7;
	}
	.stat_content * {
		font-size: 14px !important;
	}
	.stat_content h5 {
		margin-bottom: 3px;
	}
	.stat_content p {
		margin-bottom: 5px;
		font-weight: 600;
		color: #1F9BD7;
	}
	a[href="javascript:void(0);"] {
		color: inherit;
		cursor: default;
		text-decoration: none !important;
	}
</style>
	<main id="content" class="dashboard">
		<div class="container">
			<h1>Main dashboard</h1>
			<div class="statistics mb-3">
				<div class="stat_box d-flex align-items-center justify-content-center">
					<div class="stat_icon d-flex align-items-center justify-content-center">
						<i class="fa fa-eur"></i>
					</div>
					<div class="stat_content text-center">
						<h5>Earnings last week</h5>
						<p>€ {{$week['earnings']}}</p>
						<h5>Earnings last month</h5>
						<p>€ {{$month['earnings']}}</p>
					</div>
				</div>
				<div class="stat_box d-flex align-items-center justify-content-center">
					<div class="stat_icon d-flex align-items-center justify-content-center">
						<i class="fa fa-list-alt"></i>
					</div>
					<div class="stat_content text-center">
						<h5>Orders last week</h5>
						<p>{{$week['orders']}}</p>
						<h5>Orders last month</h5>
						<p>{{$month['orders']}}</p>
					</div>
				</div>
				<div class="stat_box d-flex align-items-center justify-content-center">
					<div class="stat_icon d-flex align-items-center justify-content-center">
						<i class="fa fa-th-large"></i>
					</div>
					<div class="stat_content text-center">
						<h5>Properties last week</h5>
						<p>{{$week['properties']}}</p>
						<h5>Properties last month</h5>
						<p>{{$month['properties']}}</p>
					</div>
				</div>
				<div class="stat_box d-flex align-items-center justify-content-center">
					<div class="stat_icon d-flex align-items-center justify-content-center">
						<i class="fa fa-search"></i>
					</div>
					<div class="stat_content text-center">
						<h5>Ads last week</h5>
						<p>{{$week['wanteds']}}</p>
						<h5>Ads last month</h5>
						<p>{{$month['wanteds']}}</p>
					</div>
				</div>
			</div>
			<div class="last_orders prop_table">
				<div class="card">
					<div class="card-header">Last 3 Orders <span><a href="/back-office/orders">>> show all</a></span></div>
					<div class="card-body">
						<table class="table table-responsive">
						<thead>
							<tr>
								<th>ID</th>
								<th>User name</th>
								<th>Property address</th>
								<th>Ad address</th>
								<th>Order type</th>
								<th>Order status</th>
								<th>Order amount</th>
								<th>Created at</th>
								<th>Delete?</th>
							</tr>
						</thead>
						@foreach($orders as $order)
						<tr>
							<td><a href="javascript:void(0);">{{$order->id}}</a></td>
							<td><a href="javascript:void(0);">{{$order->user->name.' '.$order->user->family_name}}</a></td>
							<td><a href="javascript:void(0);">@isset($order->property_id) {{$order->property->address}} @else - @endisset</a></td>
							<td><a href="javascript:void(0);">@isset($order->wanted_id) {{$order->wanted->location}} @else - @endisset</a></td>
							<td><a href="javascript:void(0);">{{$order->type}}</a></td>
							<td><a href="javascript:void(0);">{{$order->status}}</a></td>
							<td><a href="javascript:void(0);">€ {{$order->amount}}</a></td>
							<td><a href="javascript:void(0);">{{$order->created_at}}</a></td>
							<td><form action="/orders/{{$order->id}}" method="POST">
								{!! csrf_field() !!}
								<input type="hidden" name="_method" value="DELETE">
								<button class="btn btn-delete btn-danger"><i class="fa fa-close"></i></button>
							</form></td>
						</tr>
							@endforeach
						</table>
					</div>
				</div>
			</div>
			<div class="last_users prop_table">
				<div class="card">
					<div class="card-header">Last 3 Users <span><a href="/back-office/profiles">>> show all</a></span></div>
					<div class="card-body">
						<table class="table table-responsive">
							<thead>
								<tr>
									<th>ID</th>
									<th>First Name</th>
									<th>Family Name</th>
									<th>Email</th>
									<th>Phone</th>
									<th>Properties</th>
									<th>Msg remaining</th>
									<th>Amount spent</th>
									<th>Member since</th>
									<th>Verified?</th>
									<th>Delete?</th>
								</tr>
							</thead>
							@foreach($users as $user)
							<tr>
								<td><a href="/back-office/profiles/{{$user->id}}">{{$user->id}}</a></td>
								<td><a href="/back-office/profiles/{{$user->id}}">{{$user->name}}</a></td>
								<td><a href="/back-office/profiles/{{$user->id}}">{{$user->family_name}}</a></td>
								<td><a href="/back-office/profiles/{{$user->id}}">{{$user->email}}</a></td>
								<td><a href="/back-office/profiles/{{$user->id}}">{{$user->phone}}</a></td>
								<td><a href="/back-office/profiles/{{$user->id}}">{{$user->properties()->count()}}</a></td>
								<td><a href="/back-office/profiles/{{$user->id}}">{{$user->msg_in_remain}}</a></td>
								<td><a href="/back-office/profiles/{{$user->id}}">€ {{$user->orders()->sum('amount')}}</a></td>
								<td><a href="/back-office/profiles/{{$user->id}}">{{$user->created_at}}</a></td>
								<td><a href="/back-office/profiles/{{$user->id}}">@if($user->verified==1) Yes @else No @endif</a></td>
								<td><form action="/profiles/{{$user->id}}" method="POST">
									{!! csrf_field() !!}
									<input type="hidden" name="_method" value="DELETE">
									<button class="btn btn-delete btn-danger"><i class="fa fa-close"></i></button>
								</form></td>
							</tr>
							@endforeach
						</table>
					</div>
				</div>
			</div>
			<div class="last_properties prop_table">
				<div class="card">
					<div class="card-header">Last 3 Properties <span><a href="/back-office/properties">>> show all</a></span></div>
					<div class="card-body">
						<table class="table table-responsive">
							<thead>
								<tr>
									<th>ID</th>
									<th>Published by</th>
									<th>User type</th>
									<th>Address</th>
									<th>Town</th>
									<th>Status</th>
									<th>Early access?</th>
									<th>On top until</th>
									<th>Created at</th>
									<th>Delete?</th>
								</tr>
							</thead>
							@foreach($properties as $property)
							<tr>
								<td><a href="/back-office/properties/{{$property->id}}">{{$property->id}}</a></td>
								<td><a href="/back-office/properties/{{$property->id}}">{{$property->user->name.' '.$property->user->family_name}}</a></td>
								<td><a href="/back-office/properties/{{$property->id}}">{{$property->user_type}}</a></td>
								<td><a href="/back-office/properties/{{$property->id}}">{{$property->address}}</a></td>
								<td><a href="/back-office/properties/{{$property->id}}">{{$property->town}}</a></td>
								<td><a href="/back-office/properties/{{$property->id}}">{{$property->status}}</a></td>
								<td><a href="/back-office/properties/{{$property->id}}">@if($property->early_access==1) Yes @else No @endif</a></td>
								<td><a href="/back-office/properties/{{$property->id}}">{{$property->on_top_until}}</a></td>
								<td><a href="/back-office/properties/{{$property->id}}">{{$property->created_at}}</a></td>
								<td><form action="/properties/{{$property->id}}" method="POST">
									{!! csrf_field() !!}
									<input type="hidden" name="_method" value="DELETE">
									<button class="btn btn-delete btn-danger"><i class="fa fa-close"></i></button>
								</form></td>
							</tr>
							@endforeach
						</table>
					</div>
				</div>
			</div>
			<div class="last_wanteds prop_table">				
				<div class="card">
					<div class="card-header">Last 3 Ads <span><a href="/back-office/wanteds">>> show all</a></span></div>
					<div class="card-body">
						<table class="table table-responsive">
							<thead>
								<tr>
									<td>ID</td>
									<td>Published by</td>
									<td>Address</td>
									<td>Status</td>
									<td>Early access?</td>
									<td>On top until</td>
									<td>Created at</td>
									<td>Delete?</td>
								</tr>
							</thead>
							@foreach($wanteds as $wanted)
							<tr>
								<td><a href="/back-office/wanteds/{{$wanted->id}}">{{$wanted->id}}</a></td>
								<td><a href="/back-office/wanteds/{{$wanted->id}}">{{$wanted->user->name.' '.$wanted->user->family_name}}</a></td>
								<td><a href="/back-office/wanteds/{{$wanted->id}}">{{$wanted->location}}</a></td>
								<td><a href="/back-office/wanteds/{{$wanted->id}}">{{$wanted->status}}</a></td>
								<td><a href="/back-office/wanteds/{{$wanted->id}}">@if($wanted->early_access==1) Yes @else No @endif</a></td>
								<td><a href="/back-office/wanteds/{{$wanted->id}}">{{$wanted->on_top_until}}</a></td>
								<td><a href="/back-office/wanteds/{{$wanted->id}}">{{$wanted->created_at}}</a></td>
								<td><form action="/wanteds/{{$wanted->id}}" method="POST">
									{!! csrf_field() !!}
									<input type="hidden" name="_method" value="DELETE">
									<button class="btn btn-delete btn-danger"><i class="fa fa-close"></i></button>
								</form></td>
							</tr>
							@endforeach
						</table>
					</div>
				</div>
			</div>
		</div>
	</main>

@endsection 
