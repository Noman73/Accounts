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
    .input-group{
      margin-top:5px;
    }
    .nav-user-dropdown{
      min-width: 230px;
    }
    .nav-user-info{
      line-height: 1.4;
      padding: 12px;
      color: #fff;
      font-size: 13px;
      border-radius: 2px 2px 0 0;
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
        <button class="nav-link receive font-weight-bold btn" onclick='MasterModal()'>New Voucer</button>
      </li>
      <li>
        <a class="nav-link invoice btn font-weight-bold" href="{{URL::to('admin/invoice')}}">New Invoice</a>
      </li>
      <!-- user Dropdown -->
      <li class="nav-item dropdown nav-user">
                            <a class="nav-link nav-user-img" href="#" id="navbarDropdownMenuLink2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-user-circle"></i></a>
                            <div class="dropdown-menu dropdown-menu-right nav-user-dropdown" aria-labelledby="navbarDropdownMenuLink2">
                                <div class="nav-user-info">
                                    <h5 class="mb-0 text-dark nav-user-name">{{Auth::user()->name}}</h5>
                                    <span class="status"></span><span class="ml-2 text-success">Available</span>
                                </div>
                                <a class="dropdown-item" href="#"><i class="fas fa-user mr-2"></i>Account</a>
                                <a class="dropdown-item" href="#"><i class="fas fa-cog mr-2"></i>Password Change</a>
                                <a class="dropdown-item" href="{{ route('logout') }}" 

                                    onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
          <i class="fa fa-power-off mr-2" aria-hidden="true"></i>
          <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
              @csrf
          </form>
           Logout
        </a>
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
              <i class="nav-icon fas fa-donate"></i>
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
              <i class="nav-icon fas fa-file-invoice"></i>
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
              <li class="nav-item">
                <a href="{{ URL::to('/admin/all_invoice') }}" class="nav-link">
                  <i class="fas fa-circle nav-icon"></i>
                  <p>All Invoice</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-user-circle"></i>
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
              <i class="nav-icon fas fa-user-circle"></i>
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
              <i class="nav-icon fas fa-user-circle"></i>
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
              <i class="nav-icon fab fa-product-hunt"></i>
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
              <i class="nav-icon fas fa-shopping-cart"></i>
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
              <i class="nav-icon fas fa-layer-group"></i>
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
              <i class="nav-icon fas fa-money-check-alt"></i>
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
              <i class="nav-icon fas fa-file-alt"></i>
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
              <i class="nav-icon fas fa-poll"></i>
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
                <a href="{{ URL::to('/admin/getbuyerbalanceform') }}" class="nav-link">
                  <i class="fas fa-circle nav-icon"></i>
                  <p>Buyer Balance</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ URL::to('/admin/cash_details_form') }}" class="nav-link">
                  <i class="fas fa-circle nav-icon"></i>
                  <p>Cash Details</p>
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
              <i class="nav-icon fas fa-barcode"></i>
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
              <i class="nav-icon fas fa-cog"></i>
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
            </ul>
          </li>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-database"></i>
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
  <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" id="modal-voucer">
<div class="modal-dialog modal-lg">
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title" id="VModalLabel"></h5>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="ModalClose()">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <!--modal body-->
    <div class="modal-body" id="forms">
      <form id="myMasterForm">
      <div class="input-group">
        <label class="control-label col-sm-3 text-lg-right" for="date">Date:</label>
        <div class="col-md-7">
          <input type="text" id="master-date" class="master-date form-control form-control-sm">
        </div>
      </div>
      <div class="input-group">
        <label class="control-label col-sm-3 text-lg-right" for="categories">Select Category:</label>
        <div class="col-md-7">
          <select type="text" id="master-category" class="form-control form-control-sm" onchange="getMasterCat(this)">
            <option value="">--SELECT--</option>
          </select>
        </div>
      </div>
      <div class="input-group">
        <label class="control-label col-sm-3 text-lg-right" id='data-label' for="childCategory"></label>
        <div class="col-md-7">
          <select type="text" id="master-data" class="form-control form-control-sm">
            <option value="">--SELECT--</option>
          </select>
        </div>
      </div>
      <div class="input-group">
        <label class="control-label col-sm-3 text-lg-right" for="payment">Payment Type:</label>
        <div class="col-md-7">
          <select type="text" id="master-payment_type" class="form-control form-control-sm">
            <option value="">--SELECT--</option>
            <option value="Deposit">Deposit</option>
            <option value="Expence">Expence</option>
          </select>
        </div>
      </div>
      <div class="input-group">
        <label class="control-label col-sm-3 text-lg-right" for="bank">Select Bank:</label>
        <div class="col-md-7">
          <select type="text" id="master-bank" class="form-control form-control-sm">
            <option value="">--SELECT--</option>
          </select>
        </div>
      </div>
      <div class="input-group">
        <label class="control-label col-sm-3 text-lg-right" for="product">Ammount $:</label>
        <div class="col-md-7">
          <input type="text" id="master-ammount" class="form-control form-control-sm" placeholder="Enter rate......">
        </div>
      </div>
     </form>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      <button type="button" class="btn btn-primary" onclick="MasterAjaxRequest()">Save changes</button>
    </div>
  </div>
