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
.control-label{
  padding-right: 15px;
}
.input-group{
  margin-top: 5px;
}
.form-control:focus{
  background-color: rgb(188, 248, 240);
}
</style>
@endsection
<div class="container">
	<div class="card m-0">
    <div class="card-header pt-3  flex-row align-items-center justify-content-between">
      <h5 class="m-0 font-weight-bold">All Customer</h5>
     </div>
    <div class="card-body px-3 px-md-5">
        <!-- Modal -->  
              <!--modal body-->
              <div class="modal fade bd-example-modal-lg">
                <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel"></h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="ModalClose()">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body" id="forms">
                  
                </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-dark" onclick="ModalClose()" data-dismiss='modal' aria-label='Close'>Close</button>
                    <button type="button" class="btn btn-primary submit" onclick="ajaxRequest()"></button>
                  </div>
              </div>
            </div>
            </div>
        <!--End modal-->
        <table class="table table-sm text-center table-bordered table-striped data-table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Date</th>
              <th>Customer Name</th>
              <th>Total Item</th>
              <th>Total Payable</th>
              <th>Total</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
    </div>
  </div>
</div>
@endsection
@section('script')
<script type="text/javascript">
   $('.data-table').DataTable({
        processing:true,
        serverSide:true,
        ajax:{
          url:"{{ URL::to('/admin/all-invoices') }}"
        },
        columns:[
          {
            data:'DT_RowIndex',
            name:'DT_RowIndex',
            orderable:false,
            searchable:false
          },
          {
            data:'dates',
            name:'dates',
          },
          {
            data:'name',
            name:'name',
          },
          {
            data:'total_item',
            name:'total_item',
          },
          {
            data:'total_payable',
            name:'total_payable',
          },
          {
            data:'total',
            name:'total',
          },
          {
            data:'action',
            name:'action',
          }
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

$(document).on('click','.edit',function(){
  $('#exampleModalLabel').text('Update Supplier');
  $('.submit').text('Update');
$('.modal').modal('show');
  id=$(this).data('id');
  $('#id').val(id);
  axios.get('admin/get-customer/'+id)
  .then(function(response){
    var keys=Object.keys(response.data[0]);
    console.log(response.data[0])
    for (var i = 0; i < keys.length; i++) {
      $('#'+keys[i]).val(response.data[0][keys[i]])
    }
  })
})
 //ajax request from employee.js
function ajaxRequest(){
    $('.invalid-feedback').hide();
    $('input').css('border','1px solid rgb(209,211,226)');
    $('select').css('border','1px solid rgb(209,211,226)');
    let company_name=$('#company_name').val();
    let client_name=$('#name').val();
    let previous_due=$('#previous_due').val();
    let maximum_due=$('#maximum_due').val();
    let phone1=$('#phone1').val();
    let phone2=$('#phone2').val();
    let email=$('#email').val();
    let birth_date=$('#birth_date').val();
    let mariage_date=$('#mariage_date').val();
    let adress=$('#adress').val();
    let city=$('#city').val();
    let postal_code=$('#postal_code').val();
    let stutus=$('#stutus').val();
    let group=$('#group_types').val();
    let file=document.getElementById('file').files;
    console.log(maximum_due,previous_due)
    let formData= new FormData();
    formData.append('company_name',company_name);
    formData.append('name',client_name);
    formData.append('previous_due',previous_due);
    formData.append('maximum_due',maximum_due);
    formData.append('phone1',phone1);
    formData.append('phone2',phone2);
    formData.append('email',email);
    formData.append('birth_date',birth_date);
    formData.append('mariage_date',mariage_date);
    formData.append('adress',adress);
    formData.append('city',city);
    formData.append('postal_code',postal_code);
    formData.append('stutus',stutus);
    formData.append('group_types',group);
    // for(var [key,value] of formData.entries()){
    //     console.log(key,value);
    // }
    if (file[0]!=null) {
      formData.append('photo',file[0]); 
    }
    //axios post request
  axios.post('/admin/customer/'+$('#id').val(),formData)
  .then(function (response){
    console.log(response);
    if (response.data.message=='success') {
      window.toastr.success('Client Updated Success');
      document.getElementById('myForm').reset();
      $('#imagex').attr('src','http://localhost/accounts/public/storage/admin-lte/dist/img/avatar5.png');
      $('.invalid-feedback').hide();
      $('input').css('border','1px solid rgb(209,211,226)');
      $('select').css('border','1px solid rgb(209,211,226)');
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
    axios.delete('/admin/customer/'+id,{_method:'DELETE'})
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
   function ModalClose(){
  document.getElementById('myForm').reset();
  $('#photo').attr('src','http://localhost/accounts/public/storage/admin-lte/dist/img/avatar5.png');
  $('.invalid-feedback').hide();
  $('input').css('border','1px solid rgb(209,211,226)');
  $('select').css('border','1px solid rgb(209,211,226)');
 }
 </script>
@endsection
