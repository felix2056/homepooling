@extends('layouts.adminNew')

@section('content')
	<main id="content" class="dashboard">
		<div class="container">
			<h1>Manage Properties</h1>
			<div class="last_properties prop_table">
				<div class="card">
					<div class="card-body p-0">
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
								<td><a href="/back-office/profiles/{{$property->id}}">@if($property->early_access==1) Yes @else No @endif</a></td>
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
				{{$properties->links()}}
			</div>
		</div>
	</main>

@endsection 
 
