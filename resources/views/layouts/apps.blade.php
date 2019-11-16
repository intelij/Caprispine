<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta content="Developed by | Suman Arora">
  <title>CapriSpine</title>
  <!-- Favic icon -->
  <link href="{{asset('public/upload/images/favicon.ico')}}" type="image/x-icon" rel="shortcut icon"/>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="{{url('public/bower_components/bootstrap/dist/css/bootstrap.min.css')}}">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{url('public/bower_components/font-awesome/css/font-awesome.min.css')}}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="{{url('public/bower_components/Ionicons/css/ionicons.min.css')}}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{url('public/dist/css/AdminLTE.min.css')}}">
  <link rel="stylesheet" href="{{url('public/bower_components/select2/dist/css/select2.min.css')}}">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="{{url('public/dist/css/skins/_all-skins.min.css')}}">
  <!-- Morris chart -->
  <link rel="stylesheet" href="{{url('public/bower_components/morris.js/morris.css')}}">
  <!-- jvectormap -->
  <link rel="stylesheet" href="{{url('public/bower_components/jvectormap/jquery-jvectormap.css')}}">
  <!-- Date Picker -->
  <link rel="stylesheet" href="{{url('public/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')}}">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="{{url('public/bower_components/bootstrap-daterangepicker/daterangepicker.css')}}">
  <!-- bootstrap wysihtml5 - text editor -->
  <link rel="stylesheet" href="{{url('public/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css')}}">
  <link rel="stylesheet" href="{{url('public/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css')}}">
  <link rel="stylesheet" type="text/css" href="{{url('css/style.css')}}">
  <!-- Bootstrap time Picker -->
  <link rel="stylesheet" href="{{url('public/plugins/timepicker/bootstrap-timepicker.min.css')}}">
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <header class="main-header">
    <!-- Logo -->
    @if(Auth::check())
    <a href="{{url('dashboard')}}" class="logo">
    @endif
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <!-- <span class="logo-mini"><b>A</b>LT</span> -->
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>Capri</b>Spine</span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
            
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="@if(!empty(Auth::user()->profile_pic)) {{PROFILE_PIC.Auth::user()->profile_pic}} @else {{DEFAULT_PROFILE}} @endif" class="user-image" alt="User Image">
              <span class="hidden-xs">@if(Auth::check()){{Auth::user()->name}} @if(Auth::user()->branch) ( {{branchDetails(Auth::user()->branch)->name}} ) @endif @endif</span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="@if(!empty(Auth::user()->profile_pic)) {{PROFILE_PIC.Auth::user()->profile_pic}} @else {{DEFAULT_PROFILE}} @endif" class="img-circle" alt="User Image">

                <p>@if(Auth::check())
                  {{Auth::user()->name}} - @if(Auth::user()->user_type == 'superadmin') SuperAdmin @else {{userTypeDetails(Auth::user()->user_type)->name}} @endif
                  @endif
                  <small>CapriSpine</small>
                </p>
              </li>
              <li class="user-body">
                <div class="row">
                  <div class="col-md-8 text-center">
                    <a href="{{url('change-password')}}" class="btn btn-success btn-flat">Change Password</a>
                  </div>
                  <!-- <div class="col-xs-4 text-center">
                    <a href="#">Sales</a>
                  </div>
                  <div class="col-xs-4 text-center">
                    <a href="#">Friends</a>
                  </div> -->
                </div>
                <!-- /.row -->
              </li>
              <li class="user-footer">
                <div class="pull-left">
                  <a href="{{url('my-profile')}}" class="btn btn-warning btn-flat">Profile</a>
                </div>
                <div class="pull-right">
                  <a href="{{ route('logout') }}" class="btn btn-danger btn-flat"
                      onclick="event.preventDefault();
                               document.getElementById('logout-form').submit();">
                      Logout
                  </a>
                  <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                  </form>
                </div>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </nav>
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="@if(!empty(Auth::user()->profile_pic)) {{PROFILE_PIC.Auth::user()->profile_pic}} @else {{DEFAULT_PROFILE}} @endif" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p><a class="text-success" href="{{url('my-profile')}}">{{Auth::user()->name}}</a></p>
          <i class="fa fa-circle text-success"></i> Online
        </div>
      </div>
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="@if($class=='dashboard') active @endif">
          <a href="{{url('dashboard')}}">
            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
          </a>
        </li>
        @if(checkPermission('4',Auth::User()->user_type))
        <li class="treeview @if($masterclass =='users') active @endif">
          <a href="#">
            <i class="fa fa-group"></i> <span>Staff</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            @if(subModulepermission('4','1',Auth::User()->user_type))
              <li class="@if($class =='user') active @endif"><a href="{{url('all-users')}}"><i class="fa fa-circle-o"></i> All Members</a></li>
            @endif
            @if(subModulepermission('4','2',Auth::User()->user_type))
              <li class="@if($class =='adduser') active @endif"><a href="{{url('add-user')}}"><i class="fa fa-circle-o"></i> Add Members</a></li>
            @endif
          </ul>
        </li>
        @endif
        @if(checkPermission('5',Auth::User()->user_type))
        <li class="treeview @if($masterclass =='patient') active @endif">
          <a href="#">
            <i class="fa fa-user-plus"></i> <span>Patient</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            @if(subModulepermission('5','3',Auth::User()->user_type))
              <li class="@if($class =='patients') active @endif"><a href="{{url('all-patient')}}"><i class="fa fa-circle-o"></i> All Patient</a></li>
            @endif
            <!-- <li class="@if($class =='addpatient') active @endif"><a href="{{url('add-patient')}}"><i class="fa fa-circle-o"></i> Add Patient</a></li> -->
          </ul>
        </li>
        @endif
        @if(checkPermission('7',Auth::User()->user_type))
        <li class="treeview @if($masterclass =='therapist') active @endif">
          <a href="#">
            <i class="fa fa-user"></i> <span>Therapist</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            @if(subModulepermission('7','5',Auth::User()->user_type))
              <li class="@if($class =='allthe') active @endif"><a href="{{url('all-therapist')}}"><i class="fa fa-circle-o"></i> All Therapist</a></li>
            @endif
            @if(subModulepermission('7','6',Auth::User()->user_type))
              <li class="@if($class =='addthe') active @endif"><a href="{{url('add-therapist')}}"><i class="fa fa-circle-o"></i> Add Therapist</a></li>
            @endif
          </ul>
        </li>
        @endif
        @if(checkPermission('1',Auth::User()->user_type))
        <li class="@if($class=='assignment') active @endif">
          <a href="{{url('module-assignment')}}">
            <i class="fa fa-tasks"></i> <span>Module Assignment</span>
          </a>
        </li>
        @endif
        @if(checkPermission('2',Auth::User()->user_type))
        <li class="treeview @if($masterclass=='appointment') active @endif">
          <a href="#">
            <i class="fa fa-calendar"></i> <span>Appointment</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            @if(subModulepermission('2','7',Auth::User()->user_type))
              <li class="@if($class=='allapp') active @endif"><a href="{{url('all-appointment')}}"><i class="fa fa-circle-o"></i> All Appointment</a></li>
            @endif
            @if(subModulepermission('2','9',Auth::User()->user_type))
              <li class="@if($class=='allvisit') active @endif"><a href="{{url('all-daily-visits')}}"><i class="fa fa-circle-o"></i> All Visits</a></li>
            @endif
            @if(subModulepermission('2','8',Auth::User()->user_type))
              <li class="@if($class=='addapp') active @endif"><a href="{{url('add-appointment')}}"><i class="fa fa-circle-o"></i> Add Appointment</a></li>
            @endif
          </ul>
        </li>
        @endif
        @if(checkPermission('8',Auth::User()->user_type))
        <li class="treeview @if($masterclass =='report') active @endif">
          <a href="#">
            <i class="fa fa-file"></i> <span>Report</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            @if(subModulepermission('8','20',Auth::User()->user_type))
              <li class="@if($class=='tReport') active @endif"><a href="{{url('all-report')}}"><i class="fa fa-circle-o"></i> Therapist Report</a></li>
            @endif
            @if(Auth::User()->user_type == 'superadmin')
              <li class="@if($class=='phReport') active @endif"><a href="{{url('therapist-report')}}"><i class="fa fa-circle-o"></i> Private Home Care(Therapist)</a></li>
            @endif
            @if(subModulepermission('8','21',Auth::User()->user_type))
              <li class="@if($class=='pReport') active @endif"><a href="{{url('patient-report')}}"><i class="fa fa-circle-o"></i> Patient Report (All exam)</a></li>
            @endif
          </ul>
        </li>
        @endif
        @if(checkPermission('9',Auth::User()->user_type))
        <li class="treeview @if($masterclass =='receipt') active @endif">
          <a href="#">
            <i class="fa fa-money"></i> <span>Invoice</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            @if(subModulepermission('9','18',Auth::User()->user_type))
              <li class="@if($class=='greceipt') active @endif"><a href="{{url('general-receipt')}}"><i class="fa fa-circle-o"></i> General Invoice</a></li>
            @endif
            @if(subModulepermission('9','19',Auth::User()->user_type))
              <li class="@if($class=='preceipt') active @endif"><a href="{{url('package-receipt')}}"><i class="fa fa-circle-o"></i> Package Invoice</a></li>
            @endif
          </ul>
        </li>
        @endif
        @if(checkPermission('12',Auth::User()->user_type))
        <li class="treeview @if($masterclass=='generalDetails') active @endif">
          <a href="#">
            <i class="fa fa-snowflake-o"></i> <span>General Details</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            @if(subModulepermission('12','22',Auth::User()->user_type))
              <li class="@if($class=='attendance') active @endif"><a href="{{url('all-attendance')}}"><i class="fa fa-circle-o"></i> Attandance</a></li>
            @endif
            @if(subModulepermission('12','23',Auth::User()->user_type))
              <li class="@if($class=='penalties') active @endif"><a href="{{url('all-therapist-penalty')}}"><i class="fa fa-circle-o"></i> Therapist Penalties</a></li>
            @endif
            @if(subModulepermission('12','24',Auth::User()->user_type))
              <li class="@if($class=='allTest') active @endif"><a href="{{url('select-patient')}}"><i class="fa fa-circle-o"></i> Patient Exam</a></li>
            @endif
            @if(subModulepermission('12','25',Auth::User()->user_type))
              <li class="@if($class=='changePassword') active @endif"><a href="{{url('select-user-change-password')}}"><i class="fa fa-circle-o"></i> Change Password (All User)</a></li>
            @endif
            @if(subModulepermission('12','26',Auth::User()->user_type))
              <li class="@if($class=='selfExercise') active @endif"><a href="{{url('all-self-exercise')}}"><i class="fa fa-circle-o"></i> Self Exercise (All Patient)</a></li>
            @endif
          </ul>
        </li>
        @endif
        @if(checkPermission('11',Auth::User()->user_type))
        <li class="@if($class=='notification') active @endif">
          <a href="{{url('notification')}}">
            <i class="fa fa-bell"></i> <span>Notification</span>
          </a>
        </li>
        @endif
        @if(checkPermission('10',Auth::User()->user_type))
        <li class="@if($class=='banner') active @endif">
          <a href="{{url('banner')}}">
            <i class="fa fa-image"></i> <span>Banner</span>
          </a>
        </li>
        @endif
        @if(checkPermission('6',Auth::User()->user_type))
        <li class="@if($class=='cms') active @endif">
          <a href="{{url('cms')}}">
            <i class="fa fa-file"></i> <span>CMS</span>
          </a>
        </li>
        @endif
        @if(Auth::User()->user_type == 'superadmin')
        <li class="treeview @if($masterclass =='capripoint') active @endif">
          <a href="#">
            <i class="fa fa-dollar"></i> <span>Capri Privilege</span>
            @php
              $checkRequest = DB::table('capri_point')->where('type','pendingForDebit')->count('id');
            @endphp
            @if($checkRequest > 0)
              <img src="{{asset('public/upload/images/1.gif')}}" class="bellImg" alt="" width="40" height="20">
            @endif
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="@if($class=='caprilist') active @endif"><a href="{{url('all-capri-points')}}"><i class="fa fa-circle-o"></i> All Capri Points</a></li>
          </ul>
        </li>

        <li class="treeview @if($masterclass =='setting') active @endif">
          <a href="#">
            <i class="fa fa-cog"></i> <span>Setting</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="@if($class=='location') active @endif"><a href="{{url('location')}}"><i class="fa fa-circle-o"></i> Location</a></li>
            <li class="@if($class=='service') active @endif"><a href="{{url('service')}}"><i class="fa fa-circle-o"></i> Service</a></li>
            <li class="@if($class=='reference') active @endif"><a href="{{url('reference-type')}}"><i class="fa fa-circle-o"></i> Reference Type</a></li>
            <li class="@if($class=='usertype') active @endif"><a href="{{url('user-type')}}"><i class="fa fa-circle-o"></i> User Type</a></li>
            {{-- <li class="@if($class=='module') active @endif"><a href="{{url('module')}}"><i class="fa fa-circle-o"></i> Module</a></li>
            <li class="@if($class=='submodule') active @endif"><a href="{{url('submodule')}}"><i class="fa fa-circle-o"></i> Sub Module</a></li> --}}
            <li class="@if($class=='amount') active @endif"><a href="{{url('amount')}}"><i class="fa fa-circle-o"></i> Amount</a></li>
            <li class="@if($class=='package') active @endif"><a href="{{url('package')}}"><i class="fa fa-circle-o"></i> Packages</a></li>
            <li class="@if($class=='penalty') active @endif"><a href="{{url('penalty')}}"><i class="fa fa-circle-o"></i> Penalty</a></li>
            <li class="@if($class=='ipd') active @endif"><a href="{{url('ipd')}}"><i class="fa fa-circle-o"></i> IPD Calender</a></li>
            <li class="@if($class=='machine') active @endif"><a href="{{url('machine')}}"><i class="fa fa-circle-o"></i> Machines</a></li>
            <li class="@if($class=='point') active @endif"><a href="{{url('capri-point')}}"><i class="fa fa-circle-o"></i> Capri Points</a></li>
            <li class="@if($class=='exercise') active @endif"><a href="{{url('all-exercise')}}"><i class="fa fa-circle-o"></i> Exercise</a></li>
          </ul>
        </li>
        @endif
        <li class="@if($class=='contactus') active @endif">
          <a href="{{url('contact-us')}}">
            <i class="fa fa-address-book"></i> <span>Contact Us</span>
          </a>
        </li>
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        {{$title}}
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">{{$title}}</li>
      </ol>
    </section>

    @yield('content')

  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <a href="https://www.3dlogic.in" target="_blank">3D Logic</a>
    </div>
    <strong>Copyright &copy; 2018 <a href="https://www.3dlogic.in" target="_blank" >3D Logic Pvt. Ltd.</a></strong> All rights
    reserved.
  </footer>

  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> -->
