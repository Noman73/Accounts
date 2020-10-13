<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  @php
  $info=DB::table('information')->select('company_name','logo')->get()->first();
  @endphp
  <title>{{$info->company_name}}</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Font Awesome -->
  <link rel="stylesheet" type="text/css" href="{{ asset('css/app.css') }}">
  <link rel="shortcut icon" href="{{asset('storage/logo/'.$info->logo)}}" type="image/ico">
  <!-- <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css"> -->
  <!-- Ionicons -->
  @yield('link')
  <style>
    .delete{
      color:red;
    }
    .receive{
      background-color:#F8A300;
      margin-right: 5px;
    }
    .invoice{
      background-color:#8DC78A;
      margin-right: 5px;
    }
  </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      
    </ul>

    <!-- SEARCH FORM -->
    <form class="form-inline ml-3">
      <div class="input-group input-group-sm">
        <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
        <div class="input-group-append">
          <button class="btn btn-navbar" type="submit">
            <i class="fas fa-search"></i>
          </button>
        </div>
      </div>
    </form>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <li>
        <a class="nav-link receive font-weight-bold" href="{{URL::to('admin/voucer')}}">New Receive</a>
      </li>
      <li>
        <a class="nav-link invoice font-weight-bold" href="{{URL::to('admin/invoice')}}">New Invoice</a>
      </li>
      <a class="nav-link" href="{{ route('logout') }}" 

                                    onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
          <i class="fa fa-power-off" aria-hidden="true"></i>
          <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
              @csrf
          </form>
        </a>

      <!-- Messages Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="fas fa-comments"></i>
          <span class="badge badge-danger navbar-badge">3</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <a href="#" class="dropdown-item">
            <!-- Message Start -->
            <div class="media">
              <img src="dist/img/user1-128x128.jpg" alt="User Avatar" class="img-size-50 mr-3 img-circle">
              <div class="media-body">
                <h3 class="dropdown-item-title">
                  Brad Diesel
                  <span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span>
                </h3>
                <p class="text-sm">Call me whenever you can...</p>
                <p class="text-sm text-muted"><i class="fas fa-clock mr-1"></i> 4 Hours Ago</p>
              </div>
            </div>
            <!-- Message End -->
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <!-- Message Start -->
            <div class="media">
              <img src="dist/img/user8-128x128.jpg" alt="User Avatar" class="img-size-50 img-circle mr-3">
              <div class="media-body">
                <h3 class="dropdown-item-title">
                  John Pierce
                  <span class="float-right text-sm text-muted"><i class="fas fa-star"></i></span>
                </h3>
                <p class="text-sm">I got your message bro</p>
                <p class="text-sm text-muted"><i class="fas fa-clock mr-1"></i> 4 Hours Ago</p>
              </div>
            </div>
            <!-- Message End -->
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <!-- Message Start -->
            <div class="media">
              <img src="dist/img/user3-128x128.jpg" alt="User Avatar" class="img-size-50 img-circle mr-3">
              <div class="media-body">
                <h3 class="dropdown-item-title">
                  Nora Silvester
                  <span class="float-right text-sm text-warning"><i class="fas fa-star"></i></span>
                </h3>
                <p class="text-sm">The subject goes here</p>
                <p class="text-sm text-muted"><i class="fas fa-clock mr-1"></i>4 Hours Ago</p>
              </div>
            </div>
            <!-- Message End -->
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item dropdown-footer">See All Messages</a>
        </div>
      </li>
      <!-- Notifications Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="fas fa-bell"></i>
           @php 
            $notification=DB::select('select details,action,created_at from notifications');
            $counter=count($notification);
            @endphp
          <span class="badge badge-warning navbar-badge">{{$counter}}</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" id='messageBox'>
          <span class="dropdown-item dropdown-header"> </span>
          @foreach($notification as $notice)
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item ">
            <i class="fas fa-bell mr-2 {{$notice->action}}"></i> 
            <p class="d-inline">
              @php
               echo htmlspecialchars_decode($notice->details);
              @endphp 
            </p>
            <span class="float-right text-muted text-sm">@php
              $dt = Carbon\Carbon::parse($notice->created_at);
              echo $dt->diffForHumans();
              @endphp</span>
          </a>
          <div class="dropdown-divider"></div>
          @endforeach
          <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
          <i class="fas fa-th-large"></i>
        </a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
      <img src="{{asset('storage/logo/'.$info->logo)}}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-light">{{$info->company_name}}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        
        <div class="info">
          <a href="#" class="d-block"><i class="fas fa-circle text-success"></i> {{Auth::user()->name}}</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2 pb-5">
        <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
         
          
        
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-chart-pie"></i>
              <p>
                Banks
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ URL::to('/admin/banks') }}" class="nav-link">
                  <i class="fas fa-circle nav-icon"></i>
                  <p>Manage Bank</p>
                </a>
              </li>
            
            </ul>
          </li>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-chart-pie"></i>
              <p>
                Bill/Invoice
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ URL::to('/admin/invoice') }}" class="nav-link">
                  <i class="fas fa-circle nav-icon"></i>
                  <p>New Invoice</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ URL::to('/admin/invoice_back') }}" class="nav-link">
                  <i class="fas fa-circle nav-icon"></i>
                  <p>Sales Return</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-chart-pie"></i>
              <p>
                Employee
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ URL::to('/admin/employee') }}" class="nav-link">
                  <i class="fas fa-circle nav-icon"></i>
                  <p>Manage Employee</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-chart-pie"></i>
              <p>
                Supplier
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ URL::to('/admin/supplier') }}" class="nav-link">
                  <i class="fas fa-circle nav-icon"></i>
                  <p>Manage Supplier</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-chart-pie"></i>
              <p>
                Customer/Client
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview ">
              <li class="nav-item">
                <a href="{{ URL::to('/admin/customer') }}" class="nav-link">
                  <i class="fas fa-circle nav-icon"></i>
                  <p>Add Customer/Client</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ URL::to('/admin/all-customer') }}" class="nav-link">
                  <i class="fas fa-circle nav-icon"></i>
                  <p>All Customer</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-chart-pie"></i>
              <p>
                Products
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ URL::to('/admin/product') }}" class="nav-link">
                  <i class="fas fa-circle nav-icon"></i>
                  <p>Manage Product</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ URL::to('/admin/product_type') }}" class="nav-link">
                  <i class="fas fa-circle nav-icon"></i>
                  <p>Manage Product Type</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ URL::to('/admin/category') }}" class="nav-link">
                  <i class="fas fa-circle nav-icon"></i>
                  <p>Manage Category</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ URL::to('/admin/child_category') }}" class="nav-link">
                  <i class="fas fa-circle nav-icon"></i>
                  <p>Manage Child Category</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-chart-pie"></i>
              <p>
                Purchase
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ URL::to('/admin/purchase') }}" class="nav-link">
                  <i class="fas fa-circle nav-icon"></i>
                  <p>add Purchase</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ URL::to('/admin/purchase_return') }}" class="nav-link">
                  <i class="fas fa-circle nav-icon"></i>
                  <p>Purchase Return</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-chart-pie"></i>
              <p>
                Stock
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ URL::to('/admin/stock') }}" class="nav-link">
                  <i class="fas fa-circle nav-icon"></i>
                  <p>Stock</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-chart-pie"></i>
              <p>
                Create Voucer
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ URL::to('/admin/voucer') }}" class="nav-link">
                  <i class="fas fa-circle nav-icon"></i>
                  <p>Manage Voucer</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-chart-pie"></i>
              <p>
                Custom Report
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ URL::to('/admin/name') }}" class="nav-link">
                  <i class="fas fa-circle nav-icon"></i>
                  <p>Manage Name</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ URL::to('/admin/name_relation') }}" class="nav-link">
                  <i class="fas fa-circle nav-icon"></i>
                  <p>Manage Name-Relation</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-chart-pie"></i>
              <p>
                Reports
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ URL::to('/admin/running-total') }}" class="nav-link">
                  <i class="fas fa-circle nav-icon"></i>
                  <p>Running Total</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ URL::to('/admin/invoice_summery') }}" class="nav-link">
                  <i class="fas fa-circle nav-icon"></i>
                  <p>Invoice Summery</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ URL::to('/admin/daily_statement') }}" class="nav-link">
                  <i class="fas fa-circle nav-icon"></i>
                  <p>Daily Statement</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ URL::to('/admin/buyerlistform') }}" class="nav-link">
                  <i class="fas fa-circle nav-icon"></i>
                  <p>Buyer List</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ URL::to('/admin/sales_report') }}" class="nav-link">
                  <i class="fas fa-circle nav-icon"></i>
                  <p>C-W-Sales-Report</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-chart-pie"></i>
              <p>
                Barcode
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ URL::to('/admin/barcode') }}" class="nav-link">
                  <i class="fas fa-circle nav-icon"></i>
                  <p>Genarate Barcode</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-chart-pie"></i>
              <p>
                Setting
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ URL::to('/admin/info_form') }}" class="nav-link">
                  <i class="fas fa-circle nav-icon"></i>
                  <p>Add Info</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ URL::to('/admin/name_relation') }}" class="nav-link">
                  <i class="fas fa-circle nav-icon"></i>
                  <p>Manage Name-Relation</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-chart-pie"></i>
              <p>
                Backup
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ URL::to('/admin/backup') }}" class="nav-link">
                  <i class="fas fa-circle nav-icon"></i>
                  <p>DB Backup</p>
                </a>
              </li>
            </ul>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    @yield('content')
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <strong>Copyright &copy; 2020 <a href="http://devtunes-technology.com">DevTunes Technology</a>.</strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      <b>Version</b>1.0.0
    </div>
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="{{ asset('js/app.js') }}"></script>
@yield('script')
<script>
$(document).ready(function(){
    const url = window.location;

    $('ul.nav-sidebar a').filter(function() {
        return this.href == url;
    }).parent().addClass('active');

    $('ul.nav-treeview a').filter(function() {
        return this.href == url;
    }).parentsUntil(".sidebar-menu > .nav-treeview").addClass('menu-open');

    $('ul.nav-treeview a').filter(function() {
        return this.href == url;
    }).addClass('active');

    $('li.has-treeview a').filter(function() {
        return this.href == url;
    }).addClass('active');

    $('ul.nav-treeview a').filter(function() {
        return this.href == url;
    }).parentsUntil(".sidebar-menu > .nav-treeview").children(0).addClass('active');

});
</script>
</body>
</html>
