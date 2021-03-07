
<!doctype html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Language" content="en" />
    <meta name="msapplication-TileColor" content="#2d89ef">
    <meta name="theme-color" content="#4188c9">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent"/>
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="HandheldFriendly" content="True">
    <meta name="MobileOptimized" content="320">
    <link rel="icon" href="./favicon.ico" type="image/x-icon"/>
    <link rel="shortcut icon" type="image/x-icon" href="./favicon.ico" />
    <!-- Generated: 2018-04-16 09:29:05 +0200 -->
    <title>@section('page_title') Money Savings @show</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter&family=Poppins&display=swap');
    </style>
    <script src="./assets/js/require.min.js"></script>
    <script>
        requirejs.config({
            baseUrl: '.'
        });
    </script>
    <!-- Dashboard Core -->
    <link href="{{ asset('assets/css/dashboard.css') }}" rel="stylesheet" />
    <script src="{{ asset('assets/js/dashboard.js') }}"></script>
    <!-- c3.js Charts Plugin -->
    <link href="{{ asset('assets/plugins/charts-c3/plugin.css') }}" rel="stylesheet" />
    <script src="{{ asset('assets/plugins/charts-c3/plugin.js') }}"></script>
    <!-- Input Mask Plugin -->
    <script src="{{ asset('assets/plugins/input-mask/plugin.js') }}"></script>
</head>
<body class="">
<div class="page">
    <div class="page-main">
        <div class="header py-4">
            <div class="container">
                <div class="d-flex">
                    <a class="header-brand" href="">
                        RZ | Dev
                    </a>
                    <div class="d-flex order-lg-2 ml-auto">
                        <div class="dropdown d-none d-md-flex">
                            <a class="nav-link icon" data-toggle="dropdown">
                                <i class="fe fe-bell"></i>
                                <span class="nav-unread"></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                <a href="#" class="dropdown-item d-flex">
                                    <span class="avatar mr-3 align-self-center" style=""></span>
                                    <div>
                                        <strong>Oops!</strong> Feature is not ready yet.
                                        <div class="small text-muted">Feature is not ready yet</div>
                                    </div>
                                </a>
                                <div class="dropdown-divider"></div>
                                <a href="#" class="dropdown-item text-center text-muted-dark">Mark all as read</a>
                            </div>
                        </div>
                        <div class="dropdown">
                            <a href="#" class="nav-link pr-0 leading-none" data-toggle="dropdown">
                                <span class="avatar" style="background-image: url({{ asset('assets/images/user/user-' . auth()->user()->id . '.jpg') }})"></span>
                                <span class="ml-2 d-none d-lg-block">
                      <span class="text-default">{{ auth()->user()->email }}</span>
                      <small class="text-muted d-block mt-1">Administrator</small>
                    </span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                <a class="dropdown-item" href="#">
                                    <i class="dropdown-icon fe fe-user"></i> Profile
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="dropdown-icon fe fe-settings"></i> Settings
                                </a>
                                <div class="dropdown-divider"></div>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    @method('POST')
                                    <button class="dropdown-item" href="#">
                                        <i class="dropdown-icon fe fe-log-out"></i> Sign out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <a href="#" class="header-toggler d-lg-none ml-3 ml-lg-0" data-toggle="collapse" data-target="#headerMenuCollapse">
                        <span class="header-toggler-icon"></span>
                    </a>
                </div>
            </div>
        </div>
        <div class="header collapse d-lg-flex p-0" id="headerMenuCollapse">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg order-lg-first">
                        <ul class="nav nav-tabs border-0 flex-column flex-lg-row">
                            <li class="nav-item">
                                <a href="{{ route('admin_index') }}" class="nav-link @if(\Request::segment(2) == '') active @endif"><i class="fe fe-home"></i> Beranda</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('list_saving') }}" class="nav-link @if(\Request::segment(2) == 'list-tabungan') active @endif"><i class="fe fe-list"></i> List Tabungan</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="my-3 my-md-5">
            <div class="container">
                <div class="page-header">
                    <h1 class="page-title">
                        @section('title')
                            Dashboard
                        @show
                    </h1>
                </div>

                @yield('content')
            </div>
        </div>

    </div>
    <footer class="footer bg-transparent border-0">
        <div class="container">
            <div class="row">
                <div class="col-12 col-lg-12 mt-lg-0 text-center">
                    Copyright &copy; 2021 | RZ Dev
                </div>
            </div>
        </div>
    </footer>
</div>
</body>
</html>
