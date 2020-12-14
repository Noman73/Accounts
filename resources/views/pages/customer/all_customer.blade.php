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
              <button type="button" class="btn btn-primary mb-1" {{-- data-toggle="modal" data-target="#exampleModal" --}} onclick="AddNew()">
              Add New <i class="fas fa-plus"></i>
              </button>
              <!-- Modal -->  
              <div class="modal fade bd-example-modal-lg" id="Modalx">
                <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel"></h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="ModalClose()">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body" id="forms">
                  <form class="form-horizontal" id="myForm">
                    <input type="hidden" id="id">
                  <div class="text-center">
                      <img id="imagex" src="{{asset('storage/admin-lte/dist/img/avatar5.png')}}" class="d-flex image-upload" style="height:100px;width:100px;">
                      <input class="d-none" type="file" id="file" name="" onchange="readURL(this)">
                      <label for="file"  class="file">choose</label>
                      <div id="photo_msg" class="invalid-feedback">
                       </div>
                  </div>
                 {{-- forms inputs --}}
                 <div class="input-group mt-4">
                 <label class="control-label col-sm-3 text-lg-right" for="name">Company Name :</label>
                 <div class="col-sm-9">
                   <input type="text" class="form-control form-control-sm" id="company_name" placeholder="Enter Company Name....">
                   <div id="company_name_msg" class="invalid-feedback">
                   </div>
                 </div>
               </div>
               <div class="input-group">
                 <label class="control-label col-sm-3 text-lg-right" for="name">Customer/Client Name:</label>
                 <div class="col-sm-9">
                   <input type="text" class="form-control form-control-sm" id="name" placeholder="Enter Customer/Client Name....">
                   <div id="name_msg" class="invalid-feedback">
                   </div>
                 </div>
               </div>
               <div class="input-group input-group-sm">
                  <label class="control-label col-sm-3 text-lg-right" for="name">Opening Balance :</label>
                  <div class="col-sm-7">
                      <input type="text" class="form-control form-control-sm" id="opening_balance" placeholder="Enter Opening Balance....">
                      
                      <div id="opening_balance_msg" class="invalid-feedback">
                      </div>
                  </div>
                  <div class='col-sm-2'>
                    <select type="text" class='form-control form-control-sm' id='balance_type'>
                        <option value="1">Balance</option>
                        <option value="0">Due</option>
                    </select>
                    <div id="opening_balance_msg" class="invalid-feedback">
                      </div>
                  </div>
               </div>
               <div class="input-group">
                 <label class="control-label col-sm-3 text-lg-right" for="name">Maximum Due :</label>
                 <div class="col-sm-9">
                   <input type="text" class="form-control form-control-sm" id="maximum_due" placeholder="Enter Maximum Due....">
                   <div id="maximum_due_msg" class="invalid-feedback">
                   </div>
                 </div>
               </div>
               <div class="input-group">
                 <label class="control-label col-sm-3 text-lg-right" for="phone1">Phone 1 :</label>
                 <div class="col-sm-9">
                   <input type="text" class="form-control form-control-sm" id="phone1" placeholder="Enter Phone No 1....">
                   <div id="phone1_msg" class="invalid-feedback">
                   </div>
                 </div>
               </div>
               <div class="input-group">
                 <label class="control-label col-sm-3 text-lg-right" for="phone2">Phone 2 :</label>
                 <div class="col-sm-9">
                   <input type="text" class="form-control form-control-sm" id="phone2" placeholder="Enter Phone No 2....">
                   <div id="phone2_msg" class="invalid-feedback">
                   </div>
                 </div>
               </div>
               <div class="input-group">
                 <label class="control-label col-sm-3 text-lg-right" for="name">Email :</label>
                 <div class="col-sm-9">
                   <input type="text" class="form-control form-control-sm" id="email" placeholder="Enter Email....">
                   <div id="email_msg" class="invalid-feedback">
                   </div>
                 </div>
               </div>
               <div class="input-group">
                 <label class="control-label col-sm-3 text-lg-right" for="birthDate">Birth Date :</label>
                 <div class="col-sm-9">
                   <input type="text" class="form-control form-control-sm" id="birth_date" placeholder="Enter Birth Date...">
                   <div id="birth_date_msg" class="invalid-feedback">
                   </div>
                 </div>
               </div>
               <div class="input-group">
                 <label class="control-label col-sm-3 text-lg-right" for="mariageDate">Mariage Date :</label>
                 <div class="col-sm-9">
                   <input type="text" class="form-control form-control-sm" id="mariage_date" placeholder="Enter Marriage Date..">
                   <div id="mariage_date_msg" class="invalid-feedback">
                   </div>
                 </div>
               </div>
               <div class="input-group">
                 <label class="control-label col-sm-3 text-lg-right" for="adress">Adress:</label>
                 <div class="col-sm-9">
                   <input type="text" class="form-control form-control-sm" id="adress" placeholder="Enter Adress....">
                   <div id="adress_msg" class="invalid-feedback">
                   </div>
                 </div>
               </div>
               <div class="input-group">
                 <label class="control-label col-sm-3 text-lg-right" for="city">City :</label>
                 <div class="col-sm-9">
                   <input type="text" class="form-control form-control-sm" id="city" placeholder="Enter City Name....">
                   <div id="city_msg" class="invalid-feedback">
                   </div>
                 </div>
               </div>
               <div class="input-group">
                 <label class="control-label col-sm-3 text-lg-right" for="postalCode">Postal Code :</label>
                 <div class="col-sm-9">
                   <input type="text" class="form-control form-control-sm" id="postal_code" placeholder="Enter Postal Code ....">
                   <div id="postal_code_msg" class="invalid-feedback">
                   </div>
                 </div>
               </div>
               <div class="input-group">
                 <label class="control-label col-sm-3 text-lg-right" for="stutus">Stutus :</label>
                 <div class="col-sm-9">
                   <select  class="form-control form-control-sm" id="stutus">
                     <option value="">--SELECT--</option>
                     <option value="1">ACTIVE</option>
                     <option value="0">DEACTIVE</option>
                   </select>
                   <div id="stutus_msg" class="invalid-feedback">
                   </div>
                 </div>
               </div>
               <div class="input-group">
                 <label class="control-label col-sm-3 text-lg-right" for="group">Group :</label>
                 <div class="col-sm-9">
                   <select  class="form-control form-control-sm" id="group_types">
                     <option value="">--SELECT--</option>
                     <option value="Regular">Regular</option>
                     <option value="Walking">Walking</option>
                   </select>
                   <div id="group_types_msg" class="invalid-feedback">
                   </div>
                 </div>
               </div>

                 </form>
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
              <th>Name</th>
              <th>Phone</th>
              <th>Adress</th>
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
          url:"{{ URL::to('/admin/all-customer') }}"
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
            data:'phone1',
            name:'phone1',
          },
          {
            data:'adress',
            name:'adress',
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
$('#Modalx').modal('show');
  id=$(this).data('id');
  $('#id').val(id);
  axios.get('admin/get-customer/'+id)
  .then(function(response){
    var keys=Object.keys(response.data[0]);
    console.log(response.data[0])
    for (var i = 0; i < keys.length; i++) {
      console.log(keys[i]+':',response.data[0][keys[i]])
      if (keys[i]!=='opening_balance') {
         $('#'+keys[i]).val(response.data[0][keys[i]])
      }else{
         if(parseFloat(response.data[0][keys[i]])>0){
            $('#'+keys[i]).val(response.data[0][keys[i]])
            $('#balance_type').val(1)
         }else{
            $('#'+keys[i]).val(response.data[0][keys[i]])
            $('#balance_type').val(0)
         }
      }
    }
    $('#imagex').attr('src','{{asset('storage/customer')}}/'+((response.data[0]['photo']==null) ? 'fixed.jpg' : response.data[0]['photo']))
  })
})
function AddNew(){
document.getElementById('myForm').reset();
$('#id').val('');
$('#exampleModalLabel').text('Add New Customer');
$('.submit').text('Save');
$('#Modalx').modal('show');
}
 //ajax request from employee.js
