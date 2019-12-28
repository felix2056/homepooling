@extends('layouts.adminNew')

@section('content')
<div class="content">
    <div class="animated fadeIn">
    	<div class="row">
    		<div class="col-md-12">
    			<div class="card">
    				<div class="card-header">
    					<strong class="card-title">Data Table</strong>
    				</div>
    				<div class="card-body">
    					<table id="data-table" class="table table-striped table-bordered">
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
    						<tbody>
    							@foreach($orders as $order)
									<tr>
										<td>{{$order->id}}</td>
										<td>{{$order->user->name.' '.$order->user->family_name}}</td>
										<td>Adrss</td>
										<td>@isset($order->wanted_id) {{$order->wanted->location}} @else - @endisset</td>
										<td>{{$order->type}}</td>
										<td>{{$order->status}}</td>
										<td>€{{$order->amount}}</td>
										<td>{{$order->created_at}}</td>
										<td>
											<form action="/orders/{{$order->id}}" method="POST">
												{!! csrf_field() !!}
												<input type="hidden" name="_method" value="DELETE">
												<button class="btn btn-delete btn-danger">
													<i class="fa fa-close"></i>
												</button>
											</form>
										</td>
									</tr>
								@endforeach
    						</tbody>
    					</table>
    				</div>
    			</div>
    			{{$orders->links()}}
    		</div>
    	</div>
    </div><!-- .animated -->
</div><!-- .content -->
@endsection 
 