<!-- jQuery 3 -->
<script src="{{url('public/bower_components/jquery/dist/jquery.min.js')}}"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{url('public/bower_components/jquery-ui/jquery-ui.min.js')}}"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Bootstrap 3.3.7 -->
<script src="{{url('public/bower_components/bootstrap/dist/js/bootstrap.min.js')}}"></script>
<!-- Morris.js charts -->
<script src="{{url('public/bower_components/raphael/raphael.min.js')}}"></script>
<script src="{{url('public/bower_components/morris.js/morris.min.js')}}"></script>
<!-- Sparkline -->
<script src="{{url('public/bower_components/jquery-sparkline/dist/jquery.sparkline.min.js')}}"></script>
<!-- jvectormap -->
<script src="{{url('public/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js')}}"></script>
<script src="{{url('public/plugins/jvectormap/jquery-jvectormap-world-mill-en.js')}}"></script>
<!-- jQuery Knob Chart -->
<script src="{{url('public/bower_components/jquery-knob/dist/jquery.knob.min.js')}}"></script>
<!-- daterangepicker -->
<script src="{{url('public/bower_components/moment/min/moment.min.js')}}"></script>
<script src="{{url('public/bower_components/bootstrap-daterangepicker/daterangepicker.js')}}"></script>
<!-- datepicker -->
<script src="{{url('public/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="{{url('public/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js')}}"></script>
<!-- datatable -->
<script src="{{url('public/bower_components/datatables.net/js/jquery.dataTables.min.js')}}"></script>
<script src="{{url('public/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js')}}"></script>
<!-- Slimscroll -->
<script src="{{url('public/bower_components/jquery-slimscroll/jquery.slimscroll.min.js')}}"></script>
<!-- FastClick -->
<script src="{{url('public/bower_components/fastclick/lib/fastclick.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{url('public/dist/js/adminlte.min.js')}}"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="{{url('public/dist/js/pages/dashboard.js')}}"></script>
<script src="{{url('public/bower_components/select2/dist/js/select2.full.min.js')}}"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{url('public/dist/js/demo.js')}}"></script>
<script src="{{url('public/js/myscript.js')}}"></script>
<!-- FLOT CHARTS -->
<script src="{{url('public/bower_components/Flot/jquery.flot.js')}}"></script>
<!-- FLOT CATEGORIES PLUGIN - Used to draw bar charts -->
<script src="{{url('public/bower_components/Flot/jquery.flot.categories.js')}}"></script>
<!-- bootstrap time picker -->
<script src="{{url('public/plugins/timepicker/bootstrap-timepicker.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/sweetalert2.all.min.js') }}"></script>
<!-- <script src="{{url('bower_components/ckeditor/ckeditor.js')}}"></script> -->

<script src="https://www.skillassessment.org/apparel/DataTableLib/dataTables.buttons.min.js"></script>
<script src="https://www.skillassessment.org/apparel/DataTableLib/buttons.flash.min.js"></script>
<script src="https://www.skillassessment.org/apparel/DataTableLib/jszip.min.js"></script>
<!-- <script src="https://www.skillassessment.org/apparel/DataTableLib/pdfmake.min.js"></script> -->
<script src="https://www.skillassessment.org/apparel/DataTableLib/vfs_fonts.js"></script>
<script src="https://www.skillassessment.org/apparel/DataTableLib/buttons.html5.min.js"></script>
<script src="https://www.skillassessment.org/apparel/DataTableLib/buttons.print.min.js"></script>
@yield('footer_script')
<script>
  $(function () {
    $('.select2').select2()
    $('#example1').DataTable()
    $('#example2').DataTable({
      'paging'      : true,
      'lengthChange': false,
      'searching'   : false,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : false
    })


    //Timepicker
    $('.timepicker').timepicker({
      showInputs: false,
      // timeFormat: 'h:i A',
      // useCurrent: false
      // disableTimeRanges: [['12am', getCurrentTime(new Date())]]
    })

  })
</script>
</body>
</html>
