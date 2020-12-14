@extends('layouts.master')
@section('content')
@section('link')
<style type="text/css">
  .file {
    border: 1px solid #ccc;
    display: inline-block;
    width: 150\px;
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
      <h5 class="m-0 font-weight-bold">Manage Product</h5>
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
                <h5 class="modal-title" id="exampleModalLabel">Add 
                New Product</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="ModalClose()">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <!--modal body-->
              <div class="modal-body" id="forms">
                <div id="CustomModal"></div>
                <form id="myForm">
                  <input type="hidden" id="id">
                <div class="text-center">
                    <img id="imagex" src="{{asset('storage/admin-lte/dist/img/noimage.png')}}" class="d-flex image-upload" style="height:100px;width:150px;">
                    <input class="d-none" type="file" id="file" onchange="readURL(this)">
                    <label for="file"  class="file">choose</label>
                    <div id="photo_msg" class="invalid-feedback">
                     </div>
                </div>
                 
                 <div class="input-group mt-4">
                   <label class="control-label col-sm-3 text-lg-right"  for="Category">Category :</label>
                   <div class="col-sm-8">
                       <select type="text"  class="form-control form-control-sm" id="category" onchange="getChildCat()">
                        <option value="">--SELECT--</option>
                        @foreach($category as $cat)
                        <option value="{{$cat['id']}}">{{$cat['text']}}</option>
                        @endforeach
                       </select>
                       <div id="category_msg" class="invalid-feedback">
                       </div>
                    </div>
                    <div class="col-sm-1">
                       <a onclick="openModal(this)" data-id='1' class="btn btn-sm btn-primary d-block">Add</a>
                    </div>
                 </div>
                 <div class="input-group ">
                   <label class="control-label col-sm-3 text-lg-right" for="name">Child Category :</label>
                   <div class="col-sm-8">
                       <select type="text" class="form-control form-control-sm" id="child_category">
                        <option value="">--SELECT--</option>
                       </select>
                       <div id="child_category_msg" class="invalid-feedback">
                       </div>
                    </div>
                    <div class="col-sm-1">
                      <a onclick="openModal(this)" data-id='2' class="btn btn-sm btn-primary d-block">Add</a>
                    </div>
                 </div>
                 <div class="input-group ">
                   <label class="control-label col-sm-3 text-lg-right" for="name">Product Name :</label>
                   <div class="col-sm-9">
                       <input type="text" class="form-control form-control-sm" id="product_name" placeholder="Enter Product Name....">
                       <div id="product_name_msg" class="invalid-feedback">
                       </div>
                    </div>
                 </div>
                 <div class="input-group ">
                   <label class="control-label col-sm-3 text-lg-right" for="product_code">Product Code :</label>
                   <div class="col-sm-9">
                       <input type="text" class="form-control form-control-sm" id="product_code" placeholder="Enter Product Code....">
                       <div id="product_code_msg" class="invalid-feedback">
                       </div>
                    </div>
                 </div>
                 <div class="input-group">
                   <label class="control-label col-sm-3 text-lg-right" for="name">Model No:</label>
                   <div class="col-sm-9">
                       <input type="text" class="form-control form-control-sm" id="model_no" placeholder="Enter Model No....">
                       <div id="model_no_msg" class="invalid-feedback">
                       </div>
                    </div>
                 </div>
                 <div class="input-group">
                   <label class="control-label col-sm-3 text-lg-right" for="name">Warranty:</label>
                   <div class="col-sm-9">
                       <input type="text" class="form-control form-control-sm" id="warranty" placeholder="Enter Warranty....">
                       <div id="warranty_msg" class="invalid-feedback">
                       </div>
                    </div>
                 </div>
                 <div class="input-group">
                   <label class="control-label col-sm-3 text-lg-right" for="name">Product Type:</label>
                   <div class="col-sm-8">
                       <select type="text" class="form-control form-control-sm" id="product_type" placeholder="Enter Warranty....">
                        <option value="">--SELECT--</option>
                        @foreach($ptype as $type)
                          <option value="{{$type->id}}">{{$type->name}}</option>
                        @endforeach
                       </select>
                       <div id="product_type_msg" class="invalid-feedback">
                       </div>
                    </div>
                    <div class="col-sm-1">
                        <a onclick="openModal(this)" data-id='3' class="btn btn-sm btn-primary d-block">Add</a>
                    </div>
                 </div>
                 <div class="input-group">
                   <label class="control-label col-sm-3 text-lg-right" for="name">Packaging:</label>
                   <div class="col-sm-9">
                       <select type="text" class="form-control form-control-sm" id="packaging" placeholder="Enter Warranty....">
                        <option value="">--SELECT--</option>
                        <option value="Packed">Packed</option>
                        <option value="Unpacked">Unpacked</option>
                       </select>
                       <div id="packaging_msg" class="invalid-feedback">
                       </div>
                    </div>
                 </div>
                  <div class="input-group">
                   <label class="control-label col-sm-3 text-lg-right" for="name">Price
                    <span class="bg-info pl-1 pr-1">$</span> :</label>
                   <div class="col-sm-9">
                       <input type="text" class="form-control form-control-sm" id="price" placeholder="Enter Product Code....">
                       <div id="price_msg" class="invalid-feedback">
                       </div>
                    </div>
                 </div>
               </form>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="ModalClose()" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary submit" onclick="ajaxRequest()"></button>
              </div>
            </div>
          </div>
        </div>
        {{-- datatable start --}}
        {{-- <div class="container-fluid" id="container-wrapper"> --}}
            <!-- Datatables -->
                <div class="table-responsive mt-2">
                  <table class="table table-sm table-bordered table-striped align-items-center display table-flush data-table text-center">
                    <thead class="thead-light">
                     <tr>
                        <th>No.</th>
                        <th>Photo</th>
                        <th>Product Name</th>
                        <th>Category</th>
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
<script src="{{asset('js/custom_modal.js')}}"></script>
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
function addNew(){
  console.log('addNew')
document.getElementById('myForm').reset();
$('#id').val('');
$('#exampleModalLabel').text('Add New Product');
$('.submit').text('Save');
$('#Modalx').modal('show');
}
 $(document).on('click','.edit',function(){
  $('#exampleModalLabel').text('Update Product');
  $('.submit').text('Update');
  $('#Modalx').modal('show');
  id=$(this).data('id');
  $('#id').val(id);
  axios.get('admin/product_by_id/'+id)
  .then(function(response){
    var keys=Object.keys(response.data[0]);
    console.log(response.data[0])
    for (var i = 0; i < keys.length; i++) {
      if (keys[i]=='category'){
      $('#category').val(response.data[0][keys[i]])
      getChildCat(response.data[0]['child_category']);
      }else{
      $('#'+keys[i]).val(response.data[0][keys[i]])
      }
    }
    $('#imagex').attr('src',"{{asset('storage/product')}}/"+response.data[0]['photo'])
  })
})
 //ajax request from employee.js
function ajaxRequest(){
    $('.invalid-feedback').hide();
    $('input').css('border','1px solid rgb(209,211,226)');
    $('select').css('border','1px solid rgb(209,211,226)');
    let id=$('#id').val();
    let product_name    =$('#product_name').val();
    let category        =$('#category').val();
    let child_category  =$('#child_category').val();
    let product_code    =$('#product_code').val();
    let model_no        =$('#model_no').val();
    let warranty        =$('#warranty').val();
    let product_type    =$('#product_type').val();
    let packaging       =$('#packaging').val();
    let price           =$('#price').val();
    let file            =document.getElementById('file').files;
    let formData= new FormData();
    formData.append('product_name',product_name);
    formData.append('category',category);
    formData.append('child_category',child_category);
    formData.append('product_code',product_code);
    formData.append('model_no',model_no);
    formData.append('warranty',warranty);
    formData.append('product_type',product_type);
    formData.append('packaging',packaging);
    formData.append('price',price);
    if (file[0]!=null) {
      formData.append('photo',file[0]);
    }
    //axios post request
 if (!id) {
  axios.post('/admin/product',formData)
  .then(function (response){
    console.log(response);
    if (response.data.message=='success'){
      window.toastr.success('Product Added Success');
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
 }else{
  axios.post('/admin/product/'+id,formData)
  .then(function (response){
    console.log(response);
    if (response.data.message=='success'){
      window.toastr.success('Product Updated Success');
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
 })
 }
}
 function getChildCat(selected=null){
  let data=$('#category').val();
  if (data===null || data==='') {
    alert('you selected null value! please select a valid value');
    return false
  }
  if (data>0) {
        axios.get('admin/get_child_cat/'+data)
      .then(function(response){
        console.log(response);
        let html='<option>--select--</option>';
        response.data.forEach(function(d){
           html +="<option value='"+d.id+"'>"+d.name+"</option>";
        });
        $('#child_category').html(html);
        $("#child_category option[value='"+selected+"']").attr('selected',true);
        })
    }
 }


 $('table').on('click','.delete',function(){
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
    axios.delete('/admin/product/'+id,{_method:'DELETE'})
      .then((res)=>{
        console.log(res);
        if (res.data.message=='success') {
          window.toastr.success('Product Deleted Success');
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
  $('#imagex').attr('src',"{{asset('storage/admin-lte/dist/img')}}"+'/noimage.png');
  $('.invalid-feedback').hide();
  $('input').css('border','1px solid rgb(209,211,226)');
  $('select').css('border','1px solid rgb(209,211,226)');
  $("select option[value='']").attr('selected',true);
 }
function openModal(this_val){
  id=$(this_val).data('id');
    switch(true){
      case id==1:
      data=CustomModalForm({
      setting:{
          title:'Add New Category',
          unique:1,
          SubmitButton:{
            text:'Submit',
            class:'btn  btn-primary CustomSubmit',
            type:'',
          }
        },
        forms:{
          form1:{
            category:'input',
            label:'Category',
            type:'text',
            class:'form-control form-control-sm',
            id:'name',
            placeholder:'Enter Category Name',
            option:<?php echo json_encode($category) ?>
          },
        }
    });
      $('#CustomModal').html(data)
      console.log(data);
      $('#CustomModalForm').modal('show')
      $('#CustomModalForm select').select2({
        placeholder:'select',
        theme:'bootstrap4',
        allowClear:true
      })
      break;
      case id==2:
      data=CustomModalForm({
      setting:{
          title:'Add New Child Category',
          unique:2,
          SubmitButton:{
            text:'Submit',
            class:'btn  btn-primary CustomSubmit',
            type:'',
          }
        },
        forms:{
          form1:{
            category:'select',
            label:'Category',
            type:'text',
            class:'form-control form-control-sm',
            id:'category',
            placeholder:'Enter Category Name',
            option:<?php echo json_encode($category) ?>
          },
          form2:{
            category:'input',
            label:'Child Category',
            type:'text',
            class:'form-control form-control-sm',
            id:'child_category',
            placeholder:'Enter Child Category Name',
          },
        }
    });
      $('#CustomModal').html(data)
      console.log(data);
      $('#CustomModalForm').modal('show')
      $('#CustomModalForm select').select2({
        placeholder:'select',
        theme:'bootstrap4',
        allowClear:true
      })
      break;
      case id==3:
      data=CustomModalForm({
      setting:{
          title:'Add New Product Type',
          unique:3,
          SubmitButton:{
            text:'Submit',
            class:'btn  btn-primary CustomSubmit',
            type:'',
          }
        },
        forms:{
          form1:{
            category:'input',
            label:'Product Type',
            type:'text',
            class:'form-control form-control-sm',
            id:'product_type',
            placeholder:'Enter Product Type',
            option:<?php echo json_encode($category) ?>
          },
        }
    });
      $('#CustomModal').html(data)
      console.log(data);
      $('#CustomModalForm').modal('show')
      $('#CustomModalForm select').select2({
        placeholder:'select',
        theme:'bootstrap4',
        allowClear:true
      })
      default: 
      return false
      break;

  }
}
$(document).on('click','.CustomSubmit',function(){
 data=$('#myCustomForm').serializeArray();
 var formData=new FormData;
 for(i=0;i<data.length;i++){
  formData.append(data[i]['name'],data[i]['value']);
 }
 unique_id=$('#unique_id').val();
     switch(true){
      case unique_id==1:
      url='admin/category';
      break;
      case unique_id==2:
      url='admin/child_category';
      break;
      case unique_id==3:
      url='admin/product_type'
     }
     axios.post(url,formData)
     .then((res)=>{
        console.log(res);
        if (res.data.message) {
          toastr.success(res.data.message);
        }else{
          keys=Object.keys(res.data)
          for (var i = 0; i < keys.length; i++) {
            alert(res.data[keys[i]]+'\n');
          }
        }
     })
     .error((error)=>{
      console.log(error);
     })
})
 
 </script>
@endsection
