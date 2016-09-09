<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>ValetU</title>

    <!-- Styles -->
    <!-- Vendor CSS -->
    <!-- Vendor CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css">
    <link href="/js/vendors/bootgrid/jquery.bootgrid.min.css" rel="stylesheet">
    <link href="/js/vendors/bower_components/animate.css/animate.min.css" rel="stylesheet">
    <link href="/js/vendors/bower_components/bootstrap-sweetalert/lib/sweet-alert.css" rel="stylesheet">
    <link href="/js/vendors/bower_components/material-design-iconic-font/dist/css/material-design-iconic-font.min.css" rel="stylesheet">
    <link href="/js/vendors/bower_components/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.min.css" rel="stylesheet">        
    <link href="/js/vendors/bower_components/bootstrap-select/dist/css/bootstrap-select.css" rel="stylesheet">
    <link href="/js/vendors/bower_components/nouislider/distribute/jquery.nouislider.min.css" rel="stylesheet">
    <link href="/js/vendors/bower_components/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
    <link href="/js/vendors/farbtastic/farbtastic.css" rel="stylesheet">
    <link href="/js/vendors/bower_components/chosen/chosen.min.css" rel="stylesheet">
    <link href="/js/vendors/summernote/dist/summernote.css" rel="stylesheet">        
        
    <!-- CSS -->
    <link href="/css/app.min.1.css" rel="stylesheet">
    <link href="/css/app.min.2.css" rel="stylesheet"> 
    <link href="/css/custom.css" rel="stylesheet">

 <!-- Scripts -->
    <!-- <script src="/js/app.js"></script> -->
<!-- Javascript Libraries -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/1.0.14/vue.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/vue-resource/0.6.1/vue-resource.min.js"></script>
    <script src="/js/vendors/bower_components/jquery/dist/jquery.min.js"></script>
    <script src="/js/vendors/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    
    <script src="/js/vendors/bower_components/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js"></script>
    <script src="/js/vendors/bower_components/Waves/dist/waves.min.js"></script>
    <script src="/js/vendors/bootstrap-growl/bootstrap-growl.min.js"></script>
    <script src="/js/vendors/bower_components/bootstrap-sweetalert/lib/sweet-alert.min.js"></script>
    
    <script src="/js/vendors/bower_components/moment/min/moment.min.js"></script>
    <script src="/js/vendors/bower_components/autosize/dist/autosize.min.js"></script>
    <script src="/js/vendors/bower_components/bootstrap-select/dist/js/bootstrap-select.js"></script>
    <script src="/js/vendors/bower_components/nouislider/distribute/jquery.nouislider.all.min.js"></script>
    <script src="/js/nouislider.js"></script>
    <script src="/js/vendors/bower_components/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>
    <script src="/js/vendors/bower_components/typeahead.js/dist/typeahead.bundle.min.js"></script>
    <script src="/js/vendors/summernote/dist/summernote-updated.min.js"></script>
    <script src="/js/vendors/bootgrid/jquery.bootgrid.updated.min.js"></script>


    <!-- Placeholder for IE9 -->
    <!--[if IE 9 ]>
        <script src="vendors/bower_components/jquery-placeholder/jquery.placeholder.min.js"></script>
    <![endif]-->
    
    <script src="/js/vendors/bower_components/chosen/chosen.jquery.min.js"></script>
    <script src="/js/vendors/fileinput/fileinput.min.js"></script>
    <script src="/js/vendors/input-mask/input-mask.min.js"></script>
    <script src="/js/vendors/farbtastic/farbtastic.min.js"></script>
    <script src="/js/validator.js"></script> 
    <script src="/js/functions.js"></script> 

    <!-- Scripts -->
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>
</head>
<body>
    <header id="header" class="clearfix" data-current-skin="blue">
        <ul class="header-inner">
            <li id="menu-trigger" data-trigger="#sidebar">
                <div class="line-wrap">
                    <div class="line top"></div>
                    <div class="line center"></div>
                    <div class="line bottom"></div>
                </div>
            </li>
        
            <li class="hidden-xs">
                <a href="index.html" class="m-l-10">ValetU</a>
            </li>
            
            <li class="pull-right">
                <ul class="top-menu">
                   <!--  <li id="top-search">
                        <a href=""><i class="tm-icon zmdi zmdi-search"></i></a>
                    </li> -->
                    <li id="toggle-width">
                        <div class="toggle-switch">
                            <input id="tw-switch" type="checkbox" hidden="hidden">
                            <label for="tw-switch" class="ts-helper"></label>
                        </div>
                    </li>
                    
                    
                </ul>
            </li>
        </ul>
        
        <!-- Top Search Content -->
       <!--  <div id="top-search-wrap">
            <div class="tsw-inner">
                <i id="top-search-close" class="zmdi zmdi-arrow-left"></i>
                <input type="text">
            </div>
        </div> -->
    </header>
    <section id="main" data-layout="layout-1">

    <aside id="sidebar" class="sidebar c-overflow">
        <div class="profile-menu">
            <a href="/">
                <div class="profile-pic">
                    <img src="/img/logo.png" alt="">
                </div>

                <div class="profile-info">
                   @unless (Auth::guest())
                    {{ Auth::user()->name }} 
                   @endif

                    <i class="zmdi zmdi-caret-down"></i>
                </div>
            </a>
             @unless (Auth::guest())
            <ul class="main-menu">
                <li>
                    <a href="{{ url('/logout') }}"
                                        onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();"><i class="zmdi zmdi-time-restore"></i> Logout</a>
                                                 <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                                        {{ csrf_field() }}
                                    </form>
                </li>
            </ul>
            @endif
        </div>

        <ul class="main-menu">
           @if (Auth::guest())
                <li><a href="{{ url('/login') }}">Login</a></li>
                <li><a href="{{ url('/register') }}">Register</a></li>
            @else
                <li class="menu-home"><a href="/home"><i class="zmdi zmdi-home"></i> Home</a></li>
                <li class="menu-user-management"><a href="/user"><i class="zmdi zmdi-home"></i> Rider management</a></li>
            @endif
        </ul>
    </aside>

    <section id="content">
        <div class="container">
             @yield('content')
        </div>
    </section>
</section>

<footer id="footer">
    Copyright &copy; 2015 
    <ul class="f-menu">
        <li><a href="/home">Home</a></li>
        <li><a href="/user">Rider Management</a></li>
    </ul>
</footer>
<!-- Page Loader -->
<div class="page-loader">
    <div class="preloader pls-blue">
        <svg class="pl-circular" viewBox="25 25 50 50">
            <circle class="plc-path" cx="50" cy="50" r="20" />
        </svg>

        <p>Please wait...</p>
    </div>
</div>

      <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA7yEDNx3eV1mQ-6N-p2h4dxQ8k0sDgQDg&libraries=places&callback=initMap" async defer></script>

</body>
</html>