</div>
</div>
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
  </aside>
</div>
{{-- voucer modal --}}

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

function MasterModal(){
  $('#modal-voucer').modal('show');
  $('#VModalLabel').text('Create New Voucer');
  document.getElementById('myMasterForm').reset();
  axios.get('admin/getVoucerFormData')
  .then((res)=>{
    console.log(res)
   data=Object.keys(res.data);
   banks="<option>--select--</option>";
   category="<option>--select--</option>";
   for (var i = 0; i < data.length; i++) {
     for (var x = 0; x < res.data[data[i]].length; x++) {
      if (data[i]=='banks'){
        banks+="<option value='"+res.data[data[i]][x].id+"'>"+res.data[data[i]][x].name+"</option>";
      }else if(data[i]=='category'){
        category+="<option value='"+res.data[data[i]][x].id+"'>"+res.data[data[i]][x].name+"</option>";
      }
     }
   }
   $('#master-category').html(category);
   $('#master-bank').html(banks);
  })
}
function getMasterCat(data){
  category=data.options[data.selectedIndex].text;
  $('#data-label').text(category+':');
  console.log(data.value);
  $('#master-data').html('');
  $('.master-data').removeClass('d-none');
  switch(category){
    case 'customer':
    url="{{URL::to('admin/')}}"+'/search_customer';
    method='post';
    break;
    case 'supplier':
    url="{{URL::to('admin/')}}"+'/search_supplier';
    method='post';
    break;
    default :
    url="{{URL::to('admin/')}}"+'/relation_search/'+data.value;
    method='get';
    break;
  }
  console.log(category);
  $('#master-data').select2({
    theme:'bootstrap4',
    placeholder:'Select '+category+'....',
    allowClear:true,
    ajax:{
      url:url,
      type:method,
      dataType:'json',
      delay:20,
      data:function(params){
        return {
          searchTerm:params.term,
          _token:"{{csrf_token()}}",
          }
      },
      processResults:function(response){
        return {
          results:response,
        }
      },
      cache:true,
    }
  })
}
function MasterAjaxRequest(){
    $('.invalid-feedback').hide();
    $('input').css('border','1px solid rgb(209,211,226)');
    $('select').css('border','1px solid rgb(209,211,226)');
    let main_date=$('#master-date').val();
    let main_category=$('#master-category option:selected').text();
    let main_data=$('#master-data').val();
    let main_payment_type=$('#master-payment_type').val();
    let main_bank=$('#master-bank').val();
    let main_ammount=$('#master-ammount').val();
    let formData= new FormData();
    console.log(main_category);
    formData.append('date',main_date);
    formData.append('category',main_category);
    formData.append('data',main_data);
    formData.append('payment_type',main_payment_type);
    formData.append('bank',main_bank);
    formData.append('ammount',main_ammount);
    //axios post request
  axios.post('/admin/voucer',formData)
  .then(function (response){
    console.log(response);
    if (response.data.message=='success') {
      window.toastr.success('Purchase Added Success');
      $('.data-table').DataTable().ajax.reload();
      document.getElementById('myForm').reset();
    }
    var keys=Object.keys(response.data[0]);
    for(var i=0; i<keys.length;i++){
        $('#'+keys[i]+'_msg').html(response.data[0][keys[i]][0]);
        $('#'+keys[i]).css('border','1px solid red');
        $('#'+keys[i]+'_msg').show();
      }
  })
   .catch(function (error) {
    console.log(error.request.response);
  });
 }
// $('select').select2({
//   placeholder:'select',
//   theme:"bootstrap4",
// })
 $('#master-date').daterangepicker({
 showDropdowns:true,
 singleDatePicker: true,
 locale: {
    format: 'DD-MM-YYYY',
  },
  minDate: '01-01-1950',
  maxDate: '01-01-2050'
});

 console.log(n2words(999999999999998))
</script>
</body>
</html>
