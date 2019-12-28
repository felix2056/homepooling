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
											<th>Author</th>
											<th>Created at</th>
											<th>Delete?</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($reports as $report)
											<tr>
												<td><a href="@isset($report->property_id) /properties/{{$report->property_id}} @else /profiles/{{$report->user_id}} @endisset">{{$report->id}}</a></td>
												<td><a href="@isset($report->property_id) /properties/{{$report->property_id}} @else /profiles/{{$report->user_id}} @endisset">@isset($report->user_id) {{$report->user->name.' '.$report->user->family_name}} @else - @endisset</a></td>
												<td><a href="@isset($report->property_id) /properties/{{$report->property_id}} @else /profiles/{{$report->user_id}} @endisset">@isset($report->property_id) {{$report->property->address.', '.$report->property->town}} @else - @endisset</a></td>
												<td><a href="@isset($report->property_id) /properties/{{$report->property_id}} @else /profiles/{{$report->user_id}} @endisset">{{$report->author->name.' '.$report->author->family_name}}</a></td>
												<td><a href="@isset($report->property_id) /properties/{{$report->property_id}} @else /profiles/{{$report->user_id}} @endisset">{{$report->created_at}}</a></td>
												<td><form action="/back-office/reports/{{$report->id}}" method="POST">
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
                        {{$reports->links()}}
                    </div>
                </div>
            </div><!-- .animated -->
        </div><!-- .content -->
	<!--<main id="content" class="dashboard">
		<div class="container">
			<h1>Manage Reports</h1>
			<div class="last_orders prop_table">
				<div class="card">
					<div class="card-body">
						<table class="table table-responsive">
							<thead>
								<tr>
									<th>ID</th>
									<th>User name</th>
									<th>Property address</th>
									<th>Author</th>
									<th>Created at</th>
									<th>Delete?</th>
								</tr>
							</thead>
							@foreach($reports as $report)
							<tr>
								<td><a href="@isset($report->property_id) /properties/{{$report->property_id}} @else /profiles/{{$report->user_id}} @endisset">{{$report->id}}</a></td>
								<td><a href="@isset($report->property_id) /properties/{{$report->property_id}} @else /profiles/{{$report->user_id}} @endisset">@isset($report->user_id) {{$report->user->name.' '.$report->user->family_name}} @else - @endisset</a></td>
								<td><a href="@isset($report->property_id) /properties/{{$report->property_id}} @else /profiles/{{$report->user_id}} @endisset">@isset($report->property_id) {{$report->property->address.', '.$report->property->town}} @else - @endisset</a></td>
								<td><a href="@isset($report->property_id) /properties/{{$report->property_id}} @else /profiles/{{$report->user_id}} @endisset">{{$report->author->name.' '.$report->author->family_name}}</a></td>
								<td><a href="@isset($report->property_id) /properties/{{$report->property_id}} @else /profiles/{{$report->user_id}} @endisset">{{$report->created_at}}</a></td>
								<td><form action="/back-office/reports/{{$report->id}}" method="POST">
									{!! csrf_field() !!}
									<input type="hidden" name="_method" value="DELETE">
									<button class="btn btn-delete btn-danger"><i class="fa fa-close"></i></button>
								</form></td>
							</tr>
							@endforeach
						</table>
					</div>
				</div>
				{{$reports->links()}}
			</div>
		</div>
	</main>-->
@endsection 
 
