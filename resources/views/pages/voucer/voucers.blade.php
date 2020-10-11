@extends('layouts.master')
@section('content')
@section('link')
<style type="text/css">
  .file {
    border: 1px solid #ccc;
    display: inline-block;
    width: 100px;
    cursor: pointer;
    background-color:green;
    color:white;
}
.file:hover{
  background-color:#fff000;
}
.image-upload{
  margin:0 auto;
}
.input-group{
  margin-top: 5px;
}
</style>
@endsection
<section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Manage Voucers</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ URL::to('/home') }}">Home</a></li>
              <li class="breadcrumb-item">Voucer</li>
              <li class="breadcrumb-item active">Manage Voucers</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
<div class="container">
	<div class="card m-0">
    <div class="card-header pt-3  flex-row align-items-center justify-content-between">
      <h5 class="m-0 font-weight-bold">Manage Voucers</h5>
     </div>
    <div class="card-body px-3 px-md-5">
		  	<button type="button" class="btn btn-primary" data-toggle="modal" data-target=".bd-example-modal-lg">
          Create New <i class="fas fa-plus"></i>
        </button>

        <!-- Modal -->
        <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Create 
                New Voucers</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="ModalClose()">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <!--modal body-->
              <div class="modal-body" id="forms">
                <form id="myForm">
                <div class="input-group">
                  <label class="control-label col-sm-3 text-lg-right" for="date">Date:</label>
                  <div class="col-md-7">
                    <input type="text" id="date" class="date form-control form-control-sm">
                  </div>
                </div>
                <div class="input-group">
                  <label class="control-label col-sm-3 text-lg-right" for="supplier">Select Category:</label>
                  <div class="col-md-7">
                    <select type="text" id="category" onchange="getVoucerRelName()" class="form-control form-control-sm">
                      <option value="">--SELECT--</option>
                      @foreach($names as $name)
                      <option value="{{$name->name}}">{{$name->name}}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="input-group">
                  <label class="control-label col-sm-3 text-lg-right" for="childCategory">Select Data:</label>
                  <div class="col-md-7">
                    <select type="text" id="data" class="form-control form-control-sm">
                      <option value="">--SELECT--</option>
                    </select>
                  </div>
                </div>
                <div class="input-group">
                  <label class="control-label col-sm-3 text-lg-right" for="payment">Pyment Type:</label>
                  <div class="col-md-7">
                    <select type="text" id="payment_type" class="form-control form-control-sm">
                      <option value="">--SELECT--</option>
                      <option value="Deposit">Deposit</option>
                      <option value="Expence">Expence</option>
                    </select>
                  </div>
                </div>
                <div class="input-group">
                  <label class="control-label col-sm-3 text-lg-right" for="bank">Select Bank:</label>
                  <div class="col-md-7">
                    <select type="text" id="bank" class="form-control form-control-sm">
                      <option value="">--SELECT--</option>
                      @foreach($banks as $bank)
                      <option value="{{$bank->id}}">{{$bank->name}}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="input-group">
                  <label class="control-label col-sm-3 text-lg-right" for="product">Ammount $:</label>
                  <div class="col-md-7">
                    <input type="text" id="ammount" class="form-control form-control-sm" placeholder="Enter rate......">
                  </div>
                </div>
               </form>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="ModalClose()" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="ajaxRequest()">Save changes</button>
              </div>
            </div>
          </div>
        </div>
        {{-- datatable start --}}
        {{-- <div class="container-fluid" id="container-wrapper"> --}}
            <!-- Datatables -->
                <div class="table-responsive mt-2">
                  <table class="table table-sm table-bordered table-striped align-items-center display table-flush data-table">
                    <thead class="thead-light">
                     <tr>
                        <th>No.</th>
                        <th>Date</th>
                        <th>Name</th>
                        <th>Ammount</th>
                        <th>Payment Type</th>
                        <th>Bank Name</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                  </table>
                </div>
        {{-- datatable end --}}
    </div>
  </div>
</div>
@endsection
@section('script')
<script type="text/javascript">
   $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }

    });
    $('.data-table').DataTable({
        processing:true,
        serverSide:true,
        ajax:{
          url:"{{ URL::to('/admin/voucer') }}"
        },
        columns:[
          {
            data:'DT_RowIndex',
            name:'DT_RowIndex',
            orderable:false,
            searchable:false
          },
          {
            data:'dat',
            name:'dat',
          },
          {
            data:'name',
            name:'name',
          },
          {
            data:'ammount',
            name:'ammount',
          },
          {
            data:'payment_type',
            name:'payment_type',
          },
          {
            data:'bank_name',
            name:'bank_name',
          },
       ]
    });
// read Image 
 function readURL(input) {
      if (input.files && input.files[0]) {
          var reader = new FileReader();
          reader.onload = function (e) {
            document.getElementById('imagex').setAttribute('src', e.target.result)
          };
          reader.readAsDataURL(input.files[0]);
      }
   }
 //ajax request from employee.js
function ajaxRequest(){
    $('.invalid-feedback').hide();
    $('input').css('border','1px solid rgb(209,211,226)');
    $('select').css('border','1px solid rgb(209,211,226)');
    let date=$('#date').val();
    let category=$('#category').val();
    let data=$('#data').val();
    let payment_type=$('#payment_type').val();
    let bank=$('#bank').val();
    let ammount=$('#ammount').val();
    let formData= new FormData();
    formData.append('date',date);
    formData.append('category',category);
    formData.append('data',data);
    formData.append('payment_type',payment_type);
    formData.append('bank',bank);
    formData.append('ammount',ammount);
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

 function getVoucerRelName(){
  var category=$('#category').val();
    if (category!='' || category!=undefined || category!=null || category!=NaN){
      axios.get('admin/voucer_get_name/'+category)
      .then(function(response){
        console.log(response.data.length);
        let html="<option value=''>--SELECT--</option>";
        if (response.data.length>0) {
       response.data.forEach(function(d){
          html +="<option value='"+d.id+"'>"+d.name+"</option>"
       })
        }else{
          html+="<option disabled value=''>data not found</option>"
        }
        
       $('#data').html(html);
      })
      .catch(function (error) {
    console.log(error.request.response);
    });

    }
 }

  // get child category
 function ModalClose(){
  document.getElementById('myForm').reset();
  $('#imagex').attr('src','http://localhost/accounts/public/storage/admin-lte/dist/img/avatar5.png');
  $('.invalid-feedback').hide();
  $('input').css('border','1px solid rgb(209,211,226)');
  $('select').css('border','1px solid rgb(209,211,226)');
 }
 // $('#dates').daterangepicker({
 //  locale:{
 //    format:'DD/MMM/YYYY',
 //  },
  
 // },function(start,end,label){
 //  start.format('DD/MMM/YYYY')
 // }); 

 $('.date').daterangepicker({
        showDropdowns: true,
        singleDatePicker: true,
        parentEl: ".bd-example-modal-lg .modal-body",
        locale: {
            format: 'DD-MM-YYYY',
        }
    });

 </script>
@endsection
