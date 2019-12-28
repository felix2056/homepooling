@extends('layouts.adminNew')

@section('content')
<div class="animated fadeIn">
                <!-- Widgets  -->
                <div class="row">
                    <div class="col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="stat-widget-five">
                                    <div class="stat-icon dib flat-color-1">
                                        <i class="pe-7s-cash"></i>
                                    </div>
                                    <div class="stat-content">
                                        <div class="text-left dib">
                                            <div class="stat-text">$<span class="count">{{ $data['earnings'] }}</span></div>
                                            <div class="stat-heading">Earnings</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="stat-widget-five">
                                    <div class="stat-icon dib flat-color-2">
                                        <i class="pe-7s-cart"></i>
                                    </div>
                                    <div class="stat-content">
                                        <div class="text-left dib">
                                            <div class="stat-text"><span class="count">{{ $data['orders'] }}</span></div>
                                            <div class="stat-heading">Orders</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="stat-widget-five">
                                    <div class="stat-icon dib flat-color-3">
                                        <i class="pe-7s-browser"></i>
                                    </div>
                                    <div class="stat-content">
                                        <div class="text-left dib">
                                            <div class="stat-text"><span class="count">{{ $data['properties'] }}</span></div>
                                            <div class="stat-heading">Properties</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="stat-widget-five">
                                    <div class="stat-icon dib flat-color-4">
                                        <i class="pe-7s-users"></i>
                                    </div>
                                    <div class="stat-content">
                                        <div class="text-left dib">
                                            <div class="stat-text"><span class="count">{{ $data['users'] }}</span> </div>
                                            <div class="stat-heading">Users</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /Widgets -->
                
                <!--  Traffic  -->
                <div class="row">
                    <!-- Left col -->
                    <div class="col-md-12">
                        <!-- MAP & BOX PANE -->
                        <div class="box box-success">
                            <div class="box-header with-border">
                                <h3 class="box-title">Google Analytics Report</h3>
                                <div class="box-tools pull-right">
                                    <div id="active-users-container"></div>
                                </div>
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body">
                                @if($googleToken['token'])
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="Chartjs">
                                            <header class="Titles">
                                                <h1 class="Titles-main">This Week vs Last Week</h1>
                                                <div class="Titles-sub">By sessions</div>
                                            </header>
                                            <figure class="Chartjs-figure" id="chart-1-container"></figure>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="Chartjs">
                                            <header class="Titles">
                                                <h1 class="Titles-main">This Year vs Last Year</h1>
                                                <div class="Titles-sub">By users</div>
                                            </header>
                                            <figure class="Chartjs-figure" id="chart-2-container"></figure>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="Chartjs">
                                            <header class="Titles">
                                                <h1 class="Titles-main">Top Browsers</h1>
                                                <div class="Titles-sub">By pageview</div>
                                            </header>
                                            <figure class="Chartjs-figure" id="chart-3-container"></figure>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="Chartjs">
                                            <header class="Titles">
                                                <h1 class="Titles-main">Top Countries This Year</h1>
                                                <div class="Titles-sub">By user</div>
                                            </header>
                                            <figure class="Chartjs-figure" id="chart-4-container"></figure>
                                        </div>
                                    </div>
                                </div>
                                    @else
                                    <div class="alert alert-danger keepIt">
                                        <h3>{{$googleToken['message']}}</h3>
                                    </div>
                                @endif
                            </div>
                            <!-- /.box-body -->
                        </div>
                    </div>
                </div>
                <!--  /Traffic -->
                <div class="clearfix"></div>

                <!-- Users And Orders -->
                <div class="orders">
                    <div class="row">
                        <div class="col-xl-8">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="box-title">Users</h4>
                                </div>
                                <div class="card-body--" style="padding: 0.25em">
                                    <div class="table-stats order-table ov-h">
                                        <table class="table" id="user-table">
                                            <thead>
                                                <tr>
                                                    <th class="avatar">Avatar</th>
                                                    <th>Name</th>
                                                    <th>Email</th>
                                                    <th>Verified?</th>
                                                    <th>Delete?</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($users as $user)
                                                <tr>
                                                    <td class="avatar">
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
                                                    <td>
                                                        <a href="/back-office/profiles/{{$user->id}}">
                                                            <span class="name">{{ $user->name .' '. $user->family_name }}</span>
                                                        </a>
                                                    </td>
                                                    <td>
                                                        <span class="product">{{ $user->email }}</span>
                                                    </td>
                                                    <td>
                                                        @if($user->verified==1)
                                                        <span class="badge badge-complete">Verified</span>
                                                        @else
                                                        <span class="badge badge-pending">Pending</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <form action="/profiles/{{$user->id}}" method="POST">
                                                            {!! csrf_field() !!}
                                                            <input type="hidden" name="_method" value="DELETE">
                                                            <button class="btn btn-delete btn-danger"><i class="fa fa-close"></i></button>
                                                        </form>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div> <!-- /.table-stats -->
                                </div>
                            </div> <!-- /.users -->
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="box-title">Orders</h4>
                                </div>
                                <div class="card-body--">
                                    <div class="table-stats order-table ov-h">
                                        <table class="table" id="order-table">
                                            <thead>
                                                <tr>
                                                    <th class="serial">#</th>
                                                    <th>User name</th>
                                                    <th>Order type</th>
                                                    <th>Order amount</th>
                                                    <th>Delete?</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($orders as $order)
                                                <tr>
                                                    <td class="serial">{{ $loop->count }}</td>
                                                    <td>
                                                        <span class="name">{{$order->user->name.' '.$order->user->family_name}}</span>
                                                    </td>
                                                    <td>
                                                        <span class="product">{{$order->type}}</span>
                                                    </td>
                                                    <td>
                                                        <span class="count">€{{$order->amount}}</span>
                                                    </td>
                                                    <td>
                                                        <form action="/orders/{{$order->id}}" method="POST">
                                                            {!! csrf_field() !!}
                                                            <input type="hidden" name="_method" value="DELETE">
                                                            <button class="btn btn-delete btn-danger"><i class="fa fa-close"></i></button>
                                                        </form>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div> <!-- /.table-stats -->
                                </div>
                            </div> <!-- /.orders -->
                        </div>  <!-- /.col-lg-8 -->

                        <div class="col-xl-4">
                            <div class="row">
                                <div class="col-lg-6 col-xl-12">
                                    <div class="card br-0">
                                        <div class="card-body">
                                            <div class="chart-container ov-h">
                                                <div id="flotPie1" class="float-chart"></div>
                                            </div>
                                        </div>
                                    </div><!-- /.card -->
                                </div>

                                <div class="col-lg-6 col-xl-12">
                                    <div class="card bg-flat-color-3  ">
                                        <div class="card-body">
                                            <h4 class="card-title m-0  white-color ">{{ date('F Y') }}</h4>
                                        </div>
                                         <div class="card-body">
                                             <div id="flotLine5" class="flot-line"></div>
                                         </div>
                                    </div>
                                </div>

                                <div class="col-lg-6 col-xl-12">
                                    <div class="card ov-h">
                                        <div class="card-body bg-flat-color-2">
                                            <div id="flotBarChart" class="float-chart ml-4 mr-4"></div>
                                        </div>
                                        <div id="cellPaiChart" class="float-chart"></div>
                                    </div><!-- /.card -->
                                </div>
                            </div>
                        </div> <!-- /.col-md-4 -->
                    </div>
                </div>
                <!-- /.orders -->
                
                <!-- Calender Chart Weather  -->
                <div class="row">

                    
                </div>
                <!-- /Calender Chart Weather -->
                <!-- Modal - Calendar - Add New Event -->
                <div class="modal fade none-border" id="event-modal">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <h4 class="modal-title"><strong>Add New Event</strong></h4>
                            </div>
                            <div class="modal-body"></div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-success save-event waves-effect waves-light">Create event</button>
                                <button type="button" class="btn btn-danger delete-event waves-effect waves-light" data-dismiss="modal">Delete</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /#event-modal -->
                <!-- Modal - Calendar - Add Category -->
                <div class="modal fade none-border" id="add-category">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <h4 class="modal-title"><strong>Add a category </strong></h4>
                            </div>
                            <div class="modal-body">
                                <form>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="control-label">Category Name</label>
                                            <input class="form-control form-white" placeholder="Enter name" type="text" name="category-name"/>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="control-label">Choose Category Color</label>
                                            <select class="form-control form-white" data-placeholder="Choose a color..." name="category-color">
                                                <option value="success">Success</option>
                                                <option value="danger">Danger</option>
                                                <option value="info">Info</option>
                                                <option value="pink">Pink</option>
                                                <option value="primary">Primary</option>
                                                <option value="warning">Warning</option>
                                            </select>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-danger waves-effect waves-light save-category" data-dismiss="modal">Save</button>
                            </div>
                        </div>
                    </div>
                </div>
            <!-- /#add-category -->
            </div>
