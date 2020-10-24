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
<div class="container">
	<div class="card m-0">
    <div class="card-header pt-3  flex-row align-items-center justify-content-between">
      <h5 class="m-0 font-weight-bold">Manage Employees</h5>
     </div>
    <div class="card-body px-3 px-md-5">
		  	<button type="button" class="btn btn-primary" onclick="addNew()">
          Add New <i class="fas fa-plus"></i>
        </button>

        <!-- Modal -->
        <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" id="Modalx">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="ModalClose()">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <!--modal body-->
              <div class="modal-body" id="forms">
                <form id="myForm">
                  <input type="hidden" id="id">
                <div class="text-center">
                    <img id="photo" src="{{asset('storage/admin-lte/dist/img/avatar5.png')}}" class="d-flex image-upload photo" style="height:100px;width:100px;">
                    <input class="d-none" type="file" id="file" name="" onchange="readURL(this)">
                    <label for="file"  class="file">choose</label>
                    <div id="photo_msg" class="invalid-feedback">
                     </div>
                </div>
                   <div class="input-group mt-4">
                     <label class="control-label col-sm-3 text-lg-right" for="name">Name :</label>
                     <div class="col-sm-9">
                         <input type="text" class="form-control form-control-sm" id="name" placeholder="Enter Employee Name....">
                         <div id="product_name_msg" class="invalid-feedback">
                         </div>
                      </div>
                    </div>
                   <div class="input-group">
                     <label class="control-label col-sm-3 text-lg-right" for="email">Email :</label>
                     <div class="col-sm-9">
                         <input type="text" class="form-control form-control-sm" id="email" placeholder="Enter Email....">
                         <div id="email_msg" class="invalid-feedback">
                         </div>
                      </div>
                    </div>
                   <div class="input-group">
                     <label class="control-label col-sm-3 text-lg-right" for="phone">Phone :</label>
                     <div class="col-sm-9">
                         <input type="text" class="form-control form-control-sm" id="phone" placeholder="Enter Phone Number....">
                         <div id="phone_msg" class="invalid-feedback">
                         </div>
                      </div>
                    </div>
                   <div class="input-group">
                     <label class="control-label col-sm-3 text-lg-right" for="name">Adress :</label>
                     <div class="col-sm-9">
                         <input type="text" class="form-control form-control-sm" id="adress" placeholder="Enter Adress....">
                         <div id="adress_msg" class="invalid-feedback">
                         </div>
                      </div>
                    </div>
                  <div class="input-group">
                     <label class="control-label col-sm-3 text-lg-right" for="name">Nid :</label>
                     <div class="col-sm-9">
                         <input type="text" class="form-control form-control-sm" id="nid" placeholder="Enter Nid....">
                         <div id="nid_msg" class="invalid-feedback">
                         </div>
                      </div>
                    </div>
                   <div class="input-group">
                     <label class="control-label col-sm-3 text-lg-right" for="name">Experience :</label>
                     <div class="col-sm-9">
                         <input type="text" class="form-control form-control-sm" id="experience" placeholder="Enter Experience....">
                         <div id="experience_msg" class="invalid-feedback">
                         </div>
                      </div>
                    </div>
                   <div class="input-group">
                     <label class="control-label col-sm-3 text-lg-right" for="name">Job Department :</label>
                     <div class="col-sm-9">
                         <input type="text" class="form-control form-control-sm" id="job_department" placeholder="Enter Job Department ....">
                         <div id="job_department_msg" class="invalid-feedback">
                         </div>
                      </div>
                    </div>
                   <div class="input-group">
                     <label class="control-label col-sm-3 text-lg-right" for="name">City :</label>
                     <div class="col-sm-9">
                         <input type="text" class="form-control form-control-sm" id="city" placeholder="Enter City Name....">
                         <div id="city_msg" class="invalid-feedback">
                         </div>
                      </div>
                    </div>
                    <div class="input-group">
                     <label class="control-label col-sm-3 text-lg-right" for="name">Salary ৳:</label>
                     <div class="col-sm-9">
                         <input type="text" class="form-control form-control-sm" id="salary" placeholder="Enter Salary ....">
                         <div id="salary_msg" class="invalid-feedback">
                         </div>
                      </div>
                    </div>
               </form>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="ModalClose()" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="ajaxRequest($('#id').val())">Save changes</button>
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
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Adress</th>
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
          url:"{{ URL::to('/admin/employee') }}"
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
            data:'email',
            name:'email',
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
            document.getElementById('photo').setAttribute('src', e.target.result)
          };
          reader.readAsDataURL(input.files[0]);
      }
   }
 //ajax request from employee.js
