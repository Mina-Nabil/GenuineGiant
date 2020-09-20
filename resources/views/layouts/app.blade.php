<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ Session::token() }}">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/whaleLogo.png') }}">
    <title>{{  config('app.name', 'GenuineGiant') }}</title>
    <!-- Custom CSS -->
    <link href="{{asset('dist/css/style.min.css')}}" rel="stylesheet">
    <link href="{{asset('dist/css/fonts/cheltenham/style.css')}}" rel="stylesheet">
    <link href="{{asset('dist/css/style.min.css')}}" media=print rel="stylesheet">
    <!-- Datatable CSS -->
    <link href="{{ asset('assets/node_modules/datatables/media/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
    <!-- BT Switch -->
    <link href="{{ asset('dist/css/pages/bootstrap-switch.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/node_modules/bootstrap-switch/bootstrap-switch.min.css') }}" rel="stylesheet">
    <!-- Form CSS -->
    <link href="{{ asset('dist/css/pages/file-upload.css')}}" rel="stylesheet">
    <link href="{{ asset('assets/node_modules/dropify/dist/css/dropify.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/node_modules/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/node_modules/select2/dist/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/node_modules/toast-master/css/jquery.toast.css') }}" rel="stylesheet" type="text/css" />

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Oregano" />
    <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Oswald" />

    <!-- Sweet Alert Notification -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
    @yield("head_content")
    @stack('head')
</head>

