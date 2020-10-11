@extends('layouts.master')
@section('content')
@section('link')
<style type="text/css">
  .file {
    border: 1px solid #ccc;
    display: inline-block;
    width: 150px;
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
#p_photo{
  height: 50px;
  width:80px;
}
</style>
@endsection
 
<div class="container">
  <div class="card m-0">
    <div class="card-header pt-3 flex-row align-items-center justify-content-between">
      <h5 class="m-0 font-weight-bold">Add Company Info</h5>
    </div>
    <div class="card-body px-3 px-md-5">
        <!-- Modal -->
                <form id="myForm">
                  <input type="hidden" id="id" value="{{$res->id}}">
                <div class="text-center">
                    <img id="imagex" src="{{asset('storage/logo/'.$res->logo)}}" class="d-flex image-upload" style="height:80px;width:150px;">
                    <input class="d-none" type="file" id="file" onchange="readURL(this)">
                    <label for="file"  class="file">choose logo</label>
                    <div id="logo" class="invalid-feedback">
                     </div>
                </div>
                 <div class="input-group mt-4">
                   <label class="control-label col-sm-3 text-lg-right" for="name">Company Name:</label>
                   <div class="col-sm-9">
                       <input type="text" class="form-control form-control-sm" id="company_name" placeholder="Enter Company Name...." value="{{$res->company_name}}">
                       <div id="company_name_msg" class="invalid-feedback">
                       </div>
                    </div>
                 </div>
                 <div class="input-group">
                   <label class="control-label col-sm-3 text-lg-right" for="name">Company Slogan:</label>
                   <div class="col-sm-9">
                       <input type="text" class="form-control form-control-sm" id="company_slogan" placeholder="Enter Company Name...." value="{{$res->company_slogan}}">
                       <div id="company_slogan_msg" class="invalid-feedback">
                       </div>
                    </div>
                 </div>
                 <div class="input-group">
                   <label class="control-label col-sm-3 text-lg-right" for="name">Country:</label>
                   <div class="col-sm-9">
                       <input type="text" class="form-control form-control-sm" id="country" placeholder="Enter Country Name...." value="{{$res->country}}">
                       <div id="country_msg" class="invalid-feedback">
                       </div>
                    </div>
                 </div>
                 <div class="input-group">
                   <label class="control-label col-sm-3 text-lg-right" for="name">Adress:</label>
                   <div class="col-sm-9">
                       <input type="text" class="form-control form-control-sm" id="adress" placeholder="Enter Adress Here...." value="{{$res->adress}}">
                       <div id="adress_msg" class="invalid-feedback">
                       </div>
                    </div>
                 </div>
                 <div class="input-group">
                   <label class="control-label col-sm-3 text-lg-right" for="name">Phone/Mobile:</label>
                   <div class="col-sm-9">
                       <input type="text" class="form-control form-control-sm" id="phone" placeholder="Enter Number Here...."value="{{$res->phone}}">
                       <div id="phone_msg" class="invalid-feedback">
                       </div>
                    </div>
                 </div>
                 <div class="input-group">
                   <label class="control-label col-sm-3 text-lg-right" for="name">Email:</label>
                   <div class="col-sm-9">
                       <input type="text" class="form-control form-control-sm" id="email" placeholder="Enter Email Here...."value="{{$res->email}}">
                       <div id="email_msg" class="invalid-feedback">
                       </div>
                    </div>
                 </div>
                 <div class="input-group">
                   <label class="control-label col-sm-3 text-lg-right" for="name">City:</label>
                   <div class="col-sm-9">
                       <input type="text" class="form-control form-control-sm" id="city" placeholder="Enter City Name Here...." value="{{$res->city}}">
                       <div id="city_msg" class="invalid-feedback">
                       </div>
                    </div>
                 </div>
                 <div class="input-group">
                   <label class="control-label col-sm-3 text-lg-right" for="name">State:</label>
                   <div class="col-sm-9">
                       <input type="text" class="form-control form-control-sm" id="state" placeholder="Enter State Here...."value="{{$res->state}}">
                       <div id="state_msg" class="invalid-feedback">
                       </div>
                    </div>
                 </div>
                 <div class="input-group">
                   <label class="control-label col-sm-3 text-lg-right" for="name">Post Code:</label>
                   <div class="col-sm-9">
                       <input type="text" class="form-control form-control-sm" id="post_code" placeholder="Enter Post Code Here...." value="{{$res->post_code}}">
                       <div id="post_code_msg" class="invalid-feedback">
                       </div>
                    </div>
                 </div>
                 <div class="input-group">
                   <label class="control-label col-sm-3 text-lg-right" for="name">Stock Warning:</label>
                   <div class="col-sm-9">
                       <input type="text" class="form-control form-control-sm" id="stock_warning" placeholder="Enter stock warning Here...." value="{{$res->stock_warning}}">
                       <div id="stock_warning_msg" class="invalid-feedback">
                       </div>
                    </div>
                 </div>
                 <div class="input-group">
                   <label class="control-label col-sm-3 text-lg-right" for="name">SMS Api:</label>
                   <div class="col-sm-9">
                       <input type="text" class="form-control form-control-sm" id="sms_api" placeholder="Enter SMS Api Here...." value="{{$res->sms_api}}">
                       <div id="sms_api_msg" class="invalid-feedback">
                       </div>
                    </div>
                 </div>
                 <div class="input-group">
                   <label class="control-label col-sm-3 text-lg-right" for="name">SMS Sender:</label>
                   <div class="col-sm-9">
                       <input type="text" class="form-control form-control-sm" id="sms_sender" placeholder="Enter Sender Here...." value="{{$res->sms_sender}}">
                       <div id="sms_sender_msg" class="invalid-feedback">
                       </div>
                    </div>
                 </div>
                 <div class="input-group">
                   <label class="control-label col-sm-3 text-lg-right" for="name">SMS Setting:</label>
                   <div class="col-sm-9">
                       <select class="form-control form-control-sm" id="sms_setting" value="{{$res->sms_setting}}">
                         <option value="">--select--</option>
                         <option value="1">ON</option>
                         <option value="0">OFF</option>
                       </select>
                       <div id="sms_setting_msg" class="invalid-feedback">
                       </div>
                    </div>
                 </div>
               </form>
               <button onclick="ajaxRequest()" class="btn  btn-primary float-right mt-4 mr-2">Save</button>
               <button onclick="document.getElementById('myForm').reset()" class="btn  btn-info float-right mt-4 mr-2">Reset</button>
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
          url:"{{ URL::to('/admin/product') }}"
        },
        columns:[
          {
            data:'DT_RowIndex',
            name:'DT_RowIndex',
            orderable:false,
            searchable:false
          },
          {
            data:'photo',
            name:'photo',
          },
          {
            data:'product_name',
            name:'product_name',
          },
          {
            data:'name',
            name:'name',
          },
          {
            data:'action',
            name:'action',
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
    let id=$('#id').val();
    let company_name    =$('#company_name').val();
    let company_slogan  =$('#company_slogan').val();
    let adress          =$('#adress').val();
    let mobile          =$('#phone').val();
    let country           =$('#country').val();
    let email           =$('#email').val();
    let city            =$('#city').val();
    let state           =$('#state').val();
    let post_code       =$('#post_code').val();
    let stock_warning   =$('#stock_warning').val();
    let sms_api         =$('#sms_api').val();
    let sms_sender      =$('#sms_sender').val();
    let sms_setting     =$('#sms_setting').val();
    let file            =document.getElementById('file').files;
    let formData= new FormData();
    formData.append('company_name',company_name);
    formData.append('company_slogan',company_slogan);
    formData.append('adress',adress);
    formData.append('phone',mobile);
    formData.append('email',email);
    formData.append('country',country);
    formData.append('city',city);
    formData.append('state',state);
    formData.append('post_code',post_code);
    formData.append('stock_warning',stock_warning);
    formData.append('sms_api',sms_api);
    formData.append('sms_sender',sms_sender);
    formData.append('sms_setting',sms_setting);
    if (file[0]!=null) {
      formData.append('logo',file[0]);
    }
    //axios post request
  axios.post('/admin/add_info/'+id,formData)
  .then(function (response){
    console.log(response);
    if (response.data.message=='success'){
      window.toastr.success('Company Updated Success');
      $('.data-table').DataTable().ajax.reload();
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
 </script>
@endsection
