<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Synapsis Smart Booking</title>

    <!-- Custom fonts for this template-->
    <link href="{{ asset('css/all.min.css') }}" rel="stylesheet" type="text/css">

    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{ asset('css/sb-admin-2.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('css/font-awesome.min.css') }}" media="all" rel="stylesheet" type="text/css">

    {{-- <link href="{{ asset('css/chart.min.css') }}" rel="stylesheet" type="text/css"> --}}
    {{-- <link href="{{asset('datatables/jquery.dataTables.min.css')}}" rel="stylesheet" type="text/css"> --}}
    @yield('style')
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion customsidebar" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center"
                href="{{ route('admin.dashboard') }}">
                <div class="sidebar-brand-icon">
                    <i class="fas fa-address-book"></i>
                </div>
                <div class="sidebar-brand-text mx-2">Smart Booking</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            {{-- @if (Auth::user())
                @if (Auth::user()->level === 'admin')
                    <li class="nav-item {{ request()->is('home*') ? 'active' : '' }}">
                    @else
                    <li class="nav-item {{ request()->is('user*') ? 'active' : '' }}">
                @endif
                <li class="nav-item {{ request()->is('admin.dashboard') ? 'active' : '' }}">
            @endif --}}
            <li class="nav-item {{ Route::is('admin.dashboard') ? 'active' : '' }}">
                <a class="nav-link w-100" href="{{ route('admin.dashboard') }}">
                    <i class="fas fa-fw fa-home"></i>
                    <span>Dashboard</span></a>
            </li>

            <!--Tansaksi per User-->
            {{-- <li class="nav-item {{ request()->is('transaction*') ? 'active' : '' }}">
                <a class="nav-link w-100" href="#">
                    <i class="fas fa-shopping-basket fa-fw"></i>
                    <span>@lang('layout.transaction')</span></a>
            </li> --}}

            <!-- Nav Item - Stock Collapse Menu -->
            {{-- <li
                class="nav-item {{ request()->is('stocknow*', 'stockin*', 'stockout*', 'stockout.user*', 'stockopname*') ? 'active' : '' }}">
                <a class="nav-link collapsed w-100" href="#" data-toggle="collapse" data-target="#collapseTwo"
                    aria-expanded="true" aria-controls="collapseTwo">
                    <i class="fas fa-boxes fa-fw"></i>
                    <span>@lang('layout.stock')</span>
                </a>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">@lang('layout.stock')</h6>
                        <a class="collapse-item {{ request()->is('stocknow*') ? 'active' : '' }}"
                            href="#">@lang('layout.stocknow')</a>
                        @if (Auth::user())
                            @if (Auth::user()->level === 'admin')
                                <a class="collapse-item {{ request()->is('stockin*') ? 'active' : '' }}"
                                    href="#">@lang('layout.stockin')</a>
                                <a class="collapse-item {{ request()->is('stockout*') ? 'active' : '' }}"
                                    href="#">@lang('layout.stockout')</a>
                                <a class="collapse-item {{ request()->is('stockopname*') ? 'active' : '' }}"
                                    href="#">@lang('layout.stockopname')</a>
                            @endif
                        @endif
                    </div>
                </div>
            </li> --}}

            {{-- @if (Auth::user())
                @if (Auth::user()->level === 'admin') --}}
            <!-- Nav Item - Master Collapse Menu -->
            {{-- <li class="nav-item {{ request()->is('floor*', 'sector*', 'desk*', 'user*') ? 'active' : '' }}"> --}}
            <li class="nav-item {{ Route::is('floor*', 'user*') ? 'active' : '' }}">
                <a class="nav-link collapsed w-100" href="#" data-toggle="collapse" data-target="#collapseUtilities"
                    aria-expanded="true" aria-controls="collapseUtilities">
                    <i class="fas fa-fw fa-wrench"></i>
                    <span>Master</span>
                </a>
                <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Master</h6>
                        <a class="collapse-item {{ Route::is('floor*') ? 'active' : '' }}"
                            href="{{ route('floor.index') }}">Floor</a>
                        {{-- <a class="collapse-item {{ Route::is('sector*') ? 'active' : '' }}" href="#">Sector</a>
                        <a class="collapse-item {{ Route::is('desk*') ? 'active' : '' }}" href="#">Desk</a> --}}
                        <a class="collapse-item {{ Route::is('user*') ? 'active' : '' }}" href="#">User</a>
                    </div>
                </div>
            </li>

            <!--Settings-->
            <li class="nav-item {{ Route::is('setting*') ? 'active' : '' }}">
                <a class="nav-link w-100" href="#">
                    <i class="fas fa-cog fa-fw"></i>
                    <span>Settings</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>
                    <h6 style="margin-bottom:0"> Synapsis Smart Booking </h6>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">
                        {{-- <div class="topbar-divider d-none d-sm-block"></div> --}}
                        <!-- Language Wrapper -->
                        {{-- <li class="nav-item dropdown">
                            <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
                                <span
                                    class="mr-2 d-none d-lg-inline text-gray-600 small">{{ Config::get('languages')[App::getLocale()] }}</span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                @foreach (Config::get('languages') as $lang => $language)
                                    @if ($lang != App::getLocale())
                                        <a href="{{ route('lang.switch', $lang) }}"
                                            class="dropdown-item">{{ $language }}</a>
                                    @endif
                                @endforeach
                            </div>
                        </li> --}}
                        <!-- End of Language Wrapper -->

                        <div class="topbar-divider d-none d-sm-block"></div>
                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            @if (Auth::user())
                                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span
                                        class="mr-2 d-none d-lg-inline text-gray-600 small">{{ isset(Auth::user()->name) ? Auth::user()->name : 'User' }}</span>
                                    @if (Auth::user()->photo != '')
                                        <img class="img-profile rounded-circle" style="object-fit: contain;"
                                            src="{{ asset('images/users') }}/{{ Auth::user()->photo }}">
                                    @else
                                        <img class="img-profile rounded-circle" style="object-fit: contain;"
                                            src="{{ asset('images/users') }}/default.png">
                                    @endif
                                </a>
                            @else
                                <a class="nav-link dropdown-toggle" href="#">
                                    <span class="mr-2 d-none d-lg-inline text-gray-600 small">Login</span>
                                </a>
                            @endif
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="{{-- isset(Auth::user()->name) ? action('ProfileController@index') : '#' --}}">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
                                </a>
                                <a class="dropdown-item" href="{{-- isset(Auth::user()->name) ? action('ProfileController@editPassword', Auth::user()->id) : '#' --}}">
                                    <i class="fas fa-lock fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Edit Password
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    @yield('content')
                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Logout</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Are you sure want to Logout?</div>
                <div class="modal-footer">
                    <button class="btn btn-outline-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <form id="logout-form" action="#" method="POST">
                        @csrf
                        <button class="btn btn-primary" type="submit">Yes, Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{ asset('js/jquery.easing.min.js') }}"></script>
    <!-- Chart JS -->
    {{-- <script src="{{ asset('js/chart.min.js') }}"></script> --}}
    <!-- Custom scripts for all pages-->
    <script src="{{ asset('js/sb-admin-2.min.js') }}"></script>
    @yield('script')
</body>

</html>
