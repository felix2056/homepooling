@extends('layouts.adminNew')

@section('content')
<div class="content container">
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
                                            <th>Avatar</th>
                                            <th>Activity</th>
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
                                    <tbody>
                                        @foreach($users as $user)
											<tr>
												<td>
													<div class="round-img" style="position: relative;">
                                                        <a href="/back-office/profiles/{{$user->id}}">
                                                            <img class="rounded-circle" src="{{ isset( $user->photo ) ? $user->photo : '/storage/img/profile_placeholder.png' }}" alt=""></a>
                                                            @if($user->isOnline())
                                                                <span class="text-success" style="position: absolute;right: 21px;bottom: 0px;background: #008000eb;width: 20px;height: 20px;border-radius: 100%;border: 2px solid white;z-index: 1;"></span>
                                                                @else
                                                                    <span class="text-success" style="position: absolute;right: 21px;bottom: 0px;background: #868e96;width: 20px;height: 20px;border-radius: 100%;border: 2px solid white;z-index: 1;"></span>
                                                                @endif
                                                    </div>
												</td>
												<td class="text-center">
													@if($user->isOnline())
													    <li class="text-success">Online</li>
													@else
														<li class="text-muted">Offline</li>
													@endif
												</td>
												<td><a href="/back-office/profiles/{{$user->id}}">{{$user->name}}</a></td>
												<td>{{$user->family_name}}</td>
												<td>{{$user->email}}</td>
												<td>{{$user->phone}}</td>
												<td>{{$user->properties()->count()}}</td>
												<td>{{$user->msg_in_remain}}</td>
												<td>€{{$user->orders()->sum('amount')}}</td>
												<td>{{$user->created_at}}</td>
												<td>@if($user->verified==1) Yes @else No @endif </td>
												<td><form action="/profiles/{{$user->id}}" method="POST">
													{!! csrf_field() !!}
													<input type="hidden" name="_method" value="DELETE">
													<button class="btn btn-delete btn-danger"><i class="fa fa-close"></i></button>
												</form></td>
											</tr>
										@endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        {{$users->links()}}
                    </div>
                </div>
            </div><!-- .animated -->
        </div><!-- .content -->
	<!--<main id="content" class="dashboard">
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
	</main>-->

@endsection 
 
