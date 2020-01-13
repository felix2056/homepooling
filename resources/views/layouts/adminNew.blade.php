<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang=""> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Homepooling | Administrator</title>
    <meta name="description" content=">Homepooling | Administrator">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!--<link rel="apple-touch-icon" href="https://i.imgur.com/QRAUqs9.png">
    <link rel="shortcut icon" href="https://i.imgur.com/QRAUqs9.png">-->

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/normalize.css@8.0.0/normalize.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lykmapipo/themify-icons@0.1.2/css/themify-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pixeden-stroke-7-icon@1.2.3/pe-icon-7-stroke/dist/pe-icon-7-stroke.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.2.0/css/flag-icon.min.css">

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.css">

    <link rel="stylesheet" href="{{ asset('adminTemplate/css/cs-skin-elastic.css') }}">
    <link rel="stylesheet" href="{{ asset('adminTemplate/css/style.css') }}">

    <link rel="stylesheet" href="{{ asset('adminTemplate/css/site-dashboard.css') }}">

    <!-- vendor libraries CSS -->
    <link href="{{ asset('adminTemplate/css/vendor.css') }}" rel="stylesheet" type="text/css">
    <!-- theme CSS -->
    <link href="{{ asset('adminTemplate/css/theme.css') }}" rel="stylesheet" type="text/css">
    <!-- <script type="text/javascript" src="https://cdn.jsdelivr.net/html5shiv/3.7.3/html5shiv.min.js"></script> -->
    <link href="https://cdn.jsdelivr.net/npm/chartist@0.11.0/dist/chartist.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/jqvmap@1.5.1/dist/jqvmap.min.css" rel="stylesheet">

   <style>
    #weatherWidget .currentDesc {
        color: #ffffff!important;
    }
        .traffic-chart {
            min-height: 335px;
        }
        #flotPie1  {
            height: 150px;
        }
        #flotPie1 td {
            padding:3px;
        }
        #flotPie1 table {
            top: 20px!important;
            right: -10px!important;
        }
        .chart-container {
            display: table;
            min-width: 270px ;
            text-align: left;
            padding-top: 10px;
            padding-bottom: 10px;
        }
        #flotLine5  {
             height: 105px;
        }

        #flotBarChart {
            height: 150px;
        }
        #cellPaiChart{
            height: 160px;
        }

    </style>
</head>

