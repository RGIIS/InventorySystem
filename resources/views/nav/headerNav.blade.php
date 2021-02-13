<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../assets/img/favicon.png">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <title>
    Dashboard
  </title>
  <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <!--     Fonts and icons     -->
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
  <link rel="stylesheet" href={{asset('/css/font-awesome.min.css')}}>
  {{-- <link rel="stylesheet" type="text/css" href={{asset("/fonts/font-awesome-4.7.0/css/font-awesome.min.css")}}> --}}

{{-- <link rel="stylesheet" type="text/css" href="fonts/iconic/css/material-design-iconic-font.min.css"> --}}
  <!-- CSS Files -->
  <link rel="stylesheet" href={{ asset('/css/overlay-loading.css') }}>
  <link href={{ asset('/css/material-dashboard.css?v=2.1.2') }} rel="stylesheet" />
  <script src={{asset('/js/updateVersion.js')}} defer></script>
  @yield('script')
 <style type="text/css">
   .dropdown-toggle::after {
    position:relative;
    float: right;
    top:-15px;
}
    
 </style>
</head>
<body class="">
  <div class="overlay" id="overlay" hidden>
    <div class="overlay__inner" >
        <div class="overlay__content" ><span class="spinner"></span> <h3 id='loadingMsg' class="text-center" style="color:white !important; padding:0px !important;">Checking for update... Please wait</h3></div>
        
    </div>
  </div>
    <div class="wrapper ">
      <div class="sidebar" data-color="purple" data-background-color="white" >
        
        <!--
          Tip 1: You can change the color of the sidebar using: data-color="purple | azure | green | orange | danger"
  
          Tip 2: you can also add an image using data-image tag
      -->
        <div class="logo"><a href="#" class="simple-text logo-normal">
        Inventory System
          </a></div>
          
        <div class="sidebar-wrapper">
          <ul class="nav">
            @if(session('User')->role=='admin')
            <li class="nav-item @if (Route::current()->getName() == 'dashboard') active @endif">
              <a class="nav-link" href="/dashboard">
                {{-- <i class="material-icons">dashboard</i> --}}
                <i class="fa fa-dashboard"></i>
                <p>Dashboard</p>
              </a>
            </li>
            <li class="nav-item @if (Route::current()->getName() == 'inventory') active @endif">
              <a class="nav-link" href="/inventory">
                <i class="fa fa-inbox"></i>
                <p>Inventory</p>
              </a>
            </li>
            <li class="nav-item @if (Route::current()->getName() == 'monthly_sales'||Route::current()->getName() == 'daily_sales'||Route::current()->getName() == 'detailed_sales') active @endif">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-line-chart"></i>
                <p>Sales</p>
              </a>
              <div class="dropdown-menu" aria-labelledby="#navbarDropdown">
                <a class="dropdown-item" href="/sales/detailed"><i class="fa fa-line-chart"></i><span @if(Route::current()->getName() == 'detailed_sales')class="text-info"@endif>Detailed</span></a>
                <a class="dropdown-item" href="/sales/daily"><i class="fa fa-line-chart"></i><span @if(Route::current()->getName() == 'daily_sales')class="text-info"@endif>Daily</span></a>
                <a class="dropdown-item" href="/sales/monthly"><i class="fa fa-line-chart"></i><span @if(Route::current()->getName() == 'monthly_sales')class="text-info"@endif>Monthly</span></a>
              </div>
            </li>
            <li class="nav-item @if (Route::current()->getName() == 'accounts') active @endif">
              <a class="nav-link" href="/accounts">
                <i class="fa fa-user-circle"></i>
                <p>Accounts</p>
              </a>
            </li>
            <li class="nav-item @if (Route::current()->getName() == 'logs') active @endif">
              <a class="nav-link" href="/logs">
                <i class="fa fa-file-text"></i>
                <p>Logs</p>
              </a>
            </li>
            @endif
          </ul>
        </div>
       
      </div>
      <div class="main-panel">
        
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg navbar-primary bg-primary navbar-absolute fixed-top ">
          <div class="container-fluid">
            <div class="navbar-wrapper">
                @yield('pagetitle')
              
            </div>
            <button class="navbar-toggler" type="button" data-toggle="collapse" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
              <span class="sr-only">Toggle navigation</span>
              <span class="navbar-toggler-icon icon-bar"></span>
              <span class="navbar-toggler-icon icon-bar"></span>
              <span class="navbar-toggler-icon icon-bar"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end">
             
              <ul class="navbar-nav">
                <li class="nav-item dropdown">
                  <a class="nav-link" href="javascript:;" id="navbarDropdownProfile" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-user"></i>
                    <p class="d-lg-none d-md-block">
                      {{session('User')->name}}
                    </p>
                  </a>
                  <form class='navbar-form'>
                  <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownProfile">
                    <a class="dropdown-item" href="#" onclick="checkUpdate()">Check for update</a>
                    <a class="dropdown-item" href="/logout">Log out</a>
                    <div class="dropdown-divider"></div>
                  </div>
                </form>
                </li>
              </ul>
            </div>
          </div>
        </nav>
        
        <!-- End Navbar -->
