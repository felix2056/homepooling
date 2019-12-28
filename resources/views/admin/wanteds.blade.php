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
                                <table id="bootstrap-data-table" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
											<th>Published by</th>
											<th>Address</th>
											<th>Status</th>
											<th>Early access?</th>
											<th>On top until</th>
											<th>Created at</th>
											<th>Delete?</th>
                                        </tr>
                                    </thead>
                                    <tbody>
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
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        {{$wanteds->links()}}ll
                    </div>
                </div>
            </div><!-- .animated -->
        </div><!-- .content -->
	<!--<main id="content" class="dashboard">
		<div class="container">
			<h1>Manage Ads</h1>
			<div class="last_wanteds prop_table">
				<div class="card">
					<div class="card-body p-0">
						<table class="table table-responsive">
							<thead>
								<tr>
									<th>ID</th>
									<th>Published by</th>
									<th>Address</th>
									<th>Status</th>
									<th>Early access?</th>
									<th>On top until</th>
									<th>Created at</th>
									<th>Delete?</th>
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
				{{$wanteds->links()}}
			</div>
		</div>
	</main>-->

@endsection