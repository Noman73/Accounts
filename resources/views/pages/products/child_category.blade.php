@extends('layouts.master')
@section('content')
<div class="container">
  <div class="card m-0">
    <div class="card-header pt-3  flex-row align-items-center justify-content-between">
      <h5 class="m-0 font-weight-bold">Manage Child Category</h5>
     </div>
    <div class="card-body px-3 px-md-5">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
          Add New<i class="fas fa-plus"></i>
        </button>

        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add 
                New Child Category</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="ModalClose()">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <!--modal body-->
              <div class="modal-body">
                <form action="" id="myForm">
                  <div class="form-group">
                    <label class="font-weight-bold">Category:</label>
                    <select class="form-control form-control-sm" id="category">
                      <option value="">--SELECT--</option>
                      @foreach($category as $cat)
                      <option value="{{$cat->id}}">{{$cat->name}}</option>
                      @endforeach
                    </select>
                    <div id="category_msg" class="invalid-feedback">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="font-weight-bold">Child Category Name:</label>
                    <input class="form-control form-control-sm" id="child_category"  type="text" placeholder="Enter Category Name...">
                    <div id="child_category_msg" class="invalid-feedback">
                    </div>
                  </div>
                </form>
               <!--end 2nd column -->
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
                        <th>Category Name</th>
                        <th>Child Category Name</th>
                        <th>Created By</th>
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
          url:"{{ URL::to('/admin/child_category') }}"
        },
        columns:[
          {
            data:'DT_RowIndex',
            name:'DT_RowIndex',
            orderable:false,
            searchable:false
          },
          {
            data:'cat_name',
            name:'cat_name',
          },
          {
            data:'childname',
            name:'childname',
          },
           {
            data:'username',
            name:'username',
          }
        ]
    });
 //ajax request from employee.js
function ajaxRequest(){
    $('.invalid-feedback').hide();
    $('input').css('border','1px solid rgb(209,211,226)');
    $('select').css('border','1px solid rgb(209,211,226)');
    let category=$('#category').val();
    let childCat=$('#child_category').val();
    let formData=new FormData();
    formData.append('category',category); 
    formData.append('child_category',childCat); 
    //axios post request
  axios.post('/admin/child_category',formData)
  .then(function (response){
    console.log(response);
    if (response.data.message=='success') {
      window.toastr.success('Child Category Added Success');
      $('.data-table').DataTable().ajax.reload();
    }
    var keys=Object.keys(response.data);
    for(var i=0; i<keys.length;i++){
        $('#'+keys[i]+'_msg').html(response.data[keys[i]][0]);
        $('#'+keys[i]).css('border','1px solid red');
        $('#'+keys[i]+'_msg').show();
      }
  })
   .catch(function (error) {
    console.log(error.request);
  });

 }
 function ModalClose(){
  document.getElementById('myForm').reset();
  $('.invalid-feedback').hide();
  $('input').css('border','1px solid rgb(209,211,226)');
  $('select').css('border','1px solid rgb(209,211,226)');
 }
 </script>
@endsection
