@extends('layouts.master')
@section('content')
<div class="container">
  <div class="card m-0">
    <div class="card-header pt-3  flex-row align-items-center justify-content-between">
      <h5 class="m-0 font-weight-bold">Product Stock</h5>
     </div>
    <div class="card-body px-3 px-md-5">
        <div class="table-responsive mt-2">
          <table class="table table-sm table-bordered table-striped align-items-center display table-flush data-table">
            <thead class="thead-light">
             <tr>
                <th>No.</th>
                <th>Product Name</th>
                <th>Available</th>
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
          url:"{{ URL::to('/admin/stock') }}"
        },
        columns:[
          {
            data:'DT_RowIndex',
            name:'DT_RowIndex',
            orderable:false,
            searchable:false
          },
          {
            data:'product_name',
            name:'product_name',
          },
           {
            data:'total',
            name:'total',
          }
        ]
    });

 </script>
@endsection
