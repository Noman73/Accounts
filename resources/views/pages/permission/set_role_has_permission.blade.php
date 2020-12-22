@extends('layouts.master')
@section('content')
<div class="container">
	<div class="card m-0">
    <div class="card-header pt-3  flex-row align-items-center justify-content-between">
      <h5 class="m-0 font-weight-bold">Manage Permission</h5>
     </div>
    <div class="card-body px-3 px-md-5">
		
        <div class="table-responsive mt-2">
          {{-- <form action="{{route('roleHasPermission')}}" method="post"> --}}
            @csrf
          <table class="table table-sm table-bordered table-striped align-items-center text-center display table-flush data-table">
            <thead class="thead-light">
             <tr>
                <th>No.</th>
                <th>Role Name</th>
                @foreach($permission as $permissions)
                <th>{{$permissions->name}}</th>
                @endforeach
             </tr>
            </thead>
            <tbody>
              @php
              $i=0;
              @endphp
              @foreach($role as $roles)
              <tr>
              <td>{{$i=$i+1}}</td>
              <td>{{$roles->name}} <input type="hidden" name="role[]" value="{{$roles->id}}"></td>
              @foreach($permission as $permissions)
                  <td><input type="checkbox" name="{{$permissions->name.'[]'}}" value='true'></td>
              @endforeach
              </tr>
              @endforeach
            </tbody>
          </table>
          <button type="submit" onclick="Checked()" class="btn btn-sm btn-primary">Submit</button>
          {{-- </form> --}}
        </div>
        {{-- datatable end --}}
    </div>
  </div>
</div>
@endsection
@section('script')
<script type="text/javascript">
function Checked(){
  perm=(<?php echo json_encode($permission); ?>);
  role=(<?php echo json_encode($role); ?>)
  arr=[];
  for (var i = 0; i < perm.length; i++) {
    v=$("input[name='"+perm[i]['name']+"[]']").map(function(){
      if ($(this).is(':checked')){
        return 'on'
      }else{
        return 'off'
      }
    }).get();
    arr.push(v);
  }
  console.log(role);
  console.log(perm);
  console.log(arr);
  axios.post('admin/set_role_has_permission',{role:role,permission:perm,array:arr})
  .then((res)=>{
    console.log(res);
    if (res.data.message){
      toastr.success(res.data.message);
    }
  })
  .catch((error)=>{
    console.log(error);
  })
}
function getRoleHasPermissions(){
  axios.get('admin/get_role_has_permission')
  .then((res)=>{
    console.log(res)
    i=0;
    $("input[name='role[]']").each(function(){
      thisval=$(this);
      for (var i = 0; i < res.data.length; i++) {
        if (thisval.val()==res.data[i]['role_id']) {
          console.log(thisval.parent().parent().find("input[name='"+res.data[i]['name']+"[]']").val());
            thisval.parent().parent().find("input[name='"+res.data[i]['name']+"[]']").attr('checked',true);
        }
      }
    });
  })
  .catch((error)=>{
    console.log(error);
  })
}
getRoleHasPermissions();
 </script>
@endsection