function addNew(){
  $('#Modalx').modal('show');
  $('.modal-title').text('Add New Employee');
  $('#id').val('');
 }
 $(document).on('click','.edit',function(){
    $('#Modalx').modal('show');
    $('.modal-title').text('Emplyee Update');
    let id=$(this).data('id');
    $('#id').val(id);
    axios.get('admin/employee/'+id)
    .then((response)=>{
        console.log(response);
        var keys=Object.keys(response.data[0]);
        console.log(keys[2])
        for (var i = 0; i < keys.length; i++) {
          if (keys[i]!='job_dept') {
            $('#'+keys[i]).val(response.data[0][keys[i]])
          }else{
            $('#job_department').val(response.data[0][keys[i]]);
          }
        }
        document.getElementById('photo').setAttribute('src','http://localhost/accounts/public/storage/employee_img/'+response.data[0]['photo'])
    })
    .catch((error)=>{
      console.log(error.request);
    })
 })
function ajaxRequest(id){
    $('.invalid-feedback').hide();
    $('input').css('border','1px solid rgb(209,211,226)');
    $('select').css('border','1px solid rgb(209,211,226)');
    let name=$('#name').val();
    let email=$('#email').val();
    let phone=$('#phone').val();
    let adress=$('#adress').val();
    let nid=$('#nid').val();
    let experience=$('#experience').val();
    let job_department=$('#job_department').val();
    let city=$('#city').val();
    let salary=$('#salary').val();
    let file=document.getElementById('file').files;
    let formData= new FormData();
    formData.append('name',name);
    formData.append('email',email);
    formData.append('phone',phone);
    formData.append('adress',adress);
    formData.append('nid',nid);
    formData.append('experience',experience);
    formData.append('job_department',job_department);
    formData.append('city',city);
    formData.append('salary',salary);

    if (file[0]!=null) {
      formData.append('photo',file[0]);
    }
    //axios post request
    if(!id){
        axios.post('/admin/employee',formData)
        .then(function (response){
          console.log(response);
          if (response.data.message=='success') {
            window.toastr.success('Banks Added Success');
            $('.data-table').DataTable().ajax.reload();
            $('#Modalx').modal('hide');
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
      axios.post('/admin/employee/'+id,formData)
        .then(function (response){
          console.log(response);
          if (response.data.message=='success') {
            window.toastr.success('Banks Added Success');
            $('.data-table').DataTable().ajax.reload();
            $('#Modalx').modal('hide');
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
  

 }
 function ModalClose(){
  document.getElementById('myForm').reset();
  $('#photo').attr('src','http://localhost/accounts/public/storage/admin-lte/dist/img/avatar5.png');
  $('.invalid-feedback').hide();
  $('input').css('border','1px solid rgb(209,211,226)');
  $('select').css('border','1px solid rgb(209,211,226)');
 }

 $('table').on('click','.delete',function(){
    // console.log('xxx');
    Swal.fire({
    title:"Are you sure?",
    text:"Once deleted, you will not be able to recover this imaginary file!",
    icon:"warning",
    showCancelButton:true,
    // dangerMode: true,
    confirmButtonColor:"#DD6B55",
    cancelButtonText:"CANCEL",
    confirmButtonText:"CONFIRM",
  })
  .then((isConfirmed) => {
    if (isConfirmed.isConfirmed){
    var id=$(this).data('id');
      console.log(id);
      axios.delete('/admin/employee/'+id,{_method:'DELETE'})
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