<body class="skin-default fixed-layout">
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div class="preloader">
        <div class="loader">
            <div class="loader__figure"></div>
            <p class="loader__label">{{ config('app.name', 'Laravel') }}</p>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <div id="main-wrapper">
        <!-- ============================================================== -->
        <!-- Topbar header - style you can find in pages.scss -->
        <!-- ============================================================== -->
        <header class="topbar ">
            <nav class="navbar top-navbar navbar-expand-md navbar-dark">
                <!-- ============================================================== -->
                <!-- Logo -->
                <!-- ============================================================== -->
                <div class="navbar-header">
                    <a class="navbar-brand" href="{{route('home')}}">
                        <!-- Logo icon --><b>
                            <!--You can put here icon as well // <i class="wi wi-sunset"></i> //-->
                            <!-- Dark Logo icon -->
                            <img src="{{ asset('images/genuine.png') }}" height=70px alt="homepage" class="dark-logo" />
                            <!-- Light Logo icon -->
                            <img src="{{ asset('images/genuine.png') }}" height=70px alt="homepage" class="light-logo" />
                        </b>
                        <!--End Logo icon -->
                        <!-- Logo text --><span>

                            <h4 class="text-themecolor"
                                style="text-align: center; top: 60%;font-family: 'ITC Cheltenham Std Bold Condensed' ; transform: translateY(-50%); font-size:25px ; display: inline;   position: absolute;  color:#059248"
                                class="dark-text"> Dashboard</h4>


                            <!-- dark Logo text -->
                            {{-- <img src="{{ asset('images/dark-text.png') }}" height=40px alt="homepage" class="dark-logo" /> --}}
                            <!-- Light Logo text -->
                            {{-- <img src="{{ asset('images/light-text.png') }}" height=40px class="light-logo" alt="homepage" /> --}}
                        </span> </a>
                </div>
                <!-- ============================================================== -->
                <!-- End Logo -->
                <!-- ============================================================== -->
                <div class="navbar-collapse">
                    <!-- ============================================================== -->
                    <!-- toggle and nav items -->
                    <!-- ============================================================== -->
                    <ul class="navbar-nav mr-auto">
                        <!-- This is  -->
                        <li class="nav-item"> <a class="nav-link nav-toggler d-block d-md-none waves-effect waves-dark" href="javascript:void(0)"><i class="ti-menu"></i></a> </li>
                        <li class="nav-item"> <a class="nav-link sidebartoggler d-none d-lg-block d-md-block waves-effect waves-dark" href="javascript:void(0)"><i class="icon-menu"></i></a> </li>
                        <!-- ============================================================== -->
                        <!-- Search -->
                        <!-- ============================================================== -->
                        <li class="nav-item">
                            <form class="app-search d-none d-md-block d-lg-block">
                                <input type="text" class="form-control" placeholder="Search & enter">
                            </form>
                        </li>
                    </ul>
                    <!-- ============================================================== -->
                    <!-- User profile and search -->
                    <!-- ============================================================== -->
                    <ul class="navbar-nav my-lg-0">
                        <!-- ============================================================== -->
                        <!-- Comment -->
                        <!-- ============================================================== -->
                        <li class="nav-item right-side-toggle"> <a class="nav-link  waves-effect waves-light" href="javascript:void(0)"><i class="ti-settings"></i></a></li>
                    </ul>
                </div>
            </nav>
        </header>
        <!-- ============================================================== -->
        <!-- End Topbar header -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <aside class="left-sidebar">
            <!-- Sidebar scroll-->
            <div class="scroll-sidebar">
                <!-- User Profile-->
                <div class="user-profile">
                    <div class="user-pro-body">
                        @if(isset(Auth::user()->DASH_IMGE))
                        <div><img src="{{ asset( 'storage/'. Auth::user()->DASH_IMGE ) }} " alt="user-img" class="img-circle"></div>
                        @else
                        <div><img src="{{ asset('assets/images/users/def-user.png') }} " alt="user-img" class="img-circle"></div>
                        @endif
                        <div class="dropdown">
                            <a href="javascript:void(0)" class="dropdown-toggle u-dropdown link hide-menu" data-toggle="dropdown" role="button" aria-haspopup="true"
                                aria-expanded="false">{{ Auth::user()->DASH_USNM }} <span class="caret"></span></a>
                            <div class="dropdown-menu animated flipInY">
                                <!-- text-->
                                <a href="{{route('logout')}}" class="dropdown-item"><i class="fa fa-power-off"></i>
                                    Logout</a>
                                <!-- text-->
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Sidebar navigation-->
                <nav class="sidebar-nav">
                    <ul id="sidebarnav">
                        <li> <a class="has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="fas fa-cart-plus"></i><span class="hide-menu">Orders</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li><a href="{{url('orders/active')}}">Active Orders</a></li>
                                <li><a href="{{url('orders/month')}}">Current Month</a></li>
                                <li><a href="{{url('orders/load/history')}}">History</a></li>
                                <li><a href="{{url('orders/add')}}">Place an Order</a></li>
                            </ul>
                        </li>

                        <li>
                            <a class="has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="fas fa-boxes"></i><span class="hide-menu"> Inventory</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li><a href="{{url('inventory/current/stock')}}">Current Stock List</a></li>
                                <li><a href="{{url('inventory/entry/new')}}">Add New Stock</a></li>
                                <li><a href="{{url('inventory/transactions')}}">Transactions</a></li>
                            </ul>
                        </li>

                        <li> <a class="has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="icon-people"></i><span class="hide-menu"> Clients</span></a>
                            <ul aria-expanded="false" class="collapse">

                                <li><a href="{{url('clients/show/all')}}">Show All</a></li>
                                <li><a href="{{url('clients/add')}}">Add Client</a></li>
                            </ul>
                        </li>

                        <li> <a class="has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="fas fa-chart-bar"></i><span class="hide-menu"> Reports</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li><a href="{{url('suppliers/trans/prepare')}}">Inventory</a></li>
                                <li><a href="{{url('suppliers/trans/quick')}}">Sales</a></li>
                                <li><a href="{{url('suppliers/show')}}">Users</a></li>
                            </ul>
                        </li>

                        <li> <a class="has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="ti-book"></i><span class="hide-menu"> Accounts</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li><a href="{{url('accounts/cash')}}">Cash</a></li>
                                <li><a href="{{url('accounts/invoices')}}">Raw Invoices</a></li>
                            </ul>
                        </li>
                    
                        <li> <a class="has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="icon-people"></i><span class="hide-menu"> Suppliers</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li><a href="{{url('suppliers/show/all')}}">All Suppliers</a></li>
                                <li><a href="{{url('suppliers/show/trans')}}">Transactions</a></li>
                                <li><a href="{{url('suppliers/add')}}">Add Supplier</a></li>
                            </ul>
                        </li>



                        <li>
                            <a class="has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="fas fa-flask"></i><span class="hide-menu"> Raw Materials</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li><a href="{{url('rawmaterials/stock')}}"> Stock</a></li>
                                <li><a href="{{url('rawmaterials/entry/new')}}"> Inventory Entry</a></li>
                                <li><a href="{{url('rawmaterials/show')}}">Show All</a></li>
                            </ul>
                        </li>


                        <li> <a class="has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="fas fa-cogs"></i><span class="hide-menu"> Settings </span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li><a class="has-arrow waves-effect waves-dark" href="javascript:void(0)"> Delivery</a>
                                    <ul aria-expanded="false" class="collapse">
                                        <li><a href="{{url('areas/show')}}">Areas</a></li>
                                        <li><a href="{{url('drivers/show')}}">Drivers</a></li>
                                        <li><a href="{{url('slots/show')}}">Delivery Slots</a></li>
                                    </ul>
                                </li>
                                <li><a class="has-arrow waves-effect waves-dark" href="javascript:void(0)"> Catalogue</a>
                                    <ul aria-expanded="false" class="collapse">
                                        <li><a href="{{url('products/show/all')}}">All Products</a></li>
                                        <li><a href="{{url('products/filter/category')}}">By Category</a></li>
                                        <li><a href="{{url('products/add')}}">Add Product</a></li>
                                        <li><a href="{{url('categories/show')}}">Categories</a></li>
                                    </ul>
                                </li>
                                <li><a href="{{url('paymentoptions/show')}}">Payment Options</a></li>
                            </ul>
                        </li>
                        <li> <a href="{{url('dash/users/all')}}"><i class=" fas fa-users"></i><span class="hide-menu">Dashboard Admins</span></a></li>

                    </ul>
                </nav>
                <!-- End Sidebar navigation -->
            </div>
            <!-- End Sidebar scroll-->
        </aside>
        <!-- ============================================================== -->
        <!-- End Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Page wrapper  -->
        <!-- ============================================================== -->
        <div class="page-wrapper">
            <!-- ============================================================== -->
            <!-- Container fluid  -->
            <!-- ============================================================== -->
            <div class="container-fluid">
                <!-- ============================================================== -->
                <!-- Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->
                <div class="row page-titles">
                    <div class="col-md-5 align-self-center">
                        <h4 class="text-themecolor" style="font-family: 'Oregano' ; font-size:33px">{{config('app.name', 'GenuineGiant')}} Management System</h4>
                    </div>
                    <div class="col-md-7 align-self-center text-right">
                        <div class="d-flex justify-content-end align-items-center">
                            <a style="font-family: 'Oswald'" href="{{url('products/add')}}" class="btn btn-success d-none d-lg-block m-l-15"><i class="fa fa-plus-circle"></i> Add Product</a>
                            <a style="font-family: 'Oswald'" href="{{url('categories/show')}}" class="btn btn-success d-none d-lg-block m-l-15"><i class="fa fa-plus-circle"></i> Add Category</a>
                            <a style="font-family: 'Oswald'" href="{{url('orders/show/active')}}" class="btn btn-success d-none d-lg-block m-l-15"><i class="fas fa-info-circle"></i> Check Orders </a>
                            <a style="font-family: 'Oswald'" href="{{url('clients/add')}}" class="btn btn-success d-none d-lg-block m-l-15"><i class="fa fa-plus-circle"></i> Add User</a>
                        </div>
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- End Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->
                <!-- ============================================================== -->
                <!-- Start Page Content -->
                <!-- ============================================================== -->
                @yield('content')
                <!-- ============================================================== -->
                <!-- End PAge Content -->
                <!-- ============================================================== -->
                <!-- ============================================================== -->
                <!-- Right sidebar -->
                <!-- ============================================================== -->
                <!-- .right-sidebar -->
                <div class="right-sidebar">
                    <div class="slimscrollright">
                        <div class="rpanel-title"> Service Panel <span><i class="ti-close right-side-toggle"></i></span>
                        </div>
                        <div class="r-panel-body">
                            <ul id="themecolors" class="m-t-20">
                                <li><b>With Light sidebar</b></li>
                                <li><a href="javascript:void(0)" data-skin="skin-default" class="default-theme working">1</a></li>
                                <li><a href="javascript:void(0)" data-skin="skin-green" class="green-theme">2</a></li>
                                <li><a href="javascript:void(0)" data-skin="skin-red" class="red-theme">3</a></li>
                                <li><a href="javascript:void(0)" data-skin="skin-blue" class="blue-theme">4</a></li>
                                <li><a href="javascript:void(0)" data-skin="skin-purple" class="purple-theme">5</a></li>
                                <li><a href="javascript:void(0)" data-skin="skin-megna" class="megna-theme">6</a></li>
                                <li class="d-block m-t-30"><b>With Dark sidebar</b></li>
                                <li><a href="javascript:void(0)" data-skin="skin-default-dark" class="default-dark-theme ">7</a></li>
                                <li><a href="javascript:void(0)" data-skin="skin-green-dark" class="green-dark-theme">8</a></li>
                                <li><a href="javascript:void(0)" data-skin="skin-red-dark" class="red-dark-theme">9</a>
                                </li>
                                <li><a href="javascript:void(0)" data-skin="skin-blue-dark" class="blue-dark-theme">10</a></li>
                                <li><a href="javascript:void(0)" data-skin="skin-purple-dark" class="purple-dark-theme">11</a></li>
                                <li><a href="javascript:void(0)" data-skin="skin-megna-dark" class="megna-dark-theme ">12</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- End Right sidebar -->
                <!-- ============================================================== -->
            </div>
            <!-- ============================================================== -->
            <!-- End Container fluid  -->
            <!-- ============================================================== -->
        </div>
        <!-- ============================================================== -->
        <!-- End Page wrapper  -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- footer -->
        <!-- ============================================================== -->
        <footer class="footer">
            © 2020 {{config('app.name', 'PetMatch')}} by mSquareApps
        </footer>
        <!-- ============================================================== -->
        <!-- End footer -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- All Jquery -->
    <!-- ============================================================== -->
    <script src="{{ asset('assets/node_modules/jquery/jquery-3.2.1.min.js') }}"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="{{ asset('assets/node_modules/popper/popper.min.js') }}"></script>
    <script src="{{ asset('assets/node_modules/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <!-- slimscrollbar scrollbar JavaScript -->
    <script src="{{ asset('dist/js/perfect-scrollbar.jquery.min.js') }}"></script>
    <!--Wave Effects -->
    <script src="{{ asset('dist/js/waves.js') }}"></script>
    <!--Menu sidebar -->
    <script src="{{ asset('dist/js/sidebarmenu.js') }}"></script>
    <!--stickey kit -->
    <script src="{{ asset('assets/node_modules/sticky-kit-master/dist/sticky-kit.min.js') }}"></script>
    <script src="{{ asset('assets/node_modules/sparkline/jquery.sparkline.min.js') }}"></script>
    <!--Custom JavaScript -->
    <script src="{{ asset('dist/js/custom.min.js') }}"></script>
    <!-- This is data table -->
    <script src="{{ asset('assets/node_modules/datatables/datatables.min.js') }}"></script>
    <!-- This is for printing invoices -->
    <script src="{{ asset('dist/js/pages/jquery.PrintArea.js') }}" type="text/JavaScript"></script>
    <!-- This is for the bt switch -->
    <script src="{{ asset('assets/node_modules/bootstrap-switch/bootstrap-switch.min.js') }}"></script>

    <script type="text/javascript">
        $(".bt-switch input[type='checkbox'], .bt-switch input[type='radio']").bootstrapSwitch();
        var radioswitch = function () {
            var bt = function () {
                $(".radio-switch").on("switch-change", function () {
                    $(".radio-switch").bootstrapSwitch("toggleRadioState")
                }), $(".radio-switch").on("switch-change", function () {
                    $(".radio-switch").bootstrapSwitch("toggleRadioStateAllowUncheck")
                }), $(".radio-switch").on("switch-change", function () {
                    $(".radio-switch").bootstrapSwitch("toggleRadioStateAllowUncheck", !1)
                })
            };
            return {
                init: function () {
                    bt()
                }
            }
        }();
        $(document).ready(function () {
            radioswitch.init()
        });
    </script>
    <!-- start - This is for export functionality only -->
    <script src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.print.min.js"></script>

    <!-- End export script -->
    <!-- Form JS -->
    <script src="{{ asset('dist/js/pages/jasny-bootstrap.js') }}"></script>
    <script src="{{ asset('assets/node_modules/dropify/dist/js/dropify.min.js')}}"></script>
    <script src="{{ asset('assets/node_modules/select2/dist/js/select2.full.min.js') }}" type="text/javascript">
    </script>

    <!-- Start Table Search Script -->
    <script>
        $(document).ready(function () {
            $("#print").click(function () {
                var mode = 'iframe'; //popup
                var close = mode == "popup";
                var options = {
                    mode: mode,
                    popClose: close
                };
                $("div.printableArea").printArea(options);
            });
        });


        $(function () {

            $(function () {
                var table = $('#example').DataTable({
                    "columnDefs": [{
                        "visible": false,
                        "targets": 2
                    }],
                    "order": [
                    ],
                    "displayLength": 25,
                    "drawCallback": function (settings) {
                        var api = this.api();
                        var rows = api.rows({
                            page: 'current'
                        }).nodes();
                        var last = null;
                        api.column(2, {
                            page: 'current'
                        }).data().each(function (group, i) {
                            if (last !== group) {
                                $(rows).eq(i).before('<tr class="group"><td colspan="5">' + group + '</td></tr>');
                                last = group;
                            }
                        });
                    }
                });
                // Order by the grouping
                $('#example tbody').on('click', 'tr.group', function () {
                    var currentOrder = table.order()[0];
                    if (currentOrder[0] === 2 && currentOrder[1] === 'asc') {
                        table.order([2, 'desc']).draw();
                    } else {
                        table.order([2, 'asc']).draw();
                    }
                });
            });
        });
        const d = new Date();
        const year = d.getFullYear(); // 2019
        const day = d.getDay();
        const month = d.getMonth();
        const formatted = day + "/" + month + "/" + year;
      


        $(' .buttons-print, .buttons-pdf, .buttons-excel').addClass('btn btn-info mr-1');
        // File Upload JS
        $(document).ready(function () {
            // Basic
            $('.dropify').dropify();
            // Translated
            $('.dropify-fr').dropify({
                messages: {
                    default: 'Glissez-déposez un fichier ici ou cliquez',
                    replace: 'Glissez-déposez un fichier ou cliquez pour remplacer',
                    remove: 'Supprimer',
                    error: 'Désolé, le fichier trop volumineux'
                }
            });
            // Used events
            var drEvent = $('#input-file-events').dropify();
            drEvent.on('dropify.beforeClear', function (event, element) {
                return confirm("Do you really want to delete \"" + element.file.name + "\" ?");
            });
            drEvent.on('dropify.afterClear', function (event, element) {
                alert('File deleted');
            });
            drEvent.on('dropify.errors', function (event, element) {
                console.log('Has Errors');
            });
            var drDestroy = $('#input-file-to-destroy').dropify();
            drDestroy = drDestroy.data('dropify')
            $('#toggleDropify').on('click', function (e) {
                e.preventDefault();
                if (drDestroy.isDropified()) {
                    drDestroy.destroy();
                } else {
                    drDestroy.init();
                }
            })
        });
        $(function () {
            // For select 2
            $(".select2").select2();
        });


    </script>
    <!-- End Table Search Script -->
    @yield("js_content")
    @stack('scripts')
</body>

</html>