@yield('content')
{{-- MESSAGE FOR UPDATES --}}
            


<nav  class="m-0 navbar fixed-bottom" style="background-color:transparent !important; box-shadow:none !important;">
 
  <ul></ul>
  <div  id='updateAlert' class="alert alert-success alert-dismissible fade show form-inline" role="alert" hidden>
    <span id='updateMessage'></span>
  </div>
  <div id='newUpdate'  class="mx-3 alert alert-primary text-center form-inline" role="alert" hidden>
    New : Version&nbsp;<span class="mr-3" id="newVersion">0</span>
    <button id='getUpdate' type="button" class="btn btn-info">Update</button>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
</nav>


<script src={{ asset("/js/core/jquery.min.js") }}></script>
<script src={{ asset("/js/core/popper.min.js") }}></script>
<script src={{ asset("/js/core/bootstrap-material-design.min.js") }}></script>
<script src={{ asset("/js/plugins/perfect-scrollbar.jquery.min.js") }}></script>
<!-- Plugin for the momentJs  -->
<script src={{ asset("/js/plugins/moment.min.js") }}></script>
<script src={{asset('/js/plugins/bootstrap-datetimepicker.min.js')}}></script>
{{-- <!--  Plugin for Sweet Alert -->
<script src="../assets/js/plugins/sweetalert2.js"></script>
<!-- Forms Validations Plugin -->
<script src="../assets/js/plugins/jquery.validate.min.js"></script>
<!-- Plugin for the Wizard, full documentation here: https://github.com/VinceG/twitter-bootstrap-wizard -->
<script src="../assets/js/plugins/jquery.bootstrap-wizard.js"></script>
<!--	Plugin for Select, full documentation here: http://silviomoreto.github.io/bootstrap-select -->
<script src="../assets/js/plugins/bootstrap-selectpicker.js"></script>
<!--  Plugin for the DateTimePicker, full documentation here: https://eonasdan.github.io/bootstrap-datetimepicker/ -->
<script src="../assets/js/plugins/bootstrap-datetimepicker.min.js"></script>
<!--  DataTables.net Plugin, full documentation here: https://datatables.net/  -->
<script src="../assets/js/plugins/jquery.dataTables.min.js"></script>
<!--	Plugin for Tags, full documentation here: https://github.com/bootstrap-tagsinput/bootstrap-tagsinputs  -->
<script src="../assets/js/plugins/bootstrap-tagsinput.js"></script>
<!-- Plugin for Fileupload, full documentation here: http://www.jasny.net/bootstrap/javascript/#fileinput -->
<script src="../assets/js/plugins/jasny-bootstrap.min.js"></script>
<!--  Full Calendar Plugin, full documentation here: https://github.com/fullcalendar/fullcalendar    -->
<script src="../assets/js/plugins/fullcalendar.min.js"></script>
<!-- Vector Map plugin, full documentation here: http://jvectormap.com/documentation/ -->
<script src="../assets/js/plugins/jquery-jvectormap.js"></script>
<!--  Plugin for the Sliders, full documentation here: http://refreshless.com/nouislider/ -->
<script src="../assets/js/plugins/nouislider.min.js"></script>
<!-- Include a polyfill for ES6 Promises (optional) for IE11, UC Browser and Android browser support SweetAlert -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/core-js/2.4.1/core.js"></script>
<!-- Library for adding dinamically elements -->
<script src="../assets/js/plugins/arrive.min.js"></script>
<!--  Google Maps Plugin    -->
<script src="https://maps.googleapis.com/maps/api/js?key=YOUR_KEY_HERE"></script>
<!-- Chartist JS -->
<script src="../assets/js/plugins/chartist.min.js"></script>
<!--  Notifications Plugin    -->
<script src="../assets/js/plugins/bootstrap-notify.js"></script>
<!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->

<!-- Material Dashboard DEMO methods, don't include it in your project! -->
<script src="../assets/demo/demo.js"></script> --}}
<script src={{asset("/js/material-dashboard.js?v=2.1.2")}} type="text/javascript"></script>
<script>
  $(document).ready(function() {
    $().ready(function() {
      $sidebar = $('.sidebar');

      $sidebar_img_container = $sidebar.find('.sidebar-background');

      $full_page = $('.full-page');

      $sidebar_responsive = $('body > .navbar-collapse');

      window_width = $(window).width();

      fixed_plugin_open = $('.sidebar .sidebar-wrapper .nav li.active a p').html();

      if (window_width > 767 && fixed_plugin_open == 'Dashboard') {
        if ($('.fixed-plugin .dropdown').hasClass('show-dropdown')) {
          $('.fixed-plugin .dropdown').addClass('open');
        }

      }

      $('.fixed-plugin a').click(function(event) {
        // Alex if we click on switch, stop propagation of the event, so the dropdown will not be hide, otherwise we set the  section active
        if ($(this).hasClass('switch-trigger')) {
          if (event.stopPropagation) {
            event.stopPropagation();
          } else if (window.event) {
            window.event.cancelBubble = true;
          }
        }
      });

      $('.fixed-plugin .active-color span').click(function() {
        $full_page_background = $('.full-page-background');

        $(this).siblings().removeClass('active');
        $(this).addClass('active');

        var new_color = $(this).data('color');

        if ($sidebar.length != 0) {
          $sidebar.attr('data-color', new_color);
        }

        if ($full_page.length != 0) {
          $full_page.attr('filter-color', new_color);
        }

        if ($sidebar_responsive.length != 0) {
          $sidebar_responsive.attr('data-color', new_color);
        }
      });

      $('.fixed-plugin .background-color .badge').click(function() {
        $(this).siblings().removeClass('active');
        $(this).addClass('active');

        var new_color = $(this).data('background-color');

        if ($sidebar.length != 0) {
          $sidebar.attr('data-background-color', new_color);
        }
      });

      $('.fixed-plugin .img-holder').click(function() {
        $full_page_background = $('.full-page-background');

        $(this).parent('li').siblings().removeClass('active');
        $(this).parent('li').addClass('active');


        var new_image = $(this).find("img").attr('src');

        if ($sidebar_img_container.length != 0 && $('.switch-sidebar-image input:checked').length != 0) {
          $sidebar_img_container.fadeOut('fast', function() {
            $sidebar_img_container.css('background-image', 'url("' + new_image + '")');
            $sidebar_img_container.fadeIn('fast');
          });
        }

        if ($full_page_background.length != 0 && $('.switch-sidebar-image input:checked').length != 0) {
          var new_image_full_page = $('.fixed-plugin li.active .img-holder').find('img').data('src');

          $full_page_background.fadeOut('fast', function() {
            $full_page_background.css('background-image', 'url("' + new_image_full_page + '")');
            $full_page_background.fadeIn('fast');
          });
        }

        if ($('.switch-sidebar-image input:checked').length == 0) {
          var new_image = $('.fixed-plugin li.active .img-holder').find("img").attr('src');
          var new_image_full_page = $('.fixed-plugin li.active .img-holder').find('img').data('src');

          $sidebar_img_container.css('background-image', 'url("' + new_image + '")');
          $full_page_background.css('background-image', 'url("' + new_image_full_page + '")');
        }

        if ($sidebar_responsive.length != 0) {
          $sidebar_responsive.css('background-image', 'url("' + new_image + '")');
        }
      });

      $('.switch-sidebar-image input').change(function() {
        $full_page_background = $('.full-page-background');

        $input = $(this);

        if ($input.is(':checked')) {
          if ($sidebar_img_container.length != 0) {
            $sidebar_img_container.fadeIn('fast');
            $sidebar.attr('data-image', '#');
          }

          if ($full_page_background.length != 0) {
            $full_page_background.fadeIn('fast');
            $full_page.attr('data-image', '#');
          }

          background_image = true;
        } else {
          if ($sidebar_img_container.length != 0) {
            $sidebar.removeAttr('data-image');
            $sidebar_img_container.fadeOut('fast');
          }

          if ($full_page_background.length != 0) {
            $full_page.removeAttr('data-image', '#');
            $full_page_background.fadeOut('fast');
          }

          background_image = false;
        }
      });

      $('.switch-sidebar-mini input').change(function() {
        $body = $('body');

        $input = $(this);

        if (md.misc.sidebar_mini_active == true) {
          $('body').removeClass('sidebar-mini');
          md.misc.sidebar_mini_active = false;

          $('.sidebar .sidebar-wrapper, .main-panel').perfectScrollbar();

        } else {

          $('.sidebar .sidebar-wrapper, .main-panel').perfectScrollbar('destroy');

          setTimeout(function() {
            $('body').addClass('sidebar-mini');

            md.misc.sidebar_mini_active = true;
          }, 300);
        }

        // we simulate the window Resize so the charts will get updated in realtime.
        var simulateWindowResize = setInterval(function() {
          window.dispatchEvent(new Event('resize'));
        }, 180);

        // we stop the simulation of Window Resize after the animations are completed
        setTimeout(function() {
          clearInterval(simulateWindowResize);
        }, 1000);

      });
    });
  });
</script>
{{-- <script>
  $(document).ready(function() {
    // Javascript method's body can be found in assets/js/demos.js
    md.initDashboardPageCharts();

  });
</script> --}}
@yield('footerscript')
</body>
</html>