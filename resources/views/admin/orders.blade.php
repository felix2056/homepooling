@extends('layouts.adminNew')

@section('content')
	<main id="content" class="dashboard">
		<div class="container">
			<h1>Manage Orders</h1>
			<div class="last_orders prop_table">
				<div class="card">
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
								<td>{{$order->id}}</td>
								<td>{{$order->user->name.' '.$order->user->family_name}}</td>
								<td>@isset($order->property_id) {{$order->property->address}} @else - @endisset</td>
								<td>@isset($order->wanted_id) {{$order->wanted->location}} @else - @endisset</td>
								<td>{{$order->type}}</td>
								<td>{{$order->status}}</td>
								<td>€{{$order->amount}}</td>
								<td>{{$order->created_at}}</td>
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
				{{$orders->links()}}
			</div>
		</div>
	</main>

@endsection 
 