@endsection 


@section('chartScript')
 <!--Website Info Chart Stuff-->
    <script>
        jQuery(document).ready(function($) {
            "use strict";

            // Pie chart flotPie1
            var piedata = [
                { label: "Desktop visits", data: [[1,32]], color: '#5c6bc0'},
                { label: "Tab visits", data: [[1,33]], color: '#ef5350'},
                { label: "Mobile visits", data: [[1,35]], color: '#66bb6a'}
            ];

            $.plot('#flotPie1', piedata, {
                series: {
                    pie: {
                        show: true,
                        radius: 1,
                        innerRadius: 0.65,
                        label: {
                            show: true,
                            radius: 2/3,
                            threshold: 1
                        },
                        stroke: {
                            width: 0
                        }
                    }
                },
                grid: {
                    hoverable: true,
                    clickable: true
                }
            });
            // Pie chart flotPie1  End

            // cellPaiChart
            const orderPercentage = "{{$data['orders']}}";
            const userPercentage = "{{$data['users']}}";

            var cellPaiChart = [
                { label: "Users", data: [[1,userPercentage]], color: '#5b83de'},
                { label: "Orders", data: [[1,orderPercentage]], color: '#00bfa5'}
            ];
            $.plot('#cellPaiChart', cellPaiChart, {
                series: {
                    pie: {
                        show: true,
                        stroke: {
                            width: 0
                        }
                    }
                },
                legend: {
                    show: false
                },grid: {
                    hoverable: true,
                    clickable: true
                }

            });
            // cellPaiChart End
            // Line Chart  #flotLine5
            var newCust = [[0, 3], [1, 5], [2,4], [3, 7], [4, 9], [5, 3], [6, 6], [7, 4], [8, 10]];

            var plot = $.plot($('#flotLine5'),[{
                data: newCust,
                label: 'New Data Flow',
                color: '#fff'
            }],
            {
                series: {
                    lines: {
                        show: true,
                        lineColor: '#fff',
                        lineWidth: 2
                    },
                    points: {
                        show: true,
                        fill: true,
                        fillColor: "#ffffff",
                        symbol: "circle",
                        radius: 3
                    },
                    shadowSize: 0
                },
                points: {
                    show: true,
                },
                legend: {
                    show: false
                },
                grid: {
                    show: false
                }
            });
            // Line Chart  #flotLine5 End
            
            // Bar Chart #flotBarChart
            $.plot("#flotBarChart", [{
                data: [[0, 18], [2, 8], [4, 5], [6, 13],[8,5], [10,7],[12,4], [14,6],[16,15], [18, 9],[20,17], [22,7],[24,4], [26,9],[28,11]],
                bars: {
                    show: true,
                    lineWidth: 0,
                    fillColor: '#ffffff8a'
                }
            }], {
                grid: {
                    show: false
                }
            });
            // Bar Chart #flotBarChart End
        });
    </script>
@endsection
<!-- BEGIN PAGE JS-->
@if($googleToken['token'])
@section('extraScript')
    <script>
        (function(w,d,s,g,js,fs){
            g=w.gapi||(w.gapi={});g.analytics={q:[],ready:function(f){this.q.push(f);}};
            js=d.createElement(s);fs=d.getElementsByTagName(s)[0];
            js.src='https://apis.google.com/js/platform.js';
            fs.parentNode.insertBefore(js,fs);js.onload=function(){g.load('analytics');};
        }(window,document,'script'));
    </script>
    <script src="{{ asset('adminTemplate/js/site-dashboard.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            window.gid = '{{$gaId}}';
            window.gtok = "{{$googleToken['token']}}";
            GoogleAnalytics.init();
        });
    </script>
@endsection
@endif
<!-- END PAGE JS-->