function ajaxRequest(){

    $('.invalid-feedback').hide();
    $('input').css('border','1px solid rgb(209,211,226)');
    $('select').css('border','1px solid rgb(209,211,226)');
    let id=$('#id').val();
    let company_name=$('#company_name').val();
    let client_name=$('#name').val();
    let opening_balance=$('#opening_balance').val();
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
    let balance_type=$('#balance_type').val();
    let file=document.getElementById('file').files;
    let formData= new FormData();

    formData.append('company_name',company_name);
    formData.append('name',client_name);
    formData.append('opening_balance',opening_balance);
    formData.append('balance_type',balance_type);
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
    if(id){
        //axios post request
          axios.post('/admin/customer/'+$('#id').val(),formData)
          .then(function (response){
            console.log(response);
            if (response.data.message) {
              window.toastr.success(response.data.message);
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
     }else{
        axios.post('/admin/customer',formData)
          .then(function (response){
            console.log(response);
            if (response.data.message) {
              window.toastr.success(response.data.message);
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
  $('#Modalx').modal('hide')
 }
 $('#birth_date,#mariage_date').daterangepicker({
        showDropdowns: true,
        singleDatePicker: true,
        locale:{
            format: 'DD-MM-YYYY',
            separator:' to ',
            customRangeLabel: "Custom",
        },
        minDate: '01-01-1970',
        maxDate: '01/01/2050'
  }) </script>
@endsection
