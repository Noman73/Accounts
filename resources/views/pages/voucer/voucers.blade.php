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
		  	<button type="button" class="btn btn-primary" data-toggle="modal" data-target=".bd-example-modal-lg" onclick="MasterModal()">
          Create New <i class="fas fa-plus"></i>
        </button>
        {{-- datatable start --}}
        {{-- <div class="container-fluid" id="container-wrapper"> --}}
            <!-- Datatables -->
                <div class="table-responsive mt-2">
                  <table class="table table-sm table-bordered table-striped align-items-center text-center display table-flush data-table">
                    <thead class="thead-dark text-light">
                     <tr>
                        <th>No.</th>
                        <th>Date</th>
                        <th>Name</th>
                        <th>Debit</th>
                        <th>Credit</th>
                        <th>Bank Name</th>
                    </tr>
                    </thead>
                    <tbody class="bg-secondary">
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
            data:'categories',
            name:'categories',
          },
          {
            data:'debit',
            name:'debit',
          },
          {
            data:'credit',
            name:'credit',
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

 $('#data').select2({
    theme:'bootstrap4',
    placeholder:'select',
    allowClear:true,
    ajax:{
      url:"{{URL::to('admin/search_customer')}}",
      type:'post',
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