<body>
    <!-- Left Panel -->
    <aside id="left-panel" class="left-panel">
        <nav class="navbar navbar-expand-sm navbar-default">
            <div id="main-menu" class="main-menu collapse navbar-collapse">
                <ul class="nav navbar-nav">
                    <li class="active">
                        <a href="{{ url('/back-office') }}"><i class="menu-icon fa fa-desktop"></i>Dashboard </a>
                    </li>
                    <li class="menu-title">Pages</li><!-- /.menu-title -->
                    <li class="menu-item">
                        <a href="{{ route('admin.users') }}" aria-haspopup="true" aria-expanded="false">
                            <i class="menu-icon fa fa-users"></i>Users
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('admin.properties') }}" aria-haspopup="true" aria-expanded="false"> <i class="menu-icon fa fa-building-o"></i>Properties</a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('admin.orders') }}" aria-haspopup="true" aria-expanded="false">
                            <i class="menu-icon fa fa-shopping-cart"></i>Orders</a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('admin.reports') }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="menu-icon fa fa-bar-chart"></i>Reports</a>
                    </li>

                    <li class="menu-title">Settings</li><!-- /.menu-title -->
                    <li class="menu-item-has-children dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="menu-icon fa fa-cogs"></i>Site</a>
                        <ul class="sub-menu children dropdown-menu">
                            <li>
                                <i class="fa fa-cogs"></i>
                                <a href="{{ route('admin.settings') }}">General Settings</a>
                            </li>
                            <li>
                                <i class="fa fa-id-badge"></i>
                                <a href="{{ route('admin.gallery') }}">Gallery</a>
                            </li>
                            <li>
                                <i class="fa fa-id-badge"></i>
                                <a href="{{ route('slider.index') }}">Slider</a>
                            </li>
                            <li>
                                <i class="fa fa-info-circle"></i>
                                <a href="{{ route('admin.terms') }}">Terms & Conditions</a>
                            </li>
                            <li>
                                <i class="fa fa-clock-o"></i>
                                <a href="{{ route('admin.timeline') }}">Timeline</a>
                            </li>
                            <li>
                                <i class="fa fa-map-marker"></i>
                                <a href="{{ route('admin.contact_us') }}">Contact Us</a>
                            </li>
                        </ul>
                    </li>


                    <li class="menu-title">Extras</li><!-- /.menu-title -->

                    <li class="menu-item">
                        <a href="{{ route('admin.analytics') }}" aria-haspopup="true" aria-expanded="false"> 
                            <i class="menu-icon fa fa-google"></i>Google Analytics
                        </a>
                    </li>
                </ul>
            </div><!-- /.navbar-collapse -->
        </nav>
    </aside>
    <!-- /#left-panel -->
    <!-- Right Panel -->
    <div id="right-panel" class="right-panel">
        <!-- Header-->
        <header id="header" class="header">
            <div class="top-left">
                <div class="navbar-header">
                    <a class="navbar-brand" href="{{ url('/back-office') }}"><img src="{{ asset('storage/img/logo.png') }}" alt="Homepooling Logo"></a>
                    <a class="navbar-brand hidden" href="./"><img src="{{ asset('storage/img/logo.png') }}" alt="Logo"></a>
                    <a id="menuToggle" class="menutoggle"><i class="fa fa-bars"></i></a>
                </div>
            </div>
            <div class="top-right">
                <div class="header-menu">
                    <div class="user-area dropdown float-right">
                        <a href="#" class="dropdown-toggle active" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img class="user-avatar rounded-circle" src="{{ isset( Auth::user()->photo ) ? Auth::user()->photo : '/storage/img/profile_placeholder.png' }}" alt="User Avatar">
                        </a>

                        <div class="user-menu dropdown-menu">
                            <a class="nav-link" href="{{ route('admin.settings') }}"><i class="fa fa -cog"></i>Settings</a>

                            <a class="nav-link" href="{{ route('logout') }}"><i class="fa fa-power -off"></i>Logout</a>
                        </div>
                    </div>

                </div>
            </div>
        </header>
        <!-- /#header -->
        <!-- Content -->
        <div class="content">
            <!-- Message -->
            @if (Session::has('success') || Session::has('error') || Session::has('warning'))
                <div class="alert custom_alert @if (Session::has('success')) alert-success @elseif(Session::has('error')) alert-danger @else alert-warning @endif alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    @if (Session::has('success'))
                        <h5><i class="icon fa fa-check"></i>{!! Session::get('success') !!} </h5>
                    @elseif(Session::has('error'))
                        <h5><i class="icon fa fa-ban"></i>{!! Session::get('error') !!} </h5>
                    @else
                        <h5><i class="icon fa fa-warning"></i>{!!  Session::get('warning') !!} </h5>
                        @endif
                        </h5>
                </div>
            @endif
            @if (Session::has('message'))
                <div class="alert  alert-success keepIt">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h5 style="font-weight: bold; font-size: large;"><i class="icon fa fa-check"></i>{!! Session::get('message') !!} </h5>
                </div>
            @endif

            <!-- Animated -->
            @yield('content')
            <!-- .animated -->
        </div>
        <!-- /.content -->
        <div class="clearfix"></div>
        <!-- Footer -->
        <footer class="site-footer">
            <div class="footer-inner bg-white">
                <div class="row">
                    <div class="col-sm-6">
                        Copyright &copy; 2018 Ela Admin
                    </div>
                    <div class="col-sm-6 text-right">
                        Designed by <a href="https://colorlib.com">Colorlib</a>
                    </div>
                </div>
            </div>
        </footer>
        <!-- /.site-footer -->
    </div>
    <!-- /#right-panel -->

    
    <!-- Scripts -->
    <script src="{{ asset('adminTemplate/js/jquery.min.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/jquery@2.2.4/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.4/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-match-height@0.7.2/dist/jquery.matchHeight.min.js"></script>
    <script src="{{ asset('adminTemplate/js/main.js') }}"></script>
    <script src="{{ asset('adminTemplate/js/dashboard.js') }}"></script>
    <script src="{{ asset('adminTemplate/js/widgets.js') }}"></script>
    <script src="{{ asset('adminTemplate/js/dropzone.js') }}"></script>
    <script src="{{ asset('adminTemplate/js/vendor.js') }}"></script>

    <!--  Chart js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.7.3/dist/Chart.bundle.min.js"></script>

    <!--Chartist Chart-->
    <script src="https://cdn.jsdelivr.net/npm/chartist@0.11.0/dist/chartist.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartist-plugin-legend@0.6.2/chartist-plugin-legend.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/jquery.flot@0.8.3/jquery.flot.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flot-pie@1.0.0/src/jquery.flot.pie.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flot-spline@0.0.1/js/jquery.flot.spline.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/simpleweather@3.1.0/jquery.simpleWeather.min.js"></script>
    <script src="assets/js/init/weather-init.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/moment@2.22.2/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@3.9.0/dist/fullcalendar.min.js"></script>
    <script src="assets/js/init/fullcalendar-init.js"></script>

    <!-- Data Tables -->
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.js"></script>
    <script>
        jQuery(document).ready( function () {
            jQuery('#data-table').DataTable();
        });

        jQuery(document).ready( function () {
            jQuery('#user-table').DataTable();
        });

        jQuery(document).ready( function () {
            jQuery('#order-table').DataTable();
        });
    </script>

<!--Chart script only for home.blade.php-->
@yield("chartScript")

<!-- Extra js from child page -->
@yield("extraScript")
<!-- END JAVASCRIPT -->
</body>
</html>
