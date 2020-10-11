@extends('layouts.master')
@section('content')
<div class="container">
	<div class="card m-0">
    <div class="card-header pt-3  flex-row align-items-center justify-content-between">
      <h5 class="m-0 font-weight-bold">Manage Supplier</h5>
     </div>
    <div class="card-body px-3 px-md-5">
		  	<button type="button" class="btn btn-primary" {{-- data-toggle="modal" data-target="#exampleModal" --}} onclick="AddNew()">
          Add New <i class="fas fa-plus"></i>
        </button>

        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="ModalClose()">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <!--modal body-->
              <div class="modal-body">
                <form id="myForm">
                  <input type="hidden" id="id">
                  <div class="form-group">
                    <label for="name" class="font-weight-bold">Name:</label>
                    <input class="form-control form-control-sm" id="name"  type="text" placeholder="Enter Supplier Name...">
                    <div id="name_msg" class="invalid-feedback">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="email" class="font-weight-bold">Email:</label>
                    <input class="form-control form-control-sm" id="email"  type="text" placeholder="Enter Email Account...">
                    <div id="email_msg" class="invalid-feedback">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="phone" class="font-weight-bold">Phone:</label>
                    <input class="form-control form-control-sm" id="phone"  type="text" placeholder="Enter Phone Number...">
                    <div id="phone_msg" class="invalid-feedback">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="adress" class="font-weight-bold">Adress:</label>
                    <input class="form-control form-control-sm" id="adress"  type="text" placeholder="Enter Supplier Adress...">
                    <div id="adress_msg" class="invalid-feedback">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="adress" class="font-weight-bold">Supplier Type:</label>
                    <select class="form-control form-control-sm" id="supplier_type">
                      <option value="">--select--</option>
                      <option value="Distributor">Distributor</option>
                      <option value="Whole Saler">Whole Saler</option>
                      <option value="Company">Company</option>
                    </select>
                    <div id="supplier_type_msg" class="invalid-feedback">
                    </div>
                  </div>
                </form>
               <!--end second column -->
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="ModalClose()" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary submit" onclick="ajaxRequest($('#id').val())"></button>
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
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Adress</th>
                        <th>Supplier Type</th>
                        <th>Action</th>
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
          url:"{{ URL::to('/admin/supplier') }}"
        },
        columns:[
          {
            data:'DT_RowIndex',
            name:'DT_RowIndex',
            orderable:false,
            searchable:false
          },
          {
            data:'name',
            name:'name',
          },
          {
            data:'phone',
            name:'phone',
          },
          {
            data:'adress',
            name:'adress',
          },
           {
            data:'supplier_type',
            name:'supplier_type',
          },
          {
            data:'action',
            name:'action',
          }
        ]
    });
function AddNew(){
document.getElementById('myForm').reset();
$('#id').val('');
$('#exampleModalLabel').text('Add New Supplier');
$('.submit').text('Save');
$('.modal').modal('show');
}
$(document).on('click','.edit',function(){
  $('#exampleModalLabel').text('Update Supplier');
  $('.submit').text('Update');
$('.modal').modal('show');
  id=$(this).data('id');
  $('#id').val(id);
  axios.get('admin/get-supplier/'+id)
  .then(function(response){
    console.log(response);
    $('#name').val(response.data[0].name);
    $('#email').val(response.data[0].email);
    $('#adress').val(response.data[0].adress);
    $('#phone').val(response.data[0].phone);
    $('#supplier_type').val(response.data[0].supplier_type);
  })
})
 //ajax request from employee.js
function ajaxRequest(id){
    $('.invalid-feedback').hide();
    $('input').css('border','1px solid rgb(209,211,226)');
    $('select').css('border','1px solid rgb(209,211,226)');
    let name=$('#name').val();
    let email=$('#email').val();
    let adress=$('#adress').val();
    let phone=$('#phone').val();
    let supplierType=$('#supplier_type').val();
    let formData= new FormData();
    formData.append('name',name);
    formData.append('email',email);
    formData.append('adress',adress);
    formData.append('phone',phone);
    formData.append('supplier_type',supplierType);
    //axios post request
    if (!id){
         axios.post('/admin/supplier',formData)
        .then(function (response){
          console.log(response);
          if (response.data.message=='success') {
            window.toastr.success('Supplier Added Success');
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
          console.log(error.request);
        });
    }else{
      axios.post('/admin/supplier/'+id,formData)
        .then(function (response){
          console.log(response);
          if (response.data.message=='success') {
            window.toastr.success('Supplier Updated Success');
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
          console.log(error.request);
        });
    }
  

 }
 function ModalClose(){
  document.getElementById('myForm').reset();
  $('.invalid-feedback').hide();
  $('input').css('border','1px solid rgb(209,211,226)');
  $('select').css('border','1px solid rgb(209,211,226)');
 }
 $('table').on('click','.delete',function(){
   console.log('xxx');
     Swal.fire({
  title: "Are you sure?",
  text: "Once deleted, you will not be able to recover this imaginary file!",
  icon: "warning",
  showCancelButton: true,
  // dangerMode: true,
  confirmButtonColor: "#DD6B55",
  cancelButtonText: "CANCEL",
  confirmButtonText: "CONFIRM",
})
.then((isConfirmed) => {
  if (isConfirmed.isConfirmed) {
  var id=$(this).data('id');
    console.log(id);
    axios.delete('/admin/supplier/'+id,{_method:'DELETE'})
      .then((res)=>{
        if (res.data.message=='success') {
          window.toastr.success('Supplier Deleted Success');
          $('.data-table').DataTable().ajax.reload();
        }
      })
      .catch((error)=>{
        console.log(error.request);
      })
  }
});
 })
 </script>
@endsection
