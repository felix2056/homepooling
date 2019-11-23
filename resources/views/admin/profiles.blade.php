@extends('layouts.adminNew')

@section('content')
	<main id="content" class="dashboard">
		<div class="container">
			<h1>Manage Users</h1>
			<div class="last_users prop_table">
				<div class="card">
					<div class="card-body p-0">
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
								<td><a href="/back-office/profiles/{{$user->id}}">€{{$user->orders()->sum('amount')}}</a></td>
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
				{{$users->links()}}
			</div>
		</div>
	</main>

@endsection 
 
